<?php
session_start();
include '../../service/condb.php';

$conn = new ConnectionDatabase();
$conn = $conn->connect();

// ตรวจสอบการอัปเดตสถานะคำสั่งซื้อ
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];

    // อัปเดตสถานะคำสั่งซื้อในฐานข้อมูล
    $update_query = "UPDATE orders SET order_status='$order_status' WHERE order_id='$order_id'";
    
    if (mysqli_query($conn, $update_query)) {
        $message = "<div class='alert alert-success text-center'>สถานะคำสั่งซื้อถูกอัปเดตเรียบร้อยแล้ว</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>เกิดข้อผิดพลาดในการอัปเดตสถานะ: " . mysqli_error($conn) . "</div>";
    }
}

// ดึงข้อมูลคำสั่งซื้อจากฐานข้อมูล
$query = "SELECT * FROM orders";
$result = mysqli_query($conn, $query);

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Tracking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #f8f9fa;
            padding: 20px;
            border-right: 1px solid #ddd;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            z-index: 1000;
        }
        .sidebar h4 {
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar .nav-link {
            color: #333;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .sidebar .nav-link:hover {
            background-color: #e9ecef;
        }
        .sidebar .nav-link.active {
            background-color: #28a745;
            color: white;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        .content {
            margin-left: 240px;
            padding: 20px;
            width: 100%;
        }
        .table thead {
            background-color: #28a745;
            color: white;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8f9fa;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4>Menu</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="list_user.php">
                    <i class="fas fa-user"></i> Manage
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="inventory.php">
                    <i class="fas fa-box"></i> Inventory
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="admin_tracking.php">
                    <i class="fas fa-car"></i> Tracking
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="alert_setting.php">
                    <i class="fas fa-cog"></i> Alert Setting
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="reporting.php">
                    <i class="fas fa-file-alt"></i> Report
                </a>
            </li>
        </ul>
        <hr>
        <a href="singout.php" class="nav-link text-danger">
            <i class="fas fa-sign-out-alt"></i> Sign Out
        </a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container mt-5">
            <h3 class="text-success text-center">Admin - การติดตามการสั่งซื้อ</h3>

            <!-- การแจ้งเตือนการอัปเดตสถานะ -->
            <?= $message ?>

            <!-- ตารางแสดงคำสั่งซื้อ -->
            <table class="table table-bordered table-striped text-center mt-4">
                <thead class="table-success">
                    <tr>
                        <th>Order ID</th>
                        <th>Store ID</th>
                        <th>Total Price</th>
                        <th>Order Status</th>
                        <th>Update Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['order_id']); ?></td>
                                <td><?= htmlspecialchars($row['store_id']); ?></td>
                                <td>฿<?= number_format($row['total_price'], 2); ?></td>
                                <td><?= htmlspecialchars(ucfirst($row['order_status'])); ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="order_id" value="<?= $row['order_id']; ?>">
                                        <select name="order_status" class="form-select">
                                            <option value="จัดส่ง" <?= ($row['order_status'] == 'จัดส่ง') ? 'selected' : ''; ?>>จัดส่ง</option>
                                            <option value="รอตรวจสอบ" <?= ($row['order_status'] == 'รอตรวจสอบ') ? 'selected' : ''; ?>>รอตรวจสอบ</option>
                                            <option value="รายงานปัญหา" <?= ($row['order_status'] == 'รายงานปัญหา') ? 'selected' : ''; ?>>รายงานปัญหา</option>
                                            <option value="ส่งคืน" <?= ($row['order_status'] == 'ส่งคืน') ? 'selected' : ''; ?>>ส่งคืน</option>
                                            <option value="สินค้าเสียหาย" <?= ($row['order_status'] == 'สินค้าเสียหาย') ? 'selected' : ''; ?>>สินค้าเสียหาย</option>
                                            <option value="สินค้าอยู่ในคลัง" <?= ($row['order_status'] == 'สินค้าอยู่ในคลัง') ? 'selected' : ''; ?>>สินค้าอยู่ในคลัง</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-primary btn-sm mt-2">อัปเดต</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">ไม่พบคำสั่งซื้อ</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
