<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header("Location:login.php");
    exit();
}
$user = $_SESSION['user'];
?>

<div class="menu">
    <a href="index.php">Home</a>

    <?php if($user['role'] == 'employee'): ?>
        <a href="employee_dashboard.php">Dashboard</a>
        <a href="leave_apply.php">Apply Leave</a>
    <?php endif; ?>

    <?php if($user['role'] == 'admin'): ?>
        <a href="admin_dashboard.php">Admin Dashboard</a>
        <a href="leave_manage.php">Manage Leaves</a>
    <?php endif; ?>

    <a href="logout.php">Logout</a>
</div>
