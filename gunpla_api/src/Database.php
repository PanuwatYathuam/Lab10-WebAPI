<?php
// src/Database.php

class Database {
    private string $host = "localhost";
    private string $db_name = "gunpla_webapi"; // ต้องตรงกับชื่อฐานข้อมูลที่สร้าง
    private string $username = "root"; 
    private string $password = ""; // ใส่รหัสผ่านหากมีการตั้งค่าไว้
    private ?PDO $conn = null;

    public function getConnection(): PDO {
        if ($this->conn !== null) {
            return $this->conn;
        }
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            // ตั้งค่า PDO เพื่อจัดการ Error และรูปแบบการดึงข้อมูล
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            // ส่ง 500 Internal Server Error หากเชื่อมต่อ DB ไม่ได้
            http_response_code(500);
            echo json_encode(["error" => "Database connection error: " . $exception->getMessage()]);
            exit();
        }
        return $this->conn;
    }
}