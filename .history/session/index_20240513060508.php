<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_SESSION['user'])) {
    header("location: ../index.php");
    exit();
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('../db.php');

    $mygmail = mysqli_real_escape_string($con, $_POST['username']);
    $enteredPassword = mysqli_real_escape_string($con, $_POST['password']);

    mysqli_close($con);

    $sql = "SELECT NIK, password, is_admin FROM users WHERE gmail = '$mygmail'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $count = mysqli_num_rows($result);

        if ($count == 1) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            if ($mygmail === 'aditgaming105@gmail.com' && $row['is_admin'] && $enteredPassword === 'admin') {
                $_SESSION['user'] = $mygmail;
                $_SESSION['NIK'] = $row['NIK']; // Store the NIK in the session
                header("location: ../user/index.php");
                exit();
            } elseif (!$row['is_admin'] && password_verify($enteredPassword, $row['password'])) {
                $_SESSION['user'] = $mygmail;
                $_SESSION['NIK'] = $row['NIK']; // Store the NIK in the session
                header("location: ../index.php");
                exit();
            } else {
                $error_message = "Your Login Name or Password is invalid";
            }
        } else {
            $error_message = "Your Login Name or Password is invalid";
        }
    } else {
        $error_message = "Error executing the query: " . mysqli_error($con);
    }

    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Grand Emporium Territory</title>
    <style>
        
    </style>
</head>
<body>
<div class="container">
    <form class="login-form" id="loginForm" method="post" action="" autocomplete="off">
        <h1>Login</h1>
        <div class="form-group">
            <label for="username">Gmail </label>
            <input type="text" id="username" name="username" required />
        </div>
        <div class="form-group">
            <label for="password">Password </label>
            <input type="password" id="password" name="password" required />
        </div>
        <div class="form-group">
            <button type="submit" name="login">Login</button>
        </div>
        <?php if ($error_message !== ''): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
    </form>
    <p>Don't have an account? <a href="register.php">Register</a></p>
</div>
</body>
</html>
