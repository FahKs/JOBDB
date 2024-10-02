<?php

session_start();


// delete $_SESSION['profile'] variable
$_SESSION['profile'] = null;

session_unset();

session_destroy();

header('Location: ../login.php');



คำอธิบาย 
<?php

// เริ่มต้นการทำงานของ session เพื่อให้สามารถเข้าถึงหรือจัดการตัวแปร session ได้
session_start();

// ลบข้อมูลในตัวแปร session ชื่อ 'profile' โดยการกำหนดค่าให้เป็น null
$_SESSION['profile'] = null;

// ลบตัวแปรทั้งหมดที่ถูกเก็บไว้ใน session แต่ไม่ทำลาย session เอง
session_unset();

// ทำลาย session ทั้งหมด ซึ่งหมายความว่าการเชื่อมต่อ session นี้จะถูกยกเลิกและข้อมูลทั้งหมดถูกลบ
session_destroy();

// เปลี่ยนเส้นทางผู้ใช้ไปยังหน้า login.php หลังจากที่ทำลาย session สำเร็จ
header('Location: ../login.php');
