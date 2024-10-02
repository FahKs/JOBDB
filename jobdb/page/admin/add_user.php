<?php
session_start();
include '../../service/condb.php';

$conn = new ConnectionDatabase();
$conn = $conn->connect();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $tel = $_POST['tel'];
    $position = $_POST['position'];
    $store_id = $_POST['store_id'];

    // check email exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<!doctype html>
            <html>
            <body>
            <script>alert('Email already exists'); window.location='add_user.php';</script>
            </body>
            </html>";
        exit();
    }

    // เพิ่มข้อมูลลงในฐานข้อมูลและตั้งค่า update_at เป็น CURRENT_TIMESTAMP
    $sql = "INSERT INTO users (name, surname, email, password, tel, position, store_id, update_at) VALUES ('$name', '$surname', '$email', '$password', '$tel', '$position', '$store_id', CURRENT_TIMESTAMP)";

    if (mysqli_query($conn, $sql)) {
        echo "<!doctype html>
            <html>
            <body>
            <script>alert('Add user success'); window.location='list_user.php';</script>
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
    <title>Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <a href="list_user.php" class="btn btn-success mb-4">Go to List User</a>
        <div class="h4 text-center alert alert-success mb-4 mt-4" role="alert">Add User</div>

        <form action="" method="post" class="mb-5">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="surname" class="form-label">Surname</label>
                <input type="text" class="form-control" id="surname" name="surname" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="tel" class="form-label">Tel</label>
                <input type="tel" class="form-control" id="tel" name="tel" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Position:</label>
                <select name="position" class="form-select" required>
                    <option value="Admin">Admin</option>
                    <option value="Manager">Manager</option>
                    <option value="Staff">Staff</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="store_id" class="form-label"></label>Store_ID
                <input type="store_id" class="form-control" id="store_id" name="store_id" required>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
            <a href="list_user.php" class="btn btn-danger">Cancel</a>
        </form>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN