<?php
// เริ่มต้นเซสชันเพื่อจัดเก็บข้อมูลของผู้ใช้ที่เข้าสู่ระบบ
session_start();

// นำเข้าไฟล์ 'condb.php' ซึ่งประกอบด้วยการเชื่อมต่อฐานข้อมูล
include '../../service/condb.php';

// สร้างอ็อบเจ็กต์จากคลาส ConnectionDatabase
$conn = new ConnectionDatabase();

// เรียกใช้เมธอด connect() เพื่อเชื่อมต่อกับฐานข้อมูลและเก็บการเชื่อมต่อไว้ในตัวแปร $conn
$conn = $conn->connect();

// รับค่าการค้นหาที่ถูกส่งมาจากฟอร์มผ่าน URL ถ้าไม่มีการส่งค่ามาจะใช้ค่าว่าง ('') แทน
$search = isset($_GET['search']) ? $_GET['search'] : '';

// เขียนคำสั่ง SQL เพื่อค้นหาข้อมูลผู้ใช้ในฐานข้อมูลที่มีชื่อผู้ใช้ (name), รหัสผู้ใช้ (user_id), หรือนามสกุล (surname) ตรงกับคำที่ค้นหา
$query = "SELECT * FROM users WHERE name LIKE '%$search%' OR user_id LIKE '%$search%' OR surname LIKE '%$search%'";

// รันคำสั่ง SQL โดยใช้ฟังก์ชัน mysqli_query() และเก็บผลลัพธ์ไว้ในตัวแปร $result
$result = mysqli_query($conn, $query);
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
  <!-- นำเข้า Bootstrap CSS เวอร์ชัน 5.3.3 จาก CDN เพื่อช่วยในการจัดรูปแบบของหน้าเว็บ -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- นำเข้าไอคอนของ Font Awesome จาก CDN เพื่อใช้งานในหน้าเว็บ -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
    /* กำหนดรูปแบบการแสดงผลของ body ให้เป็นแบบ flex เพื่อใช้สำหรับการจัดวางเลย์เอาต์ */
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
      <!-- รายการเมนู Inventory เป็นลิงก์ -->
      <li class="nav-item dropdown">
        <!-- ลิงก์หลักของเมนู Inventory มีคลาส active เพื่อระบุว่าเป็นเมนูที่ถูกเลือก -->
        <a class="nav-link active" href="../staff/index.php">
          <!-- ไอคอนของ Inventory และข้อความ Inventory -->
          <i class="fas fa-warehouse"></i> Inventory
        </a>
        <!-- รายการเมนูย่อย -->
        <ul class="nav flex-column">
          <!-- ลิงก์ไปหน้ารายการแจ้งปัญหา -->
          <li class="nav-item side-menu">
            <a class="nav-link" href="product_list.php">
              <!-- ไอคอนเครื่องหมายตกใจ และข้อความ Report Problem -->
              <i class="fas fa-exclamation-circle"></i> Report Problem
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
</body>
</html>
