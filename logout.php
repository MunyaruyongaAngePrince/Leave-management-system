
<?php
session_start();

// Siba session zose
$_SESSION = [];

// Kurandura session burundu
session_destroy();

// Gukumira browser cache (Back button)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Ohereza kuri login
header("Location: login.php");
exit();
