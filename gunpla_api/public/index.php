<?php
// public/index.php

require_once '../src/Database.php';
require_once '../src/Response.php';
require_once '../src/GunplaModel.php';
require_once '../src/GunplaController.php';

// จัดการ CORS Preflight (OPTIONS Request)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Max-Age: 86400"); // Cache preflight 1 day
    exit(0);
}

// 1. กำหนด URI และ Method
$method = $_SERVER['REQUEST_METHOD'];
$uri = trim($_SERVER['REQUEST_URI'], '/');
// ดึงส่วนของ path ที่หลัง /api/
$path = preg_replace('/^gunpla_api\/public\/api\//', '', $uri); 
$parts = explode('/', $path);

// 2. เชื่อมต่อ DB และสร้าง Controller
$db = (new Database())->getConnection();
$model = new GunplaModel($db);
$controller = new GunplaController($model);

// 3. Routing (ใช้ Switch/Case อย่างง่าย)
if ($parts[0] === 'gunplas') {
    $id = $parts[1] ?? null;

    if ($id) {
        // Single Resource: /api/gunplas/{id}
        switch ($method) {
            case 'GET':
                $controller->readOne($id);
                break;
            case 'PUT':
            case 'PATCH':
                $controller->update($id); // แก้ไข
                break;
            case 'DELETE':
                $controller->delete($id); // ลบ
                break;
            default:
                Response::json(["error" => "Method Not Allowed"], 405);
        }
    } else {
        // Collection: /api/gunplas
        switch ($method) {
            case 'GET':
                $controller->readAll(); // อ่าน/ค้นหา
                break;
            case 'POST':
                $controller->create(); // สร้างใหม่
                break;
            default:
                Response::json(["error" => "Method Not Allowed"], 405);
        }
    }
} else {
    // 404 Not Found สำหรับเส้นทางที่ไม่รองรับ
    Response::notFound("API endpoint not found");
}