<?php
session_start();

// ตรวจสอบว่ามีการส่งข้อมูลจากหน้าสินค้าหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["confirm_order"])) { // ตรวจสอบว่าการส่งข้อมูลไม่ใช่การยืนยันคำสั่งซื้อ
    $product_id = $_POST["product_id"];
    $product_name = $_POST["product_name"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];

    // เก็บข้อมูลสินค้าใน session
    $_SESSION["cart"][] = [
        "product_id" => $product_id,
        "product_name" => $product_name,
        "price" => $price,
        "quantity" => $quantity
    ];
}

// คำนวณยอดรวมสินค้า
$total_price = 0;
if (isset($_SESSION["cart"])) {
    foreach ($_SESSION["cart"] as $item) {
        $total_price += $item["price"] * $item["quantity"];
    }
}

// ยืนยันการสั่งซื้อ
if (isset($_POST["confirm_order"])) {
    // ย้ายข้อมูลสินค้าทั้งหมดไปยังหน้าติดตามสถานะการจัดส่ง
    $_SESSION["order_tracking"] = $_SESSION["cart"];
    unset($_SESSION["cart"]); // ลบข้อมูลในตะกร้าสินค้า
    header("Location: tracking.php"); // ไปยังหน้าติดตามสถานะการจัดส่ง
    exit;
}
?>

<!doctype html>
<html lang="en">
<!-- ส่วนหัวของเอกสาร HTML -->

<head>
    <!-- กำหนดการเข้ารหัสอักขระของหน้าเว็บเป็น UTF-8 -->
    <meta charset="utf-8">
    <!-- กำหนดให้หน้าเว็บแสดงผลอย่างเหมาะสมบนอุปกรณ์ต่าง ๆ เช่น มือถือและแท็บเล็ต -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- ชื่อของหน้าเว็บ -->
    <title>Cart</title>
    <!-- นำเข้า CSS ของ Bootstrap เวอร์ชัน 5.3.3 จาก CDN เพื่อช่วยในการจัดรูปแบบของหน้าเว็บ -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<!-- เริ่มต้นส่วนเนื้อหาของเอกสาร HTML -->

<body>
    <!-- กำหนดคอนเทนเนอร์หลักสำหรับเนื้อหาของหน้าเว็บ โดยเว้นระยะห่างด้านบน 5 หน่วย (mt-5) -->
    <div class="container mt-5">
        <!-- หัวข้อของหน้าเว็บ เป็นข้อความสีเขียวขนาดใหญ่ (h3) จัดกึ่งกลาง (text-center) -->
        <h3 class="text-success text-center">Shopping Cart</h3>
        <!-- ตรวจสอบว่ามีสินค้าที่อยู่ในตะกร้า (เซสชัน "cart") หรือไม่ ถ้ามีให้แสดงตารางสินค้าในตะกร้า -->
        <?php if (!empty($_SESSION["cart"])) { ?>
            <!-- ตารางแสดงรายการสินค้าในตะกร้า -->
            <table class="table table-bordered table-striped text-center">
                <!-- ส่วนหัวของตาราง แสดงชื่อคอลัมน์ -->
                <thead class="table-success">
                    <tr>
                        <th>Product Name</th> <!-- คอลัมน์สำหรับชื่อสินค้า -->
                        <th>Price</th> <!-- คอลัมน์สำหรับราคาสินค้า -->
                        <th>Quantity</th> <!-- คอลัมน์สำหรับจำนวนสินค้าที่สั่ง -->
                        <th>Subtotal</th> <!-- คอลัมน์สำหรับราคารวมของสินค้าแต่ละรายการ -->
                    </tr>
                </thead>
                <!-- ส่วนเนื้อหาของตาราง -->
                <tbody>
                    <!-- วนลูปแสดงรายการสินค้าทั้งหมดที่อยู่ในตะกร้า -->
                    <?php foreach ($_SESSION["cart"] as $item) { ?>
                        <tr>
                            <!-- แสดงชื่อสินค้า โดยใช้ htmlspecialchars() เพื่อป้องกันการแสดงผลที่ไม่ถูกต้องหรือการโจมตีแบบ XSS -->
                            <td><?= htmlspecialchars($item["product_name"]) ?></td>
                            <!-- แสดงราคาสินค้าในรูปแบบทศนิยม 2 ตำแหน่ง โดยใช้ htmlspecialchars() และ number_format() -->
                            <td>$<?= htmlspecialchars(number_format($item["price"], 2)) ?></td>
                            <!-- แสดงจำนวนสินค้าที่สั่ง -->
                            <td><?= htmlspecialchars($item["quantity"]) ?></td>
                            <!-- แสดงราคารวมของสินค้าแต่ละรายการ (ราคาสินค้า x จำนวนสินค้า) -->
                            <td>$<?= htmlspecialchars(number_format($item["price"] * $item["quantity"], 2)) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <!-- ส่วนท้ายของตาราง -->
                <tfoot>
                    <tr>
                        <!-- รวมคอลัมน์ 3 คอลัมน์ เพื่อแสดงข้อความ "Total:" ทางขวา (text-end) -->
                        <th colspan="3" class="text-end">Total:</th>
                        <!-- แสดงราคารวมของสินค้าทั้งหมดในตะกร้า โดยใช้ number_format() เพื่อแสดงรูปแบบทศนิยม 2 ตำแหน่ง -->
                        <th>$<?= number_format($total_price, 2) ?></th>
                    </tr>
                </tfoot>
            </table>
            <!-- ฟอร์มสำหรับปุ่มยืนยันการสั่งซื้อ -->
            <form method="post">
                <!-- ปุ่มยืนยันการสั่งซื้อ (Confirm Order) เต็มความกว้าง (w-100) และมีสีเขียว (btn-success) -->
                <button type="submit" name="confirm_order" class="btn btn-success w-100">Confirm Order</button>
            </form>
        <!-- ถ้าตะกร้าสินค้าว่าง จะแสดงข้อความว่า "Your cart is empty." -->
        <?php } else { ?>
            <p class="text-center">Your cart is empty.</p>
        <?php } ?>
    </div>
    <!-- นำเข้า JS ของ Bootstrap เพื่อใช้งานฟังก์ชันต่าง ๆ ในหน้าเว็บ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
