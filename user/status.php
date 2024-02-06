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

function getUserReservations($conn, $start, $limit)
{
    $sql = "SELECT * FROM roombook LIMIT $start, $limit";
    $result = $conn->query($sql);
    $reservations = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reservations[] = $row;
        }
    }

    return $reservations;
}

if (!isset($_SESSION['user'])) {
    header("Location: ../session/index.php");
    exit();
}


if ($_SESSION['user'] !== 'aditgaming105@gmail.com') {
    echo json_encode(["success" => false, "message" => "Admin access only. Go Out"]);
    exit();
}

// Pengaturan pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$entriesPerPage = 10;
$start = ($page - 1) * $entriesPerPage;

$reservations = getUserReservations($conn, $start, $entriesPerPage);
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

        .top-navbar {
            background-color: #f0f0f0;
            padding: 10px;
            text-align: center;
            box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
        }

        .top-navbar a {
            text-decoration: none;
            color: #555;
            font-weight: bold;
            font-size: 18px;
            margin: 0 20px; 
        }

        .top-navbar a:hover {
            color: #0077b6; 
        }

        .reservation-container {
            padding: 20px;
            text-align: center;
        }

        .reservation-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .reservation-table th, .reservation-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .reservation-table th {
            background-color: #f0f0f0;
        }

        .action-column {
            width: 100px;
        }

        .action-link {
            display: block;
            text-align: center;
            padding: 5px;
            text-decoration: none;
            background-color: #0077b6;
            color: white;
            border-radius: 5px;
            margin: 5px auto;
        }
    </style>
</head>

<body>

<div class="top-navbar">
    <a href="beranda.php">Beranda</a>
    <a href="status.php">Status Roombooking</a>
    <a href="payment.php">Payment</a>
    <a href="../logout.php">Logout</a>
</div>

<div class="reservation-container">
    <h2>Data Roombook</h2>
    <table class="reservation-table">
        <thead>
        <tr>
        <th>ID</th>
            <th>FName</th>
            <th>LName</th>
            <th>Email</th>
            <th>Phone</th>
            <th>TRoom</th>
            <th>Bed</th>
            <th>NRoom</th>
            <th>cin</th>
            <th>cout</th>
            <th>Status</th>
            <th>NoDays</th>
            <th>Total Cost</th>
            <th>ID User</th>
            <th class="action-column">Action</th>
            <th class="action-column">Confirm Reservation</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($reservations as $reservation): ?>
            <tr>
                <td><?php echo $reservation['id_reservation']; ?></td>
                <td><?php echo $reservation['FName']; ?></td>
                <td><?php echo $reservation['LName']; ?></td>
                <td><?php echo $reservation['Email']; ?></td>
                <td><?php echo $reservation['Phone']; ?></td>
                <td><?php echo $reservation['TRoom']; ?></td>
                <td><?php echo $reservation['Bed']; ?></td>
                <td><?php echo $reservation['NRoom']; ?></td>
                <td><?php echo $reservation['cin']; ?></td>
                <td><?php echo $reservation['cout']; ?></td>
                <td><?php echo $reservation['stat']; ?></td>
                <td><?php echo $reservation['nodays']; ?></td>
                <td><?php echo $reservation['total_cost']; ?></td>
                <td><?php echo $reservation['id_user']; ?></td>
                <td class="action-column">
                    <a class="action-link" href="action/edit_status.php?id=<?php echo $reservation['id_reservation']; ?>">Edit</a>
                    <a class="action-link" href="action/create_status.php">Create</a>
                    <a class="action-link" href="action/delete_status.php?id=<?php echo $reservation['id_reservation']; ?>">Delete</a>
                </td>
                <td>
                    <a class="action-link" href="action/confirm_status.php?id=<?php echo $reservation['id_reservation']; ?>">Confirm</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- pagination harus ingat -->
    <?php
    $totalEntries = mysqli_num_rows($conn->query("SELECT id_reservation FROM roombook"));
    $totalPages = ceil($totalEntries / $entriesPerPage);

    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='?page=$i'>$i</a> ";
    }
    ?>
</div>

</body>

</html>
