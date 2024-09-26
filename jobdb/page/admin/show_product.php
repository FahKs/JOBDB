<?php
session_start();
include '../../service/condb.php';

// สร้างการเชื่อมต่อกับฐานข้อมูล
$conn = new ConnectionDatabase();
$conn = $conn->connect();

// ตรวจสอบการเชื่อมต่อกับฐานข้อมูล
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

// ใช้ Prepared Statement เพื่อป้องกัน SQL Injection
$query = "SELECT * FROM list_product WHERE listproduct_id LIKE ? OR product_name LIKE ? OR category LIKE ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$search_param = "%$search%";
$stmt->bind_param("sss", $search_param, $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();

// ฟังก์ชันสำหรับการปิดบังข้อมูล
function maskData($data, $visibleLength = 3) {
    if (strlen($data) <= $visibleLength) {
        return $data;
    }
    return substr($data, 0, $visibleLength) . str_repeat('*', strlen($data) - $visibleLength);
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
        }

        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #f8f9fa;
            padding: 20px;
            border-right: 1px solid #ddd;
            position: fixed;
        }

        .sidebar h4 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar .nav-link {
            color: #333;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .sidebar .nav-link:hover {
            background-color: #e9ecef;
        }

        .sidebar .nav-link.active {
            background-color: #28a745;
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .content {
            margin-left: 240px;
            padding: 20px;
            width: 100%;
        }

        .masked {
            font-style: italic;
            color: #999;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4>Menu</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="list_user.php">
                    <i class="fas fa-user"></i> Manage
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle active" id="dropdownMenuLink" data-bs-toggle="dropdown"
                    aria-expanded="false" href="inventory.php">
                    <i class="fas fa-box"></i> Inventory
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <li><a class="dropdown-item active" href="show_product.php">Show Product</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="list_barcode.php">
                    <i class="fas fa-barcode"></i> Barcode
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="alert_setting.php">
                    <i class="fas fa-cog"></i> Alert Setting
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="reporting.php">
                    <i class="fas fa-file-alt"></i> Report
                </a>
            </li>
        </ul>
        <hr>
        <a href="singout.php" class="nav-link text-danger">
            <i class="fas fa-sign-out-alt"></i> Sign Out
        </a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <?php include '../../components/admin/navbar.php'; ?>
        <div class="container">
            <div class="h4 text-center alert alert-success mb-4 mt-4" role="alert">Product Info</div>

            <!-- ฟอร์มค้นหา -->
            <form action="show_product.php" method="get" class="d-flex align-items-center mb-3">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                    placeholder="Search..." class="form-control me-2">
                <button type="submit" class="btn btn-success me-2" style="width: 100px;">Search</button>
                <a href="show_product.php" class="btn btn-secondary" style="width: 100px;">Back</a>
            </form>

            <!-- ตารางแสดงข้อมูล -->
            <div class="table-responsive table-container">
                <table class="table table-success table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price_Set</th>
                            <th>Product Info</th>
                            <th>Quantity_Set</th>
                            <th>Product Pic</th>
                            <th>Update At</th>
                            <th>Visible</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $visible = $row["visible"] ? "checked" : ""; // เช็คสถานะ visible
                        ?>
                                <tr data-product-id="<?= $row["listproduct_id"] ?>">
                                    <td data-field="listproduct_id"><?= htmlspecialchars($row["listproduct_id"]) ?></td>
                                    <td data-field="product_name"><?= htmlspecialchars($row["product_name"]) ?></td>
                                    <td data-field="category"><?= htmlspecialchars($row["category"]) ?></td>
                                    <td data-field="price_set"><?= htmlspecialchars($row["price_set"]) ?></td>
                                    <td data-field="product_info"><?= htmlspecialchars($row["product_info"]) ?></td>
                                    <td data-field="quantity_set"><?= htmlspecialchars($row["qauntity_set"]) ?></td>
                                    <td data-field="product_pic">
                                        <?php
                                        $imagePath = "../../service/uploads/" . htmlspecialchars($row["product_pic"]);
                                        if (!empty($row["product_pic"]) && file_exists($imagePath)) : ?>
                                            <img src="<?= $imagePath ?>" alt="Product Image" width="120" height="90">
                                        <?php else : ?>
                                            No Image
                                        <?php endif; ?>
                                    </td>
                                    <td data-field="update_at"><?= htmlspecialchars($row["update_at"]) ?></td>
                                    <td>
                                        <!-- เพิ่ม Checkbox สำหรับเปิด/ปิดการมองเห็นของสินค้า -->
                                        <input type="checkbox" class="form-check-input visibility-checkbox"
                                            data-product-id="<?= $row['listproduct_id'] ?>" 
                                            onchange="updateVisibility(<?= $row['listproduct_id'] ?>, this.checked)"
                                            <?= $visible ?>>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center'>No records found.</td></tr>"; // แก้ไข colspan ให้ตรงกับจำนวนคอลัมน์ทั้งหมด
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- รวม Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateVisibility(productId, isVisible) {
            // ส่งข้อมูลไปยังไฟล์ update_visibility.php
            fetch('update_visibility.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `listproduct_id=${productId}&visible=${isVisible ? 1 : 0}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toggleMask(productId, isVisible);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการอัปเดตข้อมูล');
                });
        }

        function toggleMask(productId, showMask) {
            const row = document.querySelector(`tr[data-product-id="${productId}"]`);
            if (row) {
                const fields = row.querySelectorAll('td[data-field]');
                fields.forEach(cell => {
                    if (showMask) {
                        cell.setAttribute('data-original', cell.textContent);
                        cell.textContent = '*'.repeat(cell.textContent.length);
                    } else {
                        cell.textContent = cell.getAttribute('data-original') || cell.textContent;
                    }
                });

                const imgCell = row.querySelector('td[data-field="product_pic"] img');
                if (imgCell) {
                    imgCell.style.display = showMask ? 'none' : 'block';
                }
            }
        }
    </script>

    <?php
    // ปิดการเชื่อมต่อฐานข้อมูล
    $stmt->close();
    $conn->close();
    ?>
</body>

</html>
