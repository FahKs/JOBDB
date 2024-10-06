<?php
session_start();
include 'functions.php';

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    
    // ตรวจสอบว่ามีสินค้าในตะกร้าแล้วหรือไม่
    if (isset($_SESSION["cart"][$product_id])) {
        // ลบสินค้าจากตะกร้า
        unset($_SESSION["cart"][$product_id]);
    }

    // ส่งค่าจำนวนสินค้าในตะกร้ากลับไปยังไคลเอนต์
    echo array_sum($_SESSION["cart"]);
    exit();
}
?>
