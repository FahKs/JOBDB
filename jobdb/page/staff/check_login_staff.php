<?php
session_start();
include '../../service/condb.php'; // เรียกใช้ไฟล์ condb.php

// สร้างอินสแตนซ์ของคลาส ConnectionDatabase
$db = new ConnectionDatabase();
$conn = $db->connect(); // สร้างการเชื่อมต่อฐานข้อมูล

if (!$db->isConnectionValid($conn)) {
    die("Connection failed: " . $conn->connect_error); // ตรวจสอบว่าการเชื่อมต่อฐานข้อมูลสำเร็จหรือไม่
}

if (isset($_POST['tel'])) {
    $tel = $_POST['tel'];

    // เตรียม statement เพื่อป้องกัน SQL Injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE tel = ? AND position = 'Staff'");
    $stmt->bind_param("s", $tel);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // เก็บข้อมูลผู้ใช้ใน session
        $_SESSION['user'] = $user;

        // หากยืนยันหมายเลขโทรศัพท์ได้ถูกต้อง เด้งไปที่หน้า staff/index.php
        header("Location: /jobdb/page/staff/index.php");
        exit();
    } else {
        // หากไม่พบข้อมูลผู้ใช้
        $_SESSION['error'] = "หมายเลขโทรศัพท์ไม่ถูกต้อง หรือสถานะไม่ใช่ Staff";
        header("Location: /jobdb/page/staff/login_staff.php"); // กลับไปหน้าล็อกอินใหม่
        exit();
    }
} else {
    $_SESSION['error'] = "กรุณากรอกหมายเลขโทรศัพท์";
    header("Location: /jobdb/page/staff/login_staff.php");
    exit();
}

$conn->close(); 
?>

