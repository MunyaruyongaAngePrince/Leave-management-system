<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* 🚫 PREVENT BACK BUTTON AFTER LOGOUT */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/* DATABASE CONNECTION */
$conn = mysqli_connect("localhost", "root", "", "leave_system");

function checkLogin() {
    if (!isset($_SESSION['user'])) {
        header("Location: " . BASE_URL . "login.php");
        exit();
    }
}

/* AUTH CHECKS */
function checkAdmin() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header("Location: login.php");
        exit();
    }
}

function checkEmployee() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employee') {
        header("Location: login.php");
        exit();
    }
}






/*

// start session everywhere
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "leave_system";

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}


// change if your folder name is different
define("BASE_URL", "leave_system");


function checkLogin() {
    if (!isset($_SESSION['user'])) {
        header("Location: " . BASE_URL . "login.php");
        exit();
    }
}

function checkAdmin() {
    checkLogin();
    if ($_SESSION['user']['role'] !== 'admin') {
        die("Access denied");
    }
}

function checkEmployee() {
    checkLogin();
    if ($_SESSION['user']['role'] !== 'employee') {
        die("Access denied");
    }
}

*/

