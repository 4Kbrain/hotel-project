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
    $sql = "SELECT * FROM roombook WHERE id = $reservation_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

if (!isset($_SESSION['user']) || !isset($_GET['id'])) {
    header("Location: ../session/index.php");
    exit();
}

$reservation_id = $_GET['id'];
$reservation_details = getReservationDetails($conn, $reservation_id);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_status'])) {
    $confirm_status = $_POST['confirm_status'];

    $update_sql = "UPDATE roombook SET confirm_status = '$confirm_status' WHERE id = $reservation_id";
    if ($conn->query($update_sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Reservation status updated successfully. UwU"]);
        exit();
    } else {
        echo json_encode(["success" => false, "message" => "Error updating reservation status. TwT"]);
        exit();
    }
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

        .form-select {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }

        .form-button {
            background-color: #0077b6;
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
    <h2>Confirm Reservation</h2>
    <form action="confirm_reservation.php?id=<?php echo $reservation_id; ?>" method="post">
        <label for="confirm_status">Select Reservation Status:</label>
        <select class="form-select" name="confirm_status" id="confirm_status">
            <option value="Pending" <?php echo ($reservation_details['confirm_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="Confirm" <?php echo ($reservation_details['confirm_status'] == 'Confirm') ? 'selected' : ''; ?>>Confirm</option>
        </select>
        <button type="submit" class="form-button">Save Changes</button>
    </form>
</div>

</body>

</html>
