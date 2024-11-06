<?php
session_start();
include('../../config/db.php');
  // เปลี่ยนเส้นทางการเชื่อมต่อฐานข้อมูล
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../../auth/login.php');  // เปลี่ยนเส้นทางการเช็ค role
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT u.name, u.surname, u.role, u.store_id, s.store_name 
          FROM users u
          LEFT JOIN stores s ON u.store_id = s.store_id 
          WHERE u.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $name = $user['name'];
    $surname = $user['surname'];
    $role = $user['role'];
} else {
    header("Location: login.php");
    exit();
}
$query = "SELECT nr.*, u.name, u.surname, s.store_name
          FROM notiflyreport nr
          LEFT JOIN users u ON nr.user_id = u.user_id
          LEFT JOIN stores s ON nr.store_id = s.store_id
          WHERE nr.notiflyreport_type IN ('issue_order', 'issue_product', 'add_product' , 'order_product', 'deli_order')
          AND nr.status = 'unread'
          ORDER BY nr.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./respontive.css">
    <style>
        .chat-messages {
            flex-grow: 1;
            overflow-y: auto;
            padding: 15px;
            
        }
        .notification-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            padding: 10px;
            max-width: 70%;
            border-radius: 15px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            position: relative;
            background-color: #e6f7ff;
            color: #333;
        }
        
        .action .btn {
            margin-top: 10px;
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 10px;
        }
        .chat-header {
    background-color: #f9f9f9;
    margin-top: 20px;
    font-size: 1.5rem;
    font-weight: bold;
    padding-left: 20px; /* ขยับข้อความไปทางขวา */
    text-align: left; /* จัดให้ข้อความอยู่ด้านซ้าย (หรือคุณสามารถใช้ center ก็ได้ถ้าต้องการจัดกลาง) */
}

       
        .dashboard-container {
          display: flex;
          justify-content: space-between;
          align-items: flex-start;
          margin-top: 20px;
          padding: 0 20px;
         gap: 20px; /* เว้นช่องว่างระหว่างกล่อง */
        }

/* ปรับแต่งกล่องแจ้งเตือน */
.chat-container, #echart-doughnut {
    flex: 1;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    background-color: #f9f9f9;
}

/* ตั้งค่า chat-container */
.chat-container {
    display: flex;
    flex-direction: column;
    height: 300px; /* สูงเท่ากัน */
}

/* ตั้งค่ากราฟโดนัท */
#echart-doughnut {
    width: 400px; /* ตั้งค่าความกว้าง */
    height: 300px; /* ตั้งค่าความสูง */
    max-width: 100%; /* ให้ responsive ตามหน้าจอ */
    margin: 0 auto; /* จัดกึ่งกลาง */
    padding: 20px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); /* เพิ่มเงา */
    border-radius: 8px; /* เพิ่มมุมโค้ง */
}
.card-body{
    margin-top: 10px;
}

.card{
    margin-top: 20px;
}
    </style>
</head>
<body>
    <button id="menu-toggle">☰</button>
    <header id="banner">
        <a id="user-info">Name: <?php echo htmlspecialchars($name) . ' ' . htmlspecialchars($surname); ?> | Role: <?php echo htmlspecialchars($role); ?></a>
        <button class="btn btn-danger" onclick="window.location.href='../../auth/logout.php'">Log Out</button>
    </header>
    <div id="sidebar">
        <h4 class="text-center">Menu</h4>
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_user.php">Manage Users</a>
        <a href="manage_store.php">Manage Stores</a>
        <a href="product_menu.php">Product Menu</a>
        <a href="order_management.php">Order Request</a>
        <a href="product_management.php">Product Report</a>
        <a href="notification-settings.php">Notification Settings</a>
        <a href="reports.php">Reports</a>
    </div>
    <div class="chat-container mt-5">
    <div class="dashboard-container">
        <div class="chat-container">
            <div class="chat-header">Notifications</div>
            <div class="chat-messages">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="notification-item <?php echo $row['status'] === 'unread' ? 'unread' : ''; ?>">
                            <div class="message-content">
                                <div class="type">Type: <?php echo $row['notiflyreport_type']; ?></div>
                                <div class="reporter">Reporter: <?php echo htmlspecialchars($row['name'] . ' ' . $row['surname']); ?></div>
                                <div class="status">Status: <?php echo ucfirst($row['status']); ?></div>
                                <div class="message-info">Date: <?php echo date('Y-m-d H:i:s', strtotime($row['created_at'])); ?></div>
                                <div class="action">
                                    <?php if ($row['order_id']): ?>
                                        <a href="tracking.php?order_id=<?php echo $row['order_id']; ?>&notiflyreport_id=<?php echo $row['notiflyreport_id']; ?>" class="btn btn-primary btn-sm">View Order</a>
                                    <?php endif; ?>
                                    <?php if ($row['product_id']): ?>
                                        <a href="resolution.php?product_id=<?php echo $row['product_id']; ?>&notiflyreport_id=<?php echo $row['notiflyreport_id']; ?>" class="btn btn-info btn-sm">View Product</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-info text-center">No notifications found</div>
                <?php endif; ?>
            </div>
        </div>

        <div id="echart-doughnut"></div>
    </div>
    

    <div class="card-group">
                      <div class="card">
                        <img class="card-img-top" src="https://images.pexels.com/photos/302899/pexels-photo-302899.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1">
                        <div class="card-body">
                          <h4 class="card-title">Botoko Coffee</h4>
                          <p class="card-text">Crafting Coffee with Care.</p>
                          <p class="card-text">
                            <small class="text-muted">Last updated 45 mins ago</small>
                          </p>
                        </div>
                      </div>
                      <div class="card">
                        <img class="card-img-top" src="https://images.pexels.com/photos/1695052/pexels-photo-1695052.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1">
                        <div class="card-body">
                          <h4 class="card-title">Where Every Bean Counts</h4>
                          <p class="card-text">From Bean to Brew, Pure Passio.</p>
                          <p class="card-text">
                            <small class="text-muted">Last updated 5 mins ago</small>
                          </p>
                        </div>
                      </div>
                      <div class="card">
                        <img class="card-img-top" src="https://images.pexels.com/photos/317377/pexels-photo-317377.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1">
                        <div class="card-body">
                          <h4 class="card-title">Coffee That Inspires Productivity"</h4>
                          <p class="card-text">The Perfect Blend for Work and Play.</p>
                          <p class="card-text">
                            <small class="text-muted">Last updated 15 mins ago</small>
                          </p>
                        </div>
                      </div>
                      <div class="card">
                        <img class="card-img-top" src="https://images.pexels.com/photos/266755/pexels-photo-266755.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1">
                        <div class="card-body">
                          <h4 class="card-title">Every Cup, A Masterpiece of Flavor</h4>
                          <p class="card-text">Crafted for the Coffee Lover in You.</p>
                          <p class="card-text">
                            <small class="text-muted">Last updated 45 mins ago</small>
                          </p>
                        </div>
                      </div>
                    </div>
                  
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.0.2/dist/echarts.min.js"></script>
    <script>
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('main-content').classList.toggle('sidebar-active');
        });

        var chartDom = document.getElementById('echart-doughnut');
        var myChart = echarts.init(chartDom);

        var option = {
            tooltip: {
                trigger: 'item',
                formatter: '{a} <br/>{b} : {c} ({d}%)'
            },
            legend: {
                orient: 'vertical',
                left: 'left',
                data: ['Gold Blend', 'Arabica', 'Robusta', 'Dark Roast']
            },
            series: [
                {
                    name: 'Best Seller',
                    type: 'pie',
                    radius: ['50%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: '20',
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: [
                        { value: 1200, name: 'Gold Blend' },
                        { value: 900, name: 'Arabica' },
                        { value: 700, name: 'Robusta' },
                        { value: 400, name: 'Dark Roast' }
                    ]
                }
            ]
        };

        myChart.setOption(option);
    </script>
</body>
</html>