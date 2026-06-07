<?php
session_start();
include("../config/db.php");

$message = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $query = "SELECT * FROM users WHERE email='$email' AND status='active'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: ../admin/dashboard.php");
                exit();
            }

            elseif ($user['role'] == 'manager') {
                header("Location: ../manager/dashboard.php");
                exit();
            }

            else {
                header("Location: ../employee/dashboard.php");
                exit();
            }

        } else {
            $message = "Invalid password!";
        }

    } else {
        $message = "User not found or inactive!";
    }
}
?>

<!DOCTYPE html>

<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<p style="color:red;">
    <?php echo $message; ?>
</p>

<form method="POST">

```
<input
    type="email"
    name="email"
    placeholder="Email"
    required
    style="border:2px solid black;padding:8px;width:250px;"
>

<br><br>

<input
    type="password"
    name="password"
    placeholder="Password"
    required
    style="border:2px solid black;padding:8px;width:250px;"
>

<br><br>

<button type="submit" name="login">
    Login
</button>
```

</form>

</body>
</html>
