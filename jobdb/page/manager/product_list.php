<?php
// เริ่มต้นเซสชันเพื่อจัดเก็บข้อมูลของผู้ใช้ เช่น สินค้าที่อยู่ในตะกร้า
session_start();

// นำเข้าไฟล์ 'condb.php' ที่มีการเชื่อมต่อฐานข้อมูล
include '../../service/condb.php';

// สร้างอ็อบเจ็กต์จากคลาส ConnectionDatabase
$conn = new ConnectionDatabase();

// เรียกใช้เมธอด connect() เพื่อเชื่อมต่อกับฐานข้อมูล และเก็บการเชื่อมต่อไว้ในตัวแปร $conn
$conn = $conn->connect();

// ตรวจสอบการเชื่อมต่อฐานข้อมูล ถ้าไม่สำเร็จ ให้แสดงข้อความแสดงข้อผิดพลาดและหยุดการทำงานของสคริปต์
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ตรวจสอบว่ามีสินค้าในตะกร้าหรือไม่ ถ้ามีให้นับจำนวนสินค้าในตะกร้า (เก็บในเซสชัน) ถ้าไม่มีให้ใช้ค่าเริ่มต้นเป็น 0
$cart_count = isset($_SESSION["cart"]) ? count($_SESSION["cart"]) : 0;

// ถ้ามีการกดปุ่มลบสินค้าในตะกร้า
if (isset($_POST["remove_product"])) {
    // เก็บดัชนีของสินค้าที่ต้องการลบในตัวแปร $remove_index
    $remove_index = $_POST["remove_index"];
    // ลบสินค้าจากตะกร้า (เซสชัน) โดยใช้ดัชนีที่ระบุ
    unset($_SESSION["cart"][$remove_index]);
    // จัดเรียง index ใหม่ให้กับสินค้าในตะกร้า เพื่อให้ดัชนีต่อเนื่อง
    $_SESSION["cart"] = array_values($_SESSION["cart"]);
}

// อัปเดตจำนวนสินค้าในตะกร้าเมื่อกดปุ่ม 'update_cart'
if (isset($_POST["update_cart"])) {
    // วนลูปผ่านสินค้าที่ส่งมาทั้งหมด โดยใช้ดัชนีและจำนวนสินค้าที่จะอัปเดต
    foreach ($_POST["quantities"] as $index => $quantity) {
        // อัปเดตจำนวนสินค้าที่ตรงกับดัชนีในตะกร้า
        $_SESSION["cart"][$index]["quantity"] = $quantity;
    }
}

// รับค่าค้นหาที่ส่งมาจาก URL ถ้าไม่มีการส่งค่ามาจะใช้ค่าว่าง ('') แทน
$search = isset($_GET['search']) ? $_GET['search'] : '';

// คำสั่ง SQL สำหรับค้นหาสินค้าตามชื่อสินค้า รหัสสินค้า หรือหมวดหมู่ โดยใช้ LIKE เพื่อค้นหาคำที่คล้ายกัน
$query = "SELECT * FROM list_product WHERE listproduct_id LIKE ? OR product_name LIKE ? OR category LIKE ?";

// เตรียมคำสั่ง SQL เพื่อป้องกัน SQL Injection
$stmt = $conn->prepare($query);

// กำหนดค่าสำหรับพารามิเตอร์การค้นหา โดยใช้ % เพื่อทำให้การค้นหาครอบคลุมทุกคำที่มีส่วนประกอบตามที่ค้นหา
$search_param = "%$search%";

// ผูกพารามิเตอร์การค้นหากับคำสั่ง SQL ทั้งสามตำแหน่ง
$stmt->bind_param("sss", $search_param, $search_param, $search_param);

// รันคำสั่ง SQL
$stmt->execute();

// เก็บผลลัพธ์ของการค้นหาในตัวแปร $result
$result = $stmt->get_result();
?>

<!doctype html>
<html lang="en">

<head>
    <!-- กำหนดการเข้ารหัสอักขระของหน้าเว็บเป็น UTF-8 -->
    <meta charset="utf-8">
    <!-- กำหนดให้หน้าเว็บแสดงผลอย่างเหมาะสมบนอุปกรณ์ต่าง ๆ -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- ชื่อหน้าเว็บ -->
    <title>Product List</title>
    <!-- นำเข้า CSS ของ Bootstrap เวอร์ชัน 5.3.3 จาก CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- นำเข้า CSS ของ Font Awesome เวอร์ชัน 6.0.0-beta3 จาก CDN เพื่อใช้ไอคอน -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* กำหนดสีพื้นหลังของ body และเว้นพื้นที่ด้านบนให้กับ navbar */
        body {
            background-color: #f8f9fa;
            padding-top: 70px;
        }

        /* กำหนดสไตล์ของ navbar ให้ติดอยู่ด้านบนและมีเงา */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* กำหนดสไตล์ของแถบด้านข้าง (sidebar) */
        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #f8f9fa;
            padding: 20px;
            border-right: 1px solid #ddd;
            position: fixed;
            margin-top: 70px; /* เว้นพื้นที่สำหรับ navbar */
        }

        /* กำหนดสไตล์หัวข้อในแถบด้านข้าง */
        .sidebar h4 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* กำหนดสไตล์ของลิงก์ในแถบด้านข้าง */
        .sidebar .nav-link {
            color: #333;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        /* เปลี่ยนสีพื้นหลังเมื่อเอาเมาส์ไปชี้ที่ลิงก์ในแถบด้านข้าง */
        .sidebar .nav-link:hover {
            background-color: #e9ecef;
        }

        /* กำหนดสีพื้นหลังของลิงก์ที่ถูกเลือก (active) */
        .sidebar .nav-link.active {
            background-color: #28a745;
            color: white;
        }

        /* กำหนดระยะห่างระหว่างไอคอนกับข้อความในลิงก์ */
        .sidebar .nav-link i {
            margin-right: 10px;
        }

        /* กำหนดสไตล์ของเนื้อหาหลักในหน้าเว็บ (content) */
        .content {
            margin-left: 240px;
            padding: 20px;
            width: 100%;
        }

        /* กำหนดสไตล์ของการ์ดสินค้า */
        .product-card {
            margin-bottom: 20px;
        }

        /* กำหนดสไตล์ของ footer */
        .footer {
            background-color: #28a745;
            color: white;
            padding: 15px;
            text-align: center;
        }

        /* กำหนดสไตล์ของ modal */
        .modal .modal-content {
            border: none;
            border-radius: 8px;
        }

        /* กำหนดสไตล์ของส่วนหัว modal */
        .modal-header {
            border-bottom: none;
        }

        /* กำหนดสไตล์ของส่วนท้าย modal */
        .modal-footer {
            border-top: none;
        }

        /* กำหนดสไตล์ของภาพสินค้าในรายละเอียด */
        .product-detail-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        /* กำหนดสไตล์ของไอคอนตะกร้าสินค้า */
        .cart-icon {
            position: relative;
        }

        /* กำหนดสไตล์ของตัวนับจำนวนสินค้าในตะกร้า */
        .cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 3px 7px;
            font-size: 12px;
        }

        /* กำหนดสไตล์ของตะกร้าสินค้า dropdown */
        .dropdown-cart {
            max-width: 400px;
            min-width: 300px;
        }

        /* กำหนดสไตล์ของรายการสินค้าในตะกร้า */
        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        /* กำหนดสไตล์ของภาพสินค้าในตะกร้า */
        .cart-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 10px;
        }

        /* กำหนดสไตล์ของข้อมูลสินค้าในตะกร้า */
        .cart-item .item-info {
            flex-grow: 1;
        }

        /* กำหนดสไตล์ของข้อความในข้อมูลสินค้า */
        .cart-item .item-info p {
            margin: 0;
        }

        /* กำหนดสไตล์ของข้อความย่อยในข้อมูลสินค้า */
        .cart-item .item-info small {
            color: #888;
        }

        /* กำหนดสไตล์ของปุ่มลบสินค้า */
        .cart-item .remove-btn {
            color: red;
            cursor: pointer;
        }

        /* กำหนดสไตล์ของ footer ในตะกร้าสินค้า */
        .cart-footer {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <!-- ชื่อของร้านค้า -->
            <a class="navbar-brand" href="#">ShopName</a>
            <!-- ปุ่มสำหรับแสดงเมนูในหน้าจอขนาดเล็ก -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- เมนูใน navbar -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- ลิงก์ไปหน้าแรก -->
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <!-- ลิงก์ไปหน้ารายการสินค้า -->
                    <li class="nav-item">
                        <a class="nav-link" href="product_list.php">Products</a>
                    </li>
                    <!-- ลิงก์ไปหน้าติดตามสินค้า -->
                    <li class="nav-item">
                        <a class="nav-link" href="tracking.php">Tracking</a>
                    </li>
                    <!-- เมนู dropdown สำหรับตะกร้าสินค้า -->
                    <li class="nav-item dropdown">
                        <a class="nav-link cart-icon" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- ไอคอนตะกร้าสินค้า -->
                            <i class="fas fa-shopping-cart"></i>
                            <!-- แสดงจำนวนสินค้าในตะกร้า -->
                            <?php if ($cart_count > 0) { ?>
                                <span class="cart-count"><?= $cart_count ?></span>
                            <?php } ?>
                        </a>
                        <!-- แสดงรายการสินค้าในตะกร้าแบบ dropdown -->
                        <div class="dropdown-menu dropdown-menu-end dropdown-cart p-3">
                            <h5 class="mb-3">ตะกร้าสินค้า</h5>
                            <form method="post">
                                <!-- แสดงรายการสินค้าในตะกร้า ถ้ามีสินค้าอยู่ -->
                                <?php if ($cart_count > 0) { ?>
                                    <?php foreach ($_SESSION["cart"] as $index => $item) { ?>
                                        <div class="cart-item">
                                            <!-- รูปภาพของสินค้า -->
                                            <img src="https://via.placeholder.com/50" alt="Product">
                                            <!-- ข้อมูลสินค้า -->
                                            <div class="item-info">
                                                <p><?= htmlspecialchars($item["product_name"]) ?></p>
                                                <small>฿<?= htmlspecialchars(number_format($item["price"], 2)) ?> / <?= htmlspecialchars($item["quantity"]) ?> ชิ้น</small>
                                            </div>
                                            <!-- ป้อนจำนวนสินค้าใหม่ในตะกร้า -->
                                            <input type="number" name="quantities[<?= $index ?>]" value="<?= htmlspecialchars($item["quantity"]) ?>" class="form-control form-control-sm" style="width: 50px;" min="1">
                                            <!-- ปุ่มลบสินค้าออกจากตะกร้า -->
                                            <button type="submit" name="remove_product" value="<?= $index ?>" class="btn btn-sm remove-btn">&times;</button>
                                        </div>
                                    <?php } ?>
                                    <!-- ปุ่มอัปเดตตะกร้าและเริ่มการสั่งซื้อ -->
                                    <div class="cart-footer mt-3">
                                        <button type="submit" name="update_cart" class="btn btn-primary btn-sm">อัปเดตตะกร้า</button>
                                        <a href="cart.php" class="btn btn-success btn-sm">เริ่มการสั่งซื้อ</a>
                                    </div>
                                <?php } else { ?>
                                    <!-- ข้อความแจ้งเมื่อไม่มีสินค้าในตะกร้า -->
                                    <p class="text-center">ไม่มีสินค้าในตะกร้า</p>
                                <?php } ?>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- เนื้อหาหลัก -->
    <div class="content">
        <h3 class="text-success text-center">Product List</h3>
        <div class="container mt-5">
            <div class="row">
                <?php
                // แสดงรายการสินค้า ถ้ามีผลลัพธ์จากการค้นหา
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // กำหนดเส้นทางของภาพสินค้า
                        $imagePath = "../../service/uploads/" . $row["product_pic"];
                        // ถ้าพบภาพสินค้าที่อัปโหลดไว้ให้ใช้ภาพนั้น ถ้าไม่พบให้ใช้ภาพแทน
                        $imageSrc = file_exists($imagePath) ? $imagePath : "https://via.placeholder.com/150";
                ?>
                        <!-- การ์ดสินค้า -->
                        <div class="col-md-4 product-card">
                            <div class="card">
                                <!-- รูปภาพสินค้า -->
                                <img src="<?= htmlspecialchars($imageSrc) ?>" class="card-img-top" alt="<?= htmlspecialchars($row["product_name"]) ?>" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <!-- ชื่อสินค้า -->
                                    <h5 class="card-title"><?= htmlspecialchars($row["product_name"]) ?></h5>
                                    <!-- ราคาสินค้า -->
                                    <p class="text-success">฿<?= htmlspecialchars(number_format($row["price_set"], 2)) ?></p>
                                    <!-- ปุ่มเพิ่มสินค้าลงในตะกร้า -->
                                    <button type="button" class="btn btn-success w-100" onclick="openModal(<?= $row['listproduct_id'] ?>)">Add to Cart</button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal สำหรับแสดงรายละเอียดสินค้า -->
                        <div class="modal fade" id="productModal-<?= $row['listproduct_id'] ?>" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <!-- ชื่อสินค้าในหัว modal -->
                                        <h5 class="modal-title"><?= htmlspecialchars($row["product_name"]) ?></h5>
                                        <!-- ปุ่มปิด modal -->
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <!-- ภาพสินค้ารายละเอียด -->
                                            <div class="col-md-6">
                                                <img src="<?= htmlspecialchars($imageSrc) ?>" class="product-detail-img" alt="<?= htmlspecialchars($row["product_name"]) ?>">
                                            </div>
                                            <!-- ข้อมูลรายละเอียดสินค้าและปุ่มเพิ่มสินค้าลงตะกร้า -->
                                            <div class="col-md-6">
                                                <!-- ราคาสินค้า -->
                                                <h5 class="text-success">฿<?= htmlspecialchars(number_format($row["price_set"], 2)) ?></h5>
                                                <!-- รายละเอียดสินค้า -->
                                                <p><?= htmlspecialchars($row["product_info"]) ?></p>
                                                <form action="cart.php" method="post">
                                                    <!-- ส่งข้อมูลสินค้าไปที่ฟอร์ม -->
                                                    <input type="hidden" name="product_id" value="<?= $row["listproduct_id"] ?>">
                                                    <input type="hidden" name="product_name" value="<?= $row["product_name"] ?>">
                                                    <input type="hidden" name="price" value="<?= $row["price_set"] ?>">
                                                    <!-- ควบคุมจำนวนสินค้าที่จะเพิ่มลงในตะกร้า -->
                                                    <div class="quantity-control d-flex align-items-center mb-3">
                                                        <button type="button" class="btn btn-secondary" onclick="decreaseQuantity(<?= $row['listproduct_id'] ?>)">-</button>
                                                        <input type="number" id="quantity-<?= $row['listproduct_id'] ?>" name="quantity" value="1" min="1" class="form-control mx-2" style="width: 60px;">
                                                        <button type="button" class="btn btn-secondary" onclick="increaseQuantity(<?= $row['listproduct_id'] ?>)">+</button>
                                                    </div>
                                                    <!-- ปุ่มใส่ตะกร้า -->
                                                    <button type="submit" class="btn btn-success w-100">ใส่ตะกร้า</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <!-- ปุ่มปิด modal -->
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ซื้อสินค้าต่อ</button>
                                        <!-- ลิงก์ไปหน้าชำระเงิน -->
                                        <a href="cart.php" class="btn btn-primary">ชำระเงิน</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    // ข้อความแจ้งเมื่อไม่พบสินค้า
                    echo "<p class='text-center'>No products found.</p>";
                }
                ?>
            </div>
        </div>
    </div>
    <!-- นำเข้า JS ของ Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ฟังก์ชันเปิด modal ตามรหัสสินค้าที่ระบุ
        function openModal(productId) {
            var modalId = 'productModal-' + productId;
            var modal = new bootstrap.Modal(document.getElementById(modalId));
            modal.show();
        }

        // ฟังก์ชันเพิ่มจำนวนสินค้า
        function increaseQuantity(productId) {
            var quantityInput = document.getElementById('quantity-' + productId);
            var currentQuantity = parseInt(quantityInput.value);
            quantityInput.value = currentQuantity + 1;
        }

        // ฟังก์ชันลดจำนวนสินค้า
        function decreaseQuantity(productId) {
            var quantityInput = document.getElementById('quantity-' + productId);
            var currentQuantity = parseInt(quantityInput.value);
            if (currentQuantity > 1) {
                quantityInput.value = currentQuantity - 1;
            }
        }
    </script>
</body>

</html>

<?php
// ปิดคำสั่งเตรียม statement
$stmt->close();
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
