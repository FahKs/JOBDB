<?php
session_start();
include '../../service/condb.php';

// สร้างการเชื่อมต่อกับฐานข้อมูล
$conn = new ConnectionDatabase();
$conn = $conn->connect();

// ตรวจสอบการเชื่อมต่อ
if (!$conn) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . mysqli_connect_error());
}

// ดึงข้อมูลจากตาราง inventory_reports
$query = "SELECT * FROM inventory_reports";
$result = mysqli_query($conn, $query);

// เก็บข้อมูลในรูปแบบอาร์เรย์
$data = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>

<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>รายงานสินค้าคงคลัง</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <style>
        .container {
            margin-top: 40px;
        }
    </style>
</head>

<body>
    <div class="container">
    <div class="h4 text-center alert alert-success mb-4 mt-4" role="alert">Inventory Reports</div>
        <table class="table table-striped table-hover">
            <thead class="table-success">
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Inventory Value</th>
                    <th>First Date</th>
                    <th>Update At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['product_id']) ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= htmlspecialchars($row['quantity']) ?></td>
                        <td><?= htmlspecialchars($row['inventory_value']) ?></td>
                        <td><?= htmlspecialchars($row['first_date']) ?></td>
                        <td><?= htmlspecialchars($row['update_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-end">
            <button class="btn btn-success me-2" onclick="exportToCSV()">Export File to CSV</button>
            <a href="manager_report.php" class="btn btn-secondary">Back to Report Menu</a>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function exportToCSV() {
            const data = <?= json_encode($data); ?>;
            const csvRows = [];
            const headers = Object.keys(data[0]);
            csvRows.push(headers.join(','));

            for (const row of data) {
                const values = headers.map(header => {
                    const escaped = ('' + row[header]).replace(/"/g, '\\"');
                    return `"${escaped}"`;
                });
                csvRows.push(values.join(','));
            }

            const csvData = csvRows.join('\n');
            const blob = new Blob([csvData], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.setAttribute('hidden', '');
            a.setAttribute('href', url);
            a.setAttribute('download', 'inventory_reports.csv');
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    </script>
</body>

</html>