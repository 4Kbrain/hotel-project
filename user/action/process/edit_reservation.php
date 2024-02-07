<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grand";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $troom = $_POST['troom'];
    $bed = $_POST['bed'];
    $nroom = $_POST['nroom'];

    $sql = "UPDATE roombook (FName, LName, Email, Phone, TRoom, Bed, NRoom, stat, id_user)
    VALUES ('$fname', '$lname', '$email', '$phone', '$troom', '$bed', '$nroom', '$cin', '$cout', 'Pending', $nodays, 1)";

if ($conn->query($sql) === TRUE) {
    echo '<script>alert("Your booking application has been sent!");</script>';
    echo '<script>window.location.replace("../../status.php");</script>';
} else {
    echo '<script>alert("Error adding user to the database. Check your details and try again.");</script>';
    echo '<script>window.location.replace("../edit_status.php");</script>';
}
}
header("location:../../status.php");
?>