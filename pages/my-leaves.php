<?php
include "../includes/config.php";
checkEmployee();

$userId = getCurrentUser()['id'];
$leaves = getRecords('leaves', "user_id = $userId", 'created_at DESC');

// Handle PDF export
if (isset($_GET['export_pdf'])) {
    include "../includes/pdf-helper.php";
    // Filter leaves for this employee
    $filterLeaves = array_filter($leaves, fn($l) => $l['user_id'] == $userId);
    // Export functionality
    header('Content-Type: text/html; charset=utf-8');
    echo '<html><head><style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #2563eb; color: white; }
        @media print { body { background: white; } }
    </style></head><body>';
    echo '<h1>My Leave Requests</h1>';
    echo '<p>Generated on ' . date('Y-m-d H:i:s') . '</p>';
    echo '<table><thead><tr><th>Start Date</th><th>End Date</th><th>Reason</th><th>Status</th></tr></thead><tbody>';
    foreach ($filterLeaves as $leave) {
        echo '<tr><td>' . formatDate($leave['start_date']) . '</td><td>' . formatDate($leave['end_date']) . '</td><td>' . htmlspecialchars($leave['reason']) . '</td><td>' . ucfirst($leave['status']) . '</td></tr>';
    }
    echo '</tbody></table></body></html>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Leave Requests - Leave Management System</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/main.css">
</head>
<body>
    <?php include "../includes/header.php"; ?>
    
    <div class="page-wrapper">
        <div class="container">
            <div class="page-header">
                <div class="flex-between">
                    <div>
                        <div class="page-title">
                            My Leave Requests
                        </div>
                        <p class="page-subtitle">View all your leave requests and their status</p>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <a href="<?php echo BASE_URL; ?>pages/leave-apply.php" class="btn btn-primary">New Request</a>
                        <a href="?export_pdf=1" class="btn btn-secondary" target="_blank">Export</a>
                    </div>
                </div>
            </div>
            
            <?php displayAlert(); ?>
            
            <?php if (empty($leaves)): ?>
                <div class="card">
                    <div style="text-align: center; padding: var(--spacing-2xl);">
                        <p class="text-muted">No leave requests yet</p>
                        <a href="<?php echo BASE_URL; ?>pages/leave-apply.php" class="btn btn-primary" style="margin-top: var(--spacing-lg);">Create Your First Request</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="table-wrapper">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Days</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Applied On</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($leaves as $i => $leave): 
                                    $days = calculateDays($leave['start_date'], $leave['end_date']);
                                    $statusClass = getStatusBadgeClass($leave['status']);
                                    $statusIcon = getStatusIcon($leave['status']);
                                ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td><?php echo formatDate($leave['start_date']); ?></td>
                                        <td><?php echo formatDate($leave['end_date']); ?></td>
                                        <td><?php echo $days; ?> day(s)</td>
                                        <td><?php echo htmlspecialchars(substr($leave['reason'], 0, 30)) . (strlen($leave['reason']) > 30 ? '...' : ''); ?></td>
                                        <td><span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($leave['status']); ?></span></td>
                                        <td><?php echo formatDate($leave['created_at']); ?></td>
                                        <td>
                                            <div class="table-actions">
                                                <button onclick="viewDetails(<?php echo $leave['id']; ?>)" class="btn btn-sm btn-info">View</button>
                                                <?php if ($leave['status'] === 'pending'): ?>
                                                    <a href="#" class="btn btn-sm btn-warning">Edit</a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <footer class="footer">
        <div class="footer-text">
            © 2024 Leave Management System | All Rights Reserved
        </div>
    </footer>
    
    <script>
        function viewDetails(leaveId) {
            alert('View details - Feature coming soon');
        }
    </script>
</body>
</html>
