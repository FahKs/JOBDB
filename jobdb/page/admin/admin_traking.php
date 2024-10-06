<?php
session_start();
include '../../service/condb.php';

$conn = new ConnectionDatabase();
$conn = $conn->connect();

// ดึงข้อมูลผลิตภัณฑ์จากฐานข้อมูล
$query = "SELECT listproduct_id, product_name, category FROM list_product";
$result = mysqli_query($conn, $query);

$products = []; // สร้างอาร์เรย์เพื่อเก็บข้อมูลผลิตภัณฑ์

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row; // เก็บข้อมูลผลิตภัณฑ์ลงในอาร์เรย์
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Traking</title>
    <!-- โหลด JsBarcode -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: row;
            margin: 0;
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
            width: calc(100% - 240px);
            overflow-x: auto;
            box-sizing: border-box;
        }

        .table-success th, .table-success td {
            background-color: #e9f7ef;
        }

        .barcode-canvas {
            width: 100%;
            height: auto;
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
                <a class="nav-link active" href="admin_traking.php">
                    <i class="fas fa-car"></i> Traking
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
        <?php include '../../components/admin/navbar.php'; ?>
        

    <!-- รวม Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
     
</html>