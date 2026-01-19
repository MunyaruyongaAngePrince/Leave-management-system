<?php
include "../includes/config.php";
checkAdmin();

// Handle approve/reject
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $leaveId = (int)$_POST['leave_id'];
    $action = sanitize($_POST['action']);
    $comment = sanitize($_POST['comment'] ?? '');
    
    if (in_array($action, ['approved', 'rejected'])) {
        $data = [
            'status' => $action,
            'admin_comment' => $comment,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => getCurrentUser()['id']
        ];
        
        updateRecord('leaves', $data, "id = $leaveId");
        setSuccess("Leave request " . $action);
        logActivity(getCurrentUser()['id'], 'leave_' . $action, "Leave ID: $leaveId");
    }
}

// Handle PDF export
if (isset($_GET['export_pdf'])) {
    include "../includes/pdf-helper.php";
    PDFExporter::exportAllLeaves();
    exit;
}

$leaves = getRecords('leaves', '', 'created_at DESC');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Leaves - Leave Management System</title>
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
                            Manage Leave Requests
                        </div>
                        <p class="page-subtitle">Review and approve/reject leave requests</p>
                    </div>
                    <a href="?export_pdf=1" class="btn btn-secondary" target="_blank">
                        Export to PDF
                    </a>
                </div>
            </div>
            
            <?php displayAlert(); ?>
            
            <!-- Statistics Cards -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                <?php 
                    $pendingCount = count(array_filter($leaves, fn($l) => $l['status'] === 'pending'));
                    $approvedCount = count(array_filter($leaves, fn($l) => $l['status'] === 'approved'));
                    $rejectedCount = count(array_filter($leaves, fn($l) => $l['status'] === 'rejected'));
                ?>
                <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div style="font-size: 0.875rem; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px;">Pending</div>
                    <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem;"><?php echo $pendingCount; ?></div>
                    <div style="font-size: 0.75rem; opacity: 0.8; margin-top: 0.5rem;">Awaiting review</div>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div style="font-size: 0.875rem; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px;">Approved</div>
                    <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem;"><?php echo $approvedCount; ?></div>
                    <div style="font-size: 0.75rem; opacity: 0.8; margin-top: 0.5rem;">Total approved</div>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div style="font-size: 0.875rem; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px;">Rejected</div>
                    <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem;"><?php echo $rejectedCount; ?></div>
                    <div style="font-size: 0.75rem; opacity: 0.8; margin-top: 0.5rem;">Total rejected</div>
                </div>
            </div>
            
            <div class="card">
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Days</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                    <td><?php echo htmlspecialchars(substr($leave['reason'], 0, 30)) . (strlen($leave['reason']) > 30 ? '...' : ''); ?></td>
                                    <td><span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($leave['status']); ?></span></td>
                                    <td>
                                        <div class="table-actions">
                                            <button onclick="viewLeave(<?php echo $leave['id']; ?>)" class="btn btn-sm btn-info">View</button>
                                            <?php if ($leave['status'] === 'pending'): ?>
                                                <button onclick="reviewLeave(<?php echo $leave['id']; ?>)" class="btn btn-sm btn-warning">Review</button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- View Modal -->
    <div class="modal" id="viewModal">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h3>Leave Request Details</h3>
                <button type="button" class="modal-close" onclick="closeModal('viewModal')">✕</button>
            </div>
            <div class="modal-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <label class="detail-label">Employee Name</label>
                        <p id="viewEmployeeName" class="detail-value">-</p>
                    </div>
                    <div class="detail-item">
                        <label class="detail-label">Employee Email</label>
                        <p id="viewEmployeeEmail" class="detail-value">-</p>
                    </div>
                    <div class="detail-item">
                        <label class="detail-label">Department</label>
                        <p id="viewDepartment" class="detail-value">-</p>
                    </div>
                    <div class="detail-item">
                        <label class="detail-label">Leave Type</label>
                        <p id="viewLeaveType" class="detail-value">-</p>
                    </div>
                    <div class="detail-item">
                        <label class="detail-label">Start Date</label>
                        <p id="viewStartDate" class="detail-value">-</p>
                    </div>
                    <div class="detail-item">
                        <label class="detail-label">End Date</label>
                        <p id="viewEndDate" class="detail-value">-</p>
                    </div>
                    <div class="detail-item">
                        <label class="detail-label">Number of Days</label>
                        <p id="viewDays" class="detail-value">-</p>
                    </div>
                    <div class="detail-item">
                        <label class="detail-label">Status</label>
                        <p id="viewStatus" class="detail-value">-</p>
                    </div>
                    <div class="detail-item full-width">
                        <label class="detail-label">Reason for Leave</label>
                        <p id="viewReason" class="detail-value detail-reason">-</p>
                    </div>
                    <div class="detail-item full-width" id="adminCommentSection" style="display: none;">
                        <label class="detail-label">Admin Comment</label>
                        <p id="viewAdminComment" class="detail-value detail-reason">-</p>
                    </div>
                    <div class="detail-item full-width" id="approvedBySection" style="display: none;">
                        <label class="detail-label">Processed By / Date</label>
                        <p id="viewProcessedBy" class="detail-value">-</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('viewModal')">Close</button>
                <button type="button" class="btn btn-warning" id="reviewBtn" onclick="openReviewModal()" style="display: none;">Review Request</button>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div class="modal" id="reviewModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Review Leave Request</h3>
                <button type="button" class="modal-close" onclick="closeModal('reviewModal')">✕</button>
            </div>
            <form method="POST" id="reviewForm">
                <div class="modal-body">
                    <input type="hidden" name="leave_id" id="leaveIdInput">
                    
                    <div class="form-group">
                        <label class="form-label">Decision *</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <button type="button" onclick="setDecision('approved')" class="btn btn-success" id="approveBtn" data-action="approved">
                                ✓ Approve Request
                            </button>
                            <button type="button" onclick="setDecision('rejected')" class="btn btn-danger" id="rejectBtn" data-action="rejected">
                                ✕ Deny Request
                            </button>
                        </div>
                        <input type="hidden" name="action" id="actionInput" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Comment (Optional)</label>
                        <textarea class="form-control" name="comment" placeholder="Add your comment (e.g., reason for denial, notes)..." rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('reviewModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Decision</button>
                </div>
            </form>
        </div>
    </div>
    
    <footer class="footer">
        <div class="footer-text">
            © 2024 Leave Management System | All Rights Reserved
        </div>
    </footer>
    
    <script>
        let currentLeaveData = {};
        
        function viewLeave(leaveId) {
            // Fetch leave details via AJAX
            fetch('<?php echo BASE_URL; ?>api/get-leave.php?id=' + leaveId, {
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('HTTP error, status = ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        currentLeaveData = data.leave;
                        const user = data.user;
                        
                        // Populate view modal
                        document.getElementById('viewEmployeeName').textContent = user.name;
                        document.getElementById('viewEmployeeEmail').textContent = user.email;
                        document.getElementById('viewDepartment').textContent = user.department || 'N/A';
                        document.getElementById('viewLeaveType').textContent = capitalizeFirst(data.leave.leave_type);
                        document.getElementById('viewStartDate').textContent = formatDate(data.leave.start_date);
                        document.getElementById('viewEndDate').textContent = formatDate(data.leave.end_date);
                        document.getElementById('viewDays').textContent = data.days + ' day(s)';
                        
                        const statusBadge = getStatusBadge(data.leave.status);
                        document.getElementById('viewStatus').innerHTML = statusBadge;
                        document.getElementById('viewReason').textContent = data.leave.reason;
                        
                        // Show admin comment if exists
                        if (data.leave.admin_comment && data.leave.status !== 'pending') {
                            document.getElementById('adminCommentSection').style.display = 'block';
                            document.getElementById('viewAdminComment').textContent = data.leave.admin_comment;
                        } else {
                            document.getElementById('adminCommentSection').style.display = 'none';
                        }
                        
                        // Show processed by if not pending
                        if (data.leave.status !== 'pending' && data.admin) {
                            document.getElementById('approvedBySection').style.display = 'block';
                            document.getElementById('viewProcessedBy').textContent = data.admin.name + ' on ' + formatDate(data.leave.updated_at);
                        } else {
                            document.getElementById('approvedBySection').style.display = 'none';
                        }
                        
                        // Show review button only if pending
                        if (data.leave.status === 'pending') {
                            document.getElementById('reviewBtn').style.display = 'inline-block';
                        } else {
                            document.getElementById('reviewBtn').style.display = 'none';
                        }
                        
                        openModal('viewModal');
                    } else {
                        console.error('API Error:', data.message);
                        alert('Error loading leave details: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    alert('Error loading leave details: ' + error.message);
                });
        }
        
        function reviewLeave(leaveId) {
            document.getElementById('leaveIdInput').value = leaveId;
            openModal('reviewModal');
        }
        
        function openReviewModal() {
            closeModal('viewModal');
            document.getElementById('leaveIdInput').value = currentLeaveData.id;
            openModal('reviewModal');
        }
        
        function setDecision(action) {
            document.getElementById('actionInput').value = action;
            
            // Add visual feedback
            const approveBtn = document.getElementById('approveBtn');
            const rejectBtn = document.getElementById('rejectBtn');
            
            approveBtn.style.opacity = action === 'approved' ? '1' : '0.5';
            rejectBtn.style.opacity = action === 'rejected' ? '1' : '0.5';
            
            console.log('Decision set to:', action);
        }
        
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            // Reset decision buttons
            document.getElementById('approveBtn').style.opacity = '1';
            document.getElementById('rejectBtn').style.opacity = '1';
            document.getElementById('actionInput').value = '';
            // Clear comment
            const commentTextarea = document.querySelector('textarea[name="comment"]');
            if (commentTextarea) commentTextarea.value = '';
        }
        
        function formatDate(dateStr) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateStr).toLocaleDateString('en-US', options);
        }
        
        function capitalizeFirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
        
        function getStatusBadge(status) {
            const badges = {
                'pending': '<span class="badge badge-warning">⏳ Pending</span>',
                'approved': '<span class="badge badge-success">✅ Approved</span>',
                'rejected': '<span class="badge badge-danger">❌ Rejected</span>'
            };
            return badges[status] || '<span class="badge badge-info">Unknown</span>';
        }
        
        // Handle form submission
        document.getElementById('reviewForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate that an action was selected
            const action = document.getElementById('actionInput').value;
            const leaveId = document.getElementById('leaveIdInput').value;
            
            if (!action) {
                alert('Please select Approve or Deny');
                return;
            }
            
            if (!leaveId) {
                alert('Error: Leave ID not found');
                return;
            }
            
            console.log('Submitting:', { action, leaveId });
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Processing...';
            submitBtn.disabled = true;
            
            // Create FormData from form
            const formData = new FormData(this);
            
            // Debug: log form data
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
            
            // Submit via fetch
            fetch('<?php echo BASE_URL; ?>admin/leaves.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(html => {
                console.log('Response received');
                // Reload page to show updated data
                alert('Decision submitted successfully!');
                setTimeout(() => location.reload(), 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error submitting decision: ' + error.message);
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    </script>
</body>
</html>
