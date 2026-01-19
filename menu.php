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

<nav class="menu">
    <div class="menu-left" id="menuLeft">
        <a href="index.php">🏠 Home</a>

        <?php if($user['role'] == 'employee'): ?>
            <a href="employee_dashboard.php">📊 Dashboard</a>
            <a href="leave_apply.php">📝 Apply Leave</a>
        <?php endif; ?>

        <?php if($user['role'] == 'admin'): ?>
            <a href="admin_dashboard.php">📊 Admin Panel</a>
            <a href="leave_manage.php">✅ Manage Leaves</a>
            <a href="view_employee.php">👥 Employees</a>
        <?php endif; ?>
    </div>

    <div class="menu-user">
        <span>👤 <?php echo htmlspecialchars($user['name']); ?></span>
        <a href="logout.php" style="border-bottom: 3px solid transparent;">🚪 Logout</a>
    </div>

    <button class="hamburger" id="hamburger" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
    </button>
</nav>

<script>
function toggleMenu() {
    const menuLeft = document.getElementById('menuLeft');
    menuLeft.classList.toggle('active');
}

// Close menu when clicking outside
document.addEventListener('click', function(event) {
    const menu = document.querySelector('.menu');
    const hamburger = document.getElementById('hamburger');
    if (!menu.contains(event.target)) {
        document.getElementById('menuLeft').classList.remove('active');
    }
});
</script>
