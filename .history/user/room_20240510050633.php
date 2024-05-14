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

if ($_SESSION['user'] !== 'aditgaming105@gmail.com') {
    echo json_encode(["success" => false, "message" => "Admin access only. Go Out"]);
    exit();
}

// get rooms dbs
function getRooms($conn)
{
    $sql = "SELECT * FROM kamar";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id_kamar'] . "</td>";
            echo "<td>" . $row['type_room'] . "</td>";
            echo "<td>" . $row['room_status'] . "</td>";
            echo "<td>" . $row['room_capacity'] . "</td>";
            echo "<td>" . $row["bed_type"] . "</td>";
            echo "<td>";
            echo "<form method='POST' action='" . $_SERVER['PHP_SELF'] . "'>";
            echo "<input type='hidden' name='room_id' value='" . $row['id_kamar'] . "'>";
            echo "<select name='room_status'>";
            echo "<option value='Available' " . ($row['room_status'] == 'Available' ? 'selected' : '') . ">Available</option>";
            echo "<option value='Occupied' " . ($row['room_status'] == 'Occupied' ? 'selected' : '') . ">Occupied</option>";
            echo "<option value='Under Maintenance' " . ($row['room_status'] == 'Under Maintenance' ? 'selected' : '') . ">Under Maintenance</option>";
            echo "</select>";
            echo "<button type='submit' name='update_room'>Update</button>";
            echo "<button type='submit' name='delete_room'>Delete</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No rooms found</td></tr>";
    }
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_room'])) {
        // Handle adding a new room
        $room_name = $_POST['type_room'];
        $room_status = $_POST['room_status'];
        $room_capacity = $_POST['room_capacity'];
        $bed_type = $_POST['bed_type'];

        $sql = "INSERT INTO kamar (NULL, type_room, room_status, room_capacity, bed_type) VALUES (NULL,'$room_name', '$room_status', '$room_capacity', '$bed_type')";
        if ($conn->query($sql) === TRUE) {
            echo "New room added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['update_room'])) {
        // Handle updating room status
        $room_id = $_POST['room_id'];
        $room_status = $_POST['room_status'];

        $sql = "UPDATE kamar SET room_status='$room_status' WHERE id_kamar='$room_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Room status updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } elseif (isset($_POST['delete_room'])) {
        // Handle deleting a room
        $room_id = $_POST['room_id'];

        $sql = "DELETE FROM kamar WHERE id_kamar='$room_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Room deleted successfully";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <style>
        /* Your CSS styles here */
    </style>
</head>

<body>

    <div class="navbar">
        <!-- Navbar content -->
    </div>

    <div class="sidebar">
        <!-- Sidebar content -->
    </div>

    <div class="content">
        <!-- Room management form and table -->
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="room_name">Room Name:</label>
            <select id="room_name" name="room_name" required>
                <option value="Superior Room">Superior Room</option>
                <option value="Deluxe Room">Deluxe Room</option>
                <option value="Guest House">Guest House</option>
                <option value="Single Room">Single Room</option>
            </select><br>

            <label for="room_status">Room Status:</label>
            <select id="room_status" name="room_status" required>
                <option value="Available">Available</option>
                <option value="Occupied">Occupied</option>
                <option value="Under Maintenance">Under Maintenance</option>
            </select><br>

            <label for="room_capacity">Room Capacity:</label>
            <select id="room_capacity" name="room_capacity" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select><br>

            <label for="bed_type">Bed Type:</label>
            <select id="bed_type" name="room_capacity" required>
                <option value="Single">Single</option>
                <option value="Double">Double</option>
                <option value="Triple">Triple</option>
                <option value="Quad">Quad</option>
            </select><br>

            <button type="submit" name="add_room">Add Room</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Room ID</th>
                    <th>Room Name</th>
                    <th>Room Status</th>
                    <th>Room Capacity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php getRooms($conn); ?>
            </tbody>
        </table>
    </div>

    <script>
        // Your JavaScript code here
    </script>

</body>

</html>
