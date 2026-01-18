<?php
include "config.php";
checkEmployee(); // employee only
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "menu.php"; ?>

<div class="container">
    <h2>Employee Dashboard</h2>
    <p>Welcome <strong><?php echo $_SESSION['user']['name']; ?></strong></p>

    <div class="cards">
        <div class="card">
            <h3>Apply for Leave</h3>
            <p>Request leave from administration.</p>
            <a href="leave_apply.php" class="btn btn-approve">Apply Leave</a>
        </div>

        <div class="card">
            <h3>My Leave History</h3>
            <p>View your leave requests status.</p>
            <a href="leave_history.php" class="btn btn-approve">My Leaves</a>
        </div>
    </div>
</div>

</body>
</html>
