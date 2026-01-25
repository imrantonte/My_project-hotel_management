<?php
session_start();

$conn = new mysqli("localhost", "root", "", "registration");
if ($conn->connect_error) {
    die("DB connection failed");
}

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}




/* =========================
   DELETE SELECTED BOOKINGS
========================= */
if (isset($_POST['delete_selected']) && !empty($_POST['booking_ids'])) {

    $ids = $_POST['booking_ids'];

    // Convert all IDs to integers (extra safety)
    $ids = array_map('intval', $ids);

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $sql = "DELETE FROM bookings WHERE id IN ($placeholders)";
    $stmt = $conn->prepare($sql);

    $types = str_repeat('i', count($ids));
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();

    header("Location: delete_bookings.php");
    exit();
}

/* =========================
   FETCH BOOKINGS
========================= */
$result = $conn->query("SELECT * FROM bookings ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Delete Bookings</title>
<style>
body { font-family: Arial, sans-serif; background:#f4f6f8; }
.container {
    width: 95%;
    margin: 20px auto;
    background:#fff;
    padding:20px;
    border-radius:10px;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
th, td {
    padding: 10px;
    border: 1px solid #ccc;
    text-align: center;
}
th {
    background-color: #203a43;
    color:white;
}
.btn {
    padding: 10px 20px;
    background: linear-gradient(135deg, gold, #ffd700);
    color: black;
    border: none;
    border-radius: 30px;
    font-weight: bold;
    cursor: pointer;
    text-decoration:none;
}
.btn-danger {
    background: linear-gradient(135deg, #ff4d4d, #c0392b);
    color:white;
}
.btn:hover { opacity: 0.9; }
.actions { margin-top: 15px; text-align:center; }
</style>
</head>

<body>
<div class="container">

<a href="admin_dashboard.php" class="btn">← Back to Dashboard</a>

<h2 style="text-align:center;">Delete Bookings</h2>

<form method="POST">

<table>
<tr>
    <th>Select</th>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Room</th>
    <th>Check-in</th>
    <th>Check-out</th>
    <th>Total Price</th>
</tr>

<?php if ($result->num_rows > 0): ?>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td>
        <input type="checkbox" name="booking_ids[]" value="<?= $row['id'] ?>">
    </td>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['name']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= htmlspecialchars($row['room_type']) ?></td>
    <td><?= $row['checkin'] ?></td>
    <td><?= $row['checkout'] ?></td>
    <td>$<?= $row['total_price'] ?></td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="8">No bookings found</td>
</tr>
<?php endif; ?>

</table>

<div class="actions">
    <button type="submit" name="delete_selected" class="btn btn-danger"
        onclick="return confirm('Delete selected bookings?');">
        ❌ Delete Selected
    </button>
</div>

</form>

</div>
</body>
</html>
