<?php
session_start();
include '../../service/condb.php';

$conn = new ConnectionDatabase();
$conn = $conn->connect();

$listproduct_id = isset($_GET['listproduct_id']) ? $_GET['listproduct_id'] : null;

if (!$listproduct_id) {
   
    echo "<script>alert('No Product ID provided.'); window.location.href='list_barcode.php';</script>";
    exit;
}

$query = "SELECT * FROM list_product WHERE listproduct_id = '$listproduct_idd'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
   
    $product = mysqli_fetch_assoc($result);
} else {
    echo "<script>alert('Product not found.'); window.location.href='list_barcode.php';</script>";
    exit;
}

mysqli_close($conn);
?>

<!doctype html>
<html lang="en">

<head>
    <title>Product Detail</title>
    <?php include '../../components/header.php'; ?>
</head>

<body>
    <div class="container mt-5">
    <div class="h4 text-center alert alert-success mb-4 mt-4" role="alert"> Product detail </div>
        <table class="table table-bordered table-striped">
            <thead class="table-success">
                <tr>
                    <th>Data</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Product ID</td>
                    <td><?= htmlspecialchars($product['listproduct_id']) ?></td>
                </tr>
                <tr>
                    <td>Product Name</td>
                    <td><?= htmlspecialchars($product['product_name']) ?></td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td><?= htmlspecialchars($product['category']) ?></td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><?= htmlspecialchars($product['price']) ?></td>
                </tr>
                <tr>
                    <td>Product Info</td>
                    <td><?= htmlspecialchars($product['product_info']) ?></td>
                </tr>
                <tr>
                    <td>Item Set</td>
                    <td><?= htmlspecialchars($product['item_set']) ?></td>
                </tr>
                <tr>
                    <td>Update At</td>
                    <td><?= htmlspecialchars($product['update_at']) ?></td>
                </tr>
            </tbody>
        </table>
        <!-- ปุ่มกลับไปยังหน้า list_barcode.php -->
        <div class="text-center">
            <a href="list_barcode.php" class="btn btn-success">Back</a>
        </div>
    </div>

    <!-- รวม Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
