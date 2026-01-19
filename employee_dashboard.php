<?php
include "config.php";
checkEmployee(); // employee only
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - Leave Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "menu.php"; ?>

<div class="container">
    <div class="user-profile">
        <p>Dashboard</p>
        <h2>👤 <?php echo htmlspecialchars($_SESSION['user']['name']); ?></h2>
        <p>Manage your leave requests and view your leave balance.</p>
    </div>

    <h2 class="section-title">Quick Actions</h2>

    <div class="cards">
        <div class="card">
            <h3>📝 Apply for Leave</h3>
            <p>Submit a new leave request to the administration. Select your dates and provide a reason for your leave.</p>
            <a href="leave_apply.php" class="btn btn-primary">Apply Leave</a>
        </div>

        <div class="card">
            <h3>📋 My Leave History</h3>
            <p>View all your leave requests, check their status, and see your leave history.</p>
            <a href="leave_history.php" class="btn btn-primary">My Leaves</a>
        </div>
    </div>

    <div class="footer-text">
        💡 Tip: Check your leave history regularly to track your leave balance and request status.
    </div>
</div>

</body>
</html>
