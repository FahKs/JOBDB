<?php
include '../../service/condb.php';

// สร้าง instance ของ ConnectionDatabase
$db = new ConnectionDatabase();

// เชื่อมต่อกับฐานข้อมูล
$conn = $db->connect();

// ตรวจสอบการเชื่อมต่อ
if ($db->isConnectionValid()) {
    // ดึงข้อมูลจากฐานข้อมูล orders
    $sql = "SELECT order_id, store_id, total_price, order_status FROM orders";
    $result = $db->executeQuery($sql);
} else {
    die("Connection failed");
}

// ปิดการเชื่อมต่อฐานข้อมูล
$db->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Tracking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script> <!-- โหลด JsBarcode -->
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
            color: #fff;
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
        .barcode-canvas {
            display: none; /* ซ่อน Barcode เริ่มต้น */
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>Menu</h4>
        <ul class="nav flex-column">
        <li class="nav-item">
        <a class="nav-link" href="dashbord.php"><i class="fas fa-tachometer-alt"></i> Dashbord</a>
            <li class="nav-item">
                <a class="nav-link" href="index.php"><i class="fas fa-users"></i> Show Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="product_list.php"><i class="fas fa-shopping-cart"></i> Order</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="tracking.php"><i class="fas fa-car"></i> Tracking</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="scanning.php"><i class="fas fa-barcode"></i> Scanning</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="inventory.php"><i class="fas fa-warehouse"></i> Inventory</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="report_problem.php"><i class="fas fa-exclamation-circle"></i> Report Problem</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manager_report.php"><i class="fas fa-file-alt"></i> Report</a>
            </li>
        </ul>
        <hr>
        <a href="../admin/singout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt"></i> Sign Out</a>
    </div>

    <div class="content">
        <div class="container mt-5">
            <h3 class="text-success text-center">Order Tracking</h3>
            <!-- ตารางแสดงสถานะรายละเอียดของรายการ -->
            <table class="table table-bordered table-striped text-center mt-4">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Store ID</th>
                        <th>Total Price</th>
                        <th>Order Status</th>
                        <th>Barcode</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row["order_id"]); ?></td>
                                <td><?= htmlspecialchars($row["store_id"]); ?></td>
                                <td><?= htmlspecialchars(number_format($row["total_price"], 2)); ?></td>
                                <td><?= htmlspecialchars(ucfirst($row["order_status"])); ?></td>
                                <td>
                                    <?php if ($row["order_status"] == "รอตรวจสอบ"): ?>
                                        <!-- แสดง Barcode เมื่อสถานะเป็น "รอตรวจสอบ" -->
                                        <svg id="barcode-<?= $row['order_id']; ?>" class="barcode-canvas"></svg>
                                        <button class="btn btn-success btn-sm mt-2" onclick="downloadBarcode('barcode-<?= $row['order_id']; ?>')">
                                            <i class="fas fa-download"></i> Download Barcode
                                        </button>

                                        <script>
                                            // สร้าง Barcode โดยใช้ JsBarcode
                                            JsBarcode("#barcode-<?= $row['order_id']; ?>", "<?= $row['order_id']; ?>", {
                                                format: "CODE128",
                                                displayValue: true,
                                                fontSize: 16,
                                                width: 4, // ปรับขนาดความกว้างของเส้นบาร์โค้ด
                                                height: 100, // ความสูงของบาร์โค้ด
                                                margin: 10,  // เพิ่ม margin รอบบาร์โค้ด
                                            }).render();

                                            // แสดง Barcode
                                            document.querySelector("#barcode-<?= $row['order_id']; ?>").style.display = 'block';

                                            // ฟังก์ชันสำหรับดาวน์โหลดบาร์โค้ดในรูปแบบ PNG
                                            function downloadBarcode(id) {
                                                var svg = document.getElementById(id);
                                                var svgData = new XMLSerializer().serializeToString(svg);  // แปลง SVG เป็นสตริง XML
                                                var canvas = document.createElement("canvas");             // สร้าง element canvas
                                                var ctx = canvas.getContext("2d");                         // กำหนด context ของ canvas
                                                var img = document.createElement("img");                   // สร้าง image element

                                                img.onload = function() {
                                                    canvas.width = img.width;                              // กำหนดความกว้างของ canvas ตามรูปภาพ
                                                    canvas.height = img.height;                            // กำหนดความสูงของ canvas ตามรูปภาพ
                                                    ctx.drawImage(img, 0, 0);                              // วาดรูปภาพบน canvas

                                                    // สร้างลิงก์ดาวน์โหลด
                                                    var pngFile = canvas.toDataURL("image/png");           // แปลง canvas เป็นไฟล์ PNG
                                                    var downloadLink = document.createElement("a");        // สร้างลิงก์ดาวน์โหลด
                                                    downloadLink.download = "barcode.png";                 // ตั้งชื่อไฟล์สำหรับการดาวน์โหลด
                                                    downloadLink.href = pngFile;                           // ตั้งค่า URL ของลิงก์
                                                    downloadLink.click();                                  // เรียกใช้งานการคลิกเพื่อดาวน์โหลดไฟล์
                                                };

                                                img.src = 'data:image/svg+xml;base64,' + btoa(svgData);    // แปลง SVG เป็น base64 และใส่ลงใน img
                                            }
                                        </script>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No orders found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
