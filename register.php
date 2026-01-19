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
        $success = "Account created successfully! Redirecting to login...";
        header("Refresh: 2; url=login.php");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Leave Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="welcome-body">
    <div class="welcome-container">
        <h1>👤 Create Account</h1>
        <h2>Register to Leave Management</h2>

        <?php if(isset($error)): ?>
            <div class="error">
                ⚠️ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($success)): ?>
            <div class="success">
                ✅ <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Create a password" required>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="">Select Your Role</option>
                    <option value="employee">Employee</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>

            <button type="submit" name="register">Create Account</button>
        </form>

        <div class="register-link">
            <p>Already have an account? <a href="login.php">Sign in here</a></p>
        </div>
    </div>
</body>
</html>
