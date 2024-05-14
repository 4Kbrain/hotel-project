<?php
session_start();

if (!isset($_SESSION['user'])) {
    // Handle session not set
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grand";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$NIK = $_SESSION['user'];

$sql = "SELECT * FROM reservation WHERE NIK = '$NIK'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations</title>
</head>

<body>
    <h1>My Reservations</h1>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<p>Reservation ID: " . $row["id"] . "</p>";
            echo "<p>NIK: " . $row["NIK"] . "</p>";
            echo "<p>Type Room: " . $row["troom"] . "</p>";
            echo "<p>Bed: " . $row["bed"] . "</p>";
            echo "<p>Nroom: " . $row["nroom"] . "</p>";
            echo "<p>Check-in Date: " . $row["cin"] . "</p>";
            echo "<p>Check-out Date: " . $row["cout"] . "</p>";
            echo "<p>Number of Days: " . $row["nodays"] . "</p>";
            echo "<p>Total Cost: " . $row["total_cost"] . "</p>";
            echo "<p>Status: " . $row["status"] . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No reservations found.</p>";
    }
    ?>
</body>

</html>

<?php
$conn->close();
?>
