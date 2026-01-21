<?php
$connect = mysqli_connect("localhost", "root", "", "registration");

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

$success = false;

if (isset($_POST['submit'])) {

    $fullname = mysqli_real_escape_string($connect, $_POST['fullname']);
    $email    = mysqli_real_escape_string($connect, $_POST['email']);
    $pass     = $_POST['pass'];
    $cpass    = $_POST['cpass'];

    $check = mysqli_query($connect, "SELECT * FROM register WHERE email='$email'");

    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email already exists');</script>";
    } elseif ($pass !== $cpass) {
        echo "<script>alert('Passwords do not match');</script>";
    } elseif (!preg_match('@[0-9]@', $pass) || !preg_match('@[^\w]@', $pass) || strlen($pass) < 8) {
        echo "<script>alert('Password must be at least 8 characters with number & special char');</script>";
    } else {
        $insert = "INSERT INTO register (Fullname, email, pass, cpass)
                   VALUES ('$fullname', '$email', '$pass', '$cpass')";

        if (mysqli_query($connect, $insert)) {
            $success = true;
        } else {
            echo mysqli_error($connect);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>

<ul class="navbar">
    <li><a href="index.html">Home</a></li>
    <li><a href="rooms.html">Rooms</a></li>
    <li><a href="contact.html">Contact</a></li>
    <li><a href="about.html">About</a></li>
</ul>

<div class="register-container">
    <h1>Create Account</h1>

    <form action="register.php" method="POST">
        <input type="text" name="fullname" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="pass" placeholder="Password" required>
        <input type="password" name="cpass" placeholder="Confirm Password" required>

  <button type="submit" name="submit" class="register-btn">Register</button>

    </form>
<br>
    <p>Already have an account? <a href="login.php">Login</a></p>
</div>

<?php if ($success): ?>
<script>
    alert("Registration successful!");
    localStorage.setItem("loggedIn", "true");
    window.location.href = "booking.php";
</script>
<?php endif; ?>

</body>
</html>
