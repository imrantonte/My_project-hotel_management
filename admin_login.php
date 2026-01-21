<?php
session_start();
$conn = new mysqli("localhost","root","","registration");

if(isset($_POST['login'])){
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username='$user' AND password='$pass'";
    $res = $conn->query($sql);

    if($res->num_rows === 1){
        $_SESSION['admin'] = true;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Invalid admin login";
    }
}
?>
<!DOCTYPE html>
<html>
<body>
<h2>Admin Login</h2>
<?php if(isset($error)) echo $error; ?>
<form method="POST">
<input type="text" name="username" placeholder="Admin username" required>
<input type="password" name="password" placeholder="Password" required>
<button name="login">Login</button>
</form>
</body>
</html>
