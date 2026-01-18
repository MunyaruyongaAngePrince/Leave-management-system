<?php
include "config.php";
checkAdmin();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>View Employees</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "menu.php"; ?>

<div class="container">
    <h2>Employees List</h2>

    <table>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Total Leaves</th>
            <th>Joined On</th>
        </tr>

        <?php
       $query = mysqli_query($conn, "SELECT users.id, users.name, users.email, users.role, users.created_at,COUNT(leaves.id) AS total_leaves
    FROM users LEFT JOIN leaves ON users.id = leaves.user_id WHERE users.role = 'employee'GROUP BY users.id");

        if (mysqli_num_rows($query) == 0) {
            echo "<tr><td colspan='5'>No employees found</td></tr>";
        } else {
            $i = 1;
            while ($row = mysqli_fetch_assoc($query)) {
                echo "<tr>";
                echo "<td>".$i++."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row['role']."</td>";
                echo "<td>".$row['total_leaves']."</td>";
                echo "<td>".$row['created_at']."</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
</div>

</body>
</html>
