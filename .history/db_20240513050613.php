<?php
$con = mysqli_connect('localhost', 'root', '', 'grand');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_close($con);
?>

<!-- 
<script>
window.setTimeout( function() {
window.location.reload();
}, 60000);
</script> -->