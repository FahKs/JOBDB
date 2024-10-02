<?php
session_start();
include 'functions.php'; // เชื่อมต่อกับฟังก์ชันสำหรับการดึงข้อมูลสินค้า

// ตรวจสอบการเพิ่มสินค้าในตะกร้า
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // ตรวจสอบว่ามีตะกร้าอยู่แล้วหรือไม่ ถ้าไม่มีก็สร้างใหม่
    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = [];
    }

    // ตรวจสอบว่าสินค้าตัวนี้มีอยู่ในตะกร้าแล้วหรือไม่ ถ้ามีให้เพิ่มจำนวน
    if (isset($_SESSION["cart"][$product_id])) {
        $_SESSION["cart"][$product_id] += $quantity;
    } else {
        $_SESSION["cart"][$product_id] = $quantity;
    }

    // ส่งค่าจำนวนสินค้าในตะกร้ากลับไปที่ไคลเอนต์
    echo array_sum($_SESSION["cart"]);
    exit();
}

// ดึงข้อมูลสินค้าจากฐานข้อมูล
$products = getAllProducts(); // เรียกฟังก์ชันจากไฟล์ functions.php

// ตรวจสอบว่ามีการส่งคืนค่าหรือไม่
if ($products === false) {
    die('Error retrieving products.');
}

// ตรวจสอบจำนวนสินค้าภายในตะกร้า
$cart_count = isset($_SESSION["cart"]) ? array_sum($_SESSION["cart"]) : 0;

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // ดึงข้อมูลสินค้าตาม ID
    $product = getProductById($product_id);
    
    if ($product) {
        $item = [
            "product_id" => $product["listproduct_id"],
            "product_name" => $product["product_name"],
            "price_set" => $product["price_set"],
            "quantity" => $quantity
        ];

        // ตรวจสอบว่ามีตะกร้าอยู่แล้วหรือไม่ ถ้าไม่มีก็สร้างใหม่
        if (!isset($_SESSION["cart"])) {
            $_SESSION["cart"] = [];
        }

        // เพิ่มสินค้าลงในตะกร้า
        $_SESSION["cart"][] = $item;
    }

    // ส่งกลับไปที่หน้า product_list.php
    header("Location: product_list.php");
    exit();
}
?>

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding-top: 70px; }
        .navbar { position: fixed; top: 0; width: 100%; z-index: 1000; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: #ffffff; }
        .navbar-brand { font-size: 24px; font-weight: bold; }
        .cart-icon {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }
        .cart-icon .badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 4px 8px;
            font-size: 12px;
        }
        .cart-popup {
            position: absolute;
            top: 50px;
            right: 0;
            width: 300px;
            border: 1px solid #ddd;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none; /* ซ่อน Popup */
        }
        .cart-popup .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .cart-popup .cart-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 10px;
        }
        .cart-popup .cart-item h6 {
            flex: 1;
            margin: 0;
            font-size: 14px;
        }
        .cart-popup .cart-item .remove-item {
            color: red;
            cursor: pointer;
        }
        .cart-popup .cart-footer {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-top: 1px solid #eee;
        }
        .sidebar { 
            width: 220px; 
            height: 100vh; 
            background-color: #ffffff; 
            padding: 20px; 
            border-right: 1px solid #ddd; 
            position: fixed; 
            top: 70px; 
            left: 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow-y: auto; /* เพิ่ม scrollbar หากเนื้อหายาว */
        }
        .sidebar h4 { 
            text-align: center; 
            margin-bottom: 30px; 
            font-weight: bold;
        }
        .sidebar .nav-link { 
            color: #333; 
            display: flex; 
            align-items: center; 
            padding: 10px 15px; 
            border-radius: 5px; 
            transition: background-color 0.3s; 
            font-size: 16px;
            font-weight: 400; /* ปรับฟอนต์ให้บางลง */
        }
        .sidebar .nav-link:hover { 
            background-color: #e9ecef; 
        }
        .sidebar .nav-link.active { 
            background-color: #28a745; 
            color: white; 
            font-weight: 400; /* ปรับฟอนต์ให้บางลงเช่นกันสำหรับ active link */
        }
        .sidebar .nav-link i { 
            margin-right: 10px; 
            font-size: 18px;
        }
        .sidebar .text-danger { 
            font-size: 18px; 
            font-weight: 500; 
            margin-top: 30px; 
        }
        .content { 
            margin-left: 240px; 
            padding: 20px; 
            width: calc(100% - 240px); 
        }
        .product-card { 
            margin-bottom: 20px; 
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
            width: 100%; /* ปรับความกว้างของรูปภาพให้เต็มการ์ด */
        }
    </style>
</head>
<body>
  <!-- แถบนำทาง (Navbar) -->
  <nav class="navbar navbar-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Product List</a>
      <div class="cart-icon" onmouseover="showCartPopup()" onmouseout="hideCartPopup()">
        <i class="fas fa-shopping-cart fa-2x"></i>
        <span class="badge" id="cart-count"><?= $cart_count ?></span>
        <!-- Popup สำหรับตะกร้าสินค้า -->
        <div class="cart-popup" id="cartPopup">
          <!-- แสดงรายการสินค้าในตะกร้า -->
          <div id="cartItems">
            <!-- แสดงสินค้าในตะกร้า -->
            <?php
            if (isset($_SESSION["cart"])) {
                foreach ($_SESSION["cart"] as $id => $quantity) {
                    $product = getProductById($id); // ฟังก์ชันที่ใช้ดึงข้อมูลสินค้าจาก ID
                    if ($product !== false) {
                        $imagePath = !empty($product['product_pic']) ? 'http://localhost/jobdb/service/uploads/' . htmlspecialchars($product["product_pic"]) : 'http://localhost/jobdb/service/uploads/default_image.jpg';
                        echo '<div class="cart-item" data-product-id="' . $id . '">
                                <img src="' . $imagePath . '" alt="Product">
                                <h6>' . htmlspecialchars($product['product_name']) . '</h6>
                                <span class="remove-item" onclick="removeItem(' . $id . ')"><i class="fas fa-trash"></i></span>
                              </div>';
                    } else {
                        echo '<div class="cart-item" data-product-id="' . $id . '">
                                <img src="http://localhost/jobdb/service/uploads/default_image.jpg" alt="Product">
                                <h6>สินค้าหมดแล้ว</h6>
                                <span class="remove-item" onclick="removeItem(' . $id . ')"><i class="fas fa-trash"></i></span>
                              </div>';
                    }
                }
            }
            ?>
          </div>
          <!-- Footer ของ Popup -->
          <div class="cart-footer">
            <button class="btn btn-secondary" onclick="window.location.href='product_list.php'">Back to Product</button>
            <button class="btn btn-primary" onclick="window.location.href='cart.php'">Go to Cart</button>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- แถบด้านข้าง (Sidebar) -->
  <div class="sidebar">
    <h4>Menu</h4>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="index.php">
          <i class="fas fa-users"></i> Show Users
        </a>
      </li>
      <li class="nav-item side-menu">
        <a class="nav-link active" href="product_list.php">
          <i class="fas fa-shopping-cart"></i> Order
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href=".php">
          <i class="fas fa-car"></i> Tracking
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href=".php">
          <i class="fas fa-barcode"></i> Scanning
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href=".php">
          <i class="fas fa-warehouse"></i> Inventory
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href=".php">
          <i class="fas fa-exclamation-circle"></i> Report Problem
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="manager_report.php">
          <i class="fas fa-file-alt"></i> Report
        </a>
      </li>
    </ul>
    <hr>
    <a href="../admin/singout.php" class="nav-link text-danger">
      <i class="fas fa-sign-out-alt"></i> Sign Out
    </a>
  </div>

  <!-- เนื้อหาหลักของหน้าเว็บ -->
  <div class="content">
    <div class="container mt-5">
      <div class="row">
        <!-- แสดงผลรายการสินค้า -->
        <?php while ($row = $products->fetch_assoc()): ?>
          <div class="col-md-4 product-card">
            <div class="card">
              <!-- แสดงรูปภาพของสินค้า -->
              <?php
              // ตรวจสอบว่ามีรูปภาพหรือไม่
              if (!empty($row["product_pic"])) {
                  // ใช้ชื่อไฟล์จากฐานข้อมูลและกำหนดพาธไปยังโฟลเดอร์ uploads
                  $imagePath = 'http://localhost/jobdb/service/uploads/' . htmlspecialchars($row["product_pic"]);
              } else {
                  // กรณีไม่มีรูปภาพให้ใช้รูปภาพตัวอย่าง
                  $imagePath = 'http://localhost/jobdb/service/uploads/default_image.jpg'; 
              }
              ?>
              <img src="<?= $imagePath ?>" class="card-img-top" alt="<?= htmlspecialchars($row["product_name"]) ?>">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($row["product_name"]) ?></h5>
                <p class="text-success">฿<?= htmlspecialchars(number_format($row["price_set"], 2)) ?></p>
                <p class="card-text"><?= htmlspecialchars($row["product_info"]) ?></p>
                <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#productModal" onclick="showProductDetails(<?= htmlspecialchars(json_encode($row)) ?>)">Add to Cart</button>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>

  <!-- Modal Popup แสดงรายละเอียดสินค้า -->
  <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- ข้อมูลสินค้าใน modal -->
                <img id="modalProductImage" src="" class="img-fluid mb-3" alt="">
                <h5 id="modalProductName" data-product-id=""></h5> <!-- เพิ่มการตั้งค่า data-product-id -->
                <p class="text-success" id="modalProductPrice"></p>
                <p id="modalProductInfo"></p>
                <p class="text-muted" id="modalProductStock"></p> <!-- แสดงจำนวนสต็อกสินค้า -->

                <!-- ตัวเลือกขนาดของสินค้า -->
                <div id="sizeOption" class="mb-3">
                    <!-- ตัวเลือกขนาดของสินค้าจะแสดงที่นี่ -->
                </div>

                <!-- เลือกจำนวนที่ต้องการสั่งซื้อ -->
                <div class="input-group mb-3">
                    <span class="input-group-text">จำนวน</span>
                    <input type="number" id="quantityInput" class="form-control" min="1" value="1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addToCart()">Add to Cart</button>
            </div>
        </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // ฟังก์ชันสำหรับแสดงข้อมูลสินค้าใน modal
    function showProductDetails(product) {
      const imagePath = product.product_pic ? 'http://localhost/jobdb/service/uploads/' + product.product_pic : 'http://localhost/jobdb/service/uploads/default_image.jpg';
      document.getElementById('modalProductImage').src = imagePath;
      document.getElementById('modalProductName').innerText = product.product_name;
      document.getElementById('modalProductName').setAttribute('data-product-id', product.listproduct_id); // เพิ่มการกำหนดค่า product_id
      document.getElementById('modalProductPrice').innerText = '฿' + parseFloat(product.price_set).toFixed(2);
      document.getElementById('modalProductInfo').innerText = product.product_info;
      document.getElementById('modalProductStock').innerText = `In stock ${product.quantity_set !== undefined ? product.quantity_set : 'N/A'} ชิ้น`; // แก้ไขการแสดงผล

      // ตั้งค่าตัวเลือกขนาดของสินค้า (หากมีข้อมูลขนาด)
      if (product.category === "ซอง") {
        document.getElementById('sizeOption').innerHTML = `
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="productSize" id="size250" value="250g" checked>
                <label class="form-check-label" for="size250">250 กรัม</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="productSize" id="size20" value="20pack">
                <label class="form-check-label" for="size20">20 ซอง</label>
            </div>`;
      } else {
        document.getElementById('sizeOption').innerHTML = ''; // กรณีไม่มีตัวเลือกขนาด
      }

      // ตั้งค่าเริ่มต้นสำหรับจำนวนที่ต้องการซื้อ
      document.getElementById('quantityInput').value = 1;
    }

    // ฟังก์ชันเพิ่มสินค้าในตะกร้า
    function addToCart() {
        // ดึงข้อมูลสินค้าจาก modal
        const product_id = document.getElementById('modalProductName').getAttribute('data-product-id');
        const quantity = document.getElementById('quantityInput').value;

        // ตรวจสอบว่าค่า product_id และ quantity ถูกต้องหรือไม่
        if (!product_id || quantity <= 0) {
            alert('กรุณาเลือกสินค้าที่ต้องการสั่งซื้อและระบุจำนวนที่ถูกต้อง');
            return;
        }

        // ส่งข้อมูลไปยังฝั่ง PHP เพื่อเพิ่มสินค้าลงในตะกร้า
        fetch('product_list.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `add_to_cart=1&product_id=${product_id}&quantity=${quantity}`
        })
        .then(response => response.text())
        .then(data => {
            // อัปเดตจำนวนสินค้าที่ไอคอนตะกร้า
            document.getElementById('cart-count').innerText = data;
            // ปิด Modal Popup
            var productModal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
            productModal.hide();
            // เปลี่ยนไปที่หน้า product_list.php
            window.location.href = "product_list.php";
        })
        .catch(error => console.error('Error:', error));
    }

    // แสดง Popup ของตะกร้าเมื่อเลื่อนเมาส์ไปที่ไอคอน
function showCartPopup() {
  document.getElementById('cartPopup').style.display = 'block'; // แสดง popup
}

// ซ่อน Popup ของตะกร้าเมื่อเลื่อนเมาส์ออกจากไอคอน
function hideCartPopup() {
  document.getElementById('cartPopup').style.display = 'block'; // ซ่อน popup
}

// ฟังก์ชันลบสินค้าในตะกร้า
function removeItem(product_id) {
    // ส่งข้อมูลไปยังฝั่ง PHP เพื่อลบสินค้าจากตะกร้า
    fetch('remove_from_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${product_id}`
    })
    .then(response => response.text())
    .then(data => {
        // อัปเดตจำนวนสินค้าที่ไอคอนตะกร้า
        document.getElementById('cart-count').innerText = data;
        // อัปเดตการแสดงผลใน Popup
        const itemElement = document.querySelector(`.cart-item[data-product-id="${product_id}"]`);
        if (itemElement) {
            itemElement.remove();
        }
    })
    .catch(error => console.error('Error:', error));
}
  </script>
</body>
</html>
