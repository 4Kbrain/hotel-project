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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_id = $_POST['reservation_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $troom = $_POST['troom'];
    $bed = $_POST['bed'];
    $nroom = $_POST['nroom'];
    $cin = $_POST['cin'];
    $cout = $_POST['cout'];
    $stat = $_POST['stat'];
    $total_cost = $_POST['total_cost'];

    $sql = "UPDATE roombook SET FName='$fname', LName='$lname', Email='$email', Phone='$phone', TRoom='$troom', Bed='$bed', NRoom='$nroom', cin='$cin', cout='$cout', stat='$stat', total_cost='$total_cost' WHERE id_reservation=$reservation_id";

    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Reservation updated successfully!");</script>';
        echo '<script>window.location.replace("../../status.php");</script>';
    } else {
        echo '<script>alert("Error updating reservation. Please try again.");</script>';
        echo '<script>window.location.replace("../../status.php");</script>';
    }
} else {
    echo '<script>alert("Invalid request method.");</script>';
    echo '<script>window.location.replace("../../status.php");</script>';
}

$conn->close();
?>
