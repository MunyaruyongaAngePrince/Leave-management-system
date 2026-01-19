<?php
/**
 * API Endpoint - Get Leave Request Details
 * Fetches detailed information about a specific leave request
 */

include "../includes/config.php";

// Set JSON header
header('Content-Type: application/json');

// Check if user is admin
checkAdmin();

// Get leave ID
$leaveId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$leaveId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid leave ID']);
    exit;
}

// Get leave record
$leave = getRecord('leaves', "id = $leaveId");

if (!$leave) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Leave record not found']);
    exit;
}

// Get user details
$user = getRecord('users', "id = " . $leave['user_id']);

if (!$user) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

// Get admin details if approved/rejected
$admin = null;
if ($leave['updated_by']) {
    $admin = getRecord('users', "id = " . $leave['updated_by']);
}

// Calculate days
$days = calculateDays($leave['start_date'], $leave['end_date']);

// Return response
echo json_encode([
    'success' => true,
    'leave' => [
        'id' => $leave['id'],
        'user_id' => $leave['user_id'],
        'start_date' => $leave['start_date'],
        'end_date' => $leave['end_date'],
        'reason' => $leave['reason'],
        'leave_type' => $leave['leave_type'],
        'status' => $leave['status'],
        'admin_comment' => $leave['admin_comment'],
        'updated_at' => $leave['updated_at'],
        'created_at' => $leave['created_at']
    ],
    'user' => [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'phone' => $user['phone'],
        'department' => $user['department']
    ],
    'admin' => $admin ? [
        'id' => $admin['id'],
        'name' => $admin['name'],
        'email' => $admin['email']
    ] : null,
    'days' => $days
]);
