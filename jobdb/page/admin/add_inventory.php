<?php
session_start();
include '../../service/condb.php';

$conn = new ConnectionDatabase();
$conn = $conn->connect();

// ตรวจสอบว่ามีการส่งข้อมูลผ่านฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $category = $_POST['category'];
    $price_set = $_POST['price_set'];
    $product_info = $_POST['product_info'];
    $qauntity_set = $_POST['qauntity_set'];
    $product_pic = ''; // เปลี่ยนตัวแปรเป็นค่าว่าง

    // ตรวจสอบว่ามีการอัปโหลดไฟล์รูปภาพหรือไม่
    if (isset($_FILES['product_pic']) && $_FILES['product_pic']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['product_pic']['tmp_name'];
        $fileName = $_FILES['product_pic']['name'];
        $fileSize = $_FILES['product_pic']['size'];
        $fileType = $_FILES['product_pic']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // ตรวจสอบขนาดของไฟล์ (เช่น จำกัดขนาดไม่เกิน 5MB)
        if ($fileSize > 5 * 1024 * 1024) {
            echo 'File size exceeds limit (5MB).';
            exit;
        }

        // กำหนดชื่อไฟล์ใหม่เพื่อหลีกเลี่ยงชื่อไฟล์ซ้ำ
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

        // กำหนด path ของโฟลเดอร์ที่ใช้เก็บไฟล์
        $uploadFileDir = '../../service/uploads/';
        
        // ตรวจสอบว่าโฟลเดอร์มีอยู่หรือไม่ หากไม่มีให้สร้างขึ้นมา
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }

        // ตรวจสอบสิทธิ์ในการเขียนไฟล์
        if (!is_writable($uploadFileDir)) {
            echo 'Upload directory is not writable.';
            exit;
        }

        $dest_path = $uploadFileDir . basename($newFileName);

        // ตรวจสอบชนิดของไฟล์
        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // เคลื่อนย้ายไฟล์จากตำแหน่งชั่วคราวไปยังตำแหน่งที่ต้องการ
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $product_pic = $newFileName; // เก็บชื่อไฟล์รูปภาพในตัวแปร $product_pic
            } else {
                echo 'There was some error moving the file to upload directory.';
                exit;
            }
        } else {
            echo 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
            exit;
        }
    } else {
        echo 'There is some error in the file upload. Please check the following error.<br>';
        echo 'Error: ' . $_FILES['product_pic']['error'];
        exit;
    }

// ตัวแปร $sql ต้องถูกกำหนดค่าไว้ที่นี่
$sql = "INSERT INTO list_product (product_name, category, price_set, product_info, qauntity_set, product_pic) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt) {
    // ตรวจสอบตัวแปรที่ต้องใช้ในการผูกกับคำสั่ง SQL
    $stmt->bind_param("ssdsis", $product_name, $category, $price_set, $product_info, $qauntity_set, $product_pic);
    if ($stmt->execute()) {
        echo "<!doctype html>
            <html>
            <body>
            <script>alert('Add product success'); window.location='inventory.php';</script>
            </body>
            </html>";
        $stmt->close();
    } else {
        echo 'Error executing query: ' . $stmt->error;
    }
} else {
    echo 'Error preparing statement: ' . $conn->error;
}

}


$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <a href="inventory.php" class="btn btn-success mb-4">Go to Inventory</a>
        <div class="h4 text-center alert alert-success mb-4 mt-4" role="alert">Add Product</div>

        <!-- เพิ่ม enctype="multipart/form-data" เพื่อให้สามารถอัปโหลดไฟล์ได้ -->
        <form action="" method="post" class="mb-5" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <input type="text" class="form-control" id="category" name="category" required>
            </div>
            <div class="mb-3">
                <label for="price_set" class="form-label">Price_Set</label>
                <input type="number" class="form-control" id="price_set" name="price_set" required>
            </div>
            <div class="mb-3">
                <label for="product_info" class="form-label">Product Info</label>
                <textarea class="form-control" id="product_info" name="product_info" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="qauntity_set" class="form-label">Qauntity_Set</label>
                <input type="number" class="form-control" id="qauntity_set" name="qauntity_set" required>
            </div>
            <!-- ฟอร์มสำหรับอัปโหลดไฟล์รูปภาพ -->
            <div class="mb-3">
                <label for="product_pic" class="form-label">Product Picture</label>
                <input type="file" class="form-control" id="product_pic" name="product_pic" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
            <a href="inventory.php" class="btn btn-danger">Cancel</a>
        </form>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </div>
</body>
</html>
