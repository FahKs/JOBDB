<?php
session_start();

// ตรวจสอบว่ามีการยืนยันคำสั่งซื้อและข้อมูลอยู่ใน session หรือไม่
if (!isset($_SESSION["order_tracking"])) {
    echo "No orders to track.";
    exit;
}
?>

<!doctype html>
<html lang="en">
<!-- เอกสาร HTML เริ่มต้นที่นี่ โดยกำหนดประเภทเอกสารเป็น HTML5 และกำหนดภาษาของเอกสารเป็นภาษาอังกฤษ -->

<head>
    <!-- กำหนดการเข้ารหัสอักขระของหน้าเว็บเป็น UTF-8 -->
    <meta charset="utf-8">
    <!-- กำหนดให้หน้าเว็บแสดงผลอย่างเหมาะสมบนอุปกรณ์ต่าง ๆ เช่น มือถือและแท็บเล็ต -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- ชื่อของหน้าเว็บที่จะแสดงในแท็บของเบราว์เซอร์ -->
    <title>Order Tracking</title>
    <!-- นำเข้า CSS ของ Bootstrap เวอร์ชัน 5.3.3 จาก CDN เพื่อช่วยในการจัดรูปแบบของหน้าเว็บ -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<!-- เริ่มต้นส่วนเนื้อหาของเอกสาร HTML -->

<body>
    <!-- กำหนดคอนเทนเนอร์หลักสำหรับเนื้อหาของหน้าเว็บ โดยเว้นระยะห่างด้านบน 5 หน่วย (mt-5) -->
    <div class="container mt-5">
        <!-- หัวข้อของหน้าเว็บ เป็นข้อความสีเขียวขนาดใหญ่ (h3) จัดกึ่งกลาง (text-center) -->
        <h3 class="text-success text-center">Order Tracking</h3>
        <!-- ตารางสำหรับแสดงรายการสั่งซื้อ -->
        <table class="table table-bordered table-striped text-center">
            <!-- ส่วนหัวของตาราง แสดงชื่อคอลัมน์ -->
            <thead class="table-success">
                <tr>
                    <th>Product Name</th> <!-- คอลัมน์สำหรับชื่อสินค้า -->
                    <th>Price</th> <!-- คอลัมน์สำหรับราคาสินค้า -->
                    <th>Quantity</th> <!-- คอลัมน์สำหรับจำนวนสินค้าที่สั่ง -->
                    <th>Status</th> <!-- คอลัมน์สำหรับสถานะของการสั่งซื้อ -->
                </tr>
            </thead>
            <!-- ส่วนเนื้อหาของตาราง -->
            <tbody>
                <!-- วนลูปแสดงรายการสินค้าทั้งหมดในเซสชัน "order_tracking" -->
                <?php foreach ($_SESSION["order_tracking"] as $item) { ?>
                    <tr>
                        <!-- แสดงชื่อสินค้า โดยใช้ htmlspecialchars() เพื่อป้องกันการแสดงผลที่ไม่ถูกต้องหรือการโจมตีแบบ XSS -->
                        <td><?= htmlspecialchars($item["product_name"]) ?></td>
                        <!-- แสดงราคาสินค้าในรูปแบบทศนิยม 2 ตำแหน่ง โดยใช้ htmlspecialchars() และ number_format() -->
                        <td>$<?= htmlspecialchars(number_format($item["price"], 2)) ?></td>
                        <!-- แสดงจำนวนสินค้าที่สั่ง โดยใช้ htmlspecialchars() เพื่อป้องกันการแสดงผลที่ไม่ถูกต้อง -->
                        <td><?= htmlspecialchars($item["quantity"]) ?></td>
                        <!-- แสดงสถานะของการสั่งซื้อ สามารถเปลี่ยนสถานะได้ตามที่ต้องการ -->
                        <td>Processing</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <!-- นำเข้า JS ของ Bootstrap เพื่อใช้งานฟังก์ชันต่าง ๆ ในหน้าเว็บ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

