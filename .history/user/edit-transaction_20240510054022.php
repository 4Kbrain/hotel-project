<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'aditgaming105@gmail.com') {
    header("Location: ../session/index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grand";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission for editing reservation payment
    $id = $_POST['id'];
    $payment = floatval($_POST['payment']);

    // Get the reservation details
    $reservation_sql = "SELECT * FROM reservation WHERE id='$id'";
    $reservation_result = $conn->query($reservation_sql);

    if ($reservation_result->num_rows > 0) {
        $row = $reservation_result->fetch_assoc();
        $total_cost = floatval($row['total_cost']);

        // Check if the payment is sufficient
        if ($payment >= $total_cost) {
            // Update reservation status to 'Available'
            $update_reservation_sql = "UPDATE reservation SET status='Available' WHERE id='$id'";
            $conn->query($update_reservation_sql);

            // Calculate change
            $change = $payment - $total_cost;

            // Insert transaction record with change
            $insert_transaction_sql = "INSERT INTO transactions (NIK, nama, payment, total_cost, cin, cout, `change`) SELECT NIK, CONCAT(fname, ' ', lname), '$payment', total_cost, cin, cout, '$change' FROM reservation WHERE id='$id'";
            $conn->query($insert_transaction_sql);

            echo "Payment successful. Change: $change";

            header("Location: status.php");
            exit();
        } else {
            echo "Insufficient payment. Payment should be at least $total_cost";
        }
    } else {
        echo "Reservation not found.";
    }
}

if (!isset($_GET['id'])) {
    header("Location: status.php");
    exit();
}

$id = $_GET['id'];
$reservation_sql = "SELECT * FROM reservation WHERE id='$id'";
$reservation_result = $conn->query($reservation_sql);

if ($reservation_result->num_rows == 0) {
    echo "Reservation not found.";
    exit();
}

$row = $reservation_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaction</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">

</head>

<body>
    <div class="navbar">
        <div class="logo">
            <a href="#"><span style="color:#fff;">Admin</span></a>
            <a href="index.php"><span><b>Beranda</b></span></a>
            <a href="status.php"><b><span>Roombooking</b></span></a>
            <a href="payment.php"><span>Transaction</span></a>
        </div>
        <div class="profile">
            <span style="color: #fff;">Username</span>
            <div class="profile-menu">
                <a href="#">Profile</a>
                <hr>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </div>

    <h1>Edit Transaction - <?php echo $id; ?></h1>

    <form method="post" action="edit-transaction.php" class="container">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="payment">Payment:</label>
        <input type="number" id="payment" name="payment" step="0.01" min="0.00" placeholder="Enter payment amount" required>
        <button type="submit">Submit</button>
    </form>

</body>

</html>

<?php
$conn->close();
?>
