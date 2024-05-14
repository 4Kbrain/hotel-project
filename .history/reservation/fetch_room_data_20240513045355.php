<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grand";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$troom = $_POST['troom'];


$sql = "SELECT * FROM kamar WHERE type_room = '$troom' AND room_status != 'In Use' OR room_status !=";
$result = $conn->query($sql);


$kamar = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $kamar[] = $row;
    }
}


echo json_encode($kamar);

$conn->close();
?>
