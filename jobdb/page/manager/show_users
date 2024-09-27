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
      flex-direction: column;
      min-height: 100vh;
      margin: 0;
      padding: 0;
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
    }

    .table-container {
      box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
      overflow: hidden;
    }

    @media (max-width: 768px) {
      .content {
        margin-left: 0;
        width: 100%;
      }

      .sidebar {
        display: none;
      }
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
      </li>
      <li class="nav-item">
        <a class="nav-link" href="product_list.php">
          <i class="fas fa-box"></i> Order
        </a>
      </li>
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
      <li class="nav-item">
        <a class="nav-link" href="reporting.php">
          <i class="fas fa-file-alt"></i> Report
        </a>
      </li>
    </ul>
    <hr>
    <a href="signout.php" class="nav-link text-danger">
      <i class="fas fa-sign-out-alt"></i> Sign Out
    </a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <div class="container">
      <div class="h4 text-center alert alert-success mb-4 mt-4" role="alert">Show Users</div>
      <div class="d-flex justify-content-between align-items-center mb-4">
        <form action="show_users.php" method="get" class="d-flex align-items-center">
          <input type="text" name="search" class="form-control me-2">
          <button type="submit" class="btn btn-success me-2">Search</button>
          <a href="show_users.php" class="btn btn-secondary">Back</a>
        </form>
      </div>

      <!-- ตารางแสดงข้อมูล -->
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
              <th>Store_ID</th>
              <th>Update At</th>
            </tr>
          </thead>
          <tbody>
            <!-- แสดงข้อมูลผู้ใช้ -->
            <!-- ข้อมูลจากฐานข้อมูลควรถูกนำมาแสดงในส่วนนี้ -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- รวม Bootstrap JS และ Font Awesome -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
