<?php
$conn = new mysqli("localhost", "root", "", "inventory_db");
require 'db_connect.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username   = $_POST['username'];
$address    = $_POST['address'];
$contact_number = $_POST['contact_number'];
$email      = $_POST['email'];
$password   = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, address, contact_number, email, password) 
        VALUES ('$username', '$address', '$contact_number', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    // Success popup
    showPopup("✅ Registration Successful!", "You can now log in.", "index.php", "#28a745");
} else {
    // Error popup
    showPopup("❌ Error", $conn->error, "index.php", "#dc3545");
}
$conn->close();


// 🔧 Function for styled popup
function showPopup($title, $message, $redirect, $btnColor) {
    echo "
    <div style='
      position:fixed; top:0; left:0; width:100%; height:100%;
      background:rgba(0,0,0,0.6); display:flex; justify-content:center; align-items:center;
      font-family: Arial, sans-serif;
    '>
      <div style='background:white; padding:20px; border-radius:10px; text-align:center; width:320px;'>
        <h3 style='margin-bottom:10px; color:$btnColor;'>$title</h3>
        <p style='margin-bottom:15px;'>$message</p>
        <a href='$redirect' target='top' style='
          display:inline-block; padding:10px 20px;
          background:$btnColor; color:white; border-radius:5px; text-decoration:none;
          font-weight:bold;
        '>OK</a>
      </div>
    </div>
    ";
}
?>
