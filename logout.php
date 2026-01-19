
<?php
session_start();
include "includes/config.php";

if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user']['id'];
    logActivity($userId, 'logout', 'User logged out');
}

// Clear session
$_SESSION = [];
session_destroy();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

header("Location: " . BASE_URL . "login.php");
exit;
?>
