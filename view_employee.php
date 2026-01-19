<?php
include "config.php";
checkAdmin();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Employees - Leave Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "menu.php"; ?>

<div class="container">
    <h2 class="section-title">👥 Employees List</h2>
    <p class="section-subtitle">View all registered employees and their leave statistics.</p>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Total Leaves</th>
                    <th>Joined On</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query($conn, "SELECT users.id, users.name, users.email, users.role, users.created_at,COUNT(leaves.id) AS total_leaves
                FROM users LEFT JOIN leaves ON users.id = leaves.user_id WHERE users.role = 'employee' GROUP BY users.id ORDER BY users.created_at DESC");

                if (mysqli_num_rows($query) == 0) {
                    echo "<tr><td colspan='6' style='text-align: center; padding: 30px;'><em>No employees found</em></td></tr>";
                } else {
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($query)) {
                        echo "<tr>";
                        echo "<td>" . $i++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td><span style='background-color: #dbeafe; color: #0c4a6e; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;'>" . ucfirst($row['role']) . "</span></td>";
                        echo "<td><span style='background-color: #f0fdf4; color: #166534; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;'>" . $row['total_leaves'] . " leaves</span></td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "</tr>";
                    }
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
