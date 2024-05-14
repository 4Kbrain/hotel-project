<?php
session_start();

if (!isset($_SESSION['NIK'])) {
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

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Store reservation details
    $NIK = $row['NIK'];
    $fname = $row['fname'];
    $lname = $row['lname'];
    $email = $row['email'];
    $phone = $row['phone'];
    $troom = $row['troom'];
    $bed = $row['bed'];
    $nroom = $row['nroom'];
    $cin = $row['cin'];
    $cout = $row['cout'];
    $nodays = $row['nodays'];
    $total_cost = $row['total_cost'];
    
    // Update status to 'Cancelled' in the reservation table
    $sql_update_reservation = "UPDATE reservation SET status = 'Cancelled' WHERE id = '$reservation_id'";
    $conn->query($sql_update_reservation);

    $sql_insert_history = "INSERT INTO history (id_reservation, id_kamar, NIK, fname, lname, email, phone, troom, bed, nroom, cin, cout, nodays, payment, total_cost, status) 
                           VALUES ('$reservation_id', '{$row['id_kamar']}', '$NIK', '$fname', '$lname', '$email', '$phone', '$troom', '$bed', '$nroom', '$cin', '$cout', '$nodays', '{$row['payment']}', '$total_cost', 'Cancelled')";
    $conn->query($sql_insert_history);

    // Update room_status to 'Available' and remove NIK from kamar table
    $sql_update_room = "UPDATE kamar SET room_status = 'Available', NIK = NULL WHERE id_kamar = '{$row['id_kamar']}'";
    $conn->query($sql_update_room);

    echo "Reservation cancelled successfully.";
} else {
    echo "No reservation found with ID: $reservation_id";
}

$conn->close();
?>
