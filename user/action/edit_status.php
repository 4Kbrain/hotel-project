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

.form-input,
select {
    margin-bottom: 10px;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 5px;
    width: 100%;
    box-sizing: border-box; /* Ensure padding is included in the width */
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
        <input class="form-input" type="text" name="fname" value="<?php echo $reservation_details['FName']; ?>" required>
        <input class="form-input" type="text" name="lname" value="<?php echo $reservation_details['LName']; ?>" required>
        <input class="form-input" type="email" name="email" value="<?php echo $reservation_details['Email']; ?>" required>
        <input class="form-input" type="text" name="phone" value="<?php echo $reservation_details['Phone']?>" required>
        <select name="troom" required>
    <option value="" disabled>Select Room Type</option>
    <option value="Superior Room" <?php if ($reservation_details['TRoom'] === 'Superior Room') echo 'selected'; ?>>Superior Room</option>
    <option value="Deluxe Room" <?php if ($reservation_details['TRoom'] === 'Deluxe Room') echo 'selected'; ?>>Deluxe Room</option>
    <option value="Guest House" <?php if ($reservation_details['TRoom'] === 'Guest House') echo 'selected'; ?>>Guest House</option>
    <option value="Single Room" <?php if ($reservation_details['TRoom'] === 'Single Room') echo 'selected'; ?>>Single Room</option>
</select>

<select name="bed" required>
    <option value="" disabled>Select Bedding Type</option>
    <option value="Single" <?php if ($reservation_details['Bed'] === 'Single') echo 'selected'; ?>>Single</option>
    <option value="Double" <?php if ($reservation_details['Bed'] === 'Double') echo 'selected'; ?>>Double</option>
    <option value="Triple" <?php if ($reservation_details['Bed'] === 'Triple') echo 'selected'; ?>>Triple</option>
    <option value="Quad" <?php if ($reservation_details['Bed'] === 'Quad') echo 'selected'; ?>>Quad</option>
    <option value="None" <?php if ($reservation_details['Bed'] === 'None') echo 'selected'; ?>>None</option>
</select>

<select name="nroom" required>
    <option value="" disabled>Select Number of Rooms</option>
    <?php
    for ($i =1; $i <= 7; $i++) {
        echo "<option value='$i'";
        if ($reservation_details['NRoom'] == $i) echo 'selected';
        echo ">$i</option>";
    }
    ?>
</select>
<input class="form-input" type="date" name="cin" value="<?php echo $reservation_details['cin']; ?>" required>
<input class="form-input" type="date" name="cout" value="<?php echo $reservation_details['cout']; ?>" required>
<select name="stat" required>
    <option value="" disabled selected>Select Status</option>
    <option value="Pending" <?php if ($reservation_details['stat'] === 'Pending') echo 'selected'; ?>>Pending</option>
    <option value="Confirmed" <?php if ($reservation_details['stat'] === 'Confirmed') echo 'selected'; ?>>Confirmed</option>
    <option value="Cancelled" <?php if ($reservation_details['stat'] === 'Cancelled') echo 'selected'; ?>>Cancelled</option>
</select>
<input class="form-input" type="text" name="total_cost" value="<?php echo $reservation_details['total_cost']; ?>" required>
<!-- more -->
<button type="submit" class="form-button">Save Changes</button>
    </form>
</div>

</body>

</html>