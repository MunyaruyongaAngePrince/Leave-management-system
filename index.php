<?php
// niba ushaka ko index ibe protected (user agomba kuba logged in)
include "config.php";
checkLogin();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Leave Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "menu.php"; ?>

<div class="container">
    <div class="user-profile">
        <p>Welcome back,</p>
        <h2><?php echo htmlspecialchars($_SESSION['user']['name']); ?></h2>
        <p style="color: #3b82f6; font-weight: 600;">Role: <?php echo ucfirst($_SESSION['user']['role']); ?></p>
    </div>

    <h2 class="section-title">Dashboard</h2>

    <div class="cards">
        <?php if($_SESSION['user']['role'] === 'employee'): ?>
            <div class="card">
                <h3>📝 Apply for Leave</h3>
                <p>Submit a new leave request to the administration. Select your dates and provide a reason.</p>
                <a href="leave_apply.php" class="btn btn-primary">Apply Now</a>
            </div>

            <div class="card">
                <h3>📋 My Leave History</h3>
                <p>View all your leave requests, their status, and historical records.</p>
                <a href="leave_history.php" class="btn btn-primary">View History</a>
            </div>
        <?php endif; ?>

        <?php if($_SESSION['user']['role'] === 'admin'): ?>
            <div class="card">
                <h3>✅ Manage Leaves</h3>
                <p>Review, approve, or reject employee leave requests and manage the approval workflow.</p>
                <a href="leave_manage.php" class="btn btn-primary">Manage</a>
            </div>

            <div class="card">
                <h3>👥 View Employees</h3>
                <p>Browse and manage all registered employees in the system.</p>
                <a href="view_employee.php" class="btn btn-primary">View Employees</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer-text">
        🔒 Secure • ⚡ Fast • 📊 Easy Access
    </div>
</div>

</body>
</html>
