<?php
session_start();
header('Content-Type: application/json');
require 'db_connect.php';

// Read JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['order']) || !isset($data['payment_method'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid or missing order data']);
    exit;
}

// Get logged-in customer name (or fallback)
$customer_name = $_SESSION['username'] ?? 'Guest Customer';

// Calculate total
$totalAmount = 0;
foreach ($data['order'] as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
}

// Generate next queue number
$result = $conn->query("SELECT MAX(queue_number) AS last_queue FROM orders_master");
$row = $result->fetch_assoc();
$next_queue = ($row['last_queue'] ?? 0) + 1;

$conn->begin_transaction();

try {
    // Insert into orders_master
    $stmtMaster = $conn->prepare("
        INSERT INTO orders_master (customer_name, order_date, total_amount, payment_method, status, queue_number)
        VALUES (?, NOW(), ?, ?, 'pending', ?)
    ");
    $stmtMaster->bind_param("sdsi", $customer_name, $totalAmount, $data['payment_method'], $next_queue);
    $stmtMaster->execute();
    $order_id = $stmtMaster->insert_id;

    // Insert each item into orders table
    $stmtItem = $conn->prepare("
        INSERT INTO orders (order_id, product_id, name, size, temp, quantity, price)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    foreach ($data['order'] as $item) {
        $stmtItem->bind_param(
            "iisssid",
            $order_id,
            $item['product_id'],
            $item['name'],
            $item['size'],
            $item['temp'],
            $item['quantity'],
            $item['price']
        );
        $stmtItem->execute();
    }

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Order saved successfully!',
        'queue_number' => $next_queue,
        'customer_name' => $customer_name
    ]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Error saving order: ' . $e->getMessage()]);
}

$stmtMaster->close();
$stmtItem->close();
$conn->close();
?>
