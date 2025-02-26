<?php
// ในส่วนการดึงข้อมูล
$billTypeSql = "SELECT type_bill, COUNT(*) as count FROM bill_customer GROUP BY type_bill";
$billTypeResult = $conn->query($billTypeSql);
$billTypes = [];
while($row = $billTypeResult->fetch_assoc()) {
    $billTypes[] = $row;
}

$billStatusSql = "SELECT status_bill, COUNT(*) as count FROM bill_customer GROUP BY status_bill";
$billStatusResult = $conn->query($billStatusSql);
$billStatuses = [];
while($row = $billStatusResult->fetch_assoc()) {
    $billStatuses[] = $row;
}

$customerTypeSql = "SELECT ct.type_customer, COUNT(c.id_customer) as count 
                   FROM customers c 
                   JOIN customer_types ct ON c.id_customer_type = ct.id_customer_type 
                   GROUP BY ct.type_customer";
$customerTypeResult = $conn->query($customerTypeSql);
$customerTypes = [];
while($row = $customerTypeResult->fetch_assoc()) {
    $customerTypes[] = $row;
}

$revenueSql = "SELECT c.id_customer, c.name_customer, SUM(o.all_price) as total_revenue
               FROM customers c
               JOIN bill_customer bc ON c.id_customer = bc.id_customer
               JOIN service_customer sc ON bc.id_bill = sc.id_bill
               JOIN package_list pl ON sc.id_service = pl.id_service
               JOIN product_list pr ON pl.id_package = pr.id_package
               JOIN overide o ON pr.id_product = o.id_product
               GROUP BY c.id_customer, c.name_customer";
$revenueResult = $conn->query($revenueSql);
$revenues = [];
while($row = $revenueResult->fetch_assoc()) {
    $revenues[] = $row;
}

// เพิ่มส่วนดึงข้อมูลประเภทบริการ
$serviceTypeSql = "SELECT type_service, COUNT(*) as count FROM service_customer GROUP BY type_service";
$serviceTypeResult = $conn->query($serviceTypeSql);
$serviceTypes = [];
while($row = $serviceTypeResult->fetch_assoc()) {
    $serviceTypes[] = $row;
}

// เพิ่มส่วนดึงข้อมูลประเภทอุปกรณ์
$deviceTypeSql = "SELECT type_gadget, COUNT(*) as count FROM service_customer GROUP BY type_gadget";
$deviceTypeResult = $conn->query($deviceTypeSql);
$deviceTypes = [];
while($row = $deviceTypeResult->fetch_assoc()) {
    $deviceTypes[] = $row;
}
?>
<div class="charts-container flex flex-wrap justify-center gap-4">
    <!-- แต่ละกราฟจะถูกจัดเรียงในรูปแบบ Flexbox -->
    <div class="chart-item flex-shrink-0 w-full md:w-1/2 lg:w-1/3 p-4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold mb-4">สัดส่วนประเภทบิล</h3>
            <canvas id="billTypeChart" class="w-full h-64"></canvas>
        </div>
    </div>

    <div class="chart-item flex-shrink-0 w-full md:w-1/2 lg:w-1/3 p-4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold mb-4">สถานะบิลทั้งหมด</h3>
            <canvas id="billStatusChart" class="w-full h-64"></canvas>
        </div>
    </div>
    <div class="chart-item flex-shrink-0 w-full md:w-1/2 lg:w-1/3 p-4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold mb-4">ประเภทลูกค้า</h3>
            <canvas id="customerTypeChart" class="w-full h-64"></canvas>
        </div>
    </div>
    <div class="chart-item flex-shrink-0 w-full md:w-1/2 lg:w-1/3 p-4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold mb-4">รายได้ต่อลูกค้า</h3>
            <canvas id="revenueChart" class="w-full h-64"></canvas>
        </div>
    </div>
    <div class="chart-item flex-shrink-0 w-full md:w-1/2 lg:w-1/3 p-4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold mb-4">ประเภทของบริการ</h3>
            <canvas id="serviceTypeChart" class="w-full h-64"></canvas>
        </div>
    </div>
    <div class="chart-item flex-shrink-0 w-full md:w-1/2 lg:w-1/3 p-4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold mb-4">ประเภทของอุปกรณ์</h3>
            <canvas id="deviceTypeChart" class="w-full h-64"></canvas>
        </div>
    </div>
</div>

<style>
.chart-item {
    /* กำหนดให้การแสดงผลของกราฟแต่ละตัวไม่ยืดหยุ่น */
    display: block;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script> <!-- เพิ่ม Plugin DataLabels -->
<script>
const chartInstances = {};

function initChart(canvasId, config) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    chartInstances[canvasId] = new Chart(ctx, config);
}

// การตั้งค่ากราฟทั้งหมด
initChart('billTypeChart', {
    type: 'pie',
    data: {
        labels: <?= json_encode(array_column($billTypes, 'type_bill')) ?>,
        datasets: [{
            data: <?= json_encode(array_column($billTypes, 'count')) ?>,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            datalabels: {
                formatter: function(value, context) {
                    let total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                    let percentage = (value / total * 100).toFixed(2) + '%';
                    return context.dataset.label + ': ' + value + ' (' + percentage + ')'; // แสดงทั้งปริมาณและเปอร์เซ็นต์
                },
                color: '#fff',
            }
        }
    }
});

// กราฟสถานะบิล (Donut Chart)
initChart('billStatusChart', {
    type: 'doughnut', // เปลี่ยนจาก 'pie' เป็น 'doughnut'
    data: {
        labels: <?= json_encode(array_column($billStatuses, 'status_bill')) ?>,
        datasets: [{
            data: <?= json_encode(array_column($billStatuses, 'count')) ?>,
            backgroundColor: ['#FF6384', '#36A2EB'],
            borderWidth: 0, // เอาขอบของ donut ออก
        }]
    },
    options: {
        cutout: '70%', // กำหนดพื้นที่กลางของ donut
        responsive: true, // ทำให้กราฟปรับขนาดได้ตามหน้าจอ
        plugins: {
            legend: {
                position: 'top', // กำหนดตำแหน่งของ legend
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return tooltipItem.label + ': ' + tooltipItem.raw + ' (' + (tooltipItem.raw / tooltipItem.dataset._meta[0].total * 100).toFixed(2) + '%)'; // เพิ่มเปอร์เซ็นต์ใน tooltip
                    }
                }
            },
            datalabels: {
                formatter: function(value, context) {
                    let total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                    let percentage = (value / total * 100).toFixed(2) + '%';
                    return value + ' (' + percentage + ')'; // แสดงทั้งปริมาณและเปอร์เซ็นต์
                },
                color: '#fff',
            }
        }
    }
});

// กราฟประเภทลูกค้า (Bar Chart)
initChart('customerTypeChart', {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($customerTypes, 'type_customer')) ?>,
        datasets: [{
            label: 'จำนวนลูกค้า',
            data: <?= json_encode(array_column($customerTypes, 'count')) ?>,
            backgroundColor: '#36A2EB',
            borderColor: '#000',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'จำนวนลูกค้า'
                }
            }
        },
        plugins: {
            legend: {
                position: 'top',
            }
        }
    }
});

initChart('revenueChart', {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($revenues, 'name_customer')) ?>,
        datasets: [{
            label: 'รายได้',
            data: <?= json_encode(array_column($revenues, 'total_revenue')) ?>,
            backgroundColor: '#36A2EB'
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

initChart('serviceTypeChart', {
    type: 'pie',
    data: {
        labels: <?= json_encode(array_column($serviceTypes, 'type_service')) ?>,
        datasets: [{
            data: <?= json_encode(array_column($serviceTypes, 'count')) ?>,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
        }]
    }
});

initChart('deviceTypeChart', {
    type: 'pie',
    data: {
        labels: <?= json_encode(array_column($deviceTypes, 'type_gadget')) ?>,
        datasets: [{
            data: <?= json_encode(array_column($deviceTypes, 'count')) ?>,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
        }]
    }
});
</script>
