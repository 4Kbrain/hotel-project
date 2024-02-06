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

// Handle Action dari Cange
if (isset($_GET['change_id'])) {
    $change_id = $_GET['change_id'];
    changePaymentStatus($conn, $change_id, 'Paid');
    header("Location: payment.php");
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

        .payment-container {
            padding: 20px;
            text-align: center;
        }

        .payment-table {
            width: 100%;
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
    <a href="beranda.php">Beranda</a>
    <a href="status.php">Status Roombooking</a>
    <a href="payment.php">Payment</a>
    <a href="../logout.php">Logout</a>
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
</div>

</body>

</html>
