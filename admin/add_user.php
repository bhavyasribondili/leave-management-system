<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$message = "";

// ADD USER
if (isset($_POST['add_user'])) {

    $employee_id = $_POST['employee_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $department = $_POST['department'];
    $designation = $_POST['designation'];
    $role = $_POST['role'];
    $password_plain = $_POST['password'];

    // check duplicate email
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($check) > 0) {
        $message = "❌ Email already exists!";
    } else {

        // hash password
        $password = password_hash($password_plain, PASSWORD_DEFAULT);

        $query = "INSERT INTO users
        (employee_id, name, email, mobile, department, designation, role, password, status)
        VALUES
        ('$employee_id','$name','$email','$mobile','$department','$designation','$role','$password','active')";

        if (mysqli_query($conn, $query)) {
            $message = "✅ User created successfully!";
        } else {
            $message = "❌ Error: " . mysqli_error($conn);
        }
    }
}
?>

<h2>Add User (TEST MODE)</h2>

<p style="color:blue;"><?php echo $message; ?></p>

<form method="POST">

Employee ID:<br>
<input type="text" name="employee_id" required><br><br>

Name:<br>
<input type="text" name="name" required><br><br>

Email:<br>
<input type="email" name="email" required><br><br>

Mobile:<br>
<input type="text" name="mobile" required><br><br>

Department:<br>
<input type="text" name="department" required><br><br>

Designation:<br>
<input type="text" name="designation" required><br><br>

Role:<br>
<select name="role" required>
    <option value="employee">Employee</option>
    <option value="manager">Manager</option>
    <option value="admin">Admin</option>
</select><br><br>

Password:<br>
<input type="password" name="password" required><br><br>

<button type="submit" name="add_user">Create User</button>

</form>