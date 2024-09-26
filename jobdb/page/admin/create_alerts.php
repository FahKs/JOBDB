<?php
session_start();
include '../../service/condb.php';

$conn = new ConnectionDatabase();
$conn = $conn->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $listproduct_id = $_POST['listproduct_id'];
    $set_expday = $_POST['set_expday'] ?? null; // Check Expiration Date value
    $set_lowstock = $_POST['set_lowstock'] ?? null; // Check Low Stock Level value
    $setting_info = $_POST['setting_info'] ?? null; // Optional field

    // Use Prepared Statement to prevent SQL Injection
    $stmt = $conn->prepare("INSERT INTO alerts_setting (listproduct_id, set_expday, set_lowstock, setting_info) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $listproduct_id, $set_expday, $set_lowstock, $setting_info);

    if ($stmt->execute()) {
        echo "<!doctype html>
            <html>
            <body>
            <script>alert('Create success'); window.location='alert_setting.php';</script>
            </body>
            </html>";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Alert</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <a href="alert_setting.php" class="btn btn-success mb-4">Go back</a>
        <div class="h4 text-center alert alert-success mb-4 mt-4" role="alert">Create Alert</div>

        <form action="" method="post" class="mb-5">
            <div class="mb-3">
                <label for="listproduct_id" class="form-label">ListProduct ID</label>
                <input type="number" class="form-control" id="listproduct_id" name="listproduct_id" required>
            </div>

            <!-- Form for Expiration Date -->
            <div class="mb-3">
                <label class="form-label">Set Expiration Date (days):</label>
                <input type="number" name="set_expday" class="form-control" placeholder="Enter expiration days">
            </div>

            <!-- Form for Low Stock -->
            <div class="mb-3">
                <label class="form-label">Set Low Stock Level:</label>
                <input type="number" name="set_lowstock" class="form-control" placeholder="Enter low stock level">
            </div>
            
            <div class="mb-3">
                <label for="setting_info" class="form-label">Additional Information (optional)</label>
                <input type="text" class="form-control" id="setting_info" name="setting_info" placeholder="Enter additional info (optional)">
            </div>

            <button type="submit" class="btn btn-success">Submit</button>
            <a href="alert_setting.php" class="btn btn-danger">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
