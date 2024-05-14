<?php
session_start();

if (!isset($_SESSION['user'])) {
    // Handle session not set
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

$reservation_id = $_POST['reservation_id'];

// Retrieve reservation details before deleting
$sql_select = "SELECT * FROM reservation WHERE id = '$reservation_id'";
$result = $conn->query($sql_select);
$row = $result->fetch_assoc();

$NIK = $row['NIK'];
$fname = $row['fname'];
$lname = $row['lname'];
$gmail = $row['email'];
$phone = $row['phone'];
$troom = $row['troom'];
$bed = $row['bed'];
$nroom = $row['nroom'];
$cin = $row['cin'];
$cout = $row['cout'];
$nodays = $row['nodays'];
$total_cost = $row['total_cost'];
$status = "Cancelled";

// Insert canceled reservation into history table
$sql_insert = "INSERT INTO history (NIK, fname, lname, gmail, phone, troom, bed, nroom, cin, cout, nodays, total_cost, status) 
               VALUES ('$NIK', '$fname', '$lname', '$gmail', '$phone', '$troom', '$bed', '$nroom', '$cin', '$cout', '$nodays', '$total_cost', '$status')";
$conn->query($sql_insert);

// Delete canceled reservation from reservation table
$sql_delete = "DELETE FROM reservation WHERE id = '$reservation_id'";
$conn->query($sql_delete);

$conn->close();

// Redirect back to myreservation.php
header("Location: myreservation.php");
exit();
?>
