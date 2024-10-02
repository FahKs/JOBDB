<?php
session_start();
include '../../service/condb.php';

$conn = new ConnectionDatabase();
$conn = $conn->connect();

// กำหนดค่าเริ่มต้นให้ตัวแปร
$store_name = '';
$location_store = '';
$tel_store = '';
$search = '';

$store_id = isset($_GET['store_id']) ? $_GET['store_id'] : null;

if ($store_id === null) {
    echo "Store ID is missing.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $store_id = isset($_POST['store_id']) ? $_POST['store_id'] : null;

    if ($store_id === null) {
        echo "Store ID is missing.";
        exit;
    }

    $store_name = $_POST['store_name'];
    $location_store = $_POST['location_store'];
    $tel_store = $_POST['tel_store'];
    $search = isset($_POST['search']) ? $_POST['search'] : '';

    // ป้องกัน SQL Injection
    $store_name = mysqli_real_escape_string($conn, $store_name);
    $location_store = mysqli_real_escape_string($conn, $location_store);
    $tel_store = mysqli_real_escape_string($conn, $tel_store);

    // อัปเดตข้อมูลในฐานข้อมูล
    $update_query = "UPDATE store SET store_name='$store_name', location_store='$location_store', tel_store='$tel_store' WHERE store_id='$store_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: store.php?search=" . urlencode($search)); // กลับไปยังหน้า store.php พร้อมกับการค้นหาเดิม
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
    $query = "SELECT * FROM store WHERE store_id = '$store_id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $store_name = $row["store_name"];
        $location_store = $row["location_store"];
        $tel_store = $row["tel_store"];
    } else {
        echo "Store not found.";
        exit;
    }
}

mysqli_close($conn);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Store Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="h4 text-center alert alert-info mb-4" role="alert">Edit Store Data</div>

        <!-- ฟอร์มแก้ไขผู้ใช้ -->
        <form action="" method="post">
            <input type="hidden" name="store_id" value="<?= htmlspecialchars($store_id) ?>">
            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">

            <div class="mb-3">
                <label class="form-label">Store_Name:</label>
                <input type="text" name="store_name" value="<?= htmlspecialchars($store_name) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Location_Store:</label>
                <input type="text" name="location_store" value="<?= htmlspecialchars($location_store) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tel_Store:</label>
                <input type="tel" name="tel_store" value="<?= htmlspecialchars($tel_store) ?>" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
            <button type="button" class="btn btn-danger" onclick="window.location.href='store.php?search=<?= urlencode($search) ?>'">Cancel</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
