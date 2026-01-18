<?php
include "config.php";
checkAdmin(); // admin only
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "menu.php"; ?>

<div class="container">
    <h2>Admin Dashboard</h2>
    <p>Welcome <strong><?php echo $_SESSION['user']['name']; ?></strong></p>

    <div class="cards">
        <div class="card">
            <h3>Manage Leave Requests</h3>
            <p>Approve or reject employee leave applications.</p>
            <a href="leave_manage.php" class="btn btn-approve">Manage Leaves</a>
        </div>

        <div class="card">
            <h3>Employees</h3>
            <p>View registered employees.</p>
            <a href="view_employee.php" class="btn btn-approve">View Employees</a>
        </div>
    </div>
</div>

</body>
</html>
