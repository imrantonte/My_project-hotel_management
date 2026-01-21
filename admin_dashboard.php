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
</style>
</head>
<body>

<h2>📊 Booking Management</h2>

<table>
<tr>
<th>Name</th>
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
<!-- Redirect to delete bookings page -->
<a href="delete_bookings.php">
    <button style="padding:10px 20px; background-color:red; color:white; border:none; border-radius:5px; cursor:pointer;">
        Delete Bookings
    </button>
</a>

</body>
</html>
