<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    header("Location: ../auth/login.php");
    exit();
}

$leave_types = mysqli_query($conn, "SELECT * FROM leave_types");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_id = $_SESSION['user_id'];
    $leave_type = $_POST['leave_type'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $reason = $_POST['reason'];

    if ($start < date('Y-m-d')) {
        echo "ERROR: Start date cannot be earlier than today!";
        exit();
    }

    if (strtotime($end) < strtotime($start)) {
        echo "ERROR: End date cannot be before start date!";
        exit();
    }

    $days = (strtotime($end) - strtotime($start)) / 86400 + 1;

    $overlap = mysqli_query($conn,
        "SELECT id FROM leave_requests
         WHERE user_id='$user_id'
         AND status IN ('pending','approved')
         AND (
            ('$start' BETWEEN start_date AND end_date)
            OR ('$end' BETWEEN start_date AND end_date)
            OR (start_date BETWEEN '$start' AND '$end')
         )"
    );

    if (mysqli_num_rows($overlap) > 0) {
        echo "ERROR: Overlapping leave request exists!";
        exit();
    }

    $balance_query = mysqli_query($conn,
        "SELECT total_days, used_days
         FROM leave_balance
         WHERE user_id='$user_id'
         AND leave_type_id='$leave_type'"
    );

    $balance = mysqli_fetch_assoc($balance_query);

    if (!$balance) {
        echo "ERROR: Leave balance not found!";
        exit();
    }

    $available = $balance['total_days'] - $balance['used_days'];

    if ($days > $available) {
        echo "ERROR: Insufficient leave balance!";
        exit();
    }

    $insert = mysqli_query($conn,
        "INSERT INTO leave_requests
        (user_id, leave_type_id, start_date, end_date, total_days, reason, status)
        VALUES
        ('$user_id', '$leave_type', '$start', '$end', '$days', '$reason', 'pending')"
    );

    if ($insert) {
        echo "SUCCESS: Leave applied successfully!";
    } else {
        echo "ERROR: Database error!";
    }

    exit();
}
?>

<h2>Apply Leave</h2>

<div id="message"></div>

<form id="leaveForm">

<select name="leave_type" required>
    <option value="">Select Leave Type</option>
    <?php while($row = mysqli_fetch_assoc($leave_types)) { ?>
        <option value="<?php echo $row['id']; ?>">
            <?php echo $row['type_name']; ?>
        </option>
    <?php } ?>
</select>

<br><br>

<input type="date" name="start_date" required>

<br><br>

<input type="date" name="end_date" required>

<br><br>

<textarea name="reason" placeholder="Reason" required></textarea>

<br><br>

<button type="submit">Apply Leave</button>

</form>

<script>
document.getElementById("leaveForm").addEventListener("submit", function(e){

    e.preventDefault();

    let formData = new FormData(this);

    fetch("apply_leave.php",{
        method:"POST",
        body:formData
    })
    .then(res => res.text())
    .then(data => {

        document.getElementById("message").innerHTML = data;

        if(data.startsWith("SUCCESS")){
            this.reset();
        }
    });

});
</script>
