<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'];

// GET USER DATA
$user = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM users WHERE id='$id'")
);

// UPDATE USER
if (isset($_POST['update_user'])) {

    $employee_id = $_POST['employee_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $department = $_POST['department'];
    $designation = $_POST['designation'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    mysqli_query($conn,
        "UPDATE users SET
        employee_id='$employee_id',
        name='$name',
        email='$email',
        mobile='$mobile',
        department='$department',
        designation='$designation',
        role='$role',
        status='$status'
        WHERE id='$id'"
    );

    header("Location: users.php");
    exit();
}
?>

<h2>Edit User</h2>

<form method="POST">

Employee ID:<br> <input type="text" name="employee_id" value="<?php echo $user['employee_id']; ?>" required><br><br>

Name:<br> <input type="text" name="name" value="<?php echo $user['name']; ?>" required><br><br>

Email:<br> <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br><br>

Mobile:<br> <input type="text" name="mobile" value="<?php echo $user['mobile']; ?>" required><br><br>

Department:<br> <input type="text" name="department" value="<?php echo $user['department']; ?>" required><br><br>

Designation:<br> <input type="text" name="designation" value="<?php echo $user['designation']; ?>" required><br><br>

Role:<br> <select name="role">
<option value="employee" <?php if($user['role']=='employee') echo 'selected'; ?>>Employee</option>
<option value="manager" <?php if($user['role']=='manager') echo 'selected'; ?>>Manager</option>
<option value="admin" <?php if($user['role']=='admin') echo 'selected'; ?>>Admin</option> </select><br><br>

Status:<br> <select name="status">
<option value="active" <?php if($user['status']=='active') echo 'selected'; ?>>Active</option>
<option value="inactive" <?php if($user['status']=='inactive') echo 'selected'; ?>>Inactive</option> </select><br><br>

<button type="submit" name="update_user">Update User</button>

</form>
