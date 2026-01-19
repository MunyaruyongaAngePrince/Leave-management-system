<?php
/**
 * Header/Navbar Component
 */
if (!isLoggedIn()) {
    header("Location: " . BASE_URL . "login.php");
    exit;
}

$user = getCurrentUser();
$userInitial = strtoupper($user['name'][0]);
?>

<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            Leave Management System
        </div>
        
        <button class="menu-toggle" onclick="toggleMenu()">☰</button>
        
        <ul class="navbar-menu" id="navbar-menu">
            <li><a href="<?php echo BASE_URL; ?>dashboard.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'dashboard.php') !== false ? 'active' : ''; ?>">Dashboard</a></li>
            
            <?php if (isEmployee()): ?>
                <li><a href="<?php echo BASE_URL; ?>pages/leave-apply.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'leave-apply.php') !== false ? 'active' : ''; ?>">Apply Leave</a></li>
                <li><a href="<?php echo BASE_URL; ?>pages/my-leaves.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'my-leaves.php') !== false ? 'active' : ''; ?>">My Leaves</a></li>
            <?php endif; ?>
            
            <?php if (isAdmin()): ?>
                <li><a href="<?php echo BASE_URL; ?>admin/leaves.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'admin/leaves.php') !== false ? 'active' : ''; ?>">Manage Leaves</a></li>
                <li><a href="<?php echo BASE_URL; ?>admin/employees.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'admin/employees.php') !== false ? 'active' : ''; ?>">Employees</a></li>
                <li><a href="<?php echo BASE_URL; ?>admin/reports.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'admin/reports.php') !== false ? 'active' : ''; ?>">Reports</a></li>
            <?php endif; ?>
        </ul>
        
        <div class="navbar-user">
            <div class="user-avatar"><?php echo $userInitial; ?></div>
            <div>
                <div style="color: white; font-size: 0.9rem;"><?php echo htmlspecialchars($user['name']); ?></div>
                <div style="color: rgba(255,255,255,0.7); font-size: 0.8rem;"><?php echo ucfirst($user['role']); ?></div>
            </div>
            <a href="<?php echo BASE_URL; ?>logout.php" style="color: white; padding-left: 15px; border-left: 1px solid rgba(255,255,255,0.2);">Logout</a>
        </div>
    </div>
</nav>

<script>
function toggleMenu() {
    const menu = document.getElementById('navbar-menu');
    menu.classList.toggle('active');
}

// Close menu when clicking outside
document.addEventListener('click', function(e) {
    const navbar = document.querySelector('.navbar');
    if (!navbar.contains(e.target)) {
        document.getElementById('navbar-menu').classList.remove('active');
    }
});
</script>

<style>
.navbar-menu.active {
    display: flex !important;
}
</style>
