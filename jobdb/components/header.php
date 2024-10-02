<?php
echo '
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


  <?php
// เปิดแท็ก PHP เพื่อเริ่มเขียนโค้ด PHP
echo '

// ตั้งค่าการเข้ารหัสตัวอักษร (charset) ของหน้าเว็บให้เป็น UTF-8
// UTF-8 เป็นรูปแบบการเข้ารหัสที่รองรับการแสดงผลหลายภาษา รวมถึงภาษาไทย
<meta charset="utf-8">

// ตั้งค่า viewport ให้ปรับขนาดตามอุปกรณ์ผู้ใช้ เพื่อให้หน้าเว็บแสดงผลได้อย่างเหมาะสมบนทุกอุปกรณ์ โดยเฉพาะมือถือ
// width=device-width: ทำให้ความกว้างของหน้าเว็บเท่ากับความกว้างของหน้าจออุปกรณ์
// initial-scale=1: กำหนดการแสดงผลเริ่มต้นให้ซูมที่ระดับ 1 เท่า
<meta name="viewport" content="width=device-width, initial-scale=1">

// ลิงก์ไปยังไฟล์ CSS ของ Bootstrap เวอร์ชัน 5.3.3
// Bootstrap เป็น framework ที่ช่วยให้เราสามารถจัดการสไตล์ของหน้าเว็บได้ง่ายขึ้น โดยไม่ต้องเขียน CSS เองทั้งหมด
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

// ลิงก์ไปยังไฟล์ CSS ของ Font Awesome เวอร์ชัน 6.0.0-beta3
// Font Awesome เป็นไลบรารีไอคอนที่นิยมใช้สำหรับเพิ่มไอคอนต่างๆ ลงในหน้าเว็บ
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

';
// ปิดการแสดงผลของฟังก์ชัน echo และจบคำสั่ง PHP
?>
