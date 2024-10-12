<?php
include '../../service/condb.php'; // เชื่อมต่อฐานข้อมูล

// สร้าง instance ของ ConnectionDatabase
$db = new ConnectionDatabase();

// เชื่อมต่อกับฐานข้อมูล
$conn = $db->connect();

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if (!$db->isConnectionValid()) {
    die("Connection failed");
}

// ฟังก์ชันดึงการแจ้งเตือนจากฐานข้อมูล
function fetchNotifications($conn) {
    $query = "SELECT * FROM alerts_setting WHERE set_expday <= 10 OR set_lowstock <= 10 ORDER BY update_at DESC";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '
            <div class="col-md-6">
                <div class="card notification-card mb-3">
                    <div class="card-header bg-success text-white">
                        Product ID: ' . htmlspecialchars($row['listproduct_id']) . '
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">';

            // แสดงข้อมูลการแจ้งเตือนวันหมดอายุ ถ้ามี
            if ($row['setting_info'] == 'Exp') {
                echo '
                    <li class="list-group-item">
                        <span class="badge bg-warning text-dark">Expiry Warning</span>
                        Expires in ' . htmlspecialchars($row['set_expday']) . ' days.
                    </li>';
            }

            // แสดงข้อมูลการแจ้งเตือนสินค้าใกล้หมดคลัง ถ้ามี
            if ($row['setting_info'] == 'lw') {
                echo '
                    <li class="list-group-item">
                        <span class="badge bg-danger">Low Stock Warning</span>
                        Only ' . htmlspecialchars($row['set_lowstock']) . ' left in stock.
                    </li>';
            }

            echo '
                        </ul>
                        <p class="card-text mt-3"><small class="text-muted">Last updated: ' . htmlspecialchars($row['update_at']) . '</small></p>
                    </div>
                </div>
            </div>';
        }
    } else {
        echo "<p>No notifications at this time.</p>";
    }
}

// ฟังก์ชันดึงการแจ้งเตือนแบบเรียลไทม์ด้วย AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    fetchNotifications($conn);
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!-- โหลด jQuery สำหรับ AJAX -->
    <style>
        body {
            display: flex;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #f8f9fa;
            color: #333;
            padding: 20px;
            border-right: 1px solid #ddd;
            position: fixed;
        }
        .sidebar h4 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
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
        .notification-card {
            margin-bottom: 15px;
        }
        .notification-card .card-header {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>Menu</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="staff_dashboard.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item dropdown">
                <!-- ลิงก์หลักของเมนู Inventory -->
                <a class="nav-link" href="../staff/index.php">
                    <i class="fas fa-warehouse"></i> Inventory
                </a>
                <!-- รายการเมนูย่อย -->
                <ul class="nav flex-column">
                    <!-- ลิงก์ไปหน้ารายการแจ้งปัญหา -->
                    <li class="nav-item side-menu">
                        <a class="nav-link" href="product_list.php">
                            <i class="fas fa-exclamation-circle"></i> Report Problem
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- เส้นคั่น -->
        <hr>
        <!-- ลิงก์สำหรับการออกจากระบบ -->
        <a href="../admin/singout.php" class="nav-link text-danger">
            <i class="fas fa-sign-out-alt"></i> Sign Out
        </a>
    </div>

    <div class="content">
        <h2>Staff Dashboard</h2>
        <h4>Notifications</h4>
        <div id="notifications" class="row">
            <!-- ใช้ PHP เพื่อแสดงการแจ้งเตือนเบื้องต้น -->
            <?php fetchNotifications($conn); ?>
        </div>
    </div>

    <script>
        // ฟังก์ชันดึงการแจ้งเตือนแบบเรียลไทม์ด้วย AJAX
        function fetchNotifications() {
            $.ajax({
                url: 'staff_dashboard.php',  // ดึงข้อมูลจากหน้าเดียวกัน
                type: 'POST',
                success: function(data) {
                    $('#notifications').html(data);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        // เรียกใช้ทุก 30 วินาที
        setInterval(fetchNotifications, 30000); 
    </script>

</body>
</html>
