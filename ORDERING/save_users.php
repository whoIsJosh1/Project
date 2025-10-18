<?php
include 'db_connect.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON input"]);
    exit;
}

$username = $data['username'] ?? '';
$address  = $data['address'] ?? '';
$contact  = $data['contact'] ?? '';
$email    = $data['email'] ?? '';

$stmt = $conn->prepare("INSERT INTO users (username, address, contact_number, email) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $address, $contact, $email);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}

$stmt->close();
$conn->close();
?>
