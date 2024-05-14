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
    // Handle form submission for editing reservation details
    $id = $_POST['id'];
    $id_kamar = $_POST['id_kamar'];
    $NIK = $_POST['NIK'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $troom = $_POST['troom'];
    $bed = $_POST['bed'];
    $nroom = $_POST['nroom'];
    $cin = $_POST['cin'];
    $cout = $_POST['cout'];
    $status = $_POST['status'];
    $nodays = $_POST['nodays'];
    $payment = $_POST['payment'];
    $total_cost = $_POST['total_cost'];

    // Update reservation details
    $update_sql = "UPDATE reservation SET id_kamar='$id_kamar', NIK='$NIK', fname='$fname', lname='$lname', email='$email', phone='$phone', troom='$troom', bed='$bed', nroom='$nroom', cin='$cin', cout='$cout', status='$status', nodays='$nodays', payment='$payment', total_cost='$total_cost' WHERE id='$id'";
    if ($conn->query($update_sql) === TRUE) {
        echo "Reservation updated successfully";
    } else {
        echo "Error updating reservation: " . $conn->error;
    }
}

// Fetch reservation details for the specified ID
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

    <form method="post" action="process-edit-reservation.php" class="container">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
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
        <label for="change">Change:</label><br>
        <input type="text" id="change" name="change" value="<?php echo $row['change']; ?>"><br>

        <button type="submit">Submit</button>
    </form>

</body>

</html>

<?php
$conn->close();
?>