<?php
session_start();
include './service/verify_role.php';
include '../../service/condb.php';

$conn = new ConnectionDatabase();
$conn = $conn->connect();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM store WHERE store_id LIKE '%$search%' OR store_name LIKE '%$search%' OR location_store LIKE '%$search%'";
$result = mysqli_query($conn, $query);
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Store Info</title>
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
      <a class="nav-link dropdown-toggle" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
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
          <a class="nav-link" href="admin_tracking.php">
          <i class="fas fa-car"></i> Traking
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
    <a href="singout.php" class="nav-link text-danger">
      <i class="fas fa-sign-out-alt"></i> Sign Out
    </a>
  </div>
  <!-- Main Content -->
  <div class="content">
    <!--navbar -->
    <?php include '../../components/admin/navbar.php'; ?>
    <div class="container">
      <div class="h4 text-center alert alert-success mb-4 mt-4" role="alert">Store Info</div>
      <a href="add_store.php" class="btn btn-success mb-4">+Add Store Data</a>

      <!-- ฟอร์มค้นหา -->
      <form action="store.php" method="get" class="d-flex align-items-center">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search..." class="form-control me-2">
        <button type="submit" class="btn btn-success me-2" style="width: 100px;">Search</button>
        <a href="store.php" class="btn btn-secondary" style="width: 100px;">Back</a>
      </form>

      <!-- ตารางแสดงข้อมูล -->
      <div class="table-responsive table-container">
        <table class="table table-success table-striped table-hover">
          <thead>
            <tr>
              <th>Store_ID</th>
              <th>Store_Name</th>
              <th>Location_Store</th>
              <th>detail_location</th>
              <th>Tel_Store</th>
              <th>Update At</th>
              <th>Edit</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // ตรวจสอบว่าการ Query สำเร็จหรือไม่
            if ($result) {
              // ตรวจสอบว่ามีผลลัพธ์หรือไม่
              if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                  <tr>
                    <td><?= htmlspecialchars($row["store_id"]) ?></td>
                    <td><?= htmlspecialchars($row["store_name"]) ?></td>
                    <td><?= htmlspecialchars($row["location_store"]) ?></td>
                    <td><?= htmlspecialchars($row["detail_location"]) ?></td>
                    <td><?= htmlspecialchars($row["tel_store"]) ?></td>
                    <td><?= htmlspecialchars($row["update_at"]) ?></td>	
                    <td>
                      <a href="edit_store.php?store_id=<?= htmlspecialchars($row["store_id"]) ?>&search=<?= htmlspecialchars($search) ?>" class="btn btn-warning">Edit</a>
                    </td>
                    <td>
                      <a href="delete_store.php?store_id=<?= htmlspecialchars($row['store_id']) ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this data?');">Delete</a>
                    </td>
                  </tr>
            <?php
                }
              } else {
                // แสดงข้อความหากไม่มีข้อมูล
                echo "<tr><td colspan='10' class='text-center'>No records found.</td></tr>";
              }
            } else {
              // แสดงข้อความข้อผิดพลาดหากการ Query ไม่สำเร็จ
              echo "<p>Error fetching data: " . mysqli_error($conn) . "</p>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- รวม Bootstrap JS และ Font Awesome -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>