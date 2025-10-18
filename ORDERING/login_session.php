<?php
session_start();
$_SESSION['user_name'] = $row['name']; // or whatever your users table column is
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['email'])) {
  echo json_encode(["status" => "error", "message" => "Invalid data"]);
  exit;
}

$_SESSION['user_id'] = $data['email'];
echo json_encode(["status" => "success", "user" => $_SESSION['user_id']]);
?>
