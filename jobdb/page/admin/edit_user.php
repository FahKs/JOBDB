<?php
session_start();
include '../../service/condb.php';

$conn = new ConnectionDatabase();
$conn = $conn->connect();

$user_id = $_GET['user_id'];

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
    $query = "SELECT * FROM users WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $name = $row["name"];
        $surname = $row["surname"];
        $email = $row["email"];
        $tel = $row["tel"];
        $position = $row["position"];
    } else {
        echo "User not found.";
        exit;
    }
}

// ตรวจสอบว่ามีการส่งข้อมูลผ่านฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $position = $_POST['position'];
    $search = $_POST['search'];

    // อัปเดตข้อมูลในฐานข้อมูล
    $update_query = "UPDATE users SET name='$name', surname='$surname', email='$email', tel='$tel', position='$position' WHERE user_id='$user_id'";
    
    if (mysqli_query($conn, $update_query)) {
        header("Location: list_user.php?search=" . urlencode($search)); // กลับไปยังหน้า list_user.php พร้อมกับการค้นหาเดิม
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="h4 text-center alert alert-info mb-4" role="alert">Edit User</div>

        <!-- ฟอร์มแก้ไขผู้ใช้ -->
        <form action="edit_user.php" method="post">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">

            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Surname:</label>
                <input type="text" name="surname" value="<?= htmlspecialchars($surname) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Telephone:</label>
                <input type="tel" name="tel" value="<?= htmlspecialchars($tel) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Position:</label>
                <select name="position" class="form-select" required>
                    <option value="Admin" <?= $position === 'Admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="Manager" <?= $position === 'Manager' ? 'selected' : '' ?>>Manager</option>
                    <option value="Staff" <?= $position === 'Staff' ? 'selected' : '' ?>>Staff</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
            <button type="button" class="btn btn-danger" onclick="window.location.href='list_user.php?search=<?= urlencode($search) ?>'">Cancel</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
