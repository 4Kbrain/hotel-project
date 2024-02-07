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

function getUserDetails($conn, $user_id)
{
    $user_sql = "SELECT * FROM users WHERE id_user = $user_id";
    $user_result = $conn->query($user_sql);

    if ($user_result->num_rows > 0) {
        return $user_result->fetch_assoc();
    } else {
        return null;
    }
}

function getPaymentDetails($conn, $payment_id)
{
    $payment_sql = "SELECT * FROM payment p
                    JOIN roombook r ON p.id_reservation = r.id_reservation
                    WHERE p.id_payment = $payment_id";
    $payment_result = $conn->query($payment_sql);

    if ($payment_result->num_rows > 0) {
        return $payment_result->fetch_assoc();
    } else {
        return null;
    }
}

if (!isset($_SESSION['user'])) {
    header("Location: ../session/index.php");
    exit();
}

$user_email = $_SESSION['user'];
$user_id = getUserId($conn, $user_email);

if ($user_id !== null) {
    $user_details = getUserDetails($conn, $user_id);
} else {
    echo json_encode(["success" => false, "message" => "User ID not found. QwQ"]);
    exit();
}

// Handle Print Action
if (isset($_GET['id'])) {
    $payment_id = $_GET['id'];
    $payment_details = getPaymentDetails($conn, $payment_id);

    if ($payment_details !== null) {
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Grand Emporium Invoice</title>
    
    <style>
        body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    width: 100%;
    color: #333;
}

.header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.header img {
    width: 150px;
    height: auto;
    margin-right: 50px;
}

.hotel-info {
    text-align: right;
    flex-grow: 1;
}

h2 {
    font-size:30px;
    text-align:center;
    color: #0077b6;
    border-bottom: 2px solid #0077b6;
    padding-bottom: 10px;
}

.items {
    margin: 20px 0;
}

.item {
    margin-bottom: 10px;
}

.total {
    border-top: 2px solid #0077b6;
    margin-top: 20px;
    padding-top: 10px;
}

.footer {
    text-align:right;
    margin-top: 20px;
    font-size: 12px;
    color: #555;
}

.print-btn {
    text-align: center;
    margin-top: 20px;
}

.print-btn button {
    background-color: #0077b6;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.print-btn button:hover {
    background-color: #005678;
}

@media print {
    .no-print {
        display: none;
    }
}

    </style>
</head>

<body>

<div class="container">
<h2>Invoice</h2>
    <div class="header">
        <img src="../../img/logo.png" alt="Grand Emporium Logo">
        <div class="hotel-info">
            <h1>Grand Emporium Hotel</h1>
            <p>Address: 123 Elegant Street, Cityville</p>
            <p>Phone: +1 234 567 890</p>
            <p>Email: info@grandemporiumhotel.com</p>
        </div>
    </div>

    <div class="invoice">
        <p>Issued to: <?php echo $payment_details['FName'] . " " . $payment_details['LName']; ?></p>
        <p>Invoice Number: <?php echo "INV" . uniqid(); ?></p>
        <p>Check-in Date: <?php echo $payment_details['cin']; ?></p>
        <p>Check-out Date: <?php echo $payment_details['cout']; ?></p>
    </div>

    <div class="items">
        <div class="item">
            <span>Room Type: <?php echo $payment_details['TRoom']; ?></span>
            <span>Number of Nights: <?php echo $payment_details['nodays']; ?></span>
            <span>Total Cost: <?php echo "$" . $payment_details['total_cost']; ?></span>
        </div>
        <!-- item -->
    </div>

    <div class="total">
        <p>Total Amount: <?php echo "$" . $payment_details['total_cost']; ?></p>
    </div>

    <div class="footer">
        <p>Thank you for choosing Grand Emporium Hotel!</p>
        <p>Owned by: whowasthat</p>
    </div>

    <div class="no-print">
</div>

<div class="print-btn">
    <button onclick="window.print()">Print Invoice</button>
</div>
</div>

</body>

</html>

<?php
    } else {
        echo json_encode(["success" => false, "message" => "Payment ID not found. QwQ"]);
        exit();
    }
}
?>

