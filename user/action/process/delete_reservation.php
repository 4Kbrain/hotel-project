<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grand";

$conn = new mysqli($servername, $username, $password, $dbname);

$reservation=$_GET['id'];

// Disable foreign key check
$conn->query('SET FOREIGN_KEY_CHECKS=0');

$sql = "DELETE FROM roombook where id_reservation = '$reservation'";
mysqli_query($conn, $sql);

// Enable foreign key check
$conn->query('SET FOREIGN_KEY_CHECKS=1');

$conn->close();

header("location:../../status.php");
?>
