<?php
$host = "localhost";      // default XAMPP MySQL host
$user = "root";           // default XAMPP MySQL user
$pass = "";               // default walang password
$dbname = "inventory_db"; // palitan kung iba pangalan ng database mo

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
