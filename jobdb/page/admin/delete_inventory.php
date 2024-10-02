<?php
session_start();
include '../../service/condb.php';

$conn = new ConnectionDatabase();
$conn = $conn->connect();

// ตรวจสอบว่ามีการส่งค่า listproduct_id และเป็นตัวเลขหรือไม่
if (isset($_GET['listproduct_id']) && is_numeric($_GET['listproduct_id'])) {
    $listproduct_id = intval($_GET['listproduct_id']); // แปลงค่า listproduct_id เป็นตัวเลข

    // ใช้ Prepared Statement เพื่อป้องกัน SQL Injection
    $stmt = $conn->prepare("DELETE FROM list_product WHERE listproduct_id = ?");
    if ($stmt) {
        // ผูกค่าพารามิเตอร์
        $stmt->bind_param("i", $listproduct_id);

        // ดำเนินการลบข้อมูล
        if ($stmt->execute()) {
            echo "<script>alert('ลบข้อมูลเรียบร้อยแล้ว'); window.location='inventory.php';</script>";
        } else {
            echo "Error deleting record: " . $stmt->error;
        }

        // ปิด Statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    // กรณีที่ไม่มีค่า listproduct_id ที่ถูกต้องส่งเข้ามา
    echo "<script>alert('Invalid product ID.'); window.location='inventory.php';</script>";
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
