<?php
session_start();

if (!isset($_SESSION['NIK'])) {
    // Handle session not set
    echo "Session user not set";
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

$NIK = $_SESSION['NIK'];

$sql = "SELECT * FROM reservation WHERE NIK = '$NIK'";
$result = $conn->query($sql);

// Debugging statements
echo "Session user: " . $_SESSION['NIK'] . "<br>";
echo "SQL: $sql<br>";
if ($result->num_rows > 0) {
    echo "Rows found: " . $result->num_rows . "<br>";
} else {
    echo "No rows found<br>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cancel_reservation_id'])) {
        $reservation_id = $_POST['cancel_reservation_id'];
        // Delete the reservation from the reservation table
        $delete_sql = "DELETE FROM reservation WHERE id = '$reservation_id'";
        if ($conn->query($delete_sql) === TRUE) {
            // Insert the cancelled reservation into the history table
            $insert_sql = "INSERT INTO history SELECT * FROM reservation WHERE id = '$reservation_id'";
            if ($conn->query($insert_sql) === TRUE) {
                echo "Reservation cancelled successfully.";
            } else {
                echo "Error inserting cancelled reservation into history table: " . $conn->error;
            }
        } else {
            echo "Error deleting reservation from reservation table: " . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations</title>
    <style>
 

    </style>
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
            if ($row["status"] == "Waiting For Approval") {
                echo "<form method='post'>";
                echo "<input type='hidden' name='cancel_reservation_id' value='" . $row["id"] . "'>";
                echo "<button type='submit'>Cancel Reservation</button>";
                echo "</form>";
            }
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
