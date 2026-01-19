<?php
/**
 * Application Configuration & Functions
 * Database Connection, Authentication, Permissions
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Configuration
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "leave_system");
define("BASE_URL", "http://localhost/leave/");

// Connect to Database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, "utf8mb4");

// Cache control
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// ============================================
// AUTHENTICATION FUNCTIONS
// ============================================

/**
 * Check if user is logged in
 * Redirect to login if not
 */
function checkLogin() {
    if (!isset($_SESSION['user'])) {
        header("Location: " . BASE_URL . "login.php");
        exit();
    }
}

/**
 * Check if user is admin
 */
function checkAdmin() {
    if (!isset($_SESSION['user'])) {
        header("Location: " . BASE_URL . "login.php");
        exit();
    }
    
    if ($_SESSION['user']['role'] !== 'admin') {
        http_response_code(403);
        die("Access Denied: Admin access required");
    }
}

/**
 * Check if user is employee
 */
function checkEmployee() {
    if (!isset($_SESSION['user'])) {
        header("Location: " . BASE_URL . "login.php");
        exit();
    }
    
    if ($_SESSION['user']['role'] !== 'employee') {
        http_response_code(403);
        die("Access Denied: Employee access required");
    }
}

/**
 * Check if user is logged in (no redirect)
 */
function isLoggedIn() {
    return isset($_SESSION['user']);
}

/**
 * Check if user is admin (no redirect)
 */
function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

/**
 * Check if user is employee (no redirect)
 */
function isEmployee() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'employee';
}

/**
 * Get current user
 */
function getCurrentUser() {
    return isset($_SESSION['user']) ? $_SESSION['user'] : null;
}

/**
 * Get user role
 */
function getUserRole() {
    return isset($_SESSION['user']) ? $_SESSION['user']['role'] : null;
}

// ============================================
// DATABASE FUNCTIONS
// ============================================

/**
 * Execute query safely
 */
function executeQuery($sql) {
    global $conn;
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        error_log("Database Error: " . mysqli_error($conn));
        return false;
    }
    
    return $result;
}

/**
 * Get single record
 */
function getRecord($table, $condition = "") {
    global $conn;
    $sql = "SELECT * FROM $table";
    if ($condition) $sql .= " WHERE " . $condition;
    
    $result = executeQuery($sql);
    return $result ? mysqli_fetch_assoc($result) : null;
}

/**
 * Get all records
 */
function getRecords($table, $condition = "", $orderBy = "", $limit = "") {
    global $conn;
    $sql = "SELECT * FROM $table";
    if ($condition) $sql .= " WHERE " . $condition;
    if ($orderBy) $sql .= " ORDER BY " . $orderBy;
    if ($limit) $sql .= " LIMIT " . $limit;
    
    $result = executeQuery($sql);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}

/**
 * Insert record
 */
function insertRecord($table, $data) {
    global $conn;
    $columns = implode(", ", array_keys($data));
    $values = "'" . implode("', '", array_values($data)) . "'";
    
    $sql = "INSERT INTO $table ($columns) VALUES ($values)";
    $result = executeQuery($sql);
    
    return $result ? mysqli_insert_id($conn) : false;
}

/**
 * Update record
 */
function updateRecord($table, $data, $condition) {
    global $conn;
    $set = [];
    foreach ($data as $key => $value) {
        $set[] = "$key = '" . mysqli_real_escape_string($conn, $value) . "'";
    }
    
    $sql = "UPDATE $table SET " . implode(", ", $set) . " WHERE " . $condition;
    return executeQuery($sql);
}

/**
 * Delete record
 */
function deleteRecord($table, $condition) {
    global $conn;
    $sql = "DELETE FROM $table WHERE " . $condition;
    return executeQuery($sql);
}

// ============================================
// VALIDATION FUNCTIONS
// ============================================

/**
 * Sanitize input
 */
function sanitize($input) {
    global $conn;
    return mysqli_real_escape_string($conn, strip_tags(trim($input)));
}

/**
 * Validate email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate password strength
 */
function validatePassword($password) {
    // Minimum 8 characters, at least one uppercase, one lowercase, one digit
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password);
}

/**
 * Check if email exists
 */
function emailExists($email, $excludeId = null) {
    global $conn;
    $email = sanitize($email);
    $sql = "SELECT id FROM users WHERE email = '$email'";
    if ($excludeId) {
        $sql .= " AND id != " . (int)$excludeId;
    }
    
    $result = executeQuery($sql);
    return $result && mysqli_num_rows($result) > 0;
}

/**
 * Validate dates
 */
function validateDateRange($startDate, $endDate) {
    $start = strtotime($startDate);
    $end = strtotime($endDate);
    
    if ($start === false || $end === false) {
        return false;
    }
    
    return $end >= $start;
}

// ============================================
// PERMISSION FUNCTIONS
// ============================================

/**
 * Check if user can view leave request
 */
function canViewLeave($leaveId) {
    $leave = getRecord("leaves", "id = " . (int)$leaveId);
    
    if (!$leave) return false;
    
    if (isAdmin()) return true;
    if (isEmployee() && $leave['user_id'] == $_SESSION['user']['id']) return true;
    
    return false;
}

/**
 * Check if user can edit leave request
 */
function canEditLeave($leaveId) {
    $leave = getRecord("leaves", "id = " . (int)$leaveId);
    
    if (!$leave) return false;
    if ($leave['status'] !== 'pending') return false;
    if (isAdmin()) return true;
    if (isEmployee() && $leave['user_id'] == $_SESSION['user']['id']) return true;
    
    return false;
}

/**
 * Check if user can delete leave request
 */
function canDeleteLeave($leaveId) {
    return canEditLeave($leaveId);
}

/**
 * Check if user can manage leave requests
 */
function canManageLeaves() {
    return isAdmin();
}

/**
 * Check if user can view employees
 */
function canViewEmployees() {
    return isAdmin();
}

/**
 * Check if user can edit employees
 */
function canEditEmployees() {
    return isAdmin();
}

/**
 * Check if user can delete employees
 */
function canDeleteEmployees() {
    return isAdmin();
}

// ============================================
// UTILITY FUNCTIONS
// ============================================

/**
 * Format date
 */
function formatDate($date, $format = "M d, Y") {
    return date($format, strtotime($date));
}

/**
 * Get leave status badge class
 */
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'approved':
            return 'badge-success';
        case 'rejected':
            return 'badge-danger';
        case 'pending':
            return 'badge-warning';
        default:
            return 'badge-info';
    }
}

/**
 * Get leave status icon
 */
function getStatusIcon($status) {
    switch ($status) {
        case 'approved':
            return '✅';
        case 'rejected':
            return '❌';
        case 'pending':
            return '⏳';
        default:
            return '❓';
    }
}

/**
 * Calculate days between dates
 */
function calculateDays($startDate, $endDate) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $interval = $start->diff($end);
    return $interval->days + 1; // Include both start and end dates
}

/**
 * Get remaining leave days for employee
 */
function getRemainingLeaveDays($userId, $leaveYear = null) {
    global $conn;
    
    if (!$leaveYear) {
        $leaveYear = date('Y');
    }
    
    $sql = "SELECT SUM(DATEDIFF(end_date, start_date) + 1) as total_days 
            FROM leaves 
            WHERE user_id = " . (int)$userId . " 
            AND status = 'approved' 
            AND YEAR(start_date) = " . (int)$leaveYear;
    
    $result = executeQuery($sql);
    $row = mysqli_fetch_assoc($result);
    
    $usedDays = $row['total_days'] ?? 0;
    $totalDays = 20; // Default leave allowance
    
    return max(0, $totalDays - $usedDays);
}

/**
 * Log activity
 */
function logActivity($userId, $action, $description) {
    global $conn;
    
    $data = [
        'user_id' => $userId,
        'action' => sanitize($action),
        'description' => sanitize($description),
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    return insertRecord('activity_logs', $data);
}

/**
 * Send success message
 */
function setSuccess($message) {
    $_SESSION['success'] = $message;
}

/**
 * Send error message
 */
function setError($message) {
    $_SESSION['error'] = $message;
}

/**
 * Get success message
 */
function getSuccess() {
    $message = $_SESSION['success'] ?? null;
    unset($_SESSION['success']);
    return $message;
}

/**
 * Get error message
 */
function getError() {
    $message = $_SESSION['error'] ?? null;
    unset($_SESSION['error']);
    return $message;
}

/**
 * Display alert
 */
function displayAlert() {
    $success = getSuccess();
    $error = getError();
    
    if ($success) {
        echo '<div class="alert alert-success">
                <span>✓</span>
                <div>' . htmlspecialchars($success) . '</div>
              </div>';
    }
    
    if ($error) {
        echo '<div class="alert alert-danger">
                <span>✗</span>
                <div>' . htmlspecialchars($error) . '</div>
              </div>';
    }
}

?>
