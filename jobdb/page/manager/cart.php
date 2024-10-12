<?php
session_start();

// ตรวจสอบว่ามีสินค้าในตะกร้าหรือไม่ ถ้าไม่มี ให้กำหนด array ว่างเปล่า
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ตรวจสอบการลบสินค้าออกจากตะกร้า
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_item"])) {
    $index = $_POST["index"];
    unset($_SESSION["cart"][$index]);
}

// ตรวจสอบการยืนยันคำสั่งซื้อ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_order"])) {
    include '../../service/condb.php';
    $db = new ConnectionDatabase();
    $conn = $db->connect();

    // ข้อมูลสำหรับบันทึกคำสั่งซื้อ
    $customer_name = $_POST['customer_name'];
    $total_price = 0;
    $total_sets = 0;
    $store_id = 1; // สมมุติว่าใช้รหัสร้านค้า 1

    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price_set'] * $item['quantity'];
        $total_sets += $item['quantity'];
    }

    $order_status = 'จัดส่ง'; // สถานะเริ่มต้น
    $delivery_date = date('Y-m-d H:i:s', strtotime('+3 days')); // สมมุติวันจัดส่ง

    // บันทึกข้อมูลลงฐานข้อมูล
    $sql = "INSERT INTO orders (store_id, total_sets, total_price, order_status, customer_name, delivery_date)
            VALUES ('$store_id', '$total_sets', '$total_price', '$order_status', '$customer_name', '$delivery_date')";

    if ($db->executeQuery($sql)) {
        // รับ order_id ที่เพิ่งสร้างขึ้น
        $order_id = $conn->insert_id;

        // เมื่อยืนยันคำสั่งซื้อ ทำการเปลี่ยนหน้าไปยัง purchase_receipt.php พร้อมกับส่งค่า order_id ไปด้วย
        header("Location: purchase_receipt.php?order_id=$order_id");
        exit;
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    $db->close();
}

// กำหนดยอดรวมเริ่มต้น
$total_price = 0;
if (!empty($_SESSION["cart"])) {
    foreach ($_SESSION["cart"] as $item) {
        if (is_array($item)) {
            $total_price += $item["price_set"] * $item["quantity"];
        }
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

        <!-- แสดงรายการสินค้าในตะกร้า -->
        <table class="table table-bordered table-striped text-center">
            <thead class="table-success">
                <tr>
                    <th>#</th>
                    <th>Product Image</th> <!-- เพิ่มคอลัมน์รูปภาพสินค้า -->
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($_SESSION["cart"])): ?>
                    <?php foreach ($_SESSION["cart"] as $index => $item): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><img src="http://localhost/jobdb/service/uploads/<?= htmlspecialchars($item["product_pic"]) ?>" width="90" height="0"></td>
                            <td><?= htmlspecialchars($item["product_name"]) ?></td>
                            <td>฿<?= htmlspecialchars(number_format($item["price_set"], 2)) ?></td>
                            <td><?= htmlspecialchars($item["quantity"]) ?></td>
                            <td>฿<?= htmlspecialchars(number_format($item["price_set"] * $item["quantity"], 2)) ?></td>
                            <td>
                                <!-- ปุ่มลบสินค้า -->
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="index" value="<?= $index ?>">
                                    <button type="submit" name="remove_item" class="btn btn-danger btn-sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No items in cart</td>
                    </tr>
                <?php endif; ?>
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

            <!-- แสดง QR Code สำหรับการชำระเงิน -->
            <div class="text-center mt-4">
                <h5>Scan QR Code to Pay with PromptPay</h5>
                <img src="https://promptpay.io/0919750409/<?= number_format($total_price, 2) ?>" alt="PromptPay QR Code">
            </div>

            <div class="mb-3">
                <label for="payment_slip" class="form-label">หลักฐานการชำระเงิน:</label>
                <input type="file" name="payment_slip" id="payment_slip" class="form-control" accept="image/*" required>
            </div>

            <div class="mb-3">
                <label for="branch_address" class="form-label">Branch Address:</label>
                <input type="text" name="branch_address" id="branch_address" class="form-control" placeholder="Branch Address" required>
            </div>

            <button type="submit" name="confirm_order" class="btn btn-success w-100">Confirm Order</button>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
