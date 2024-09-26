<?php
include 'condb.php';

if (isset($_POST['email']) && isset($_POST['password'])) {
    $connect = new ConnectionDatabase();
    $conn = $connect->connect();

    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL query เพื่อดึงข้อมูลจากตาราง users ตาม email และ password ที่ใส่เข้ามา
    $sql = "SELECT name, surname, email, position FROM users WHERE email = '$email' AND password = '$password'";
    $result = $connect->executeQuery($conn, $sql);

    if ($result->num_rows > 0) {
        // มีข้อมูลในฐานข้อมูล
        while ($row = $result->fetch_assoc()) {
            session_start();
            $_SESSION['login'] = true;
            $_SESSION['profile'] = $row;

            // ตรวจสอบตำแหน่งงาน (Position) ของผู้ใช้
            $position = strtolower($row['position']); // แปลง position ให้เป็นตัวพิมพ์เล็กทั้งหมดเพื่อป้องกันความผิดพลาด
            if ($position == 'manager') {
                header('Location: ./page/manager/index.php');
            } else {
                header('Location: ./page/admin/list_user.php');
            }
            exit(); // หยุดการทำงานของสคริปต์หลังการเปลี่ยนเส้นทาง
        }
    } else {
        // ไม่มีข้อมูลในฐานข้อมูล หรือข้อมูล login ไม่ถูกต้อง
        echo "Invalid login credentials. Please check your email and password.";
    }
}
?>
