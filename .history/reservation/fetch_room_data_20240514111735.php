<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grand";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$troom = $_POST['tyep_room'];


$sql = "SELECT DISTINCT * FROM kamar WHERE type_room = '$troom' AND room_status != 'In Use' AND room_status != 'Pending'";
$result = $conn->query($sql);

echo $result;

$kamar = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $kamar[] = $row;
    }
}


echo json_encode($kamar);

$conn->close();
?>
