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
    <title>Edit Reservation</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <style>
        /* Add CSS here */
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            overflow-x: hidden;
        }

        /* Rest of the CSS for styling */
        /* ... */
    </style>
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

    <h1>Edit Reservation - <?php echo $id; ?></h1>

    <form method="post" action="process_edit_reservation.php" class="container">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label for="id_kamar">Room ID:</label><br>
        <input type="text" id="id_kamar" name="id_kamar" value="<?php echo $row['id_kamar']; ?>"><br>
        <label for="NIK">NIK:</label><br>
        <input type="text" id="NIK" name="NIK" value="<?php echo $row['NIK']; ?>"><br>
        <label for="fname">First Name:</label><br>
        <input type="text" id="fname" name="fname" value="<?php echo $row['fname']; ?>"><br>
        <label for="lname">Last Name:</label><br>
        <input type="text" id="lname" name="lname" value="<?php echo $row['lname']; ?>"><br>
        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email" value="<?php echo $row['email']; ?>"><br>
        <label for="phone">Phone:</label><br>
        <input type="text" id="phone" name="phone" value="<?php echo $row['phone']; ?>"><br>
        <label for="troom">Room Type:</label><br>
        <input type="text" id="troom" name="troom" value="<?php echo $row['troom']; ?>"><br>
        <label for="bed">Bed Type:</label><br>
        <input type="text" id="bed" name="bed" value="<?php echo $row['bed']; ?>"><br>
        <label for="nroom">Number of Rooms:</label><br>
        <input type="text" id="nroom" name="nroom" value="<?php echo $row['nroom']; ?>"><br>
        <label for="cin">Check-in Date:</label><br>
        <input type="text" id="cin" name="cin" value="<?php echo $row['cin']; ?>"><br>
        <label for="cout">Check-out Date:</label><br>
        <input type="text" id="cout" name="cout" value="<?php echo $row['cout']; ?>"><br>
        <label for="status">Status:</label><br>
        <input type="text" id="status" name="status" value="<?php echo $row['status']; ?>"><br>
        <label for="nodays">Number of Days:</label><br>
        <input type="text" id="nodays" name="nodays" value="<?php echo $row['nodays']; ?>"><br>
        <label for="payment">Payment:</label><br>
        <input type="text" id="payment" name="payment" value="<?php echo $row['payment']; ?>"><br>
        <label for="total_cost">Total Cost:</label><br>
        <input type="text" id="total_cost" name="total_cost" value="<?php echo $row['total_cost']; ?>"><br>
        <input type="submit" value="Submit">
    </form>

</body>

</html>

<?php
$conn->close();
?>
