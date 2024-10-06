<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Tracking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script> <!-- เพิ่ม jsBarcode -->
    <style>
        .tracking-status {
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin-top: 20px;
        }
        .tracking-status .step {
            text-align: center;
            position: relative;
        }
        .tracking-status .step .icon {
            background-color: #ddd;
            border-radius: 50%;
            padding: 10px;
            margin-bottom: 10px;
        }
        .tracking-status .step.active .icon {
            background-color: #28a745;
            color: white;
        }
        .tracking-status .step .line {
            width: 100px;
            height: 2px;
            background-color: #ddd;
            position: absolute;
            top: 50%;
            left: calc(50% + 35px); /* Center align the line */
        }
        .tracking-status .step.active + .step .line {
            background-color: #28a745;
        }
        .tracking-status .step p {
            margin-top: 5px;
        }
        .barcode {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
        }
        svg {
            width: 300px; /* ขนาดของ Barcode */
            height: auto;
        }
        body {
      display: flex;
    }
    .sidebar {
      width: 220px;
      height: 100vh;
      background-color: #f8f9fa;
      padding: 20px;
      border-right: 1px solid #ddd;
      position: fixed;
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
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>Menu</h4>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="index.php">
                <i class="fas fa-users"></i> Show Users
            </a>
        </li>
        <li class="nav-item side-menu">
            <a class="nav-link" href="product_list.php">
                <i class="fas fa-shopping-cart"></i> Order
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="tracking.php">
                <i class="fas fa-car"></i> Tracking
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="scanning.php">
                <i class="fas fa-barcode"></i> Scanning
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="inventory.php">
                <i class="fas fa-warehouse"></i> Inventory
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="report_problem.php">
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

<!-- Content -->
<div class="content">
    <div class="container mt-5">
        <h3 class="text-success text-center">Order Tracking</h3>

        <!-- แสดง Barcode -->
        <div class="barcode">
            <svg id="barcode"></svg> <!-- จะใช้ SVG เพื่อแสดง Barcode -->
        </div>
        
        <!-- ขั้นตอนการจัดส่ง -->
        <div class="tracking-status">
            <div class="step active">
                <div class="icon">📦</div>
                <p>รับเข้าระบบ</p>
                <div class="line"></div>
            </div>
            <div class="step active">
                <div class="icon">🚚</div>
                <p>ระหว่างขนส่ง</p>
                <div class="line"></div>
            </div>
            <div class="step">
                <div class="icon">📬</div>
                <p>ออกไปนำจ่าย</p>
                <div class="line"></div>
            </div>
            <div class="step">
                <div class="icon">⚠️</div>
                <p>นำจ่ายไม่สำเร็จ</p>
            </div>
        </div>

        <!-- ตารางแสดงสถานะรายละเอียดของรายการ -->
        <table class="table table-bordered table-striped text-center mt-4">
            <thead class="table-success">
                <tr>
                    <th>เวลา</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>04/10/2567 11:35 น.</td>
                    <td>นำจ่าย ณ ตู้ไปรษณีย์เช่า (ออกใบแจ้ง)</td>
                </tr>
                <tr>
                    <td>04/10/2567 11:34 น.</td>
                    <td>ระหว่างขนส่ง</td>
                </tr>
                <tr>
                    <td>04/10/2567 06:33 น.</td>
                    <td>ส่งของออกจาก ศูนย์คัดแยก ลำพูน</td>
                </tr>
                <tr>
                    <td>02/10/2567 17:51 น.</td>
                    <td>ส่งของออกจาก กำแพงแสน</td>
                </tr>
                <tr>
                    <td>02/10/2567 12:22 น.</td>
                    <td>รับฝากสิ่งของ</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    // สร้าง Barcode จากหมายเลข Tracking โดยใช้ jsBarcode
    JsBarcode("#barcode", "<?= $order_id ?>", {
        format: "CODE128", // เลือกรูปแบบของ Barcode
        lineColor: "#0aa", // สีของเส้น
        width: 2,          // ความกว้างของเส้น
        height: 100,       // ความสูงของ Barcode
        displayValue: true  // แสดงค่าตัวอักษรใต้ Barcode
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
