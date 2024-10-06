<?php
session_start();
include '../../service/condb.php';

$conn = new ConnectionDatabase();
$conn = $conn->connect();

// ตรวจสอบการเชื่อมต่อ
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// ดึงข้อมูลจากฐานข้อมูล requisitioned_reports
$query = "SELECT * FROM orderlist_reports";
$result = mysqli_query($conn, $query);

$requisitions = []; 
// ตรวจสอบและเก็บข้อมูลลงในอาร์เรย์
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $requisitions[] = $row;
    }
}

mysqli_close($conn);
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Purchase Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <style>
        .container {
            margin-top: 40px;
        }
    </style>
</head>

<body>
    <div class="container-lg mt-5 report-container">
        <div class="h4 text-center alert alert-success mb-4 mt-4" role="alert">Purchase Reports</div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-success">
                    <tr>
                        <th>Order Detail Id</th>
                        <th>Order Id</th>
                        <th>Product Id</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Quantity Sets</th>
                        <th>Total</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requisitions as $requisition): ?>
                        <tr>
                            <td><?= htmlspecialchars($requisition['order_detail_id']) ?></td>
                            <td><?= htmlspecialchars($requisition['order_id']) ?></td>
                            <td><?= htmlspecialchars($requisition['product_id']) ?></td>
                            <td><?= htmlspecialchars($requisition['product_name']) ?></td>
                            <td><?= htmlspecialchars($requisition['category']) ?></td>
                            <td><?= htmlspecialchars($requisition['quantity_sets']) ?></td>
                            <td><?= htmlspecialchars($requisition['total']) ?></td>
                            <td><?= htmlspecialchars($requisition['unit_price']) ?></td>
                            <td><?= htmlspecialchars($requisition['subtotal']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end">
            <button class="btn btn-success me-2" onclick="exportToCSV()">Export File to CSV</button>
            <a href="manager_report.php" class="btn btn-secondary">Back to Report Menu</a>
        </div>
    </div>

    <!-- รวม Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function exportToCSV() {
            // ใช้ข้อมูลที่ถูกส่งจาก PHP ไปยัง JavaScript
            const data = <?= json_encode($requisitions); ?>;
            const csvRows = [];

            const headers = ["Order Detail Id", "Order Id", "Product Id", "Product Name", "Category", "Quantity Sets", "Total", "Unit Price", "Subtotal"];
            csvRows.push(headers.join(','));

            for (const row of data) {
                const values = headers.map(header => {
                    const key = header.toLowerCase().replace(' ', '_');
                    const escaped = ('' + row[key]).replace(/"/g, '\\"');
                    return `"${escaped}"`;
                });
                csvRows.push(values.join(','));
            }

            // แปลงข้อมูลเป็น CSV 
            const csvData = csvRows.join('\n');
            const blob = new Blob([csvData], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.setAttribute('hidden', '');
            a.setAttribute('href', url);
            a.setAttribute('download', 'purchase_reports.csv');
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    </script>
</body>

</html>
