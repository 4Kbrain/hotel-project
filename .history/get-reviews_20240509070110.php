<?php
include "db.php";

$result = $conn->query("SELECT * FROM review");
$reviews = [];

while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}

header('Content-Type: application/json');
echo json_encode($reviews);

$conn->close();

