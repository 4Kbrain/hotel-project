<?php 
include '../../db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roombook Customer</title>
    <style>
        .container h1 {
            text-align:center;
        }
    </style>
</head>
<body>
    
<div class="container">
    <h1>User Reservation Table</h1>    
<div class="resrev-table">

<table border='1'>
    <tr>
        <th>id</th>
        <th>Fname</th>
        <th>Lname</th>
        <th>gmail</th>
        <th>Phone</th>
        <th>Type Room</th>
        <th>No. of Room</th>
        <th>cin</th>
        <th>cout</th>
        <th>nodays</th>
        <th>Total Cost</th>
        <th>Action</th>
    </tr>
    <tr>
        <td>
            NULL
        </td>
        <td><?echo ""?></td>
        <td><?echo ""?></td>
        <td><?echo ""?></td>
        <td><?echo ""?></td>
        <td><?echo ""?></td>
        <td><?echo ""?></td>
        <td><?echo ""?></td>
        <td><?echo ""?></td>
        <td><?echo ""?></td>
        <td>$<?echo ""?></td>

    </tr>
</table>
    </div>
</div>
</body>
</html>