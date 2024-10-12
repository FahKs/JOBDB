<?php
// functions.php

include '../../service/condb.php'; // เส้นทางไฟล์เชื่อมต่อฐานข้อมูล

// ฟังก์ชัน connectDatabase() เพื่อเชื่อมต่อฐานข้อมูลได้ง่ายขึ้น
function connectDatabase() {
    $db = new ConnectionDatabase(); // เรียกใช้งานคลาส ConnectionDatabase
    return $db->connect(); // ส่งคืนการเชื่อมต่อฐานข้อมูล
}

// ฟังก์ชันดึงสินค้าจากฐานข้อมูลทั้งหมด
function getAllProducts() {
    $conn = connectDatabase(); // ใช้ฟังก์ชัน connectDatabase() เพื่อเชื่อมต่อ

    // ตรวจสอบการเชื่อมต่อ
    if (!$conn) {
        die("Connection failed: " . $conn->connect_error);
    }

    // ดึงข้อมูลทั้งหมดจากฐานข้อมูล โดยเลือกเฉพาะสินค้าที่มีสถานะ visible
    $query = "SELECT * FROM list_product WHERE visible = 1"; 
    $stmt = $conn->prepare($query);

    // ตรวจสอบการเตรียมคำสั่ง SQL
    if ($stmt === false) {
        die("Failed to prepare statement: " . $conn->error);
    }

    // รันคำสั่ง SQL และตรวจสอบข้อผิดพลาด
    if (!$stmt->execute()) {
        die("Failed to execute statement: " . $stmt->error);
    }

    // รับผลลัพธ์จากฐานข้อมูล
    $result = $stmt->get_result();

    $stmt->close();
    $conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล

    return $result; // ส่งผลลัพธ์ข้อมูลสินค้าไปยังไฟล์ที่เรียกใช้
}

// ฟังก์ชันดึงข้อมูลสินค้าจากฐานข้อมูลตาม ID
function getProductById($id) {
    $conn = connectDatabase(); // ใช้ฟังก์ชัน connectDatabase() เพื่อเชื่อมต่อ

    // ตรวจสอบการเชื่อมต่อ
    if (!$conn) {
        die("Connection failed: " . $conn->connect_error);
    }

    // ดึงข้อมูลสินค้าจากฐานข้อมูลตาม ID ที่ระบุ
    $query = "SELECT * FROM list_product WHERE listproduct_id = ?"; // เปลี่ยนจาก id เป็น listproduct_id
    $stmt = $conn->prepare($query);

    // ตรวจสอบการเตรียมคำสั่ง SQL
    if ($stmt === false) {
        die("Failed to prepare statement: " . $conn->error);
    }

    // ผูกค่าพารามิเตอร์ ID
    $stmt->bind_param("i", $id);

    // รันคำสั่ง SQL และตรวจสอบข้อผิดพลาด
    if (!$stmt->execute()) {
        die("Failed to execute statement: " . $stmt->error);
    }

    // รับผลลัพธ์จากฐานข้อมูล
    $result = $stmt->get_result();

    // ตรวจสอบว่ามีข้อมูลสินค้าหรือไม่
    $product = $result->fetch_assoc();

    $stmt->close();
    $conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล

    return $product; // ส่งข้อมูลสินค้าตาม ID กลับไป
}

// ฟังก์ชันลบสินค้าออกจากตะกร้า
function removeProductFromCart($index) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION["cart"][$index])) {
        unset($_SESSION["cart"][$index]);
        $_SESSION["cart"] = array_values($_SESSION["cart"]); // เรียงลำดับใหม่
    }
}

// ฟังก์ชันอัปเดตจำนวนสินค้าในตะกร้า
function updateCartQuantities($quantities) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    foreach ($quantities as $index => $quantity) {
        if (isset($_SESSION["cart"][$index])) {
            $_SESSION["cart"][$index]["quantity"] = $quantity; // ตรวจสอบว่า index นั้นมีอยู่จริงในตะกร้าหรือไม่ก่อนอัปเดต
        }
    }
}
?>
