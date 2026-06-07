<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
    header("Location: ../auth/login.php");
    exit();
}

/* =========================
   APPROVE LEAVE
========================= */
if (isset($_GET['approve'])) {

    $id = $_GET['approve'];

    $leave_query = mysqli_query(
        $conn,
        "SELECT * FROM leave_requests WHERE id='$id'"
    );

    $leave = mysqli_fetch_assoc($leave_query);

    if (!$leave || $leave['status'] != 'pending') {
        echo "Invalid or already processed request!";
        exit();
    }

    $user_id = $leave['user_id'];
    $leave_type_id = $leave['leave_type_id'];
    $days = $leave['total_days'];

    // update request
    mysqli_query(
        $conn,
        "UPDATE leave_requests
         SET status='approved'
         WHERE id='$id'"
    );

    // update balance
    mysqli_query(
        $conn,
        "UPDATE leave_balance
         SET used_days = used_days + $days
         WHERE user_id='$user_id'
         AND leave_type_id='$leave_type_id'"
    );

    // audit log
    mysqli_query(
        $conn,
        "INSERT INTO audit_log (user_id, leave_id, action, remarks)
         VALUES ('$user_id', '$id', 'approved', 'Approved by manager')"
    );

    header("Location: leave_requests.php");
    exit();
}

/* =========================
   REJECT LEAVE
========================= */
if (isset($_POST['reject'])) {

    $id = $_POST['request_id'];
    $remarks = trim($_POST['remarks']);

    if ($remarks == "") {
        echo "Manager remarks required!";
        exit();
    }

    $leave_query = mysqli_query(
        $conn,
        "SELECT * FROM leave_requests WHERE id='$id'"
    );

    $leave = mysqli_fetch_assoc($leave_query);

    if (!$leave || $leave['status'] != 'pending') {
        echo "Invalid or already processed request!";
        exit();
    }

    $user_id = $leave['user_id'];

    // update request
    mysqli_query(
        $conn,
        "UPDATE leave_requests
         SET status='rejected',
         manager_remarks='$remarks'
         WHERE id='$id'"
    );

    // audit log
    mysqli_query(
        $conn,
        "INSERT INTO audit_log (user_id, leave_id, action, remarks)
         VALUES ('$user_id', '$id', 'rejected', '$remarks')"
    );

    header("Location: leave_requests.php");
    exit();
}

/* =========================
   FETCH REQUESTS
========================= */
$query = "
SELECT lr.*, u.name, u.employee_id, u.department, lt.type_name
FROM leave_requests lr
JOIN users u ON lr.user_id = u.id
JOIN leave_types lt ON lr.leave_type_id = lt.id
ORDER BY lr.id DESC
";

$result = mysqli_query($conn, $query);
?>

<h2>Manage Leave Requests</h2>

<table border="1" cellpadding="10">
<tr>
    <th>Employee</th>
    <th>Employee ID</th>
    <th>Department</th>
    <th>Leave Type</th>
    <th>Start</th>
    <th>End</th>
    <th>Days</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['employee_id']; ?></td>
    <td><?php echo $row['department']; ?></td>
    <td><?php echo $row['type_name']; ?></td>
    <td><?php echo $row['start_date']; ?></td>
    <td><?php echo $row['end_date']; ?></td>
    <td><?php echo $row['total_days']; ?></td>
    <td><?php echo $row['status']; ?></td>

```
<td>
    <?php if ($row['status'] == 'pending') { ?>

        <a href="?approve=<?php echo $row['id']; ?>">
            Approve
        </a>

        <br><br>

        <form method="POST">
            <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
            <input type="text" name="remarks" placeholder="Enter remarks" required>
            <button type="submit" name="reject">Reject</button>
        </form>

    <?php } else { ?>
        No Action
    <?php } ?>
</td>
```

</tr>

<?php } ?>

</table>
