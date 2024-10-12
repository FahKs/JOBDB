<?php
session_start();
include 'functions.php'; // เชื่อมต่อกับฟังก์ชันสำหรับการดึงข้อมูลสินค้า

// เชื่อมต่อกับฐานข้อมูล
$db = connectDatabase(); // ฟังก์ชันใน functions.php

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
        $_SESSION["cart"][$product_id]['quantity'] += $quantity;
    } else {
        // ดึงข้อมูลสินค้าจากฐานข้อมูล
        $product = getProductById($product_id); // ฟังก์ชัน getProductById() ที่คุณสร้างไว้ใน functions.php
        if ($product !== false) {
            // เพิ่มสินค้าใหม่ในตะกร้า
            $_SESSION["cart"][$product_id] = [
                'product_name' => $product['product_name'],
                'price_set' => $product['price_set'],
                'quantity' => $quantity,
                'product_pic' => $product['product_pic'], // เพิ่มรูปสินค้าในตะกร้า
            ];
        }
    }

    // ส่งค่าจำนวนสินค้าในตะกร้ากลับไปที่ไคลเอนต์
    echo array_sum(array_column($_SESSION["cart"], 'quantity'));
    exit();
}

// ดึงข้อมูลสินค้าจากฐานข้อมูล
$sql = "SELECT listproduct_id, product_name, price_set, product_info, quantity_set, product_pic FROM list_product WHERE visible = 1";
$products = $db->query($sql);

if ($products === false) {
    die('Error retrieving products.');
}

// ตรวจสอบจำนวนสินค้าภายในตะกร้า
$cart_count = isset($_SESSION["cart"]) ? array_sum(array_column($_SESSION["cart"], 'quantity')) : 0;
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
            overflow-y: auto; 
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
            font-weight: 400; 
        }
        .sidebar .nav-link:hover { 
            background-color: #e9ecef; 
        }
        .sidebar .nav-link.active { 
            background-color: #28a745; 
            color: white; 
            font-weight: 400; 
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
            width: 100%;
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
            <?php
            if (isset($_SESSION["cart"])) {
                $totalPrice = 0;
                foreach ($_SESSION["cart"] as $id => $item) {
                    $imagePath = !empty($item['product_pic']) ? 'http://localhost/jobdb/service/uploads/' . htmlspecialchars($item["product_pic"]) : 'http://localhost/jobdb/service/uploads/default_image.jpg';
                    $itemTotalPrice = $item['price_set'] * $item['quantity'];
                    $totalPrice += $itemTotalPrice;
                    echo '<div class="cart-item" data-product-id="' . $id . '">
                            <img src="' . $imagePath . '" alt="Product">
                            <h6>' . htmlspecialchars($item['product_name']) . '</h6>
                            <p>฿' . number_format($item['price_set'], 2) . ' x ' . $item['quantity'] . '</p>
                            <p>รวม: ฿' . number_format($itemTotalPrice, 2) . '</p>
                            <span class="remove-item" onclick="removeItem(' . $id . ')"><i class="fas fa-trash"></i></span>
                          </div>';
                }
                echo '<div class="cart-footer">รวมทั้งหมด: ฿' . number_format($totalPrice, 2) . '</div>';
            }
            ?>
          </div>
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
        <a class="nav-link" href="dashbord.php">
          <i class="fas fa-tachometer-alt"></i> Dashbord
        </a>
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
        <a class="nav-link" href="tracking.php">
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
              if (!empty($row["product_pic"])) {
                  $imagePath = 'http://localhost/jobdb/service/uploads/' . htmlspecialchars($row["product_pic"]);
              } else {
                  $imagePath = 'http://localhost/jobdb/service/uploads/default_image.jpg'; 
              }
              ?>
              <img src="<?= $imagePath ?>" class="card-img-top" alt="<?= htmlspecialchars($row["product_name"]) ?>">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($row["product_name"]) ?></h5>
                <p class="text-success">฿<?= htmlspecialchars(number_format($row["price_set"], 2)) ?></p>
                <p class="card-text"><?= htmlspecialchars($row["product_info"]) ?></p>
                <p class="text-muted">จำนวนคงเหลือ: <?= isset($row["quantity_set"]) ? htmlspecialchars($row["quantity_set"]) : 'N/A'; ?> ชิ้น</p>
                <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#productModal" onclick="showProductDetails(<?= htmlspecialchars(json_encode($row)) ?>)">เพิ่มในตะกร้า</button>
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
                <h5 id="modalProductName" data-product-id=""></h5> 
                <p class="text-success" id="modalProductPrice"></p>
                <p id="modalProductInfo"></p>
                <p class="text-muted" id="modalProductStock"></p>

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
      document.getElementById('modalProductName').setAttribute('data-product-id', product.listproduct_id); 
      document.getElementById('modalProductPrice').innerText = '฿' + parseFloat(product.price_set).toFixed(2);
      document.getElementById('modalProductInfo').innerText = product.product_info;
      document.getElementById('modalProductStock').innerText = `จำนวนคงเหลือ: ${product.quantity_set ? product.quantity_set : 'N/A'} ชิ้น`;

      document.getElementById('quantityInput').value = 1;
    }

    // ฟังก์ชันเพิ่มสินค้าในตะกร้า
    function addToCart() {
        const product_id = document.getElementById('modalProductName').getAttribute('data-product-id');
        const quantity = document.getElementById('quantityInput').value;

        if (!product_id || quantity <= 0) {
            alert('กรุณาเลือกสินค้าที่ต้องการสั่งซื้อและระบุจำนวนที่ถูกต้อง');
            return;
        }

        fetch('product_list.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `add_to_cart=1&product_id=${product_id}&quantity=${quantity}`
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('cart-count').innerText = data;
            var productModal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
            productModal.hide();
            window.location.href = "product_list.php";
        })
        .catch(error => console.error('Error:', error));
    }

    function showCartPopup() {
        document.getElementById('cartPopup').style.display = 'block';
    }

    function hideCartPopup() {
        document.getElementById('cartPopup').style.display = 'block';
    }

    // ฟังก์ชันลบสินค้าในตะกร้า
    function removeItem(product_id) {
        fetch('remove_from_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${product_id}`
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('cart-count').innerText = data;
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
