<?php
session_start();
include '../../service/condb.php';

$conn = new ConnectionDatabase();
$conn = $conn->connect();

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// กำหนดค่าเริ่มต้นให้ตัวแปร
$listproduct_id = '';
$set_expday = null;
$set_lowstock = null;
$setting_info = '';

// ตรวจสอบว่ามีการส่งค่า setting_id ผ่าน URL หรือไม่
if (isset($_GET['setting_id'])) {
    $setting_id = $_GET['setting_id'];
    $query = "SELECT * FROM alerts_setting WHERE setting_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $setting_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $listproduct_id = $row['listproduct_id'];
        $set_expday = $row['set_expday'];
        $set_lowstock = $row['set_lowstock'];
        $setting_info = $row['setting_info'];
    } else {
        echo "Alert not found.";
        exit;
    }
}

// ตรวจสอบว่ามีการส่งข้อมูลผ่านฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $setting_id = $_POST['setting_id'];
    $listproduct_id = $_POST['listproduct_id'];
    $set_expday = $_POST['set_expday'] ?? null;
    $set_lowstock = $_POST['set_lowstock'] ?? null;
    $setting_info = $_POST['setting_info'] ?? null;
    $search = $_POST['search'] ?? '';

    // อัปเดตข้อมูลในฐานข้อมูล
    $update_query = "UPDATE alerts_setting SET listproduct_id = ?, set_expday = ?, set_lowstock = ?, setting_info = ?, update_at = CURRENT_TIMESTAMP WHERE setting_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("iiisi", $listproduct_id, $set_expday, $set_lowstock, $setting_info, $setting_id);

    if ($stmt->execute()) {
        header("Location: alert_setting.php?search=" . urlencode($search));
        exit;
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}

$stmt->close();
$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Alert</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <a href="alert_setting.php" class="btn btn-success mb-4">Go back</a>
        <div class="h4 text-center alert alert-success mb-4 mt-4" role="alert">Edit Alert</div>

        <form action="" method="post" class="mb-5">
            <input type="hidden" name="setting_id" value="<?= htmlspecialchars($setting_id) ?>">
            <input type="hidden" name="search" value="<?= htmlspecialchars($search ?? '') ?>">

            <div class="mb-3">
                <label for="listproduct_id" class="form-label">Product ID</label>
                <input type="number" class="form-control" id="listproduct_id" name="listproduct_id" value="<?= htmlspecialchars($listproduct_id) ?>" required>
            </div>

            <div class="mb-3">
                <label for="setting_info" class="form-label">Additional Information (optional)</label>
                <input type="text" class="form-control" id="setting_info" name="setting_info" value="<?= htmlspecialchars($setting_info) ?>">
            </div>

            <!-- Form for Expiration Date -->
            <div id="expirationDateForm" class="mb-3">
                <label class="form-label">Set Expiration Date (days):</label>
                <input type="number" name="set_expday" class="form-control" placeholder="Enter expiration days" value="<?= htmlspecialchars($set_expday) ?>">
            </div>

            <!-- Form for Low Stock -->
            <div id="lowStockForm" class="mb-3">
                <label class="form-label">Set Low Stock Level:</label>
                <input type="number" name="set_lowstock" class="form-control" placeholder="Enter low stock level" value="<?= htmlspecialchars($set_lowstock) ?>">
            </div>

            <button type="submit" class="btn btn-success">Submit</button>
            <a href="alert_setting.php" class="btn btn-danger">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
