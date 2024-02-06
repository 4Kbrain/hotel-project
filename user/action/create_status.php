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

        .form-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-top: 50px;
        }

        .form-input {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }

        .form-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="form-container">
    <h2>Create Reservation</h2>
    <form action="process_create_reservation.php" method="post">
        <input class="form-input" type="text" name="FName" placeholder="First Name" required>
        <input class="form-input" type="text" name="LName" placeholder="Last Name" required>
        <input class="form-input" type="email" name="Email" placeholder="Email" required>
        <input class="form-input" type="text" name="Phone" placeholder="Phone" required>
        <input class="form-input" type="date" name="Date" placeholder="Date" required>
        <input class="form-input" type="time" name="Time" placeholder="Time" required>
        <select class="form-input" name="Table" required>
            <option value="">Select Table</option>
            <?php
            // Fetch table numbers from the database
            $sql = "SELECT TableNumber FROM tables";

            ?>W
        <button type="submit" class="form-button">Create Reservation</button>
    </form>
</div>

</body>

</html>
