<?php
session_start();
require 'db_connect.php'; // Make sure this connects to your DB

// Check if POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // Query user
    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Save session
            $_SESSION['user_id']  = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email']    = $row['email'];

            // Redirect to menu_page.html using JS
            echo "
            <script>
                alert('✅ Login Successful! Welcome, {$row['username']}');
                window.top.location.href = 'menu_page.php';
            </script>
            ";
            exit();
        } else {
            showAlert("❌ Invalid Password. Please try again.", "index.php");
        }
    } else {
        showAlert("⚠ No user found. Please register first.", "index.php");
    }
}

// Close DB
$conn->close();

// Function to show alert and redirect
function showAlert($message, $redirect) {
    echo "
    <script>
        alert('$message');
        window.top.location.href = '$redirect';
    </script>
    ";
    exit();
}
?>
