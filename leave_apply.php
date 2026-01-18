<?php
include "config.php";
checkEmployee();

if (isset($_POST['apply'])) {
    $user_id = $_SESSION['user']['id'];
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // simple validation
    if ($start_date > $end_date) {
        $error = "End date must be after start date";
    } else {
        $sql = "INSERT INTO leaves (user_id, reason, start_date, end_date) VALUES ('$user_id', '$reason', '$start_date', '$end_date')";
        mysqli_query($conn, $sql);
        $success = "Leave applied successfully";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Apply Leave</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "menu.php"; ?>

<div class="container">
    <h2><center>Apply for Leave</center></h2>

    <?php
    if (isset($error)) echo "<p class='error'>$error</p>";
    if (isset($success)) echo "<p class='success'>$success</p>";
    ?>

    <form method="POST">
        <label>Reason</label>
        <textarea name="reason" maxlength="20" required></textarea>
        

        <label>Start Date</label>
        <input type="date" name="start_date" required>

        <label>End Date</label>
        <input type="date" name="end_date" required>

        <button type="submit" name="apply">Apply Leave</button>
    </form>
</div>

</body>
</html>
