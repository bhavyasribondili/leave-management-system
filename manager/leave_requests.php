<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
    header("Location: ../auth/login.php");
    exit();
}

if (isset($_GET['approve'])) {

    $id = $_GET['approve'];

    mysqli_query(
        $conn,
        "UPDATE leave_requests
         SET status='approved'
         WHERE id='$id'"
    );
}

if (isset($_GET['reject'])) {

    $id = $_GET['reject'];

    mysqli_query(
        $conn,
        "UPDATE leave_requests
         SET status='rejected'
         WHERE id='$id'"
    );
}

$query = "
SELECT lr.*, u.name, lt.type_name
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
    <td><?php echo $row['type_name']; ?></td>
    <td><?php echo $row['start_date']; ?></td>
    <td><?php echo $row['end_date']; ?></td>
    <td><?php echo $row['total_days']; ?></td>
    <td><?php echo $row['status']; ?></td>

    <td>
        <a href="?approve=<?php echo $row['id']; ?>">
            Approve
        </a>

        |

        <a href="?reject=<?php echo $row['id']; ?>">
            Reject
        </a>
    </td>
</tr>
<?php } ?>

</table>