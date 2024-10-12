<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <style>
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
        }
        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #f8f9fa;
            padding: 20px;
            border-right: 1px solid #ddd;
            flex-shrink: 0;
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
            width: calc(100% - 240px);
        }
        .scanner-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        #scanner-container {
            width: 100%;
            max-width: 640px;
            height: 480px;
            margin: 0 auto;
        }
        #camera-control {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>Menu</h4>
        <ul class="nav flex-column">
        <li class="nav-item">
        <a class="nav-link" href="dashbord.php"><i class="fas fa-tachometer-alt"></i> Dashbord</a>
            <li class="nav-item">
                <a class="nav-link" href="index.php"><i class="fas fa-users"></i> Show Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="product_list.php"><i class="fas fa-shopping-cart"></i> Order</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="tracking.php"><i class="fas fa-car"></i> Tracking</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="scanning.php"><i class="fas fa-barcode"></i> Scanning</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="inventory.php"><i class="fas fa-warehouse"></i> Inventory</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="report_problem.php"><i class="fas fa-exclamation-circle"></i> Report Problem</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manager_report.php"><i class="fas fa-file-alt"></i> Report</a>
            </li>
        </ul>
        <hr>
        <a href="../admin/singout.php" class="nav-link text-danger">
            <i class="fas fa-sign-out-alt"></i> Sign Out
        </a>
    </div>

    <div class="content">
        <h2 class="mt-4 mb-4">Barcode Scanning </h2>

        <!-- ตัวเลือกวิธีการสแกนบาร์โค้ด -->
        <div class="mb-3" id="camera-control">
            <button class="btn btn-primary" id="start-btn" onclick="startScanner()">Start Camera</button>
            <button class="btn btn-danger" id="stop-btn" onclick="stopScanner()">Stop Camera</button>
            <button class="btn btn-secondary" onclick="document.getElementById('upload-section').style.display = 'block'">Upload Barcode Image</button>
        </div>

        <!-- ส่วนการสแกนด้วยกล้อง -->
        <div id="scanner-container" class="scanner-container"></div>

        <!-- ส่วนการอัปโหลดรูปภาพบาร์โค้ด -->
        <div id="upload-section" style="display: none;">
            <h2 class="mt-4 mb-4">Upload and Scan Barcode Image</h2>
            <form id="upload-form" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="barcode-image" class="form-label">Select Barcode Image</label>
                    <input type="file" class="form-control" id="barcode-image" accept="image/*">
                </div>
                <button type="button" class="btn btn-primary" onclick="scanBarcodeImage()">Scan Image</button>
            </form>
        </div>

        <input type="text" id="barcode-value" class="form-control mt-3" readonly>
        <div id="product-info" class="mt-4"></div>
    </div>

    <script>
        let isScanning = false;

        // ฟังก์ชันสแกนบาร์โค้ดผ่านกล้อง
        function startScanner() {
            document.getElementById('upload-section').style.display = 'none';  // ซ่อนส่วนอัปโหลด
            if (isScanning) {
                Quagga.stop();
            }
            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#scanner-container'),
                    constraints: {
                        width: 640,
                        height: 480,
                        facingMode: "environment"
                    },
                },
                decoder: {
                    readers: ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader"]
                }
            }, function (err) {
                if (err) {
                    console.log(err);
                    return;
                }
                Quagga.start();
                isScanning = true;
            });

            Quagga.onDetected(function (result) {
                let code = result.codeResult.code;
                document.getElementById('barcode-value').value = code;
                stopScanner();
                submitBarcode(code);
            });
        }

        // ฟังก์ชันปิดการทำงานของกล้อง
        function stopScanner() {
            if (isScanning) {
                Quagga.stop();
                isScanning = false;
            }
        }

        // ฟังก์ชันสำหรับสแกนบาร์โค้ดจากรูปภาพ
        function scanBarcodeImage() {
            const input = document.getElementById('barcode-image');
            const file = input.files[0];

            if (!file) {
                alert("Please upload an image.");
                return;
            }

            const reader = new FileReader();
            reader.onload = function(event) {
                const imageDataUrl = event.target.result;

                const img = new Image();
                img.src = imageDataUrl;
                img.onload = function() {
                    Quagga.decodeSingle({
                        src: imageDataUrl,
                        numOfWorkers: 0,
                        decoder: {
                            readers: ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader"]
                        }
                    }, function(result) {
                        if (result && result.codeResult) {
                            const barcode = result.codeResult.code;
                            document.getElementById('barcode-value').value = barcode;
                            submitBarcode(barcode);
                        } else {
                            document.getElementById('product-info').innerHTML = "No barcode detected.";
                        }
                    });
                }
            };
            reader.readAsDataURL(file);
        }

        // ฟังก์ชันส่งบาร์โค้ดเพื่อประมวลผล
        function submitBarcode(barcode) {
            fetch('scaning_product.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'barcode=' + barcode
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('product-info').innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
