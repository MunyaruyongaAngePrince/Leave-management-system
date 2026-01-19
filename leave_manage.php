<?php
include "config.php";
checkAdmin();

/* Approve / Reject logic */
if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    mysqli_query($conn, "UPDATE leaves SET status='approved' WHERE id=$id");
    header("Location: leave_manage.php?success=approved");
    exit();
}

if (isset($_GET['reject'])) {
    $id = (int)$_GET['reject'];
    mysqli_query($conn, "UPDATE leaves SET status='rejected' WHERE id=$id");
    header("Location: leave_manage.php?success=rejected");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Leaves - Leave Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "menu.php"; ?>

<div class="container">
    <h2 class="section-title">✅ Manage Leave Requests</h2>
    <p class="section-subtitle">Review and approve/reject employee leave requests.</p>

    <?php if(isset($_GET['success'])): ?>
        <div class="success">
            ✅ Leave request <?php echo $_GET['success'] === 'approved' ? 'approved' : 'rejected'; ?> successfully!
        </div>
    <?php endif; ?>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Reason</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Applied On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query(
                    $conn,"SELECT leaves.*, users.name FROM leaves JOIN users ON leaves.user_id = users.id ORDER BY leaves.created_at DESC");

                $i = 1;
                while ($row = mysqli_fetch_assoc($query)) {
                    echo "<tr>";
                    echo "<td>" . $i++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['reason']) . "</td>";
                    echo "<td>" . $row['start_date'] . "</td>";
                    echo "<td>" . $row['end_date'] . "</td>";
                    
                    // Status badge
                    if ($row['status'] == 'approved') {
                        echo "<td><span class='status-approved'>✅ Approved</span></td>";
                    } elseif ($row['status'] == 'rejected') {
                        echo "<td><span class='status-rejected'>❌ Rejected</span></td>";
                    } else {
                        echo "<td><span class='status-pending'>⏳ Pending</span></td>";
                    }
                    
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "<td>";

                    if ($row['status'] == 'pending') {
                        echo "<a class='btn btn-approve' href='?approve=" . $row['id'] . "'>✅ Approve</a> ";
                        echo "<a class='btn btn-reject' href='?reject=" . $row['id'] . "'>❌ Reject</a>";
                    } else {
                        echo "<span style='color: #10b981; font-weight: 600;'>✓ Processed</span>";
                    }

                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 30px;">
        <a href="admin_dashboard.php" class="btn btn-secondary" style="display: inline-block;">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>
