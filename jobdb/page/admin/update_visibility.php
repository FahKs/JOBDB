<?php
include '../../service/condb.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['listproduct_id']) && isset($_POST['visible'])) {
        $listproduct_id = intval($_POST['listproduct_id']);
        $visible = intval($_POST['visible']);
        
        // สร้างการเชื่อมต่อกับฐานข้อมูล
        $conn = new ConnectionDatabase();
        $conn = $conn->connect();

        if (!$conn) {
            $response['message'] = "Connection failed: " . mysqli_connect_error();
            echo json_encode($response);
            exit;
        }

        $query = "UPDATE list_product SET visible = ? WHERE listproduct_id = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            $response['message'] = "Error preparing statement: " . $conn->error;
            echo json_encode($response);
            exit;
        }

        $stmt->bind_param("ii", $visible, $listproduct_id);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Update successful";
        } else {
            $response['message'] = "Error updating record: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        $response['message'] = "Invalid input.";
    }
} else {
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);
?>