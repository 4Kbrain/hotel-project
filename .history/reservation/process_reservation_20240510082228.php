// Fetch the id_kamar based on the selected room type
$fetch_kamar_sql = "SELECT id_kamar FROM kamar WHERE type_room = ?";
$fetch_kamar_stmt = $conn->prepare($fetch_kamar_sql);

// Check if the prepare() method failed
if (!$fetch_kamar_stmt) {
    die('Error preparing query: ' . $conn->error);
}

$fetch_kamar_stmt->bind_param("s", $troom);
$fetch_kamar_stmt->execute();
$fetch_kamar_result = $fetch_kamar_stmt->get_result();

if ($fetch_kamar_result->num_rows > 0) {
    $row = $fetch_kamar_result->fetch_assoc();
    $id_kamar = $row['id_kamar'];

    // Insert the reservation data into the database
    $insert_reservation_sql = "INSERT INTO reservation (NIK, id_kamar, fname, lname, email, phone, troom, bed, nroom, cin, cout, status, nodays, total_cost)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insert_reservation_stmt = $conn->prepare($insert_reservation_sql);
    $insert_reservation_stmt->bind_param("sssssssssssssd", $NIK, $id_kamar, $fname, $lname, $email, $phone, $troom, $bed, $nroom, $cin, $cout, $status, $nodays, $totalCost);

    if ($insert_reservation_stmt->execute()) {
        // Insert NIK into the kamar table
        $insert_nik_sql = "UPDATE kamar SET NIK = ? WHERE id_kamar = ?";
        $insert_nik_stmt = $conn->prepare($insert_nik_sql);
        $insert_nik_stmt->bind_param("ss", $NIK, $id_kamar);
        $insert_nik_stmt->execute();

        // Check if the reservation was inserted successfully
        $check_reservation_sql = "SELECT * FROM reservation WHERE NIK = ?";
        $check_reservation_stmt = $conn->prepare($check_reservation_sql);
        $check_reservation_stmt->bind_param("s", $NIK);
        $check_reservation_stmt->execute();
        $check_reservation_result = $check_reservation_stmt->get_result();

        if ($check_reservation_result->num_rows > 0) {
            // Insert ke tabel payment
            $id_reservation = $insert_reservation_stmt->insert_id;
            $insert_payment_sql = "INSERT INTO payment (id_reservation, confirm, total_cost) VALUES (?, 'Not Confirmed', ?)";
            $insert_payment_stmt = $conn->prepare($insert_payment_sql);
            $insert_payment_stmt->bind_param("id", $id_reservation, $totalCost);
            if ($insert_payment_stmt->execute()) {
                echo '<script>alert("Your booking application has been sent!");</script>';
                // Remove the redirection for testing 
                header("Location: myreservation.php");
                // echo '<script>window.location.replace("myreservation.php");</script>';
            } else {
                // Display a generic success message
                echo '<script>alert("Your booking application has been sent!");</script>';
                header('Location: myreservation.php');
            }
        }
    }
} else {
    // Handle case where no matching room is found
    echo '<script>alert("Selected room type is not available.");</script>';
    echo '<script>window.location.replace("reservation_form.php");</script>';
}
