<?php
include "config.php";
checkEmployee();

$user_id = $_SESSION['user']['id'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Leave History - Leave Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "menu.php"; ?>

<div class="container">
    <h2 class="section-title">📋 My Leave History</h2>
    <p class="section-subtitle">View all your leave requests and their current status.</p>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Reason</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Applied On</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query(
                    $conn,
                    "SELECT * FROM leaves WHERE user_id = $user_id ORDER BY created_at DESC" );

                if (mysqli_num_rows($query) == 0) {
                    echo "<tr><td colspan='6' style='text-align: center; padding: 30px;'><em>No leave history found</em></td></tr>";
                } else {
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($query)) {
                        echo "<tr>";
                        echo "<td>" . $i++ . "</td>";
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
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 30px;">
        <a href="leave_apply.php" class="btn btn-primary" style="display: inline-block;">➕ Apply for New Leave</a>
        <a href="employee_dashboard.php" class="btn btn-secondary" style="display: inline-block; margin-left: 10px;">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>
