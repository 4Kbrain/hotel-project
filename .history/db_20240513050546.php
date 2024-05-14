<?php
$con = mysqli_connect('localhost', 'root', '', 'grand');

// Check if the connection was successful
if (!$con) {
    // If connection failed, terminate the script and display an error message
    die("Connection failed: " . mysqli_connect_error());
}

// Connection successful, you can proceed with your database operations...

// When done with database operations, close the connection
mysqli_close($con);
?>

<!-- 
<script>
window.setTimeout( function() {
window.location.reload();
}, 60000);
</script> -->