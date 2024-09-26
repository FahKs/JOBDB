<?php
session_start();
include '../../service/condb.php';

$conn = new ConnectionDatabase();
$conn = $conn->connect();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM users WHERE name LIKE '%$search%' OR user_id LIKE '%$search%' OR surname LIKE '%$search%'";
$result = mysqli_query($conn, $query);
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

  <!-- Sidebar -->
  <div class="sidebar">
    <h4>Menu</h4>
    <ul class="nav flex-column">
      <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle active" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="fas fa-user"></i> Manage
      </a>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
          <li><a class="dropdown-item" href="store.php">Manage Store</a></li>
            </ul>
        <ul class="nav flex-column">
          <li class="nav-item side-menu">
            <a class="nav-link" href="inventory.php">
              <i class="fas fa-box"></i> Inventory
            </a>
          <li class="nav-item">
            <a class="nav-link" href="list_barcode.php">
              <i class="fas fa-barcode"></i> Barcode
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="alert_setting.php">
              <i class="fas fa-cog"></i> Alert Setting
            </a>
          </li>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="reporting.php">
          <i class="fas fa-file-alt"></i> Report
        </a>
      </li>
      </li>
    </ul>
    <hr>
    <a href="../admin/singout.php" class="nav-link text-danger">
      <i class="fas fa-sign-out-alt"></i> Sign Out
    </a>
  </div>

  <!-- รวม Bootstrap JS และ Font Awesome -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>