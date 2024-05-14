<?php
session_start();

if (!isset($_SESSION['user'])) {
    echo '<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.8); z-index: 9999; display: flex; justify-content: center; align-items: center;">';
    echo "Session variables set:<br>";
    foreach ($_SESSION as $key => $value) {
        echo "$key = $value<br>";
    }
    echo '</div>';

    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grand";

$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksis
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$NIK = $_SESSION['NIK'];

// Query mendapat data reservasi berdasarkan NIK
$sql = "SELECT * FROM reservation WHERE NIK = '$NIK'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        
    </style>
</head>

<body>
    <div class="container">
        <h1>My Reservations</h1>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='reservation'>";
                echo "<p>Reservation ID: " . $row["id"] . "</p>";
                echo "<p>NIK: " . $row["NIK"] . "</p>";
                echo "<p>Type Room: " . $row["troom"] . "</p>";
                echo "<p>Bed: " . $row["bed"] . "</p>";
                echo "<p>Nroom: " . $row["nroom"] . "</p>";
                echo "<p>Check-in Date: " . $row["cin"] . "</p>";
                echo "<p>Check-out Date: " . $row["cout"] . "</p>";
                echo "<p>Number of Days: " . $row["nodays"] . "</p>";
                echo "<p>Total Cost: " . $row["total_cost"] . "</p>";
                echo "<hr>";
                echo "<p>Status: " . $row["status"] . "</p>";

                if ($row["status"] == "Waiting For Approval") {
                    echo "<button class='cancel-btn' data-toggle='modal' data-target='#cancelModal{$row['id']}'>Cancel Reservation</button>";

                    // Modal for cancel confirmation
                    echo "<div class='modal fade' id='cancelModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='cancelModalLabel' aria-hidden='true'>";
                    echo "<div class='modal-dialog' role='document'>";
                    echo "<div class='modal-content'>";
                    echo "<div class='modal-header'>";
                    echo "<h5 class='modal-title' id='cancelModalLabel'>Cancel Reservation</h5>";
                    echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
                    echo "<span aria-hidden='true'>&times;</span>";
                    echo "</button>";
                    echo "</div>";
                    echo "<div class='modal-body'>";
                    echo "<p>Are you sure you want to cancel the reservation?</p>";
                    echo "</div>";
                    echo "<div class='modal-footer'>";
                    echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>";
                    echo "<form action='cancel_reservation.php' method='post'>";
                    echo "<input type='hidden' name='reservation_id' value='{$row["id"]}'>";
                    echo "<button type='submit' class='btn btn-danger'>Cancel Reservation</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                echo "</div>";
            }
        } else {
            echo "<p class='no-reservations'>No reservations found.</p>";
        }
        ?>
    </div>

    <div class="bottom-navbar">
        <a href="reservation_form.php" class="navbar-item">Reservation</a>
        <span class="navbar-divider">|</span>
        <a href="myreservation.php" class="navbar-item-active">My Reservation</a>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>
