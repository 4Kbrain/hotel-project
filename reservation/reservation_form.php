<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../session/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
    }

    .top-navbar {
      background-color: #f0f0f0;
      padding: 10px;
      text-align: center;
      box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
    }

    .top-navbar a {
      text-decoration: none;
      color: #555;
      font-weight: bold;
      font-size: 18px;
    }

    .bottom-navbar {
      position: fixed;
      bottom: 0;
      width: 100%;
      background-color: #f0f0f0;
      display: flex;
      justify-content: space-around;
      padding: 10px;
      box-shadow: 0px -1px 5px rgba(0, 0, 0, 0.1);
    }

    .navbar-item {
      text-align: center;
      color: #555;
      text-decoration: none;
      padding: 8px;
      border-radius: 8px;
    }

    .navbar-item:hover {
      background-color: #ddd;
    }

    .navbar-item.active {
      font-weight: 900;
      background-color: #0077b6;
      color: white;
      font-weight: bold;
    }

    .navbar-item.active:after {
      background-color: #0077b6;
      color: white;
    }

    .container {
            max-width: 400px;
            margin: 0 auto;
        }

    input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
    }

    button {
        width: 100%;
        padding: 10px;
        background-color: #0077b6;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        }

    #totalCost {
        text-align:center;
        font-size: 18px;
        color: #0077b6;
        margin-bottom: 10px;
        }

    #costBreakdown {
        text-align:center;
        font-size: 14px;
        color: #555;
        margin-bottom: 10px;
        }

    #numDays {
        text-align:center;
        font-size: 16px;
        color: #555;
        }

        h2{
            text-align:center;
        }
  </style>
</head>
<body>

  <div class="top-navbar">
    <a href="../index.php">Back to home</a>
  </div>

  <div class="container">
        <h2>Reservation Form</h2>
        <form action="process_reservation.php" method="post">
            <input type="text" name="fname" placeholder="First Name" required>
            <input type="text" name="lname" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <select name="troom" required>
                <option value="" disabled selected>Select Room Type</option>
                <option value="Superior Room">Superior Room</option>
                <option value="Deluxe Room">Deluxe Room</option>
                <option value="Guest House">Guest House</option>
                <option value="Single Room">Single Room</option>
            </select>
            <select name="bed" required>
                <option value="" disabled selected>Select Bedding Type</option>
                <option value="Single">Single</option>
                <option value="Double">Double</option>
                <option value="Triple">Triple</option>
                <option value="Quad">Quad</option>
                <option value="None">None</option>
            </select>
            <select name="nroom" required>
                <option value="" disabled selected>Select Number of Rooms</option>
                <?php
                for ($i = 1; $i <= 7; $i++) {
                    echo "<option value='$i'>$i</option>";
                }
                ?>
            </select>
            <input type="date" name="cin" placeholder="Check-In" required>
            <input type="date" name="cout" placeholder="Check-Out" required>
            <div id="totalCost">Total Cost: $0</div>
            <div id="costBreakdown">Room Cost: $0 | Bed Cost: $0</div>
            <div id="numDays">Number of Days: 0</div>

            <button type="submit" name="submit">Submit</button>
        </form>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const roomTypeSelect = document.querySelector('select[name="troom"]');
                const bedTypeSelect = document.querySelector('select[name="bed"]');
                const numRoomsSelect = document.querySelector('select[name="nroom"]');
                const cinInput = document.querySelector('input[name="cin"]');
                const coutInput = document.querySelector('input[name="cout"]');
                const totalCostElement = document.getElementById('totalCost');
                const costBreakdownElement = document.getElementById('costBreakdown');
                const numDaysElement = document.getElementById('numDays');

                const reservationForm = document.querySelector('form');

    reservationForm.addEventListener('submit', function(event) {
        const cinDate = new Date(cinInput.value);
        const coutDate = new Date(coutInput.value);

        if (coutDate <= cinDate) {
            event.preventDefault(); // Prevent form submission
            alert("Check-out date must be after check-in date.");
            return false; // Stop further processing
        }
    });
                const calculateTotalCostAndDays = () => {
    const roomTypeCosts = {
        'Superior Room': 100,
        'Deluxe Room': 150,
        'Guest House': 200,
        'Single Room': 80,
    };

    const roomType = roomTypeSelect.value;
    const bedType = bedTypeSelect.value;
    const numRooms = numRoomsSelect.value;
    const cinDate = new Date(cinInput.value);
    const coutDate = new Date(coutInput.value);

    const roomCost = roomTypeCosts[roomType] || 0;
    const bedCost = bedType === 'None' ? 0 : 20;
    
    let numDays = Math.ceil((coutDate - cinDate) / (1000 * 60 * 60 * 24));

    numDays = numDays < 0 ? 0 : numDays;

    const totalCost = (roomCost + bedCost) * numRooms * numDays;
    const roomCostBreakdown = `Room Cost: $${roomCost * numRooms * numDays}`;
    const bedCostBreakdown = `Bed Cost: $${bedCost * numRooms * numDays}`;

    totalCostElement.textContent = `Total Cost: $${totalCost}`;
    costBreakdownElement.textContent = `${roomCostBreakdown} | ${bedCostBreakdown}`;
    numDaysElement.textContent = `Number of Days: ${numDays}`;
};



                calculateTotalCostAndDays();

                roomTypeSelect.addEventListener('change', calculateTotalCostAndDays);
                bedTypeSelect.addEventListener('change', calculateTotalCostAndDays);
                numRoomsSelect.addEventListener('change', calculateTotalCostAndDays);
                cinInput.addEventListener('change', calculateTotalCostAndDays);
                coutInput.addEventListener('change', calculateTotalCostAndDays);
            });
        </script>
    </div>
    
    
  <div class="bottom-navbar">
    <a href="#" class="navbar-item-active">Reservation</a>
    <span class="navbar-divider">|</span>
    <a href="myreservation.php" class="navbar-item">My Reservation</a>
  </div>

</body>
</html>
