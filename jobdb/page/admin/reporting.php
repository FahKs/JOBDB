<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>สร้างรายงาน</title>
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
      <li class="nav-item">
        <a class="nav-link " href="list_user.php">
          <i class="fas fa-user"></i> Manage
        </a>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="inventory.php">
          <i class="fas fa-box"></i> Inventory
        </a>
        <li class="nav-item">
        <a class="nav-link" href="list_barcode.php">
          <i class="fas fa-barcode"></i> Barcode
        </a>
      </li>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="alert_setting.php">
          <i class="fas fa-cog"></i> Alert Setting
        </a>
      </li>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="reporting.php">
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
  <style>
        .report-container {
            max-width: 600px;
            margin: 60px auto;
            padding: 20px;
            border-radius: 8px;
            background-color: #f2f2f2;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
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
                    <label for="reportFirstDate" class="form-label">From-Date:</From-Date>:</label>
                    <input type="date" id="reportFirstDate" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="reportLastDate" class="form-label">To-Date:</label>
                    <input type="date" id="reportLastDate" class="form-control" required>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success me-2">Exports File</button>
                    <button type="button" class="btn btn-danger" onclick="cancelReport()">Cancel</button>
                </div>
            </form>
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
                window.location.href = 'inventory_reports.php'; // เปลี่ยนหน้าไปยังรายงานสินค้าคงคลัง
                break;
            case 'requisitioned_product':
                window.location.href = 'order_reports.php'; // เปลี่ยนหน้าไปยังรายงานสินค้าถูกเบิกใช้
                break;
            case 'purchase_order':
                window.location.href = 'purchase_reports.php'; // เปลี่ยนหน้าไปยังรายงานสั่งซื้อสินค้า
                break;
            case 'damaged_inventory':
                window.location.href = 'defective_reports.php'; // เปลี่ยนหน้าไปยังรายงานสินค้าเสียหาย
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

