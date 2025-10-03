<?php
// src/Response.php

class Response {
    /**
     * ส่ง JSON Response พร้อมตั้ง HTTP Status Code
     * @param mixed $data ข้อมูลที่ต้องการส่ง
     * @param int $status_code HTTP Status Code (เช่น 200, 201, 404)
     */
    public static function json($data, $status_code = 200) {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Content-Type: application/json; charset=utf-8");
        http_response_code($status_code);
        // JSON_UNESCAPED_UNICODE สำคัญสำหรับการแสดงภาษาไทยใน JSON
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
     * ตอบกลับด้วย 404 Not Found
     * @param mixed $data ข้อมูลข้อผิดพลาด
     */
    public static function notFound($data) {
        self::json($data, 404);
    }

    /**
     * ตอบกลับด้วย 400 Bad Request
     * @param string $message ข้อความหลัก
     * @param array|null $details รายละเอียดเพิ่มเติม
     */
    public static function badRequest(string $message, $details = null) {
        $response = ["error" => $message];
        if ($details) {
            $response["details"] = $details;
        }
        self::json($response, 400);
    }

    /**
     * ตอบกลับด้วย 409 Conflict (เช่น SKU ซ้ำ)
     * @param string $message ข้อความหลัก
     */
    public static function conflict(string $message) {
        self::json(["error" => $message], 409);
    }
}