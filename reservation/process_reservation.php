<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grand";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $troom = $_POST['troom'];
    $bed = $_POST['bed'];
    $nroom = $_POST['nroom'];
    $cin = $_POST['cin'];
    $cout = $_POST['cout'];

    // number dayss 
    $checkin = new DateTime($cin);
    $checkout = new DateTime($cout);
    $nodays = $checkin->diff($checkout)->days;

    $roomTypeCosts = [
        'Superior Room' => 100,
        'Deluxe Room' => 150,
        'Guest House' => 200,
        'Single Room' => 80,
    ];

    $roomCost = $roomTypeCosts[$troom] ?? 0;
    $bedCost = $bed === 'None' ? 0 : 20; 
    $totalCost = ($roomCost + $bedCost) * $nroom;

    $sql = "INSERT INTO roombook (FName, LName, Email, Phone, TRoom, Bed, NRoom, cin, cout, stat, nodays, total_cost)
            VALUES ('$fname', '$lname', '$email', '$phone', '$troom', '$bed', '$nroom', '$cin', '$cout', 'Pending', $nodays, $totalCost)";

    if ($conn->query($sql) === TRUE) {
        // Insert into payment table
        $id_reservation = $conn->insert_id;
        $payment_sql = "INSERT INTO payment (id_reservation, confirm, total_cost)
                        VALUES ($id_reservation, 'Not Confirmed', $totalCost)";
        if ($conn->query($payment_sql) === TRUE) {
            echo '<script>alert("Your booking application has been sent!");</script>';
            echo '<script>window.location.replace("../index.php");</script>';
        } else {
            echo '<script>alert("Error adding user to the database. Check your details and try again.");</script>';
            echo '<script>window.location.replace("reservation_form.php");</script>';
        }
    } else {
        echo '<script>alert("Error adding user to the database. Check your details and try again.");</script>';
        echo '<script>window.location.replace("reservation_form.php");</script>';
    }
}

$conn->close();
?>
