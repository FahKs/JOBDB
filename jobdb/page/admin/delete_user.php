<?php
session_start();
include '../../service/condb.php';
$conn = new ConnectionDatabase();
$conn = $conn->connect();

$userids = $_GET['user_id'];

// Corrected SQL statement
$sql = "DELETE FROM users WHERE user_id = '$userids'";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('ลบข้อมูลเรียบร้อยแล้ว'); window.location='list_user.php'</script>;";
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}

mysqli_close($conn);

?>
