<?php
session_start();
include '../../service/condb.php'; // เรียกใช้ไฟล์ condb.php

// ตรวจสอบว่า Manager เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user']) || $_SESSION['user']['position'] !== 'Manager') {
    header('Location: /jobdb/login_with_phone.php'); // ถ้ายังไม่ได้เข้าสู่ระบบ หรือไม่ใช่ Manager ให้กลับไปที่หน้า Login
    exit();
}

$manager_store_id = $_SESSION['user']['store_id']; // เก็บ store_id ของ Manager

// สร้างอ็อบเจ็กต์จากคลาส ConnectionDatabase
$conn = new ConnectionDatabase();
$conn = $conn->connect(); // เชื่อมต่อฐานข้อมูล

// รับค่าการค้นหาที่ถูกส่งมาจากฟอร์มผ่าน URL ถ้าไม่มีการส่งค่ามาจะใช้ค่าว่าง ('') แทน
$search = isset($_GET['search']) ? $_GET['search'] : '';

// เขียนคำสั่ง SQL เพื่อค้นหาผู้ใช้ที่มีตำแหน่งเป็น "Staff" และมี store_id ตรงกับ Manager
$query = "
    SELECT u.user_id, u.name, u.surname, u.email, u.tel, u.position, s.location_store, u.update_at
    FROM users u
    LEFT JOIN store s ON u.store_id = s.store_id
    WHERE u.position = 'Staff' 
    AND u.store_id = ?
    AND (u.name LIKE ? OR u.user_id LIKE ? OR u.surname LIKE ?)
";

// เตรียม statement เพื่อป้องกัน SQL Injection
$stmt = $conn->prepare($query);
$searchParam = '%' . $search . '%';
$stmt->bind_param("isss", $manager_store_id, $searchParam, $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Users Info</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
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
  <div class="sidebar">
    <h4>Menu</h4>
    <ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link" href="dashbord.php">
          <i class="fas fa-tachometer-alt"></i> Dashbord
        </a>
      <li class="nav-item">
        <a class="nav-link active" href="index.php">
          <i class="fas fa-users"></i> Show Users
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="product_list.php">
          <i class="fas fa-shopping-cart"></i> Order
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="tracking.php">
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

  <div class="content">
    <div class="container">
      <div class="h4 text-center alert alert-success mb-4 mt-4" role="alert">Show Staff Users</div>
      <div class="d-flex justify-content-between align-items-center mb-4">
        <form action="index.php" method="get" class="d-flex align-items-center">
          <input type="text" name="search" class="form-control me-2" placeholder="Search by Name, User ID, Surname">
          <button type="submit" class="btn btn-success me-2">Search</button>
          <a href="index.php" class="btn btn-secondary">Back</a>
        </form>
      </div>

      <div class="table-responsive table-container">
        <table class="table table-hover table-bordered table-striped text-center">
          <thead class="table-success">
            <tr>
              <th>User ID</th>
              <th>Name</th>
              <th>Surname</th>
              <th>Email</th>
              <th>Telephone</th>
              <th>Position</th>
              <th>Location_Store</th>
              <th>Update At</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['user_id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['surname']) . '</td>';
                echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                echo '<td>' . htmlspecialchars($row['tel']) . '</td>';
                echo '<td>' . htmlspecialchars($row['position']) . '</td>';
                echo '<td>' . (!empty($row['location_store']) ? htmlspecialchars($row['location_store']) : 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($row['update_at']) . '</td>';
                echo '</tr>';
              }
            } else {
              echo '<tr><td colspan="8" class="text-center">No staff found for this store.</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

