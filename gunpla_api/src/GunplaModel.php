<?php
// src/GunplaModel.php

class GunplaModel {
    private PDO $conn;
    private string $table_name = "gunplas";

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    /**
     * ดึงข้อมูลกันพลาทั้งหมด พร้อมรองรับ Filter, Search, Sort, Pagination
     * @param array $filters ตัวกรองที่มาจาก Query String
     * @return array
     */
    public function findAll(array $filters): array {
        $query = "SELECT * FROM " . $this->table_name;
        $conditions = [];
        $params = [];

        // 1. การกรอง (Filtering)
        if (!empty($filters['grade'])) {
            $conditions[] = "grade = :grade";
            $params[':grade'] = $filters['grade'];
        }
        if (!empty($filters['series'])) {
            $conditions[] = "series LIKE :series";
            $params[':series'] = "%{$filters['series']}%";
        }
        if (!empty($filters['min_price']) && is_numeric($filters['min_price'])) {
            $conditions[] = "price >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }
        if (!empty($filters['max_price']) && is_numeric($filters['max_price'])) {
            $conditions[] = "price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }

        if (count($conditions) > 0) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        // 2. การจัดเรียง (Sorting)
        $sort_by = 'created_at';
        $sort_order = 'DESC';
        if (!empty($filters['sort'])) {
            [$field, $order] = explode('_', $filters['sort']) + [1 => 'asc'];
            $allowed_fields = ['id', 'name', 'price', 'stock', 'created_at'];
            if (in_array($field, $allowed_fields)) {
                $sort_by = $field;
                $sort_order = (strtolower($order) === 'desc') ? 'DESC' : 'ASC';
            }
        }
        $query .= " ORDER BY $sort_by $sort_order";

        // 3. การแบ่งหน้า (Pagination)
        $page = max(1, (int)($filters['page'] ?? 1));
        $per_page = max(1, min(50, (int)($filters['per_page'] ?? 10)));
        $offset = ($page - 1) * $per_page;

        // ดึงจำนวนทั้งหมด (Total count) สำหรับ Pagination
        $countQuery = str_replace('*', 'COUNT(*) as total_records', $query);
        $countQuery = preg_replace('/ORDER BY.*/', '', $countQuery); // ลบ ORDER BY ออก
        $stmtCount = $this->conn->prepare($countQuery);
        $stmtCount->execute($params);
        $total_records = (int)($stmtCount->fetchColumn() ?? 0);

        // เพิ่ม Limit/Offset ใน Query หลัก
        $query .= " LIMIT :per_page OFFSET :offset";
        $params[':per_page'] = $per_page;
        $params[':offset'] = $offset;


        // 4. เตรียมและ Execute Statement
        $stmt = $this->conn->prepare($query);
        
        // Bind Parameter สำหรับ LIMIT และ OFFSET (ต้องเป็น integer)
        $stmt->bindParam(':per_page', $params[':per_page'], PDO::PARAM_INT);
        $stmt->bindParam(':offset', $params[':offset'], PDO::PARAM_INT);
        
        // Bind Parameter อื่นๆ
        foreach ($params as $key => &$val) {
            if ($key !== ':per_page' && $key !== ':offset') {
                $stmt->bindParam($key, $val);
            }
        }

        $stmt->execute();
        
        return [
            'total_records' => $total_records,
            'per_page' => $per_page,
            'current_page' => $page,
            'total_pages' => ceil($total_records / $per_page),
            'gunplas' => $stmt->fetchAll()
        ];
    }

    /**
     * ดึงข้อมูลกันพลาตาม ID
     * @param int $id
     * @return array|false
     */
    public function findById(int $id): array|false {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * ดึงข้อมูลกันพลาตาม SKU เพื่อตรวจสอบซ้ำ
     * @param string $sku
     * @return array|false
     */
    public function findBySku(string $sku): array|false {
        $query = "SELECT id FROM " . $this->table_name . " WHERE sku = :sku LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":sku", $sku);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * สร้างกันพลาใหม่
     * @param array $data
     * @return int ID ที่สร้างใหม่
     */
    public function create(array $data): int {
        $query = "INSERT INTO " . $this->table_name . " (sku, name, grade, series, scale, price, stock) VALUES (:sku, :name, :grade, :series, :scale, :price, :stock)";
        $stmt = $this->conn->prepare($query);

        // Bind Parameters
        $stmt->bindParam(':sku', $data['sku']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':grade', $data['grade']);
        $stmt->bindParam(':series', $data['series']);
        $stmt->bindParam(':scale', $data['scale']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':stock', $data['stock'], PDO::PARAM_INT);

        $stmt->execute();
        return (int)$this->conn->lastInsertId();
    }

    /**
     * อัพเดทข้อมูลกันพลา
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool {
        $setClauses = [];
        $params = ['id' => $id];
        
        // สร้าง Dynamic SET Clause
        foreach ($data as $key => $value) {
            $setClauses[] = "$key = :$key";
            $params[":$key"] = $value;
        }

        if (empty($setClauses)) {
            return false; // ไม่มีข้อมูลให้อัพเดท
        }

        $query = "UPDATE " . $this->table_name . " SET " . implode(', ', $setClauses) . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Bind Parameters
        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * ลบข้อมูลกันพลา
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}