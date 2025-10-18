<?php
include 'db_connect.php';

$id = $_POST['id'];
$status = $_POST['status'];

$sql = "UPDATE order_queue SET status='$status' WHERE id=$id";
if ($conn->query($sql) === TRUE) {
    header("Location: queue_list.php");
    exit();
} else {
    echo "Error updating record: " . $conn->error;
}
?>
