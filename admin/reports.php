<?php
include "../includes/config.php";
checkAdmin();

$filters = [
    'status' => $_GET['status'] ?? 'all',
    'month' => $_GET['month'] ?? date('m'),
    'year' => $_GET['year'] ?? date('Y')
];

// Build query
$condition = "1=1";
if ($filters['status'] !== 'all') {
    $condition .= " AND leaves.status = '" . sanitize($filters['status']) . "'";
}
$condition .= " AND MONTH(leaves.start_date) = " . (int)$filters['month'];
$condition .= " AND YEAR(leaves.start_date) = " . (int)$filters['year'];

$leaves = getRecords('leaves', $condition, 'leaves.start_date DESC');

// Statistics
$totalLeaves = count(getRecords('leaves', '', ''));
$approvedLeaves = count(getRecords('leaves', "status = 'approved'", ''));
$pendingLeaves = count(getRecords('leaves', "status = 'pending'", ''));
$rejectedLeaves = count(getRecords('leaves', "status = 'rejected'", ''));
$totalEmployees = count(getRecords('users', "role = 'employee'", ''));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Leave Management System</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/main.css">
</head>
<body>
    <?php include "../includes/header.php"; ?>
    
    <div class="page-wrapper">
        <div class="container">
            <div class="page-header">
                <div class="page-title">
                    Leave Reports
                </div>
                <p class="page-subtitle">View leave statistics and generate reports</p>
            </div>
            
            <!-- Statistics -->
            <div class="grid grid-cols-4">
                <div class="card">
                    <div>
                        <p class="text-muted">Total Leaves</p>
                        <h3><?php echo $totalLeaves; ?></h3>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <p class="text-muted">Approved</p>
                        <h3 style="color: var(--success);"><?php echo $approvedLeaves; ?></h3>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <p class="text-muted">Pending</p>
                        <h3 style="color: var(--warning);"><?php echo $pendingLeaves; ?></h3>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <p class="text-muted">Employees</p>
                        <h3 style="color: var(--info);"><?php echo $totalEmployees; ?></h3>
                    </div>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="card" style="margin-top: var(--spacing-2xl);">
                <div class="card-header">
                    <h3>Filter Reports</h3>
                </div>
                
                <form method="GET" class="form-row">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="status">
                            <option value="all" <?php echo $filters['status'] === 'all' ? 'selected' : ''; ?>>All Status</option>
                            <option value="pending" <?php echo $filters['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="approved" <?php echo $filters['status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                            <option value="rejected" <?php echo $filters['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Month</label>
                        <select class="form-control" name="month">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?php echo $m; ?>" <?php echo $filters['month'] == $m ? 'selected' : ''; ?>>
                                    <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Year</label>
                        <select class="form-control" name="year">
                            <?php for ($y = date('Y') - 2; $y <= date('Y'); $y++): ?>
                                <option value="<?php echo $y; ?>" <?php echo $filters['year'] == $y ? 'selected' : ''; ?>>
                                    <?php echo $y; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" style="opacity: 0;">.</label>
                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                    </div>
                </form>
            </div>
            
            <!-- Report Data -->
            <div class="card" style="margin-top: var(--spacing-2xl);">
                <div class="card-header">
                    <div class="flex-between">
                        <h3>Leave Data</h3>
                    </div>
                </div>
                
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($leaves)): ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: var(--spacing-2xl);">
                                        <p class="text-muted">No data available</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($leaves as $i => $leave): 
                                    $user = getRecord('users', "id = " . $leave['user_id']);
                                    $days = calculateDays($leave['start_date'], $leave['end_date']);
                                    $statusClass = getStatusBadgeClass($leave['status']);
                                    $statusIcon = getStatusIcon($leave['status']);
                                ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                                        <td><?php echo formatDate($leave['start_date']); ?></td>
                                        <td><?php echo formatDate($leave['end_date']); ?></td>
                                        <td><?php echo $days; ?> day(s)</td>
                                        <td><span class="badge <?php echo $statusClass; ?>"><?php echo ' ' . ucfirst($leave['status']); ?></span></td>
                                        <td><?php echo htmlspecialchars(substr($leave['reason'], 0, 40)) . (strlen($leave['reason']) > 40 ? '...' : ''); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
