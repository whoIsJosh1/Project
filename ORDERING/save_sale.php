<?php
session_start();
include 'db_connect.php';
header('Content-Type: application/json');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // ✅ Get logged-in user name or default
    $customer_name = $_SESSION['username'] ?? 'Guest Customer';

    // ✅ Get JSON data from JS
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['order']) || !isset($data['payment_method'])) {
        echo json_encode(["success" => false, "message" => "Invalid or missing order data."]);
        exit;
    }

    $order_items = json_encode($data['order']);
    $payment_method = $data['payment_method'];

    // ✅ Compute total
    $total = 0;
    foreach ($data['order'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // ✅ Generate next queue number
    $result = $conn->query("SELECT MAX(queue_number) AS last_queue FROM orders");
    $row = $result->fetch_assoc();
    $next_queue = ($row['last_queue'] ?? 0) + 1;

    // ✅ Insert order
    $sql = "INSERT INTO orders (customer_name, order_items, total, payment_method, status, queue_number)
            VALUES (?, ?, ?, ?, 'pending', ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $customer_name, $order_items, $total, $payment_method, $next_queue);
    $stmt->execute();

    // ✅ Return success with queue number
    echo json_encode([
        "success" => true,
        "message" => "Order saved successfully!",
        "queue_number" => $next_queue
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "PHP Error: " . $e->getMessage()
    ]);
}
?>
