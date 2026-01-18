<?php
session_start();
include "config.php";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = $_POST['password'];

    $q = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($q);

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user'] = $user;

        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: employee_dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
<h2><center>Login</center></h2>
<?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
</form>
<div class="register-link">
    <p>No account? click <a href="register.php">Register</a>
    </p>
</div>
</div>
</body>
</html>
