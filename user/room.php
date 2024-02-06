<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grand";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user'])) {
    header("Location: ../session/index.php");
    exit();
}


if ($_SESSION['user'] !== 'aditgaming105@gmail.com') {
    echo json_encode(["success" => false, "message" => "Admin access only. Go Out"]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .top-navbar {
            background-color: #f0f0f0;
            padding: 10px;
            text-align: center;
            box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
        }

        .top-navbar a {
            text-decoration: none;
            color: #555;
            font-weight: bold;
            font-size: 18px;
            margin: 0 20px; 
        }

        .top-navbar a:hover {
            color: #0077b6; 
        }
    </style>
</head>

<body>

<div class="top-navbar">
    <a href="room_available.php">Room Available</a>
    <a href="status.php">Booking Application</a>
    <a href="checkout.php">Checkout</a>
    <a href="payment.php">Payment</a>
    <a href="print.php">Print</a>
</div>

</body>

</html>
