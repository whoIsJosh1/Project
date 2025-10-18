<?php
header('Content-Type: application/json');
require 'db_connect.php'; // Connect sa database ng Inventory/POS

// Kunin ang category kung may filter
$category = $_GET['category'] ?? '';

// Base query: kunin lahat ng active products
$sql = "SELECT product_id, name, category, price, stock, photo, is_coffee 
        FROM products 
        WHERE status='active'";


// Kung may category filter
if ($category) {
    $sql .= " AND category = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
} else {
    $stmt = $conn->prepare($sql);
}

// Execute
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $row['photo'] = $row['photo'] ? $row['photo'] : 'ORDERING/default.jpg';

    // Add is_coffee flag
    $row['is_coffee'] = strtolower($row['category']) === 'coffee' ? 1 : 0;

    $products[] = $row;
}


echo json_encode([
    "success" => true,
    "products" => $products
]);

$stmt->close();
$conn->close();
?>
