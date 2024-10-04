<?php
// เริ่มต้นเซสชันเพื่อจัดเก็บข้อมูลของผู้ใช้ที่เข้าสู่ระบบ
session_start();

// นำเข้าไฟล์ 'condb.php' เพื่อเชื่อมต่อฐานข้อมูล
include '../../service/condb.php';

// สร้างอ็อบเจ็กต์จากคลาส ConnectionDatabase และเชื่อมต่อกับฐานข้อมูล
$conn = new ConnectionDatabase();
$conn = $conn->connect();

// รับค่าการค้นหาจากฟอร์มผ่าน URL ถ้าไม่มีการส่งค่ามาจะใช้ค่าว่าง ('') แทน
$search = isset($_GET['search']) ? $_GET['search'] : '';

// เขียนคำสั่ง SQL เพื่อค้นหาข้อมูลผู้ใช้ในฐานข้อมูล
$query = "SELECT * FROM users WHERE name LIKE '%$search%' OR user_id LIKE '%$search%' OR surname LIKE '%$search%'";
$result = mysqli_query($conn, $query);

// ตรวจสอบว่ามีผลลัพธ์จากการค้นหาหรือไม่
if ($result && mysqli_num_rows($result) > 0) {
    // ดึงข้อมูลผู้ใช้จากผลลัพธ์การค้นหา
    $user = mysqli_fetch_assoc($result);

    // ตรวจสอบตำแหน่งและสถานะการเข้าสู่ระบบครั้งแรก
    if ($user['position'] == 'manager' && $user['is_first_login'] == 1) {
        echo '<form action="reset_password.php" method="POST">';
        echo '<button type="submit" name="reset_password">Reset Password</button>';
        echo '</form>';
    }
} else {
    echo 'No users found.';
}

// ฟังก์ชันสำหรับรีเซ็ตรหัสผ่าน
if (isset($_POST['reset_password'])) {
    // โค้ดสำหรับรีเซ็ตรหัสผ่าน เช่นการเปลี่ยนสถานะ 'is_first_login'
    $user = $_SESSION['user'];
    $query = "UPDATE users SET is_first_login = 0 WHERE user_id = " . $user['user_id'];
    mysqli_query($conn, $query);

    // หลังจากรีเซ็ตแล้วให้กลับไปที่หน้า logout หรือ login ใหม่
    header("Location: ../admin/singout.php");
    exit();
}
?>

<!doctype html>
<html lang="en">

<head>
  <!-- กำหนดชุดอักขระที่ใช้ในไฟล์ HTML เป็น UTF-8 -->
  <meta charset="utf-8">
  <!-- กำหนดให้ขนาดหน้าจอแสดงผลให้เหมาะสมกับอุปกรณ์ต่าง ๆ -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- ชื่อของหน้าเว็บ -->
  <title>Users Info</title>
  <!-- นำเข้าการใช้งาน Bootstrap CSS เวอร์ชัน 5.3.3 จาก CDN เพื่อช่วยในการจัดรูปแบบของหน้าเว็บ -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- นำเข้าไอคอนของ Font Awesome จาก CDN เพื่อใช้งานในหน้าเว็บ -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
    /* กำหนดรูปแบบการแสดงผลของ body ให้แสดงเป็นแบบ flex (ใช้สำหรับการจัดวางเลย์เอาต์) */
    body {
      display: flex;
    }

    /* กำหนดขนาดและสีพื้นหลังของแถบด้านข้าง (sidebar) */
    .sidebar {
      width: 220px;
      height: 100vh;
      background-color: #f8f9fa;
      padding: 20px;
      border-right: 1px solid #ddd;
      position: fixed;
    }

    /* กำหนดรูปแบบตัวอักษรของหัวข้อในแถบด้านข้าง */
    .sidebar h4 {
      text-align: center;
      margin-bottom: 20px;
    }

    /* กำหนดรูปแบบของลิงก์ในแถบด้านข้าง */
    .sidebar .nav-link {
      color: #333;
      display: flex;
      align-items: center;
      padding: 10px 15px;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    /* กำหนดสีพื้นหลังเมื่อเอาเมาส์ไปชี้ที่ลิงก์ในแถบด้านข้าง */
    .sidebar .nav-link:hover {
      background-color: #e9ecef;
    }

    /* กำหนดสีพื้นหลังของลิงก์ที่ถูกคลิกเลือก (active) */
    .sidebar .nav-link.active {
      background-color: #28a745;
      color: white;
    }

    /* กำหนดระยะห่างระหว่างไอคอนกับข้อความในลิงก์ */
    .sidebar .nav-link i {
      margin-right: 10px;
    }

    /* กำหนดรูปแบบของเนื้อหาหลักในหน้าเว็บ (content) ให้มีการเว้นระยะห่างจากแถบด้านข้าง */
    .content {
      margin-left: 240px;
      padding: 20px;
      width: 100%;
    }
  </style>
</head>

<body>
  <!-- แถบด้านข้าง (Sidebar) -->
  <div class="sidebar">
    <!-- ชื่อหัวข้อเมนู -->
    <h4>Menu</h4>
    <!-- สร้างรายการเมนูเป็นลิสต์ -->
    <ul class="nav flex-column">
      <!-- เมนู Manage เป็น dropdown -->
      <li class="nav-item dropdown">
        <!-- ลิงก์หลักของ dropdown มีคลาส dropdown-toggle และ active -->
        <a class="nav-link active" href="index.php">
          <!-- ไอคอนผู้ใช้ และข้อความ Manage -->
          <i class="fas fa-users"></i> Show Users
        </a>
        <!-- รายการเมนูเพิ่มเติม -->
        <ul class="nav flex-column">
          <!-- ลิงก์ไปหน้ารายการสินค้า -->
          <li class="nav-item side-menu">
            <a class="nav-link" href="product_list.php">
              <!-- ไอคอนตะกร้าสินค้า และข้อความ Order -->
              <i class="fas fa-shopping-cart"></i> Order
            </a>
          </li>
          <!-- ลิงก์ไปหน้าการจัดการบาร์โค้ด -->
          <li class="nav-item">
            <a class="nav-link" href=".php">
              <!-- ไอคอนบาร์โค้ด และข้อความ Tracking -->
              <i class="fas fa-car"></i> Tracking
            </a>
          </li>
          <!-- ลิงก์ไปหน้าการตั้งค่าการแจ้งเตือน -->
          <li class="nav-item">
            <a class="nav-link" href=".php">
              <!-- ไอคอนเครื่องสแกน และข้อความ Scanning -->
              <i class="fas fa-barcode"></i> Scanning
            </a>
          </li>
          <!-- ลิงก์ไปหน้ารายงาน -->
          <li class="nav-item">
            <a class="nav-link" href=".php">
              <!-- ไอคอนโกดังสินค้า และข้อความ Inventory -->
              <i class="fas fa-warehouse"></i> Inventory
            </a>
          </li>
          <!-- ลิงก์ไปหน้ารายงานปัญหา -->
          <li class="nav-item">
            <a class="nav-link" href=".php">
              <!-- ไอคอนปัญหา และข้อความ Report Problem -->
              <i class="fas fa-exclamation-circle"></i> Report Problem
            </a>
          </li>
          <!-- ลิงก์ไปหน้ารายงาน -->
          <li class="nav-item">
            <a class="nav-link" href="manager_report.php">
              <!-- ไอคอนรายงาน และข้อความ Report -->
              <i class="fas fa-file-alt"></i> Report
            </a>
          </li>
        </ul>
      </li>
    </ul>
    <!-- เส้นคั่น -->
    <hr>
    <!-- ลิงก์สำหรับการออกจากระบบด้วยสีแดง -->
    <a href="../admin/singout.php" class="nav-link text-danger">
      <!-- ไอคอนออกจากระบบ และข้อความ Sign Out -->
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
