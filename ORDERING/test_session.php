<?php
session_start();
echo "User Name: " . ($_SESSION['user_name'] ?? 'Not logged in');
?>
