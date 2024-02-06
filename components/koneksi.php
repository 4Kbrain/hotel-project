<?php
$hostname = 'localhost'; 
$username = 'root'; 
$password = ''; 
$database = 'hotel';

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die('Koneksi ke database gagal: ' . $conn->connect_error);
}
?>