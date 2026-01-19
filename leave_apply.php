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
        $success = "Leave applied successfully! Your request is pending admin approval.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply Leave - Leave Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "menu.php"; ?>

<div class="container">
    <h2 class="section-title">📝 Apply for Leave</h2>
    <p class="section-subtitle">Submit your leave request below. The administrator will review and respond shortly.</p>

    <?php
    if (isset($error)) echo "<div class='error'>⚠️ " . htmlspecialchars($error) . "</div>";
    if (isset($success)) echo "<div class='success'>✅ " . htmlspecialchars($success) . "</div>";
    ?>

    <form method="POST">
        <div class="form-group">
            <label for="reason">Reason for Leave</label>
            <textarea id="reason" name="reason" placeholder="Please provide a reason for your leave request" maxlength="500" required></textarea>
        </div>

        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" id="start_date" name="start_date" required>
        </div>

        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" id="end_date" name="end_date" required>
        </div>

        <button type="submit" name="apply" class="btn-submit">Submit Leave Request</button>
        <a href="leave_history.php" style="display: inline-block; margin-top: 15px; padding: 12px 24px; background-color: #64748b; color: white; border-radius: 6px; text-decoration: none; text-align: center;">
            Back to Leave History
        </a>
    </form>

    <div class="info-section" style="margin-top: 30px;">
        <strong>ℹ️ Information:</strong><br>
        • Make sure your end date is after your start date<br>
        • Your leave balance will be updated upon approval<br>
        • You will receive a notification when your request is reviewed
    </div>
</div>

</body>
</html>
