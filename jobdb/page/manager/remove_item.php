<?php
session_start();

// ตรวจสอบว่ามีการส่งข้อมูลสินค้าที่ต้องการลบเข้ามาหรือไม่
if (isset($_POST['index'])) {
    $index = $_POST['index'];

    // ตรวจสอบว่ามีสินค้านี้ในตะกร้าหรือไม่
    if (isset($_SESSION['cart'][$index])) {
        // ลบสินค้านี้ออกจากตะกร้า
        unset($_SESSION['cart'][$index]);
    }
}

// หลังจากลบสินค้าแล้ว ให้กลับไปที่หน้า cart.php
header('Location: cart.php');
exit();
