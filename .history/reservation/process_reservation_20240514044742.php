<?php
session_start();

if (!isset($_SESSION['user'])) {
    echo '<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.8); z-index: 9999; display: flex; justify-content: center; align-items: center;">';
    echo "Session variables set:<br>";
    foreach ($_SESSION as $key => $value) {
        echo "$key = $value<br>";
    }
    echo '</div>';

    exit();
}

// Ambil NIK dari sesi
$nik = $_SESSION['NIK'];
echo "Session Variables Set:<br>";
echo "User = " . $_SESSION['user'] . "<br>";
echo "NIK = " . $nik;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grand";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $NIK = $_SESSION['user']; // Incorrect, use $_SESSION['NIK']
    $NIK = $nik; // Use the NIK from the session
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $troom = mysqli_real_escape_string($conn, $_POST['troom']);
    $bed = mysqli_real_escape_string($conn, $_POST['bed']);
    $nroom = mysqli_real_escape_string($conn, $_POST['nroom']);
    $cin = mysqli_real_escape_string($conn, $_POST['cin']);
    $cout = mysqli_real_escape_string($conn, $_POST['cout']);

    // Hitung jumlah hari menginap
    $checkin = new DateTime($cin);
    $checkout = new DateTime($cout);
    $nodays = $checkin->diff($checkout)->days;

    // Hitung total biaya
    $roomTypeCosts = [
        'Superior Room' => 100,
        'Deluxe Room' => 150,
        'Guest House' => 200,
        'Single Room' => 80,
    ];

    $roomCost = $roomTypeCosts[$troom] ?? 0;
    $bedCost = $bed === 'None' ? 0 : 20;
    $totalCost = ($roomCost + $bedCost) * $nroom * $nodays;

    // Check if email already exists
    $check_email_sql = "SELECT COUNT(*) AS count FROM reservation WHERE email = ?";
    $check_email_stmt = $conn->prepare($check_email_sql);
    $check_email_stmt->bind_param("s", $email);
    $check_email_stmt->execute();
    $check_email_result = $check_email_stmt->get_result();
    $row = $check_email_result->fetch_assoc();
    if ($row['count'] > 0) {
        echo '<script>alert("Email already exists. Please use a different email.");</script>';
        echo '<script>window.location.replace("reservation_form.php");</script>';
        exit(); 
    }


    // Insert data ke dalam tabel reservation
    $status = 'Pending'; // Set the status to "Pending" for the reservation

    $insert_reservation_sql = "INSERT INTO reservation (NIK, fname, lname, email, phone, troom, bed, nroom, cin, cout, status, nodays, total_cost)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insert_reservation_stmt = $conn->prepare($insert_reservation_sql);
    $insert_reservation_stmt->bind_param("sssssssssssss", $NIK, $fname, $lname, $email, $phone, $troom, $bed, $nroom, $cin, $cout, $status, $nodays, $totalCost);

    if ($insert_reservation_stmt->execute()) {
        // Get the ID of the last inserted reservation
        $id_reservation = $insert_reservation_stmt->insert_id;

        // Fetch the id_kamar from the kamar table
        $get_id_kamar_sql = "SELECT id_kamar FROM kamar WHERE type_room = ? AND room_status = 'Available' LIMIT 1";
        $get_id_kamar_stmt = $conn->prepare($get_id_kamar_sql);
        $get_id_kamar_stmt->bind_param("s", $troom);
        $get_id_kamar_stmt->execute();
        $get_id_kamar_result = $get_id_kamar_stmt->get_result();

        if ($get_id_kamar_result->num_rows > 0) {
            $row = $get_id_kamar_result->fetch_assoc();
            $id_kamar = $row['id_kamar'];

            // Update kamar table to set status to "Pending" and NIK
            $update_kamar_sql = "UPDATE kamar SET NIK = ?, room_status = 'Pending' WHERE id_kamar = ?";
            $update_kamar_stmt = $conn->prepare($update_kamar_sql);
            $update_kamar_stmt->bind_param("si", $NIK, $id_kamar);

            if ($update_kamar_stmt->execute()) {
                // Update reservation table with the fetched id_kamar
                $update_reservation_sql = "UPDATE reservation SET id_kamar = ? WHERE NIK = ?";
                $update_reservation_stmt = $conn->prepare($update_reservation_sql);
                $update_reservation_stmt->bind_param("is", $id_kamar, $NIK);
                if ($update_reservation_stmt->execute()) {
                    // Insert data into history table
                    $insert_history_sql = "INSERT INTO history (id_reservation, id_kamar, NIK, fname, lname, email, phone, troom, bed, nroom, cin, cout, nodays, total_cost, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $insert_history_stmt = $conn->prepare($insert_history_sql);
                    $insert_history_stmt->bind_param("iisssssssssssss", $id_reservation, $id_kamar, $NIK, $fname, $lname, $email, $phone, $troom, $bed, $nroom, $cin, $cout, $nodays, $totalCost, $status);

                    if ($insert_history_stmt->execute()) {
                        echo '<script>alert("Your booking application has been sent!");</script>';
                        // Remove the redirection for testing 
                        header("Location: myreservation.php");
                        // echo '<script>window.location.replace("myreservation.php");</script>';
                    } else {
                        // Handle failure to insert into history table
                        echo '<script>alert("Failed to insert into history table: ' . $conn->error . '");</script>';
                        echo '<script>window.location.replace("reservation_form.php");</script>';
                    }
                } else {
                    // Handle failure to update reservation table with id_kamar
                    echo '<script>alert("Failed to update reservation table: ' . $conn->error . '");</script>';
                    echo '<script>window.location.replace("reservation_form.php");</script>';
                }
            } else {
                // Handle failure to update kamar table
                echo '<script>alert("Failed to update kamar table: ' . $conn->error . '");</script>';
                echo '<script>window.location.replace("reservation_form.php");</script>';
            }
        } else {
            // Handle no matching room found
            echo '<script>alert("No matching room found.");</script>';
            echo '<script>window.location.replace("reservation_form.php");</script>';
        }


    } else {
        // Handle failure to insert into reservation table
        echo '<script>alert("Failed to insert into reservation table: ' . $conn->error . '");</script>';
        echo '<script>window.location.replace("reservation_form.php");</script>';
    }
}

$conn->close();
?>