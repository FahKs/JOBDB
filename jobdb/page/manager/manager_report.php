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
    <li class="nav-item">
        <a class="nav-link" href="dashbord.php">
          <i class="fas fa-tachometer-alt"></i> Dashbord
        </a>
      <!-- เมนู Manage เป็น dropdown -->
      <li class="nav-item dropdown">
        <!-- ลิงก์หลักของ dropdown มีคลาส dropdown-toggle และ active -->
        <a class="nav-link" href="index.php">
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
            <a class="nav-link" href="tracking.php">
              <!-- ไอคอนบาร์โค้ด และข้อความ Tracking -->
              <i class="fas fa-car"></i> Tracking
            </a>
          </li>
          <!-- ลิงก์ไปหน้าการตั้งค่าการแจ้งเตือน -->
          <li class="nav-item">
            <a class="nav-link" href="scanning.php">
              <!-- ไอคอนเครื่องสแกน และข้อความ Scanning -->
              <i class="fas fa-barcode"></i> Scanning
            </a>
          </li>
          <!-- ลิงก์ไปหน้ารายงาน -->
          <li class="nav-item">
            <a class="nav-link" href="inventory.php">
              <!-- ไอคอนโกดังสินค้า และข้อความ Inventory -->
              <i class="fas fa-warehouse"></i> Inventory
            </a>
          </li>
          <!-- ลิงก์ไปหน้ารายงานปัญหา -->
          <li class="nav-item">
            <a class="nav-link" href="report_problem.php">
              <!-- ไอคอนปัญหา และข้อความ Report Problem -->
              <i class="fas fa-exclamation-circle"></i> Report Problem
            </a>
          </li>
          <!-- ลิงก์ไปหน้ารายงาน -->
          <li class="nav-item">
            <a class="nav-link active" href="manager_report.php">
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
    <a href="../admin/signout.php" class="nav-link text-danger">
      <!-- ไอคอนออกจากระบบ และข้อความ Sign Out -->
      <i class="fas fa-sign-out-alt"></i> Sign Out
    </a>
  </div>

  <!-- ส่วนของเนื้อหาหลักในหน้าเว็บ -->
  <div class="content">
    <div class="container">
        <div class="report-container">
            <h3 class="text-center mb-4">Reports System</h3>
            <form id="reportForm" onsubmit="handleFormSubmit(event)">
                <div class="mb-3">
                    <label for="reportType" class="form-label">Type of Reports:</label>
                    <select id="reportType" class="form-select" required>
                        <option value="">Select Report Type</option>
                        <option value="inventory_report">Inventory Reports</option>
                        <option value="requisitioned_product">Requisitioned Products</option>
                        <option value="purchase_order">Purchase Order</option>
                        <option value="damaged_inventory">Defective Products</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="reportFirstDate" class="form-label">From-Date:</label>
                    <input type="date" id="reportFirstDate" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="reportLastDate" class="form-label">To-Date:</label>
                    <input type="date" id="reportLastDate" class="form-control" required>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success me-2">Export File</button>
                    <button type="button" class="btn btn-danger" onclick="cancelReport()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
  </div>

  <!-- ใช้งาน Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // ฟังก์ชันจัดการการส่งฟอร์ม
    function handleFormSubmit(event) {
        event.preventDefault(); // ป้องกันฟอร์มจากการส่งตามปกติ

        const reportType = document.getElementById('reportType').value;

        // ตรวจสอบการเลือกประเภทของรายงาน
        if (!reportType) {
            alert('กรุณาเลือกประเภทของรายงานที่ถูกต้อง.');
            return;
        }

        // นำทางไปยังหน้ารายงานที่เกี่ยวข้องตามประเภทที่เลือก
        switch (reportType) {
            case 'inventory_report':
                window.location.href = 'inventory_manager_reports.php'; // เปลี่ยนหน้าไปยังรายงานสินค้าคงคลัง
                break;
            case 'requisitioned_product':
                window.location.href = 'order_manager_reports.php'; // เปลี่ยนหน้าไปยังรายงานสินค้าถูกเบิกใช้
                break;
            case 'purchase_order':
                window.location.href = 'purchase_manager_reports.php'; // เปลี่ยนหน้าไปยังรายงานสั่งซื้อสินค้า
                break;
            case 'damaged_inventory':
                window.location.href = 'defective_manager_reports.php'; // เปลี่ยนหน้าไปยังรายงานสินค้าเสียหาย
                break;
            default:
                alert('Please select the correct report type.');
                break;
        }
    }

    // ฟังก์ชันยกเลิกการส่งฟอร์มและรีเซ็ตฟอร์ม
    function cancelReport() {
        document.getElementById('reportForm').reset();
    }
  </script>
</body>

</html>
