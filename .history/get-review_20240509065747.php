<?php
include "db.php";

// Fetch reviews from the database
$result = $conn->query("SELECT * FROM review");
$reviews = [];

while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}

// Return reviews as JSON
header('Content-Type: application/json');
echo json_encode($reviews);

$conn->close();
?>
