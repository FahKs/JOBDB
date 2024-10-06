<?php
session_start();
include './service/condb.php';  // ตรวจสอบเส้นทางของไฟล์นี้ให้แน่ใจว่าอยู่ถูกที่

if (isset($_POST['tel'])) {
    $tel = trim($_POST['tel']); // ลบช่องว่างออกจากเบอร์โทรศัพท์เพื่อป้องกันปัญหาข้อมูลไม่ถูกต้อง

    // เชื่อมต่อกับฐานข้อมูล
    $db = new ConnectionDatabase();
    $conn = $db->connect();

    // เตรียม statement เพื่อป้องกัน SQL Injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE tel = ?");
    $stmt->bind_param("s", $tel); // รับค่าที่กรอกมาโดยไม่ตรวจสอบความยาว
    $stmt->execute();
    $result = $stmt->get_result();

    // เก็บผลลัพธ์ลงในตัวแปร $user
    $user = $result->fetch_assoc();

    if ($user) {
        // ตรวจสอบตำแหน่งของผู้ใช้
        if ($user['position'] === 'Manager') {  // เช็คตรงกับฐานข้อมูล (ตัวอักษรใหญ่)
            // ถ้าเป็น Manager
            session_regenerate_id(true);
            $_SESSION['user'] = $user;  // เก็บข้อมูลผู้ใช้ใน session
            header("Location: /jobdb/page/manager/index.php");
            exit();
        } elseif ($user['position'] === 'Staff') {
            // ถ้าเป็น Staff
            session_regenerate_id(true);
            $_SESSION['user'] = $user;  // เก็บข้อมูลผู้ใช้ใน session
            header("Location: /jobdb/page/staff/index.php");
            exit();
        } else {
            $_SESSION['error'] = "คุณไม่มีสิทธิ์เข้าถึง";
            header("Location: login_with_phone.php");
            exit();
        }
    } else {
        // แสดงข้อผิดพลาดถ้าไม่พบผู้ใช้
        $_SESSION['error'] = "ไม่พบข้อมูลผู้ใช้หรือเบอร์โทรศัพท์ไม่ถูกต้อง";
        header("Location: login_with_phone.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login with Phone</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <div class="block_A">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQitgJ_QOFgbuQ_oNenxF1LbEshrMwfWh_HOg&usqp=CAU" alt="Botoko Cafes Logo" class="logo">
            <h1>Botoko Cafe'</h1>
            <p>"Experience the perfect blend of comfort and flavor at our cozy little coffee shop. Where every cup is crafted with love!"</p>
        </div>
        <div class="block_B">
            <h2>Welcome New User!!</h2>
            <form id="login-form" action="" method="post">
                <label for="tel">Tel:</label>
                <input type="tel" id="tel" name="tel" placeholder="Your Tel" required>

                <button type="submit">Submit</button>

                <?php if (isset($_SESSION['error'])) : ?>
                    <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>

</body>

</html>
