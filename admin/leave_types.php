<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$message = "";

/* =========================
   UPDATE LEAVE TYPE
========================= */
if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $days = $_POST['default_days'];

    // basic validation
    if ($days < 0) {
        $message = "Days cannot be negative!";
    } else {

        $id = mysqli_real_escape_string($conn, $id);
        $days = mysqli_real_escape_string($conn, $days);

        $update = mysqli_query(
            $conn,
            "UPDATE leave_types 
             SET default_days='$days'
             WHERE id='$id'"
        );

        if ($update) {
            header("Location: leave_types.php?success=1");
            exit();
        } else {
            $message = "Update failed!";
        }
    }
}

/* success message */
if (isset($_GET['success'])) {
    $message = "Leave type updated successfully!";
}

/* =========================
   FETCH LEAVE TYPES
========================= */
$result = mysqli_query($conn, "SELECT * FROM leave_types");
?>

<h2>Leave Configuration</h2>

<p style="color:green;"><?php echo $message; ?></p>

<table border="1" cellpadding="10">
<tr>
    <th>Leave Type</th>
    <th>Default Days</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>
    <form method="POST">
        <td>
            <?php echo $row['type_name']; ?>
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        </td>

        <td>
            <input type="number" name="default_days"
                   value="<?php echo $row['default_days']; ?>"
                   min="0" required>
        </td>

        <td>
            <button type="submit" name="update">Update</button>
        </td>
    </form>
</tr>

<?php } ?>

</table>