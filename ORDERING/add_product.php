<?php
require 'db_connect.php'; // ✅ Connect to your database
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $supplier_id = $_POST['supplier_id'] ?: null;
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock'] ?? 0;

    // ✅ Determine if the product is coffee
    $isCoffee = (strtolower($category) === 'coffee') ? 1 : 0;

    // ✅ Handle photo upload
    $photo = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $targetDir = __DIR__ . "/INTEGRATE/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

        $filename = pathinfo($_FILES["photo"]["name"], PATHINFO_FILENAME);
        $fileType = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
        if (in_array($fileType, ['jpg','jpeg','png'])) {
            $uniqueName = uniqid() . "." . $fileType;
            $targetFile = $targetDir . $uniqueName;

            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
                $photo = "INTEGRATE/" . $uniqueName;
            }
        }
    }

    // Validate required fields
    if (!$name || $price <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid product data']);
        exit();
    }

    // ✅ Insert into DB with is_coffee
    $stmt = $conn->prepare("INSERT INTO products 
        (supplier_id, name, category, price, stock, photo, is_coffee) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdisi", $supplier_id, $name, $category, $price, $stock, $photo, $isCoffee);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Product added successfully!']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => $stmt->error]);
    }

    $stmt->close();
}
$conn->close();
?>
