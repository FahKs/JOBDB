<?php
// เชื่อมต่อฐานข้อมูลและดึงข้อมูลใบสั่งซื้อที่เกี่ยวข้อง
include '../../service/condb.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // สร้าง instance ของ ConnectionDatabase
    $db = new ConnectionDatabase();
    $conn = $db->connect();

    // ดึงข้อมูลการสั่งซื้อจากตาราง orders โดยใช้ order_id
    $sql = "SELECT * FROM orders WHERE order_id = '$order_id'";
    $result = $db->executeQuery($sql);

    // ตรวจสอบว่ามีข้อมูลหรือไม่
    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
    } else {
        echo "ไม่พบข้อมูลการสั่งซื้อ";
        exit;
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    $db->close();
} else {
    echo "ไม่พบหมายเลขการสั่งซื้อ";
    exit;
}
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ใบสรุปการสั่งซื้อ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles */
        .order-summary {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .order-summary h5 {
            font-weight: bold;
        }

        .order-summary p {
            font-size: 1.1rem;
        }

        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        @media (max-width: 768px) {
            .order-summary {
                padding: 15px;
            }

            .order-summary h5, .order-summary p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center text-success">ใบสรุปการสั่งซื้อ</h3>

        <!-- Responsive Order Summary Card -->
        <div class="order-summary mt-4">
            <div class="row">
                <div class="col-md-6">
                    <h5>รหัสการสั่งซื้อ:</h5>
                    <p><?= $order['order_id']; ?></p>
                </div>
                <div class="col-md-6">
                    <h5>ชื่อลูกค้า:</h5>
                    <p><?= htmlspecialchars($order['customer_name']); ?></p>
                </div>
                <div class="col-md-6">
                    <h5>จำนวนชุด:</h5>
                    <p><?= htmlspecialchars($order['total_sets']); ?></p>
                </div>
                <div class="col-md-6">
                    <h5>ราคารวม:</h5>
                    <p>฿<?= number_format($order['total_price'], 2); ?></p>
                </div>
                <div class="col-md-6">
                    <h5>สถานะ:</h5>
                    <p><?= htmlspecialchars($order['order_status']); ?></p>
                </div>
                <div class="col-md-6">
                    <h5>วันที่จัดส่ง:</h5>
                    <p><?= htmlspecialchars($order['delivery_date']); ?></p>
                </div>
            </div>

            <!-- Button to Tracking -->
            <div class="text-center mt-4">
                <a href="tracking.php" class="btn btn-primary w-100">ติดตามการสั่งซื้อ</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
