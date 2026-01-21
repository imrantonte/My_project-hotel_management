<?php
session_start();
include 'db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Delete booking if delete_id is set
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: delete_bookings.php"); // refresh page
    exit();
}

// Fetch all bookings
$result = $conn->query("SELECT * FROM bookings ORDER BY checkin DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Bookings</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 95%; margin: 20px auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #f2f2f2; }
        a.delete-btn { color: red; text-decoration: none; }
        .btn { 
            padding: 10px 20px; 
            background-color: #007bff; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px; 
            margin-bottom: 10px; 
            display: inline-block;
        }
        .btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Back to Dashboard Button -->
        <a href="admin_dashboard.php" class="btn">← Back to Dashboard</a>

        <h2 style="text-align:center;">Delete Bookings</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Room Type</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Total Price</th>
                <th>Action</th>
            </tr>
            <?php if($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['user_id'] ?></td>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['room_type'] ?></td>
                        <td><?= $row['checkin'] ?></td>
                        <td><?= $row['checkout'] ?></td>
                        <td>$<?= $row['total_price'] ?></td>
                        <td>
                            <a class="delete-btn" href="delete_bookings.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this booking?');">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="9">No bookings found.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>
