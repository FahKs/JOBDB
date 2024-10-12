<?php
// ตรวจสอบเส้นทางไฟล์ condb.php ให้ถูกต้อง
include 'D:/xampp/htdocs/jobdb/service/condb.php'; 

if (isset($_POST['email']) && isset($_POST['password'])) {
    // ตรวจสอบว่าฟิลด์ email และ password ไม่ว่างเปล่า
    if (empty($_POST['email']) || empty($_POST['password'])) {
        echo "Both email and password fields are required.";
        exit();
    }

    $connect = new ConnectionDatabase();
    $conn = $connect->connect();

    // ตรวจสอบว่าการเชื่อมต่อฐานข้อมูลสำเร็จหรือไม่
    if (!$conn) {
        echo "Database connection failed.";
        exit();
    }

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // ใช้ Prepared Statements เพื่อป้องกัน SQL Injection
    $stmt = $conn->prepare("SELECT name, surname, email, password, position FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // s หมายถึง string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // มีข้อมูลในฐานข้อมูล
        $row = $result->fetch_assoc();

        // ตรวจสอบสถานะของ session ก่อนเรียก session_start()
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // ไม่ใช้ password_verify(), ตรวจสอบรหัสผ่านธรรมดา
        if ($password == $row['password']) { // เปรียบเทียบรหัสผ่านแบบตรง ๆ
            $_SESSION['login'] = true;
            $_SESSION['profile'] = $row;

            // ตรวจสอบตำแหน่งงาน (Position) ของผู้ใช้
            $position = strtolower($row['position']); // แปลง position ให้เป็นตัวพิมพ์เล็กทั้งหมดเพื่อป้องกันความผิดพลาด
            if ($position == 'manager') {
                header('Location: ./page/manager/index.php');
            } elseif ($position == 'admin') {
                header('Location: ./page/admin/list_user.php');
            } elseif ($position == 'staff') {
                header('Location: ./page/staff/index.php');
            } else {
                echo "Invalid position. Please check your user role.";
            }
            exit(); // หยุดการทำงานของสคริปต์หลังการเปลี่ยนเส้นทาง
        } else {
            // รหัสผ่านไม่ถูกต้อง
            echo "Invalid login credentials. Incorrect password.";
        }
    } else {
        // หากไม่พบอีเมลในฐานข้อมูล
        echo "Invalid login credentials. Email not found.";
    }
}
?>
