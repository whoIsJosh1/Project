<?php
include 'db_connect.php';

if (isset($_GET['queue'])) {
    $queue = intval($_GET['queue']);
    $conn->query("UPDATE orders SET status='served' WHERE queue_number=$queue");
    echo "<p>Order #$queue marked as served.</p>";
}

echo "<a href='queue_list.php'>⬅️ Back to Queue</a>";
$conn->close();
?>
