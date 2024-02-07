<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('../db.php');

    $mygmail = mysqli_real_escape_string($con, $_POST['username']);
    $enteredPassword = mysqli_real_escape_string($con, $_POST['password']);

    $sql = "SELECT id_user, password, is_admin FROM users WHERE gmail = '$mygmail'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $count = mysqli_num_rows($result);

        if ($count == 1) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            
            if ($row['is_admin'] && $enteredPassword === 'admin') {
                $_SESSION['user'] = $mygmail;
                header("location: ../user/index.php");
                exit();
            } elseif (!$row['is_admin'] && password_verify($enteredPassword, $row['password'])) {
                
                $_SESSION['user'] = $mygmail;
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
    body {
    font-family: 'Arial', sans-serif;
    background: #e7c6;
    background-repeat: no-repeat;
    background-attachment: fixed;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

.container {
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 10px;
    padding: 20px;
    margin: auto;
    width: 400px;
    transform: translateY(-25%) !important;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

.login-form {
    text-align: center;
}

h1 {
    color: #333;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input[type='text'],
input[type='password'] {
    width: calc(100% - 20px);
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

button {
    background-color: #333;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #555;
}

.error-message {
    text-align: center;
    color: red;
}

p {
    text-align: center;
    margin-top: 20px;
    color: #333;
}

a {
    color: #333;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

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
            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
        </form>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
</body>
</html>
