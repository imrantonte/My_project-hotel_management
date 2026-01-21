<?php
session_start(); // optional if using PHP sessions
$connect = mysqli_connect("localhost", "root", "", "registration");


if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = $_POST['password'];

    // Query the database
    $query = "SELECT * FROM register WHERE email='$email' AND pass='$password'";
    $result = mysqli_query($connect, $query);

    if (mysqli_num_rows($result) == 1) {
        // Login success
        echo "<script>
                localStorage.setItem('loggedIn', 'true');
                alert('Login successful!');
                window.location.href = 'booking.php';
              </script>";
        exit();
    } else {
        // Login failed
        echo "<script>
                alert('Invalid email or password!');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Grand Horizon Hotel</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <!-- Navbar -->
    <ul class="navbar">
        <li><a href="index.html">Home</a></li>
        <li><a href="rooms.html">Rooms</a></li>
        <li><a href="contact.html">Contact</a></li>
        <li><a href="about.html">About</a></li>
        <li class="right"><a href="register.php">Register</a></li>
    </ul>

    <!-- Login Form -->
    <div class="login-container">
        <h1>Welcome Back</h1>
        <p>Login to continue your booking</p>

        <form method="POST" action="login.php">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="example@email.com" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter password" required>

            <button type="submit" name="login" class="login-btn">Login</button>
        </form>

        <p class="register-text">
            Don’t have an account?
            <a href="register.php">Register</a>
        </p>
    </div>

</body>
</html>
