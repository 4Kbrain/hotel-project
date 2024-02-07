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

$sql = "DELETE FROM roombook where id_reservation = '$reservation'";

mysqli_query($conn, $sql);
$conn->close();



header("location:../../status.php");
?>