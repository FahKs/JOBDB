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
    
    // เพิ่มข้อมูลลงในฐานข้อมูลและอัปเดตถ้าหาก store_id มีอยู่แล้ว
    $sql = "INSERT INTO store (store_id, store_name, location_store, tel_store, update_at) 
            VALUES ('$store_id', '$store_name', '$location_store', '$tel_store', CURRENT_TIMESTAMP)
            ON DUPLICATE KEY UPDATE 
            store_name = '$store_name', 
            location_store = '$location_store', 
            tel_store = '$tel_store', 
            update_at = CURRENT_TIMESTAMP";

    if (mysqli_query($conn, $sql)) {
        echo "<!doctype html>
            <html>
            <body>
            <script>alert('Data added or updated successfully'); window.location='store.php';</script>
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
    <label for="location_store">Location Store</label>
    <select id="location_store" name="location_store" class="form-control">
    <option value="" disabled selected>เลือกจังหวัด</option>
    <option value="กรุงเทพมหานคร">กรุงเทพมหานคร</option>
    <option value="กระบี่">กระบี่</option>
    <option value="กาญจนบุรี">กาญจนบุรี</option>
    <option value="กาฬสินธุ์">กาฬสินธุ์</option>
    <option value="กำแพงเพชร">กำแพงเพชร</option>
    <option value="ขอนแก่น">ขอนแก่น</option>
    <option value="จันทบุรี">จันทบุรี</option>
    <option value="ฉะเชิงเทรา">ฉะเชิงเทรา</option>
    <option value="ชัยนาท">ชัยนาท</option>
    <option value="ชัยภูมิ">ชัยภูมิ</option>
    <option value="ชุมพร">ชุมพร</option>
    <option value="ชลบุรี">ชลบุรี</option>
    <option value="เชียงใหม่">เชียงใหม่</option>
    <option value="เชียงราย">เชียงราย</option>
    <option value="ตรัง">ตรัง</option>
    <option value="ตราด">ตราด</option>
    <option value="ตาก">ตาก</option>
    <option value="นครนายก">นครนายก</option>
    <option value="นครปฐม">นครปฐม</option>
    <option value="นครพนม">นครพนม</option>
    <option value="นครราชสีมา">นครราชสีมา</option>
    <option value="นครศรีธรรมราช">นครศรีธรรมราช</option>
    <option value="นครสวรรค์">นครสวรรค์</option>
    <option value="นนทบุรี">นนทบุรี</option>
    <option value="นราธิวาส">นราธิวาส</option>
    <option value="น่าน">น่าน</option>
    <option value="บึงกาฬ">บึงกาฬ</option>
    <option value="บุรีรัมย์">บุรีรัมย์</option>
    <option value="ปทุมธานี">ปทุมธานี</option>
    <option value="ประจวบคีรีขันธ์">ประจวบคีรีขันธ์</option>
    <option value="ปราจีนบุรี">ปราจีนบุรี</option>
    <option value="ปัตตานี">ปัตตานี</option>
    <option value="พะเยา">พะเยา</option>
    <option value="พระนครศรีอยุธยา">พระนครศรีอยุธยา</option>
    <option value="พังงา">พังงา</option>
    <option value="พัทลุง">พัทลุง</option>
    <option value="พิจิตร">พิจิตร</option>
    <option value="พิษณุโลก">พิษณุโลก</option>
    <option value="เพชรบุรี">เพชรบุรี</option>
    <option value="เพชรบูรณ์">เพชรบูรณ์</option>
    <option value="แพร่">แพร่</option>
    <option value="พะเยา">พะเยา</option>
    <option value="ภูเก็ต">ภูเก็ต</option>
    <option value="มหาสารคาม">มหาสารคาม</option>
    <option value="มุกดาหาร">มุกดาหาร</option>
    <option value="แม่ฮ่องสอน">แม่ฮ่องสอน</option>
    <option value="ยโสธร">ยโสธร</option>
    <option value="ยะลา">ยะลา</option>
    <option value="ร้อยเอ็ด">ร้อยเอ็ด</option>
    <option value="ระนอง">ระนอง</option>
    <option value="ระยอง">ระยอง</option>
    <option value="ราชบุรี">ราชบุรี</option>
    <option value="ลพบุรี">ลพบุรี</option>
    <option value="ลำปาง">ลำปาง</option>
    <option value="ลำพูน">ลำพูน</option>
    <option value="เลย">เลย</option>
    <option value="ศรีสะเกษ">ศรีสะเกษ</option>
    <option value="สกลนคร">สกลนคร</option>
    <option value="สงขลา">สงขลา</option>
    <option value="สตูล">สตูล</option>
    <option value="สมุทรปราการ">สมุทรปราการ</option>
    <option value="สมุทรสงคราม">สมุทรสงคราม</option>
    <option value="สมุทรสาคร">สมุทรสาคร</option>
    <option value="สระแก้ว">สระแก้ว</option>
    <option value="สระบุรี">สระบุรี</option>
    <option value="สิงห์บุรี">สิงห์บุรี</option>
    <option value="สุโขทัย">สุโขทัย</option>
    <option value="สุพรรณบุรี">สุพรรณบุรี</option>
    <option value="สุราษฎร์ธานี">สุราษฎร์ธานี</option>
    <option value="สุรินทร์">สุรินทร์</option>
    <option value="หนองคาย">หนองคาย</option>
    <option value="หนองบัวลำภู">หนองบัวลำภู</option>
    <option value="อ่างทอง">อ่างทอง</option>
    <option value="อุดรธานี">อุดรธานี</option>
    <option value="อุทัยธานี">อุทัยธานี</option>
    <option value="อุตรดิตถ์">อุตรดิตถ์</option>
    <option value="อุบลราชธานี">อุบลราชธานี</option>
    <option value="อำนาจเจริญ">อำนาจเจริญ</option>
    </select>
    <div class="mb-3">
        <label for="tel_store" class="form-label">Tel_Store</label>
        <input type="tel" class="form-control" id="tel_store" name="tel_store" required>
    </div>
    <button type="submit" class="btn btn-success">Submit</button>
    <a href="store.php" class="btn btn-danger">Cancel</a>
</form>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN
