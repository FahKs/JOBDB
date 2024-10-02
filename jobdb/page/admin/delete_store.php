<?php
session_start();
include '../../service/condb.php';
$conn = new ConnectionDatabase();
$conn = $conn->connect();

$storeids = $_GET['store_id'];

// Corrected SQL statement
$sql = "DELETE FROM store WHERE store_id = '$storeids'";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('ลบข้อมูลเรียบร้อยแล้ว'); window.location='store.php'</script>;";
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}

mysqli_close($conn);

?>