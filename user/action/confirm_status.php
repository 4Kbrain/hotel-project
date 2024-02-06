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

if (!isset($_SESSION['user'])) {
    header("Location: ../session/index.php");
    exit();
}

$reservation_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($reservation_id === null) {
    echo json_encode(["success" => false, "message" => "Reservation ID not provided. QwQ"]);
    exit();
}


$reservation_sql = "SELECT * FROM roombook WHERE id_reservation = $reservation_id";
$reservation_result = $conn->query($reservation_sql);

if ($reservation_result->num_rows > 0) {
    $reservation_data = $reservation_result->fetch_assoc();

    
    $confirm_sql = "UPDATE roombook SET stat = 'Confirm' WHERE id_reservation = $reservation_id";

    if ($conn->query($confirm_sql) === TRUE) {
        
        $insert_payment_sql = "INSERT INTO payment (id_user, id_reservation, paid) VALUES ('" . $reservation_data['id_user'] . "', '$reservation_id', 'Unpaid')";

        if ($conn->query($insert_payment_sql) === TRUE) {
            
            echo '<script>alert("Reservation confirmed successfully. UwU");</script>';
            
            echo '<script>window.history.go(-1);</script>';
            exit();
        } else {
            echo json_encode(["success" => false, "message" => "Error inserting payment data. TwT"]);
            exit();
        }
    } else {
        echo json_encode(["success" => false, "message" => "Error confirming reservation. TwT"]);
        exit();
    }
} else {
    echo json_encode(["success" => false, "message" => "Reservation data not found. QwQ"]);
    exit();
}
?>
