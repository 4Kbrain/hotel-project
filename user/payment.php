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


if (!isset($_SESSION['user'])) {
    header("Location: ../session/index.php");
    exit();
}


if ($_SESSION['user'] !== 'aditgaming105@gmail.com') {
    echo json_encode(["success" => false, "message" => "Admin access only. Go Out"]);
    exit();
}

function getUserId($conn, $gmail)
{
    $user_sql = "SELECT id_user FROM users WHERE gmail = '$gmail'";
    $user_result = $conn->query($user_sql);

    if ($user_result->num_rows > 0) {
        $user_row = $user_result->fetch_assoc();
        return $user_row['id_user'];
    } else {
        return null;
    }
}

function getUserPayments($conn, $user_id)
{
    $sql = "SELECT * FROM payment WHERE id_user = $user_id";
    $result = $conn->query($sql);
    $payments = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $payments[] = $row;
        }
    }

    return $payments;
}

function changePaymentStatus($conn, $payment_id, $new_status)
{
    $update_sql = "UPDATE payment SET paid = '$new_status' WHERE id_payment = $payment_id";
    $conn->query($update_sql);
}

if (!isset($_SESSION['user'])) {
    header("Location: ../session/index.php");
    exit();
}

$user_email = $_SESSION['user'];
$user_id = getUserId($conn, $user_email);

if ($user_id !== null) {
    $payments = getUserPayments($conn, $user_id);
} else {
    echo json_encode(["success" => false, "message" => "User ID not found. Try Again Later"]);
    exit();
}


// Handle Action dari Change
if (isset($_GET['change_id'])) {
    $change_id = $_GET['change_id'];
    changePaymentStatus($conn, $change_id, 'Paid');
    header("Location: payment.php");
    exit();
}


$payments = getUserPayments($conn, $user_id);

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$perPage = 10;
$totalPayments = count($payments);
$totalPages = ceil($totalPayments / $perPage);
$start = ($page - 1) * $perPage;
$end = $start + $perPage;
$paymentsToShow = array_slice($payments, $start, $perPage);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <style>

        ::-webkit-scrollbar {
            display:none;
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

        .payment-container {

            margin-left: 200px;
            padding: 20px;
            text-align: center;
        }

        .payment-table {
            width: 99%;
            margin-left: 10px;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .payment-table th, .payment-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .payment-table th {
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
        <div class="logo">
            <a href="#"><span style="color:#fff">Admin</span></a>
        </div>
        <div class="profile">
            <span>Username</span>
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

<div class="payment-container">
    <h2>Data Payment</h2>
    <table class="payment-table">
        <thead>
        <tr>
            <th>ID Payment</th>
            <th>ID Reservation</th>
            <th>Paid</th>
            <th class="action-column">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($payments as $payment): ?>
            <tr>
                <td><?php echo $payment['id_payment']; ?></td>
                <td><?php echo $payment['id_reservation']; ?></td>
                <td><?php echo isset($payment['paid']) ? $payment['paid'] : 'UwU'; ?></td>
                <td class="action-column">
                    <?php if (isset($payment['paid']) && $payment['paid'] === 'Unpaid'): ?>
                        <a class="action-link" href="payment.php?change_id=<?php echo $payment['id_payment']; ?>">Change</a>
                    <?php endif; ?>
                    <a class="action-link" href="action/print_payment.php?id=<?php echo $payment['id_payment']; ?>">Print</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination">
                    <?php


                $totalEntries = mysqli_num_rows($conn->query("SELECT id_payment FROM payment"));
                $totalPages = ceil($totalPayments / $PerPage);

                    for ($i = 1; $i <= $totalPages; $i++) {
                        echo "<a href='?page=$i' class='pagination-link";
                        echo ($i == $page) ? " active'" : "'";
                        echo ">$i</a> ";
                    }
                    ?>
                </div>
</div>

</body>

</html>