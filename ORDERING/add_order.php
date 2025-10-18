<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connect.php';
header('Content-Type: application/json');

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// ✅ Use logged-in user name if available
$customer_name = $_SESSION['username'] ?? 'Guest Customer';

// ✅ Only run if POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $items = $_POST['order_items'] ?? '';
    $total = $_POST['total'] ?? 0;
    $method = $_POST['method'] ?? 'Unknown';

    // Get next queue number
    $result = $conn->query("SELECT IFNULL(MAX(queue_number), 0) AS max_queue FROM orders");
    $row = $result->fetch_assoc();
    $new_queue = $row['max_queue'] + 1;

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (queue_number, customer_name, order_items, total, method, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("issds", $new_queue, $customer_name, $items, $total, $method);

    if ($stmt->execute()) {
        // ✅ Return JSON with queue number
        echo json_encode([
            "success" => true,
            "message" => "Order saved successfully!",
            "queue_number" => $new_queue,
            "customer_name" => $customer_name
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Error saving order: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>
