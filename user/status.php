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

        ::-webkit-scrollbar {
            display: none;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            background-color: #4894FE;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 999;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .logo span {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .profile {
            margin-right: 30px;
            position: relative;
        }

        .profile-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #fff;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            padding: 10px 20px 10px 20px;
            display: none;
        }

        .profile:hover .profile-menu {
            display: block;
        }

        .profile-menu a {
            display: block;
            text-decoration: none;
            color: #333;
            padding: 5px 10px;
            transition: all 0.3s ease;
        }

        .profile-menu a:hover {
            background-color: #f0f0f0;
        }

        .content {
            margin-left: 180px;
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar {
            width: 180px;
            background-color: #f0f0f0;
            box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
            padding-top: 20px;
            height: calc(100vh - 40px);
            position: fixed;
            top: 40px;
            left: 0;
            overflow-y: auto;
        }

        .sidebar a {
            display: block;
            text-decoration: none;
            color: #555;
            font-weight: bold;
            font-size: 18px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .sidebar a.active {
            background-color: #d3d3d3;
            color: #0077b6;
        }

        .sidebar a:hover {
            background-color: #d3d3d3;
            color: #0077b6;
        }

        .reservation-container {
            margin-right: -35px;
            padding: 20px;
            text-align: center;
        }

        .reservation-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 20px;
        }

        .reservation-table th,
        .reservation-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .reservation-table th {
            background-color: #f0f0f0;
        }

        .action-column {
            width: 80px;
        }

        .action-link {
            width: 80px;
            display: block;
            text-align: center;
            padding: 5px;
            text-decoration: none;
            background-color: #0077b6;
            color: white;
            border-radius: 5px;
            margin: 3px auto;
        }

        #entry {
            margin-top: 20px;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination-link {
            display: inline-block;
            padding: 5px 10px;
            margin: 0 3px;
            text-decoration: none;
            color: #333;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .pagination-link.active {
            background-color: #0077b6;
            color: #fff;
            border-color: #0077b6;
        }

        .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .popup-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        width: 300px;
        text-align: center;
    }

    .popup-buttons {
        margin-top: 20px;
    }

    .popup-buttons button {
        background: #0077b6;
        border-radius:5px;
        color: #fff;
        padding: 5px 10px;
        margin: 0 5px;
        cursor: pointer;
    }

    .popup-buttons button:hover {
        background: red;
        border-radius:5px;
        color: #fff;
        padding: 5px 10px;
        margin: 0 5px;
        cursor: pointer;
    }
    </style>
</head>

<body>

    <div class="top-navbar">
        <div class="logo">
            <a href="#"><span style="color:#fff">Admin</span></a>
        </div>
        <div class="profile">
            <span style="color:#fff;">Username</span>
            <div class="profile-menu">
                <a href="#">Profile</a><hr>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="sidebar">
    <a href="index.php" <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'class="active"' : ''; ?>>Beranda</a><hr>
    <a href="status.php" <?php echo basename($_SERVER['PHP_SELF']) == 'status.php' ? 'class="active"' : ''; ?>>Roombooking</a><hr>
    <a href="payment.php" <?php echo basename($_SERVER['PHP_SELF']) == 'payment.php' ? 'class="active"' : ''; ?>>Payment</a>
    </div>

    <div class="content">
        <div class="reservation-container">
            <h2 style="margin-top:30px;">Data Roombook</h2>
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
                        <!-- <th>ID User</th> -->
                        <th class="action-column">Action</th>
                        <!-- <th class="action-column">Confirm Reservation</th> -->
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
                            <td>$<?php echo $reservation['total_cost']; ?></td>
                            <td class="action-column">
                                <a class="action-link" href="action/edit_status.php?id=<?php echo $reservation['id_reservation']; ?>">Edit</a>  
                                <a class="action-link" href="#" onclick="confirmDelete(<?php echo $reservation['id_reservation']; ?>)">Delete</a>
                            </td>
                            <!-- <td>
                                <a class="action-link" href="action/confirm_status.php?id=<?php echo $reservation['id_reservation']; ?>">Confirm</a>
                            </td> -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="pagination">
                <?php
                $totalEntries = mysqli_num_rows($conn->query("SELECT id_reservation FROM roombook"));
                $totalPages = ceil($totalEntries / $entriesPerPage);

                for ($i = 1; $i <= $totalPages; $i++) {
                    echo "<a href='?page=$i' class='pagination-link";
                    echo ($i == $page) ? " active'" : "'";
                    echo ">$i</a> ";
                }
                ?>
            </div>

            <a class="action-link" href="../reservation/reservation_form.php" id="entry">New Entry</a>
        </div>
    </div>


    <div class="popup-overlay" id="popup">
    <div class="popup-container">
        <p>Are you sure you want to delete this reservation?</p>
        <div class="popup-buttons">
            <button onclick="deleteReservation()">Yes</button>
            <button onclick="cancelDelete()">No</button>
        </div>
    </div>
</div>

<script>
    let reservationIdToDelete;

    function confirmDelete(reservationId) {
        reservationIdToDelete = reservationId;
        document.getElementById('popup').style.display = 'flex';
    }

    function deleteReservation() {
        window.location.href = `action/process/delete_reservation.php?id=${reservationIdToDelete}`;
    }

    function cancelDelete() {
        document.getElementById('popup').style.display = 'none';
    }
</script>
</body>

</html>
