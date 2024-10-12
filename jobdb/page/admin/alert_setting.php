<?php
session_start();
include '../../service/condb.php';

try {
    $conn = new ConnectionDatabase();
    $conn = $conn->connect();

    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $query = "SELECT * FROM alerts_setting WHERE setting_id LIKE ? OR set_expday LIKE ? OR set_lowstock LIKE ?";
    $stmt = $conn->prepare($query);
    $search_param = "%$search%";
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alert Setting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: row;
            margin: 0;
        }

        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #f8f9fa;
            padding: 20px;
            border-right: 1px solid #ddd;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            z-index: 1000;
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
            width: calc(100% - 240px);
            overflow-x: auto;
        }

        .table-container {
            margin-top: 40px;
        }

        .btn-group-custom {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 15px;
        }

        .btn-group-custom .btn {
            margin-left: 10px;
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
            <li class="nav-item">
                <a class="nav-link" href="inventory.php">
                    <i class="fas fa-box"></i> Inventory
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="admin_tracking.php">
            <i class="fas fa-car"></i> Traking
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="alert_setting.php">
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
            <div class="h4 text-center alert alert-success mb-4 mt-4" role="alert">Alerts</div>
            <div class="btn-group-custom">
                <a href="create_alerts.php" class="btn btn-success">Create Alert</a>

                <form action="alert_setting.php" method="get" class="d-flex align-items-center">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search..." class="form-control me-2">
                    <button type="submit" class="btn btn-success me-2" style="width: 100px;">Search</button>
                    <a href="alert_setting.php" class="btn btn-secondary" style="width: 100px;">Back</a>
                </form>
            </div>

            <!-- Table to Display Data -->
            <div class="table-responsive table-container">
                <table class="table table-success table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Setting_ID </th>
                            <th>Listproduct_ID</th>
                            <th>Set_Expday</th>
                            <th>Set_Lowstock</th>
                            <th>Setting_Info</th>
                            <th>Update_At</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result) {
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                        ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row["setting_id"]) ?></td>
                                        <td><?= htmlspecialchars($row["listproduct_id"]) ?></td>
                                        <td><?= htmlspecialchars($row["set_expday"]) ?></td>
                                        <td><?= htmlspecialchars($row["set_lowstock"]) ?></td>
                                        <td><?= htmlspecialchars($row['setting_info']) ?></td>
                                        <td><?= htmlspecialchars($row['update_at']) ?></td>
                                        <td>
                                            <a href="edit_alerts.php?setting_id=<?= htmlspecialchars($row['setting_id']) ?>" class="btn btn-warning">Edit</a>
                                        </td>
                                        <td>
                                            <a href="delete_alerts.php?setting_id=<?= htmlspecialchars($row['setting_id']) ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this alert?');">Delete</a>
                                        </td>
                                    </tr>
                        <?php
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center'>No records found.</td></tr>";
                            }
                        } else {
                            echo "<p>Error fetching data: " . $conn->error . "</p>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php
    if ($conn && mysqli_ping($conn)) {
        mysqli_close($conn);
    }
    ?>
</body>

</html>
