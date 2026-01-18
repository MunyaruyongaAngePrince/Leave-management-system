<?php
include "config.php";
checkAdmin();

/* Approve / Reject logic */
if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    mysqli_query($conn, "UPDATE leaves SET status='approved' WHERE id=$id");
}

if (isset($_GET['reject'])) {
    $id = (int)$_GET['reject'];
    mysqli_query($conn, "UPDATE leaves SET status='rejected' WHERE id=$id");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manage Leaves</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "menu.php"; ?>

<div class="container">
    <h2>Manage Leave Requests</h2>

    <table>
        <tr>
            <th>#</th>
            <th>Employee</th>
            <th>Reason</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

        <?php
        $query = mysqli_query(
            $conn,"SELECT leaves.*, users.name FROM leaves JOIN users ON leaves.user_id = users.id ORDER BY leaves.created_at DESC");

        $i = 1;
        while ($row = mysqli_fetch_assoc($query)) {
            echo "<tr>";
            echo "<td>".$i++."</td>";
            echo "<td>".$row['name']."</td>";
            echo "<td>".$row['reason']."</td>";
            echo "<td>".$row['start_date']."</td>";
            echo "<td>".$row['end_date']."</td>";
            echo "<td>".$row['status']."</td>";
            echo "<td>".$row['created_at']."</td>";
            echo "<td>";

             if ($row['status'] == 'pending') {
                echo "<a class='btn btn-approve' href='?approve=".$row['id']."'>Approve</a>";
                echo "<a class='btn btn-reject' href='?reject=".$row['id']."'>Reject</a>";
            } else {
                echo "done ✅";
            }

            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
