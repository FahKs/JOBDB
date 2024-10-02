<?php
session_start();
include '../../service/condb.php'; 

$conn = new ConnectionDatabase();
$conn = $conn->connect();

// ตรวจสอบว่ามีการรับค่า listproduct_id หรือไม่ (เปลี่ยนจาก product_id เป็น listproduct_id)
$listproduct_id = isset($_GET['listproduct_id']) ? intval($_GET['listproduct_id']) : null;

if (!$listproduct_id) {
    echo "<script>alert('No Product ID provided.'); window.location.href='inventory.php';</script>";
    exit;
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

// ดึงข้อมูลผลิตภัณฑ์จากฐานข้อมูล
$query = "SELECT * FROM list_product WHERE listproduct_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $listproduct_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $product_name = $row["product_name"];
    $category = $row["category"];
    $price_set = $row["price_set"];
    $product_info = $row["product_info"];
    $quantity_set = $row["qauntity_set"];
    $product_pic = $row["product_pic"];   
} else {
    echo "<script>alert('Product not found.'); window.location.href='inventory.php';</script>";
    exit;
}

// ตรวจสอบว่ามีการส่งข้อมูลผ่านฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $category = $_POST['category'];
    $price_set = $_POST['price_set'];
    $product_info = $_POST['product_info'];
    $quantity_set = $_POST['quantity_set'];
    $search = $_POST['search'];
    $new_product_pic = $product_pic; // กำหนดรูปภาพเป็นรูปเดิมก่อน หากไม่มีการอัปโหลดรูปใหม่

    // ตรวจสอบว่ามีการอัปโหลดรูปภาพใหม่หรือไม่
    if (isset($_FILES['product_pic']) && $_FILES['product_pic']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['product_pic']['tmp_name'];
        $fileName = $_FILES['product_pic']['name'];
        $fileSize = $_FILES['product_pic']['size'];
        $fileType = $_FILES['product_pic']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // ตรวจสอบชนิดของไฟล์
        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // กำหนดชื่อไฟล์ใหม่เพื่อหลีกเลี่ยงชื่อไฟล์ซ้ำ
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            
            // กำหนด path ของโฟลเดอร์ที่ใช้เก็บไฟล์และตรวจสอบว่าโฟลเดอร์มีอยู่จริงหรือไม่
            $uploadFileDir = '../../service/uploads/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true); // สร้างโฟลเดอร์ถ้าไม่มีอยู่
            }
            $dest_path = $uploadFileDir . $newFileName;

            // เคลื่อนย้ายไฟล์จากตำแหน่งชั่วคราวไปยังตำแหน่งที่ต้องการ
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // ลบรูปภาพเก่าออกหากมี
                if (!empty($product_pic) && file_exists($uploadFileDir . $product_pic)) {
                    unlink($uploadFileDir . $product_pic);
                }
                $new_product_pic = $newFileName; // เก็บชื่อไฟล์รูปภาพใหม่ในตัวแปร $new_product_pic
            } else {
                echo 'There was some error moving the file to upload directory: ' . $dest_path;
                exit;
            }
        } else {
            echo 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
            exit;
        }
    }

    // ใช้ Prepared Statement เพื่ออัปเดตข้อมูลในฐานข้อมูล
    $update_query = "UPDATE list_product SET 
                     product_name = ?, 
                     category = ?, 
                     price_set = ?, 
                     product_info = ?, 
                     qauntity_set = ?,
                     product_pic = ?
                     WHERE listproduct_id = ?";
    
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssdsisi", $product_name, $category, $price_set, $product_info, $quantity_set, $new_product_pic, $listproduct_id);

    if ($stmt->execute()) {
        // หลังจากอัปเดตสำเร็จ ให้กลับไปยังหน้า inventory.php พร้อมกับการค้นหาเดิม
        header("Location: inventory.php?search=" . urlencode($search)); 
        exit;
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="h4 text-center alert alert-info mb-4" role="alert">Edit Product</div>

        <!-- ฟอร์มแก้ไขผลิตภัณฑ์ -->
        <form action="edit_inventory.php?listproduct_id=<?= htmlspecialchars($listproduct_id) ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="listproduct_id" value="<?= htmlspecialchars($listproduct_id) ?>">
            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">

            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="product_name" value="<?= htmlspecialchars($product_name) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Category</label>
                <input type="text" name="category" value="<?= htmlspecialchars($category) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Price_Set</label>
                <input type="number" name="price_set" value="<?= htmlspecialchars($price_set) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Product Info:</label>
                <textarea name="product_info" class="form-control" rows="3"><?= htmlspecialchars($product_info) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Qauntity_Set</label>
                <input type="number" name="quantity_set" value="<?= htmlspecialchars($quantity_set) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Product Picture</label>
                <input type="file" name="product_pic" class="form-control" accept="image/*">
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
            <button type="button" class="btn btn-danger" onclick="window.location.href='inventory.php?search=<?= urlencode($search) ?>'">Cancel</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>