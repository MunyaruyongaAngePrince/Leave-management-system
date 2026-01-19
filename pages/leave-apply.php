<?php
include "../includes/config.php";
checkEmployee();

$userId = getCurrentUser()['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_leave'])) {
    $startDate = $_POST['start_date'] ?? '';
    $endDate = $_POST['end_date'] ?? '';
    $reason = sanitize($_POST['reason']);
    $leaveType = sanitize($_POST['leave_type'] ?? 'general');
    
    // Validation
    if (empty($startDate) || empty($endDate) || empty($reason)) {
        setError('All fields are required');
    } elseif (!validateDateRange($startDate, $endDate)) {
        setError('End date must be after or equal to start date');
    } else {
        $days = calculateDays($startDate, $endDate);
        $remaining = getRemainingLeaveDays($userId);
        
        if ($days > $remaining) {
            setError("You only have $remaining days remaining");
        } else {
            $data = [
                'user_id' => $userId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'reason' => $reason,
                'leave_type' => $leaveType,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $leaveId = insertRecord('leaves', $data);
            logActivity($userId, 'leave_applied', "Leave ID: $leaveId");
            setSuccess('Leave request submitted successfully');
            header("Location: " . BASE_URL . "pages/my-leaves.php");
            exit;
        }
    }
}

$remaining = getRemainingLeaveDays($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Leave - Leave Management System</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/main.css">
</head>
<body>
    <?php include "../includes/header.php"; ?>
    
    <div class="page-wrapper">
        <div class="container">
            <div class="page-header">
                <div class="page-title">
                    Apply for Leave
                </div>
                <p class="page-subtitle">Submit a leave request for admin approval</p>
            </div>
            
            <?php displayAlert(); ?>
            
            <!-- Leave Balance Info -->
            <div class="grid grid-cols-2" style="margin-bottom: var(--spacing-2xl);">
                <div class="card">
                    <div>
                        <p class="text-muted">Total Leave Allowance</p>
                        <h3>20 days</h3>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <p class="text-muted">Remaining Days</p>
                        <h3 style="color: var(--success);"><?php echo $remaining; ?> days</h3>
                    </div>
                </div>
            </div>
            
            <!-- Application Form -->
            <div class="card">
                <div class="card-header">
                    <h3>Leave Request Details</h3>
                </div>
                
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Leave Type</label>
                            <select class="form-control" name="leave_type" required>
                                <option value="">Select leave type</option>
                                <option value="annual">Annual Leave</option>
                                <option value="sick">Sick Leave</option>
                                <option value="maternity">Maternity Leave</option>
                                <option value="study">Study Leave</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Start Date</label>
                            <input type="date" class="form-control" name="start_date" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">End Date</label>
                            <input type="date" class="form-control" name="end_date" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Reason for Leave</label>
                        <textarea class="form-control" name="reason" placeholder="Please provide a reason for your leave request" required maxlength="500"></textarea>
                        <small class="form-text">Maximum 500 characters</small>
                    </div>
                    
                    <div class="card-footer">
                        <a href="<?php echo BASE_URL; ?>dashboard.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" name="apply_leave" class="btn btn-primary">Submit Request</button>
                    </div>
                </form>
            </div>
            
            <!-- Info Box -->
            <div style="margin-top: var(--spacing-2xl);">
                <div class="alert alert-info">
                    <div>
                        <strong>Important Information:</strong><br>
                        • Requests must be submitted at least 2 days in advance<br>
                        • You will be notified when your request is reviewed<br>
                        • Only approved requests will be deducted from your leave balance
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="footer">
        <div class="footer-text">
            © 2024 Leave Management System | All Rights Reserved
        </div>
    </footer>
</body>
</html>
