<?php
require 'db_connect.php'; // connect sa DB ng inventory/ordering

header("Content-Type: application/json");

$category = $_GET['category'] ?? '';

// Base query para sa active products o filtered by category
$sql = "SELECT product_id, name, category, price, stock, photo, is_coffee FROM products WHERE status='active'";
$params = [];
$types = "";

if ($category !== '') {
   $sql .= " AND LOWER(category) LIKE CONCAT('%', LOWER(?), '%')";
    $params[] = $category;
    $types .= "s";

}

$stmt = $conn->prepare($sql);

// Bind params kung meron
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    // Default photo if missing
    $row['photo'] = $row['photo'] ?: 'ORDERING/default.jpg';
    $products[] = $row;
}

echo json_encode([
    "success" => true,
    "products" => $products
]);

$stmt->close();
$conn->close();
?>
