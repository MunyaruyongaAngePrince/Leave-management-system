<?php
include "config.php";
checkEmployee();

$user_id = $_SESSION['user']['id'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Leave History</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "menu.php"; ?>

<div class="container">
    <h2>My Leave History</h2>

    <table>
        <tr>
            <th>#</th>
            <th>Reason</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
            <th>Applied On</th>
        </tr>

        <?php
        $query = mysqli_query(
            $conn,
            "SELECT * FROM leaves WHERE user_id = $user_id ORDER BY created_at DESC" );

        if (mysqli_num_rows($query) == 0) {
            echo "<tr><td colspan='6'>No leave history found</td></tr>";
        } else {
            $i = 1;
            while ($row = mysqli_fetch_assoc($query)) {
                echo "<tr>";
                echo "<td>".$i++."</td>";
                echo "<td>".$row['reason']."</td>";
                echo "<td>".$row['start_date']."</td>";
                echo "<td>".$row['end_date']."</td>";

                // Status badge
                if ($row['status'] == 'approved') {
                    echo "<td class='approved'>Approved</td>";
                } elseif ($row['status'] == 'rejected') {
                    echo "<td class='rejected'>Rejected</td>";
                } else {
                    echo "<td class='pending'>Pending</td>";
                }

                echo "<td>".$row['created_at']."</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
</div>

</body>
</html>
