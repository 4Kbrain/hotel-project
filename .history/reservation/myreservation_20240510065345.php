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
    <style>body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .reservation {
            background-color: #fff;
            padding: 10px 20px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .reservation p {
            margin: 5px 0;
        }

        .cancel-btn {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }

        .cancel-btn:hover {
            background-color: #c82333;
        }

        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #ddd;
        }

        .no-reservations {
            text-align: center;
            margin-top: 20px;
            color: #777;
        }

        .bottom-navbar {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #f0f0f0;
            display: flex;
            justify-content: space-around;
            padding: 10px;
            box-shadow: 0px -1px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar-item {
            text-align: center;
            color: #555;
            text-decoration: none;
            padding: 8px;
            border-radius: 8px;
        }

        .navbar-item:hover {
            background-color: #ddd;
        }

        .navbar-item-active {
            font-weight: 900;
            background-color: #0077b6;
            color: white;
            font-weight: bold;
        }

        .navbar-item-active:after {
            background-color: #0077b6;
            color: white;
        }</style>
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
                    echo "<form action='cancel_reservation.php' method='post'>";
                    echo "<input type='hidden' name='reservation_id' value='{$row["id"]}'>";
                    echo "<input type='submit' class='cancel-btn' value='Cancel Reservation'>";
                    echo "</form>";
                }
                echo "</div>";
            }
        } else {
            echo "<p class='no-reservations'>No reservations found.</p>";
        }
        ?>
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
