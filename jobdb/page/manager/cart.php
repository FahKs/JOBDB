<?php
session_start();

// ตรวจสอบว่ามีข้อมูลสินค้าในตะกร้าหรือไม่ และตรวจสอบชนิดของข้อมูล
if (!isset($_SESSION["cart"]) || !is_array($_SESSION["cart"]) || empty($_SESSION["cart"])) {
    echo "<p class='text-center'>Your cart is empty.</p>";
    exit;
}

// ตรวจสอบว่ามีการส่งข้อมูลจากการกดปุ่ม "Confirm Order" หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_order"])) {
    // ย้ายข้อมูลสินค้าจากตะกร้าไปยังคำสั่งซื้อ
    $_SESSION["order_tracking"] = $_SESSION["cart"];
    // ลบข้อมูลในตะกร้า
    unset($_SESSION["cart"]);
    // เปลี่ยนเส้นทางไปยังหน้าติดตามสถานะการจัดส่ง
    header("Location: tracking.php");
    exit;
}

// คำนวณยอดรวมของสินค้า
$total_price = 0;
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
        <table class="table table-bordered table-striped text-center">
            <thead class="table-success">
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION["cart"] as $index => $item) { 
                    if (is_array($item)) { // ตรวจสอบว่า item เป็น array หรือไม่
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item["product_name"]) ?></td>
                        <td>$<?= htmlspecialchars(number_format($item["price_set"], 2)) ?></td>
                        <td><?= htmlspecialchars($item["quantity"]) ?></td>
                        <td>$<?= htmlspecialchars(number_format($item["price_set"] * $item["quantity"], 2)) ?></td>
                        <td>
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
                    <th colspan="3" class="text-end">Total:</th>
                    <th>$<?= number_format($total_price, 2) ?></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        <form method="post">
            <button type="submit" name="confirm_order" class="btn btn-success w-100">Confirm Order</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

