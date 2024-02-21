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

$user_email = $_SESSION['user'];
$user_sql = "SELECT id_user FROM users WHERE gmail = '$user_email'";
$user_result = $conn->query($user_sql);

if ($user_result->num_rows > 0) {
    $user_row = $user_result->fetch_assoc();
    $user_id = $user_row['id_user'];

    $sql = "SELECT * FROM roombook WHERE id_reservation = $user_id";    
    $result = $conn->query($sql);
    $reservations = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reservations[] = $row;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_reservation'])) {
        $reservation_id_to_cancel = $_POST['cancel_reservation'];

        $cancel_sql = "DELETE FROM roombook WHERE id_reservation = $reservation_id_to_cancel AND id_user = $user_id";
        if ($conn->query($cancel_sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Reservation canceled successfully. "]);
            exit();
        } else {
            echo json_encode(["success" => false, "message" => "Error canceling reservation. TwT..Try Again later"]);
            exit();
        }
    }
} else {
    echo "User ID not found. ";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<div class="bottom-navbar">
    <a href="reservation_form.php" class="navbar-item">  Reservation</a>
    <span class="navbar-divider">|</span>
    <a href="my_reservation.php" class="navbar-item active">My Reservation</a>
</div>

<div class="reservation-container">
    <?php foreach ($reservations as $reservation): ?>
        <div class="reservation-item">
            <p>Reservation ID: <?php echo $reservation['id_user']; ?></p>
            <!-- reserv detail -->

            <form class="cancel-form" method="post">
                <input type="hidden" name="cancel_reservation" value="<?php echo $reservation['id_user']; ?>">
                <button type="button" class="cancel-button" onclick="cancelReservation(<?php echo $reservation['id_user']; ?>)">
                    Cancel Reservation 
                </button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<script>
    function cancelReservation(reservationId) {
        if (confirm('Are you sure you want to cancel this reservation? ')) {
            
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "my_reservation.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert(response.message);
                        
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                }
            };
            xhr.send("cancel_reservation=" + reservationId);
        }
    }
</script>

</html>
