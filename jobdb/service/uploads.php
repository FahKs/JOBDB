<?php
// กำหนดโฟลเดอร์ที่เก็บไฟล์รูปภาพที่ถูกอัปโหลด
$uploadDirectory = __DIR__ . '/uploads/';

// ตรวจสอบว่ามีการกำหนดชื่อไฟล์ใน URL หรือไม่
if (isset($_GET['file'])) {
    $filename = $_GET['file'];

    // สร้างพาธของไฟล์ที่ถูกอัปโหลด
    $filePath = $uploadDirectory . basename($filename);

    // ตรวจสอบว่าไฟล์มีอยู่ในโฟลเดอร์อัปโหลดหรือไม่
    if (file_exists($filePath)) {
        // กำหนดประเภทของไฟล์เพื่อส่งไปยังเบราว์เซอร์
        $fileInfo = mime_content_type($filePath);
        header("Content-Type: " . $fileInfo);
        header("Content-Length: " . filesize($filePath));

        // ส่งเนื้อหาไฟล์ไปยังเบราว์เซอร์
        readfile($filePath);
        exit;
    } else {
        echo "File not found!";
    }
} else {
    echo "No file specified!";
}

