<?php
session_start();
include "config.php";

if (isset($_POST['register'])) {
    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role  = $_POST['role']; // admin or employee

    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Email already exists";
    } else {
        mysqli_query($conn,"INSERT INTO users (name,email,password,role)VALUES ('$name','$email','$pass','$role')");
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
<h2><center>Register</center></h2>
<?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
<form method="post">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>

    <select name="role" required>
        <option value="">Select Role</option>
        <option value="employee">Employee</option>
        <option value="admin">Admin</option>
    </select>
    <button type="submit" name="register">Register</button>
</form>
<div class="register-link"><p> Already have an account? click <a href="login.php">Login</a></p>
</div>
</div>
</body>
</html>
