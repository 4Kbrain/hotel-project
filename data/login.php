<?php
require_once('../koneksi.php');
session_start();


if (isset($_SESSION["user"])) {
    header('location: admin.html');
    exit(); 
}


$error_message = '';


if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM orang WHERE email='$email'");
    $fetch = mysqli_fetch_array($query);

    if ($fetch && password_verify($password, $fetch["password"])) {
        $_SESSION['user'] = $fetch;
        header('location: admin.html');
        exit(); 
    } else {
        $error_message = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hotel Grand Emporium Hotel</title>
    <link rel="shortcut icon" href="./favicon_io/favicon.ico" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://images.hdqwalls.com/wallpapers/dubai-popular-hotel-4k.jpg');
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
            transform:translateY(50%) !important;
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
            font-size: 16px;
        }

        button[type='submit'] {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        button[type='submit']:hover {
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
    <form class="login-form" id="loginForm" method="post" action="" autocomplete="off">
        <h1>Login - Grand Emporium Hotel</h1>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required />
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required />
        </div>
        <div class="form-group">
            <button type="submit" name="login">Login</button>
        </div>
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
    </form>
    <p>Don't have an account? <a href="register.php">Register</a></p>
</div>
</body>
</html>
