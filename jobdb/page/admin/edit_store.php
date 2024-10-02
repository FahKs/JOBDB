<?php
session_start();
include '../../service/condb.php';

$conn = new ConnectionDatabase();
$conn = $conn->connect();

// กำหนดค่าเริ่มต้นให้ตัวแปร
$store_name = '';
$location_store = '';
$tel_store = '';
$search = '';

$store_id = isset($_GET['store_id']) ? $_GET['store_id'] : null;

if ($store_id === null) {
    echo "Store ID is missing.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $store_id = isset($_POST['store_id']) ? $_POST['store_id'] : null;

    if ($store_id === null) {
        echo "Store ID is missing.";
        exit;
    }

    $store_name = $_POST['store_name'];
    $location_store = $_POST['location_store'];
    $tel_store = $_POST['tel_store'];
    $search = isset($_POST['search']) ? $_POST['search'] : '';

    // ป้องกัน SQL Injection
    $store_name = mysqli_real_escape_string($conn, $store_name);
    $location_store = mysqli_real_escape_string($conn, $location_store);
    $tel_store = mysqli_real_escape_string($conn, $tel_store);

    // อัปเดตข้อมูลในฐานข้อมูล
    $update_query = "UPDATE store SET store_name='$store_name', location_store='$location_store', tel_store='$tel_store' WHERE store_id='$store_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: store.php?search=" . urlencode($search)); // กลับไปยังหน้า store.php พร้อมกับการค้นหาเดิม
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
    $query = "SELECT * FROM store WHERE store_id = '$store_id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $store_name = $row["store_name"];
        $location_store = $row["location_store"];
        $tel_store = $row["tel_store"];
    } else {
        echo "Store not found.";
        exit;
    }
}

mysqli_close($conn);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Store Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="h4 text-center alert alert-info mb-4" role="alert">Edit Store Data</div>

        <!-- ฟอร์มแก้ไขผู้ใช้ -->
        <form action="" method="post">
            <input type="hidden" name="store_id" value="<?= htmlspecialchars($store_id) ?>">
            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">

            <div class="mb-3">
                <label class="form-label">Store_Name:</label>
                <input type="text" name="store_name" value="<?= htmlspecialchars($store_name) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
    <label class="form-label">Location Store:</label>
    <select name="location_store" class="form-control" required>
        <option value="" disabled <?= empty($location_store) ? 'selected' : '' ?>>เลือกจังหวัด</option>
        <option value="กรุงเทพมหานคร" <?= $location_store == 'กรุงเทพมหานคร' ? 'selected' : '' ?>>กรุงเทพมหานคร</option>
        <option value="กระบี่" <?= $location_store == 'กระบี่' ? 'selected' : '' ?>>กระบี่</option>
        <option value="กาญจนบุรี" <?= $location_store == 'กาญจนบุรี' ? 'selected' : '' ?>>กาญจนบุรี</option>
        <option value="กาฬสินธุ์" <?= $location_store == 'กาฬสินธุ์' ? 'selected' : '' ?>>กาฬสินธุ์</option>
        <option value="กำแพงเพชร" <?= $location_store == 'กำแพงเพชร' ? 'selected' : '' ?>>กำแพงเพชร</option>
        <option value="ขอนแก่น" <?= $location_store == 'ขอนแก่น' ? 'selected' : '' ?>>ขอนแก่น</option>
        <option value="จันทบุรี" <?= $location_store == 'จันทบุรี' ? 'selected' : '' ?>>จันทบุรี</option>
        <option value="ฉะเชิงเทรา" <?= $location_store == 'ฉะเชิงเทรา' ? 'selected' : '' ?>>ฉะเชิงเทรา</option>
        <option value="ชัยนาท" <?= $location_store == 'ชัยนาท' ? 'selected' : '' ?>>ชัยนาท</option>
        <option value="ชัยภูมิ" <?= $location_store == 'ชัยภูมิ' ? 'selected' : '' ?>>ชัยภูมิ</option>
        <option value="ชุมพร" <?= $location_store == 'ชุมพร' ? 'selected' : '' ?>>ชุมพร</option>
        <option value="ชลบุรี" <?= $location_store == 'ชลบุรี' ? 'selected' : '' ?>>ชลบุรี</option>
        <option value="เชียงใหม่" <?= $location_store == 'เชียงใหม่' ? 'selected' : '' ?>>เชียงใหม่</option>
        <option value="เชียงราย" <?= $location_store == 'เชียงราย' ? 'selected' : '' ?>>เชียงราย</option>
        <option value="ตรัง" <?= $location_store == 'ตรัง' ? 'selected' : '' ?>>ตรัง</option>
        <option value="ตราด" <?= $location_store == 'ตราด' ? 'selected' : '' ?>>ตราด</option>
        <option value="ตาก" <?= $location_store == 'ตาก' ? 'selected' : '' ?>>ตาก</option>
        <option value="นครนายก" <?= $location_store == 'นครนายก' ? 'selected' : '' ?>>นครนายก</option>
        <option value="นครปฐม" <?= $location_store == 'นครปฐม' ? 'selected' : '' ?>>นครปฐม</option>
        <option value="นครพนม" <?= $location_store == 'นครพนม' ? 'selected' : '' ?>>นครพนม</option>
        <option value="นครราชสีมา" <?= $location_store == 'นครราชสีมา' ? 'selected' : '' ?>>นครราชสีมา</option>
        <option value="นครศรีธรรมราช" <?= $location_store == 'นครศรีธรรมราช' ? 'selected' : '' ?>>นครศรีธรรมราช</option>
        <option value="นครสวรรค์" <?= $location_store == 'นครสวรรค์' ? 'selected' : '' ?>>นครสวรรค์</option>
        <option value="นนทบุรี" <?= $location_store == 'นนทบุรี' ? 'selected' : '' ?>>นนทบุรี</option>
        <option value="นราธิวาส" <?= $location_store == 'นราธิวาส' ? 'selected' : '' ?>>นราธิวาส</option>
        <option value="น่าน" <?= $location_store == 'น่าน' ? 'selected' : '' ?>>น่าน</option>
        <option value="บึงกาฬ" <?= $location_store == 'บึงกาฬ' ? 'selected' : '' ?>>บึงกาฬ</option>
        <option value="บุรีรัมย์" <?= $location_store == 'บุรีรัมย์' ? 'selected' : '' ?>>บุรีรัมย์</option>
        <option value="ปทุมธานี" <?= $location_store == 'ปทุมธานี' ? 'selected' : '' ?>>ปทุมธานี</option>
        <option value="ประจวบคีรีขันธ์" <?= $location_store == 'ประจวบคีรีขันธ์' ? 'selected' : '' ?>>ประจวบคีรีขันธ์</option>
        <option value="ปราจีนบุรี" <?= $location_store == 'ปราจีนบุรี' ? 'selected' : '' ?>>ปราจีนบุรี</option>
        <option value="ปัตตานี" <?= $location_store == 'ปัตตานี' ? 'selected' : '' ?>>ปัตตานี</option>
        <option value="พะเยา" <?= $location_store == 'พะเยา' ? 'selected' : '' ?>>พะเยา</option>
        <option value="พระนครศรีอยุธยา" <?= $location_store == 'พระนครศรีอยุธยา' ? 'selected' : '' ?>>พระนครศรีอยุธยา</option>
        <option value="พังงา" <?= $location_store == 'พังงา' ? 'selected' : '' ?>>พังงา</option>
        <option value="พัทลุง" <?= $location_store == 'พัทลุง' ? 'selected' : '' ?>>พัทลุง</option>
        <option value="พิจิตร" <?= $location_store == 'พิจิตร' ? 'selected' : '' ?>>พิจิตร</option>
        <option value="พิษณุโลก" <?= $location_store == 'พิษณุโลก' ? 'selected' : '' ?>>พิษณุโลก</option>
        <option value="เพชรบุรี" <?= $location_store == 'เพชรบุรี' ? 'selected' : '' ?>>เพชรบุรี</option>
        <option value="เพชรบูรณ์" <?= $location_store == 'เพชรบูรณ์' ? 'selected' : '' ?>>เพชรบูรณ์</option>
        <option value="แพร่" <?= $location_store == 'แพร่' ? 'selected' : '' ?>>แพร่</option>
        <option value="ภูเก็ต" <?= $location_store == 'ภูเก็ต' ? 'selected' : '' ?>>ภูเก็ต</option>
        <option value="มหาสารคาม" <?= $location_store == 'มหาสารคาม' ? 'selected' : '' ?>>มหาสารคาม</option>
        <option value="มุกดาหาร" <?= $location_store == 'มุกดาหาร' ? 'selected' : '' ?>>มุกดาหาร</option>
        <option value="แม่ฮ่องสอน" <?= $location_store == 'แม่ฮ่องสอน' ? 'selected' : '' ?>>แม่ฮ่องสอน</option>
        <option value="ยโสธร" <?= $location_store == 'ยโสธร' ? 'selected' : '' ?>>ยโสธร</option>
        <option value="ยะลา" <?= $location_store == 'ยะลา' ? 'selected' : '' ?>>ยะลา</option>
        <option value="ร้อยเอ็ด" <?= $location_store == 'ร้อยเอ็ด' ? 'selected' : '' ?>>ร้อยเอ็ด</option>
        <option value="ระนอง" <?= $location_store == 'ระนอง' ? 'selected' : '' ?>>ระนอง</option>
        <option value="ระยอง" <?= $location_store == 'ระยอง' ? 'selected' : '' ?>>ระยอง</option>
        <option value="ราชบุรี" <?= $location_store == 'ราชบุรี' ? 'selected' : '' ?>>ราชบุรี</option>
        <option value="ลพบุรี" <?= $location_store == 'ลพบุรี' ? 'selected' : '' ?>>ลพบุรี</option>
        <option value="ลำปาง" <?= $location_store == 'ลำปาง' ? 'selected' : '' ?>>ลำปาง</option>
        <option value="ลำพูน" <?= $location_store == 'ลำพูน' ? 'selected' : '' ?>>ลำพูน</option>
        <option value="เลย" <?= $location_store == 'เลย' ? 'selected' : '' ?>>เลย</option>
        <option value="ศรีสะเกษ" <?= $location_store == 'ศรีสะเกษ' ? 'selected' : '' ?>>ศรีสะเกษ</option>
        <option value="สกลนคร" <?= $location_store == 'สกลนคร' ? 'selected' : '' ?>>สกลนคร</option>
        <option value="สงขลา" <?= $location_store == 'สงขลา' ? 'selected' : '' ?>>สงขลา</option>
        <option value="สตูล" <?= $location_store == 'สตูล' ? 'selected' : '' ?>>สตูล</option>
        <option value="สมุทรปราการ" <?= $location_store == 'สมุทรปราการ' ? 'selected' : '' ?>>สมุทรปราการ</option>
        <option value="สมุทรสงคราม" <?= $location_store == 'สมุทรสงคราม' ? 'selected' : '' ?>>สมุทรสงคราม</option>
        <option value="สมุทรสาคร" <?= $location_store == 'สมุทรสาคร' ? 'selected' : '' ?>>สมุทรสาคร</option>
        <option value="สระแก้ว" <?= $location_store == 'สระแก้ว' ? 'selected' : '' ?>>สระแก้ว</option>
        <option value="สระบุรี" <?= $location_store == 'สระบุรี' ? 'selected' : '' ?>>สระบุรี</option>
        <option value="สิงห์บุรี" <?= $location_store == 'สิงห์บุรี' ? 'selected' : '' ?>>สิงห์บุรี</option>
        <option value="สุโขทัย" <?= $location_store == 'สุโขทัย' ? 'selected' : '' ?>>สุโขทัย</option>
        <option value="สุพรรณบุรี" <?= $location_store == 'สุพรรณบุรี' ? 'selected' : '' ?>>สุพรรณบุรี</option>
        <option value="สุราษฎร์ธานี" <?= $location_store == 'สุราษฎร์ธานี' ? 'selected' : '' ?>>สุราษฎร์ธานี</option>
        <option value="สุรินทร์" <?= $location_store == 'สุรินทร์' ? 'selected' : '' ?>>สุรินทร์</option>
        <option value="หนองคาย" <?= $location_store == 'หนองคาย' ? 'selected' : '' ?>>หนองคาย</option>
        <option value="หนองบัวลำภู" <?= $location_store == 'หนองบัวลำภู' ? 'selected' : '' ?>>หนองบัวลำภู</option>
        <option value="อ่างทอง" <?= $location_store == 'อ่างทอง' ? 'selected' : '' ?>>อ่างทอง</option>
        <option value="อุดรธานี" <?= $location_store == 'อุดรธานี' ? 'selected' : '' ?>>อุดรธานี</option>
        <option value="อุทัยธานี" <?= $location_store == 'อุทัยธานี' ? 'selected' : '' ?>>อุทัยธานี</option>
        <option value="อุตรดิตถ์" <?= $location_store == 'อุตรดิตถ์' ? 'selected' : '' ?>>อุตรดิตถ์</option>
        <option value="อุบลราชธานี" <?= $location_store == 'อุบลราชธานี' ? 'selected' : '' ?>>อุบลราชธานี</option>
        <option value="อำนาจเจริญ" <?= $location_store == 'อำนาจเจริญ' ? 'selected' : '' ?>>อำนาจเจริญ</option>
    </select>
        </div>
        <div class="mb-3">
                <label class="form-label">Tel_Store:</label>
                <input type="tel" name="tel_store" value="<?= htmlspecialchars($tel_store) ?>" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
            <button type="button" class="btn btn-danger" onclick="window.location.href='store.php?search=<?= urlencode($search) ?>'">Cancel</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
