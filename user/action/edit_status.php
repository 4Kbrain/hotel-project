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

function getReservationDetails($conn, $reservation_id)
{
    $sql = "SELECT * FROM roombook WHERE id_reservation = $reservation_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

if (!isset($_SESSION['user'])) {
    header("Location: ../session/index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $reservation_id = $_GET['id'];
    $reservation_details = getReservationDetails($conn, $reservation_id);
} else {
    echo json_encode(["success" => false, "message" => "Reservation ID not provided. QwQ"]);
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
            background-color: #4894FE;
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
    <h2>Edit Reservation</h2>
    <form action="process/edit_reservation.php" method="post">
        <input type="hidden" name="reservation_id" value="<?php echo $reservation_details['id_reservation']; ?>">
        <input class="form-input" type="text" name="FName" value="<?php echo $reservation_details['FName']; ?>" required>
        <input class="form-input" type="text" name="LName" value="<?php echo $reservation_details['LName']; ?>" required>
        <input class="form-input" type="email" name="Email" value="<?php echo $reservation_details['Email']; ?>" required>
        <input type="text" name="phone" value="<?php echo $reservation_details['Phone']?>" required>
         <select name="troom" value="<?php echo $reservation_details['TRoom']?>" required>
                <option value="" disabled selected>Select Room Type</option>
                <option value="Superior Room">Superior Room</option>
                <option value="Deluxe Room">Deluxe Room</option>
                <option value="Guest House">Guest House</option>
                <option value="Single Room">Single Room</option>
            </select>
            <select name="bed" value="<?php echo $reservation_details['Bed']?>" required>
                <option value="" disabled selected>Select Bedding Type</option>
                <option value="Single">Single</option>
                <option value="Double">Double</option>
                <option value="Triple">Triple</option>
                <option value="Quad">Quad</option>
                <option value="None">None</option>
            </select>
            <select name="nroom" value="<?php echo $reservation_details['NRoom']?>" required>
                <option value="" disabled selected>Select Number of Rooms</option>
                <?php
                for ($i = 1; $i <= 7; $i++) {
                    echo "<option value='$i'>$i</option>";
                }
                ?>
            </select>

        <!-- more -->
        <button type="submit" class="form-button">Save Changes</button>
    </form>


</div>

</body>

</html>
