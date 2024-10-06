<?php
session_start();

// ตรวจสอบว่ามีข้อมูลสินค้าในตะกร้าหรือไม่
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    //echo '<pre>';
    //print_r($_SESSION['cart']);
    //echo '</pre>';
} else {
   // echo 'Cart is empty';
    exit;
}

// ตรวจสอบการยืนยันคำสั่งซื้อ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_order"])) {
    $_SESSION["order_tracking"] = $_SESSION["cart"];
    unset($_SESSION["cart"]);
    
    $_SESSION["branch_address"] = $_POST["branch_address"];
    $_SESSION["payment_method"] = $_POST["payment_method"];
    
    header("Location: tracking.php");
    exit;
}

// กำหนดค่าเริ่มต้นให้ตัวแปรยอดรวม
$total_price = 0;

// คำนวณยอดรวมสินค้า
foreach ($_SESSION["cart"] as $item) {
    if (is_array($item)) {
        $total_price += $item["price_set"] * $item["quantity"];
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-success text-center">Shopping Cart</h3>
        
        <div class="text-start mb-3">
            <a href="product_list.php" class="btn btn-secondary">Back to Product List</a>
        </div>

        <!-- แสดงรายการสินค้า -->
        <table class="table table-bordered table-striped text-center">
            <thead class="table-success">
                <tr>
                    <th>#</th>
                    <th>รูปสินค้า</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION["cart"] as $index => $item) { 
                    if (is_array($item)) {
                        // ตรวจสอบพาธรูปภาพ
                        $imagePath = !empty($item["product_pic"]) ? 'http://localhost/jobdb/service/uploads/' . htmlspecialchars($item["product_pic"]) : 'http://localhost/jobdb/service/uploads/default_image.jpg';
                ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($item["product_name"]) ?>" width="50" height="50"></td> <!-- แสดงรูปสินค้า -->
                        <td><?= htmlspecialchars($item["product_name"]) ?></td>
                        <td>฿<?= htmlspecialchars(number_format($item["price_set"], 2)) ?></td>
                        <td><?= htmlspecialchars($item["quantity"]) ?></td>
                        <td>฿<?= htmlspecialchars(number_format($item["price_set"] * $item["quantity"], 2)) ?></td>
                        <td>
                            <!-- ปุ่มลบสินค้า -->
                            <form method="post" action="remove_item.php" class="d-inline">
                                <input type="hidden" name="index" value="<?= $index ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php 
                    }
                } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">Total:</th>
                    <th>฿<?= number_format($total_price, 2) ?></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>

        <!-- ฟอร์มที่อยู่และการชำระเงิน -->
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <h5 class="bg-light p-3">ข้อมูลสำหรับจัดส่งสินค้า</h5>
                <label for="name" class="form-label">ชื่อ-นามสกุล:</label>
                <input type="text" name="customer_name" id="name" class="form-control" placeholder="ชื่อ-นามสกุล" required>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">ที่อยู่จัดส่งสินค้า:</label>
                <textarea name="shipping_address" id="address" class="form-control" rows="3" placeholder="กรอกที่อยู่" required></textarea>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">เบอร์โทรศัพท์:</label>
                <input type="text" name="phone_number" id="phone" class="form-control" placeholder="เบอร์โทรศัพท์" required>
            </div>

            <div class="mb-3">
                <label for="payment_slip" class="form-label">หลักฐานการชำระเงิน:</label>
                <input type="file" name="payment_slip" id="payment_slip" class="form-control" accept="image/*" required>
            </div>

            <div class="mb-3">
                <label for="branch_address" class="form-label">Branch Address:</label>
                <input type="text" name="branch_address" id="branch_address" class="form-control" placeholder="Branch Address" required>
            </div>

            <div class="mb-3">
                <label for="payment_method" class="form-label">Payment Method:</label>
                <select name="payment_method" id="payment_method" class="form-select" required>
                    <option value="bank">Bank Transfer</option>
                    <option value="credit">Credit Card</option>
                </select>
            </div>

            <button type="submit" name="confirm_order" class="btn btn-success w-100">Confirm Order</button>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
