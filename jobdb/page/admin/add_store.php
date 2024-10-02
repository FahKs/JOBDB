<?php
session_start();
include '../../service/condb.php';

$conn = new ConnectionDatabase();
$conn = $conn->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $store_id = $_POST['store_id'];
    $store_name = $_POST['store_name'];
    $location_store = $_POST['location_store'];
    $tel_store = $_POST['tel_store'];
    

    // เพิ่มข้อมูลลงในฐานข้อมูลและตั้งค่า update_at เป็น CURRENT_TIMESTAMP
    $sql = "INSERT INTO store (store_id, store_name, location_store, tel_store, update_at) VALUES ('$store_id', '$store_name', '$location_store', '$tel_store', CURRENT_TIMESTAMP)";


    if (mysqli_query($conn, $sql)) {
        echo "<!doctype html>
            <html>
            <body>
            <script>alert('Add data success'); window.location='store.php';</script>
            </body>
            </html>";
        exit();   
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Store Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <a href="store.php" class="btn btn-success mb-4">Go to List store</a>
        <div class="h4 text-center alert alert-success mb-4 mt-4" role="alert">Add Store Data</div>
        <form action="" method="POST">
    <div class="mb-3">
        <label for="store_id" class="form-label">Store_ID</label>
        <input type="store_id" class="form-control" id="store_id" name="store_id" required>
    </div>
    <div class="mb-3">
        <label for="store_name" class="form-label">Store_Name</label>
        <input type="text" class="form-control" id="store_name" name="store_name" required>
    </div>
    <div class="mb-3">
        <label for="location_store" class="form-label">Location_Store</label>
        <input type="text" class="form-control" id="location_store" name="location_store" required>
    </div>
    <div class="mb-3">
        <label for="tel_store" class="form-label">Tel_Store</label>
        <input type="tel" class="form-control" id="tel_store" name="tel_store" required>
    </div>
    <button type="submit" class="btn btn-success">Submit</button>
    <a href="store.php" class="btn btn-danger">Cancel</a>
</form>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN