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

$id_kamar = 'id_kamar';
$sql = "SELECT * FROM reservation WHERE NIK = '$NIK' AND (status = 'Pending' OR status = 'Booked')" + "SELECT * FROM reservation WHERE id_kamar = '$id_kamar' AND NIK = '$NIK'";


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
        
        // Update the status in reservation table to Cancelled
        $update_sql = "UPDATE reservation SET status = 'Cancelled' WHERE id = '$reservation_id'";
        if ($conn->query($update_sql) === TRUE) {
            // Insert the cancelled reservation into the history table
            $insert_sql = "UPDATE history SET status = 'Cancelled' WHERE id = '$reservation_id' AND status = 'Pending'";

            $results = $conn->query($sequel);

            $update_kamar = "UPDATE `kamar` SET `room_status`='',`NIK`='' WHERE id_kamar = '$id_kamar'";

            if ($conn->query($insert_sql) === TRUE) {
                echo "Reservation cancelled successfully.";
            } else {
                echo "Error inserting cancelled reservation into history table: " . $conn->error;
            }
        } else {
            echo "Error updating reservation status: " . $conn->error;
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
       body {
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
        }

        /* Responsive Styling */
        @media only screen and (max-width: 600px) {
            .container {
                padding: 10px;
            }
        }

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
            if ($row["status"] == "Pending") {
                echo "<form id='cancel_form_" . $row["id"] . "' method='post'>";
                echo "<input type='hidden' name='cancel_reservation_id' value='" . $row["id"] . "'>";
                echo "<a href='javascript:void(0)' onclick='cancelReservation(" . $row["id"] . ")'>Cancel Reservation</a>";
                echo "</form>";
            }
            echo "</div>";
        }
    } else {
        echo "<p>No reservations found.</p>";
    }
    ?>
    <script>
        function cancelReservation(id) {
            var confirmCancel = confirm("Are you sure you want to cancel this reservation?");
            if (confirmCancel) {
                // If user confirms, submit the form
                document.getElementById('cancel_form_' + id).submit();
            }
        }
    </script>
</body>

</html>

<?php
$conn->close();
?>
