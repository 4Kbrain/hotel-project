<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../session/index.php");
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

$NIK = $_SESSION['user'];

// Query mendapat data reservasi berdasarkan NIK
$sql = "SELECT * FROM reservation WHERE NIK = '$NIK'";
$result = $conn->query($sql);


$sqls = "SELECT * FROM transactions";
$hasil = $conn->query($sqls);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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

        .reservation form {
            display: inline;
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

        .navbar-item.active {
            font-weight: 900;
            background-color: #0077b6;
            color: white;
            font-weight: bold;
        }

        .navbar-item.active:after {
            background-color: #0077b6;
            color: white;
        }
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
                // if ($hasil->num_rows > 0) {
                //     while($baris = $hasil->fetch_assoc()) {
                //         echo "<a class='action-link' href='action/print_invoice.php?id=" . $baris["id"] . "' target='_blank'>Print Invoice</a>";
                //     }
                // } else {
                //     echo "<p>No transactions found</p>";
                // }
                
                if ($row["status"] == "Waiting For Approval") {
                    echo "<button class='btn btn-danger' data-toggle='modal' data-target='#cancelModal{$row['id']}'>Cancel Reservation</button>";

                 

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
