<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('../db.php');

    $mygmail = mysqli_real_escape_string($con, $_POST['gmail']); 
    $mypassword = mysqli_real_escape_string($con, $_POST['password']);

    
    $hashed_password = password_hash($mypassword, PASSWORD_DEFAULT);

    
    $check_query = "SELECT * FROM users WHERE gmail = '$mygmail'"; 
    $check_result = mysqli_query($con, $check_query);

    if ($check_result) {
        if (mysqli_num_rows($check_result) > 0) {
            $error_message = "This email is already registered. Please choose a different one.";
        } else {
            
            $sql = "INSERT INTO users (gmail, password, is_admin) VALUES ('$mygmail', '$hashed_password', 0)";
            
            if (mysqli_query($con, $sql)) {
                $success_message = "Registration successful! You can now log in.";
                header("location:../session/index.php");
            } else {
                $error_message = "Error executing the query: " . mysqli_error($con);
            }
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
    <title>Grand Emporium Territory - Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;

    background: url(../img/the\ night.jpg);
    background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 20px;
            margin: 20px auto;
            max-width: 400px;
            transform: translateY(50%) !important;
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
        input[type='email'],
        input[type='password'] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-left: -10px;
            font-size: 16px;
        }

        input[type='submit'] {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        input[type='submit']:hover {
            background-color: #555;
        }

        p {
            text-align: center;
        }

        a {
            color: #333;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .error-message {
            text-align: center;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <form class="login-form" id="registerForm" method="post" action="" autocomplete="off">
            <h1>Register</h1>
            <div class="form-group">
                <label for="gmail">Email </label>
                <input type="email" id="gmail" name="gmail" required />
            </div>
            <div class="form-group">
                <label for="password">Password </label>
                <input type="password" id="password" name="password" required />
            </div>
            <div class="form-group">
                <input type="submit" name="register" value="Register">
            </div>
            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <?php if (isset($success_message)): ?>
                <p class="success-message"><?php echo $success_message; ?></p>
            <?php endif; ?>
        </form>
        <p>Already have an account? <a href="index.php">Login</a></p>
    </div>
</body>
</html>