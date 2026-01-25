<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit;
}
$conn = new mysqli("localhost","root","","registration");
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<style>
body { font-family: Arial; background:#f4f6f8; }
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}
th, td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}
th {
    background: #203a43;
    color: white;
}

/* Delete Button Style */
.delete-btn {
    background-color: #e74c3c; /* Bright red */
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.delete-btn:hover {
    background-color: #c0392b; /* Darker red on hover */
    transform: translateY(-2px);
    box-shadow: 0 6px 8px rgba(0,0,0,0.15);
}

.delete-btn:active {
    background-color: #a93226;
    transform: translateY(0);
    box-shadow: 0 3px 4px rgba(0,0,0,0.2);
}

</style>
</head>
<body>

<h2>📊 Booking Management</h2>

<table border="1" cellpadding="10">
<tr>
    <th>Name</th>
    <th>ID</th> <!-- Added ID column -->
    <th>Email</th>
    <th>Room</th>
    <th>Check-in</th>
    <th>Check-out</th>
    <th>Total</th>
    <th>Payment</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM bookings ORDER BY id DESC");
$total_income = 0;

while($row = $result->fetch_assoc()){
    $total_income += $row['total_price'];
    echo "<tr>
        <td>{$row['name']}</td>
        <td>{$row['id']}</td> <!-- Display ID here -->
        <td>{$row['email']}</td>
        <td>{$row['room_type']}</td>
        <td>{$row['checkin']}</td>
        <td>{$row['checkout']}</td>
        <td>\${$row['total_price']}</td>
        <td>{$row['payment_method']}</td>
    </tr>";
}
?>

</table>


<h3>💰 Total Revenue: $<?php echo $total_income; ?></h3>

<button class="delete-btn"><a href="delete_bookings.php">Delete Bookings</a>
   </button>
</body>
</html>
