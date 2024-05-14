<?php
$con = mysqli_connect('localhost', 'root', '', 'grand');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}