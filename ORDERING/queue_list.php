<?php
include 'db_connect.php';

// Fetch pending orders
$result = $conn->query("SELECT queue_number, customer_name, total, status FROM orders ORDER BY queue_number ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kitchen KVS - 16 Ounces Café</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background: #f9f9f9;
    color: #333;
    margin: 0;
    padding: 0;
  }
  header {
    background: hsl(25, 95%, 60%);
    color: white;
    text-align: center;
    padding: 20px;
    font-size: 28px;
    font-weight: bold;
  }
  .queue-container {
    display: flex;
    flex-wrap: wrap;
    padding: 20px;
    gap: 20px;
    justify-content: flex-start;
  }
  .order-card {
    background: white;
    border-left: 8px solid hsl(25, 95%, 60%);
    border-radius: 12px;
    padding: 16px;
    width: 220px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform 0.2s;
  }
  .order-card.pending { border-left-color: hsl(25, 95%, 60%); }
  .order-card.served { border-left-color: green; opacity: 0.6; }
  .order-card:hover { transform: scale(1.03); }
  .order-card h3 { margin: 0 0 10px; font-size: 20px; }
  .order-card p { margin: 4px 0; }
  .order-card .status { font-weight: bold; text-transform: uppercase; }
  .order-card button {
    margin-top: 10px;
    padding: 8px;
    border: none;
    background: hsl(25, 95%, 60%);
    color: white;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.2s;
  }
  .order-card button:hover { background: hsl(25, 95%, 50%); }

  @media(max-width: 600px){
    .order-card { width: 100%; }
  }
</style>
</head>
<body>

<header>Kitchen Queue - 16 Ounces Café</header>
<div class="queue-container">
<?php
while ($row = $result->fetch_assoc()) {
    $statusClass = $row['status'] === 'pending' ? 'pending' : 'served';
    echo "<div class='order-card $statusClass'>
            <h3>Queue #{$row['queue_number']}</h3>
            <p><strong>Customer:</strong> {$row['customer_name']}</p>
            <p><strong>Total:</strong> ₱" . number_format($row['total'],2) . "</p>
            <p class='status'>Status: {$row['status']}</p>
            ";
    if($row['status'] === 'pending') {
        echo "<button onclick='markServed({$row['queue_number']})'>Mark as Served</button>";
    }
    echo "</div>";
}
$conn->close();
?>
</div>

<script>
function markServed(queueNumber){
    fetch('serve.php?queue=' + queueNumber)
        .then(res => res.text())
        .then(() => location.reload())
        .catch(err => console.error(err));
}

// Auto-refresh every 5 seconds for real-time view
setInterval(() => {
    location.reload();
}, 5000);
</script>

</body>
</html>
