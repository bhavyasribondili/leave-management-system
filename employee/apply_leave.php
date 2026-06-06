<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    header("Location: ../auth/login.php");
    exit();
}

// fetch leave types
$leave_types = mysqli_query($conn, "SELECT * FROM leave_types");

if (isset($_POST['apply'])) {

    $user_id = $_SESSION['user_id'];
    $leave_type = $_POST['leave_type'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $reason = $_POST['reason'];

    // calculate days
    $days = (strtotime($end) - strtotime($start)) / (60 * 60 * 24) + 1;

    // insert request
    $query = "INSERT INTO leave_requests 
    (user_id, leave_type_id, start_date, end_date, total_days, reason, status)
    VALUES 
    ('$user_id', '$leave_type', '$start', '$end', '$days', '$reason', 'pending')";

    if (mysqli_query($conn, $query)) {
        echo "Leave applied successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<h2>Apply Leave</h2>

<form method="POST">

    <select name="leave_type" required>
        <option value="">Select Leave Type</option>
        <?php while($row = mysqli_fetch_assoc($leave_types)) { ?>
            <option value="<?php echo $row['id']; ?>">
                <?php echo $row['type_name']; ?>
            </option>
        <?php } ?>
    </select><br><br>

    Start Date: <input type="date" name="start_date" required><br><br>
    End Date: <input type="date" name="end_date" required><br><br>

    Reason:<br>
    <textarea name="reason" required></textarea><br><br>

    <button type="submit" name="apply">Apply Leave</button>

</form>