<?php
// niba ushaka ko index ibe protected (user agomba kuba logged in)
include "config.php";
checkLogin();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Leave Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "menu.php"; ?>

<div class="container">
    <h1>Leave Management System</h1>
    <p>
        Welcome <strong><?php echo $_SESSION['user']['name']; ?></strong>
        (<?php echo $_SESSION['user']['role']; ?>)
    </p>

    <div class="cards">
        <?php if($_SESSION['user']['role'] === 'employee'): ?>
            <div class="card">
                <h3>Apply for Leave</h3>
                <p>Send a leave request to administration.</p>
                <a href="leave_apply.php" class="btn btn-approve">Apply</a>
            </div>
        <?php endif; ?>

        <?php if($_SESSION['user']['role'] === 'admin'): ?>
            <div class="card">
                <h3>Manage Leaves</h3>
                <p>Approve or reject employee leave requests.</p>
                <a href="leave_manage.php" class="btn btn-approve">Manage</a>
            </div>
        <?php endif; ?>
    </div>
    <p class="footer-text">
    Secure • Fast • Easy access
</p>

</div>

</body>

</html>
