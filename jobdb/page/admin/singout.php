<?php
session_start();
session_unset();
session_destroy();

// ใช้เส้นทางสัมพัทธ์เพื่อกลับไปยัง root
header('Location:/jobdb/login.php'); // หรือใช้ /jobdb/login.php หากเรียกใช้จาก root
exit();

