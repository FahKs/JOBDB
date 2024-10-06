<?php
session_start();
include '../../service/condb.php';
$conn = new ConnectionDatabase();
$conn = $conn->connect();

if (isset($_GET['setting_id'])) {
    $setting_id = $_GET['setting_id'];

    // ใช้ Prepared Statement เพื่อป้องกัน SQL Injection
    $stmt = $conn->prepare("DELETE FROM alerts_setting WHERE setting_id = ?");
    $stmt->bind_param("i", $setting_id);

    if ($stmt->execute()) {
        echo "<script>alert('ลบข้อมูลเรียบร้อยแล้ว'); window.location='alert_setting.php';</script>";
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
