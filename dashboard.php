<?php
include "includes/config.php";
checkLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Leave Management System</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/main.css">
</head>
<body>
    <?php include "includes/header.php"; ?>
    
    <div class="page-wrapper">
        <div class="container">
            <div class="page-header">
                <div class="page-title">
                    Dashboard
                </div>
                <p class="page-subtitle">Welcome back, <?php echo htmlspecialchars(getCurrentUser()['name']); ?>!</p>
            </div>
            
            <?php displayAlert(); ?>
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-2">
                <?php if (isEmployee()):
                    $userId = getCurrentUser()['id'];
                    $totalLeaves = count(getRecords("leaves", "user_id = $userId"));
                    $approvedLeaves = count(getRecords("leaves", "user_id = $userId AND status = 'approved'"));
                    $pendingLeaves = count(getRecords("leaves", "user_id = $userId AND status = 'pending'"));
                    $remainingDays = getRemainingLeaveDays($userId);
                ?>
                    <div class="card">
                        <div class="flex-between">
                            <div>
                                <p class="text-muted">Total Leave Requests</p>
                                <h2><?php echo $totalLeaves; ?></h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="flex-between">
                            <div>
                                <p class="text-muted">Remaining Days</p>
                                <h2><?php echo $remainingDays; ?></h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="flex-between">
                            <div>
                                <p class="text-muted">Approved</p>
                                <h2><?php echo $approvedLeaves; ?></h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="flex-between">
                            <div>
                                <p class="text-muted">Pending</p>
                                <h2><?php echo $pendingLeaves; ?></h2>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (isAdmin()):
                    $totalEmployees = count(getRecords("users", "role = 'employee'"));
                    $totalLeaves = count(getRecords("leaves"));
                    $pendingLeaves = count(getRecords("leaves", "status = 'pending'"));
                    $approvedLeaves = count(getRecords("leaves", "status = 'approved'"));
                ?>
                    <div class="card">
                        <div class="flex-between">
                            <div>
                                <p class="text-muted">Total Employees</p>
                                <h2><?php echo $totalEmployees; ?></h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="flex-between">
                            <div>
                                <p class="text-muted">Total Leaves</p>
                                <h2><?php echo $totalLeaves; ?></h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="flex-between">
                            <div>
                                <p class="text-muted">Pending Review</p>
                                <h2><?php echo $pendingLeaves; ?></h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="flex-between">
                            <div>
                                <p class="text-muted">Approved</p>
                                <h2><?php echo $approvedLeaves; ?></h2>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Quick Actions -->
            <div style="margin-top: var(--spacing-2xl);">
                <h3 style="margin-bottom: var(--spacing-lg);">Quick Actions</h3>
                <div class="grid grid-cols-2">
                    <?php if (isEmployee()): ?>
                        <a href="<?php echo BASE_URL; ?>pages/leave-apply.php" class="card" style="text-decoration: none; color: inherit;">

                            <h4>Apply for Leave</h4>
                            <p class="text-muted">Submit a new leave request</p>
                        </a>
                        
                        <a href="<?php echo BASE_URL; ?>pages/my-leaves.php" class="card" style="text-decoration: none; color: inherit;">
                            <h4>My Leave Requests</h4>
                            <p class="text-muted">View your leave history</p>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (isAdmin()): ?>
                        <a href="<?php echo BASE_URL; ?>admin/leaves.php" class="card" style="text-decoration: none; color: inherit;">
                            <h4>Manage Leaves</h4>
                            <p class="text-muted">Approve or reject requests</p>
                        </a>
                        
                        <a href="<?php echo BASE_URL; ?>admin/employees.php" class="card" style="text-decoration: none; color: inherit;">
                            <h4>Manage Employees</h4>
                            <p class="text-muted">View and edit employee details</p>
                        </a>
                        
                        <a href="<?php echo BASE_URL; ?>admin/reports.php" class="card" style="text-decoration: none; color: inherit;">
                            <h4>Generate Reports</h4>
                            <p class="text-muted">Export leave statistics</p>
                        </a>
                    <?php endif; ?>
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
