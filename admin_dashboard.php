<?php
include "config.php";
checkAdmin(); // admin only
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Leave Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "menu.php"; ?>

<div class="container">
    <div class="user-profile">
        <p>Administrator Panel</p>
        <h2>👨‍💼 <?php echo htmlspecialchars($_SESSION['user']['name']); ?></h2>
        <p>Manage leave requests, employees, and system settings.</p>
    </div>

    <h2 class="section-title">Management Tools</h2>

    <div class="cards">
        <div class="card">
            <h3>✅ Manage Leave Requests</h3>
            <p>Review pending leave requests, approve or reject them, and add comments for decisions.</p>
            <a href="leave_manage.php" class="btn btn-primary">Manage Leaves</a>
        </div>

        <div class="card">
            <h3>👥 View Employees</h3>
            <p>Browse all registered employees, view their details, and manage employee accounts.</p>
            <a href="view_employee.php" class="btn btn-primary">View Employees</a>
        </div>
    </div>

    <div class="footer-text">
        🔑 Admin Access • 📊 Full Control • 🔒 Secure Management
    </div>
</div>

</body>
</html>
