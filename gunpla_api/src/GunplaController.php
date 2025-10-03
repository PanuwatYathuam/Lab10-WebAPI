<?php
// src/GunplaController.php

class GunplaController {
    private GunplaModel $model;

    public function __construct(GunplaModel $model) {
        $this->model = $model;
    }

    /**
     * ดึงข้อมูลทั้งหมด พร้อม Filter/Sort/Pagination
     * Endpoint: GET /api/gunplas
     */
    public function readAll() {
        // รับตัวกรองจาก Query String
        $filters = $_GET;
        $result = $this->model->findAll($filters);
        
        // จัดรูปแบบ Response สำหรับ Collection (200 OK)
        Response::json([
            'meta' => [
                'total_records' => $result['total_records'],
                'per_page' => $result['per_page'],
                'current_page' => $result['current_page'],
                'total_pages' => $result['total_pages'],
            ],
            'data' => $result['gunplas']
        ]);
    }

    /**
     * ดึงข้อมูลกันพลาตาม ID
     * Endpoint: GET /api/gunplas/{id}
     * @param int $id
     */
    public function readOne(int $id) {
        $gunpla = $this->model->findById($id);

        if ($gunpla === false) {
            Response::notFound(["error" => "Gunpla with ID $id not found"]);
        }

        Response::json($gunpla);
    }

    /**
     * สร้างกันพลาใหม่
     * Endpoint: POST /api/gunplas
     */
    public function create() {
        // รับ JSON Body
        $data = $this->getJsonInput();
        
        // 1. Validation เบื้องต้น
        $required_fields = ['sku', 'name', 'grade', 'series', 'scale', 'price', 'stock'];
        $errors = $this->validateInput($data, $required_fields);
        
        if (!empty($errors)) {
            Response::badRequest("Missing or invalid required fields.", $errors);
        }

        // 2. ตรวจสอบ SKU ซ้ำ (409 Conflict)
        if ($this->model->findBySku($data['sku'])) {
            Response::conflict("The SKU '{$data['sku']}' already exists.");
        }

        // 3. สร้างข้อมูล
        try {
            $new_id = $this->model->create($data);
            $new_gunpla = $this->model->findById($new_id);
            
            // 201 Created
            Response::json(["message" => "Gunpla created successfully", "data" => $new_gunpla], 201);
            
        } catch (Exception $e) {
            // 500 Internal Server Error (หากมีข้อผิดพลาดอื่นๆ ที่ไม่ได้จัดการ)
            Response::json(["error" => "Failed to create Gunpla: " . $e->getMessage()], 500);
        }
    }

    /**
     * อัพเดทข้อมูลกันพลา
     * Endpoint: PUT/PATCH /api/gunplas/{id}
     * @param int $id
     */
    public function update(int $id) {
        // รับ JSON Body
        $input_data = $this->getJsonInput();
        
        // 1. ตรวจสอบว่ามี Gunpla ID นี้อยู่จริงหรือไม่
        $existing_gunpla = $this->model->findById($id);
        if ($existing_gunpla === false) {
            Response::notFound(["error" => "Gunpla with ID $id not found"]);
        }

        // 2. Validation เบื้องต้น (ตรวจสอบเฉพาะฟิลด์ที่ส่งมา)
        $errors = $this->validateInput($input_data, [], true); 
        if (!empty($errors)) {
            Response::badRequest("Invalid input data.", $errors);
        }
        
        // 3. ตรวจสอบ SKU ซ้ำ (409 Conflict)
        if (isset($input_data['sku'])) {
            $sku_check = $this->model->findBySku($input_data['sku']);
            // ถ้าพบ SKU และ ID ของ SKU นั้นไม่ตรงกับ ID ที่กำลังอัพเดท แสดงว่าซ้ำ
            if ($sku_check && (int)$sku_check['id'] !== $id) {
                Response::conflict("The SKU '{$input_data['sku']}' already exists on another item.");
            }
        }

        // 4. อัพเดทข้อมูล
        if (empty($input_data)) {
            Response::badRequest("No data provided for update.");
        }
        
        if ($this->model->update($id, $input_data)) {
            $updated_gunpla = $this->model->findById($id);
            Response::json(["message" => "Gunpla updated successfully", "data" => $updated_gunpla]);
        } else {
             // แม้จะไม่มีการเปลี่ยนแปลงข้อมูล แต่ถือว่าสำเร็จ 200 OK
             Response::json(["message" => "No changes made, Gunpla is already up to date.", "data" => $existing_gunpla]);
        }
    }

    /**
     * ลบข้อมูลกันพลา
     * Endpoint: DELETE /api/gunplas/{id}
     * @param int $id
     */
    public function delete(int $id) {
        if ($this->model->delete($id)) {
            Response::json(["message" => "Gunpla with ID $id deleted successfully"]);
        } else {
            Response::notFound(["error" => "Gunpla with ID $id not found"]);
        }
    }

    /**
     * Helper: ดึงข้อมูลจาก JSON Body
     * @return array
     */
    private function getJsonInput(): array {
        $json_data = file_get_contents("php://input");
        $data = json_decode($json_data, true);
        if ($data === null) {
            Response::badRequest("Invalid JSON format in request body.");
        }
        return $data;
    }

    /**
     * Helper: ตรวจสอบความถูกต้องของข้อมูล
     * @param array $data ข้อมูลที่รับเข้ามา
     * @param array $required_fields ฟิลด์ที่ต้องมี
     * @param bool $partial_update โหมดอัพเดทบางส่วนหรือไม่
     * @return array รายการข้อผิดพลาด
     */
    private function validateInput(array $data, array $required_fields = [], bool $partial_update = false): array {
        $errors = [];
        $data_keys = array_keys($data);
        
        // 1. ตรวจสอบฟิลด์ที่จำเป็น (สำหรับ Create)
        foreach ($required_fields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                $errors[] = "Field '$field' is required.";
            }
        }
        
        // 2. ตรวจสอบประเภทข้อมูล/ค่า
        $fields_to_check = $partial_update ? $data_keys : $required_fields;

        foreach ($fields_to_check as $field) {
            if (!isset($data[$field])) continue;

            $value = $data[$field];

            switch ($field) {
                case 'price':
                    if (!is_numeric($value) || $value < 0) {
                        $errors[] = "Field 'price' must be a non-negative number.";
                    }
                    break;
                case 'stock':
                    if (!is_numeric($value) || (int)$value < 0) {
                        $errors[] = "Field 'stock' must be a non-negative integer.";
                    }
                    $data[$field] = (int)$value; // Cast to integer
                    break;
                case 'grade':
                    $allowed_grades = ['HG', 'RG', 'MG', 'PG', 'SD', 'RE/100', 'FM'];
                    if (!in_array(strtoupper($value), $allowed_grades) && $partial_update) {
                        // ถ้าเป็น Partial Update และค่าไม่อยู่ในรายการที่กำหนด แต่ก็ยังยอมให้ผ่านไปได้ หากไม่ใช่องค์ประกอบสำคัญ
                    }
                    break;
            }
        }

        return $errors;
    }
}