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

// !== maksudnya kalau bukan , == artinya kalau iya

if ($_SESSION['user'] !== 'aditgaming105@gmail.com') {
    echo json_encode(["success" => false, "message" => "Admin access only. Go Out"]);
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
        
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden;
        }

        body{

            background: url(../img/beskye.jpg);
            background-repeat: no-repeat;
            background-size: cover;
        }

        .navbar {
            background-color: #4894FE;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-left: 40px;
            box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 999;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .logo span {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .profile {
            position: relative;
            margin-right: 30px;
        }

        .profile-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #fff;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            padding: 10px 20px 10px 20px;
            display: none;
        }

        .profile:hover .profile-menu {
            display: block;
        }

        .profile-menu a {
            display: block;
            text-decoration: none;
            color: #333;
            padding: 5px 10px;
            transition: all 0.3s ease;
        }

        .profile-menu a:hover {
            background-color: #f0f0f0;
        }

        .sidebar {
            width: 180px;
            background-color: #f0f0f0;
            box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
            padding-top: 20px;
            height: calc(100vh - 40px);
            position: fixed;
            top: 40px;
            left: 0;
            overflow-y: auto;
        }

        .sidebar a {
            display: block;
            text-decoration: none;
            color: #555;
            font-weight: bold;
            font-size: 18px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .sidebar a.active {
            background-color: #d3d3d3;
            color: #0077b6;
        }

        .sidebar a:hover {
            background-color: #d3d3d3;
            color: #0077b6;
        }

        .content {
            padding: 20px;
            margin-left: 200px;
            flex-grow: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        #content {
            font-family: Arial, sans-serif;
            font-size: 24px;
            color: #333;
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        @media screen and (max-width: 768px) {
            .sidebar {
                left: -200px;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div class="logo">
            <a href="#"><span style="color:#fff;">Admin</span></a>
        </div>
        <div class="profile">
            <span style="color: #fff;">Username</span>
            <div class="profile-menu">
                <a href="#">Profile</a><hr>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="sidebar">
        <a href="index.php" <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'class="active"' : ''; ?>>Beranda</a>
        <hr>
        <a href="status.php" <?php echo basename($_SERVER['PHP_SELF']) == 'status.php' ? 'class="active"' : ''; ?>>Roombooking</a>
        <!-- <hr> -->
        <!-- <a href="payment.php" <?php echo basename($_SERVER['PHP_SELF']) == 'payment.php' ? 'class="active"' : ''; ?>>Payment</a> -->
        <hr>
        <a href="transaction.php" <?php echo basename($_SERVER['PHP_SELF']) == 'transaction.php' ? 'class="active"' : ''; ?>>Transactions</a>
        <hr>
        <a href="room.php" <?php echo basename($_SERVER['PHP_SELF']) == 'room.php' ? 'class="active"': '';?>>Room</a>
        <hr>
    </div>

    <div class="content">
        <!-- isi content -->
        <div id="content"></div>
    </div>

    <script>

        const motivations = [
            "Jangan pernah menyerah, Berjuanglah! Mimpimu bisa menjadi kenyataan jika kamu terus berjuang!",
            "Ketika kamu merasa lelah, ingatlah mengapa kamu memulai. Semangat, Berjuanglah!",
            "Kegagalan adalah langkah awal menuju kesuksesan. Teruslah maju!",
            "Setiap hari adalah kesempatan baru untuk menjadi lebih baik dari sebelumnya.",
            "Tidak ada yang mustahil, Berjuanglah. Semua bisa kamu capai jika kamu berani bermimpi besar!",
            "Ketika kamu merasa putus asa, ingatlah betapa jauhnya kamu sudah sampai. Kamu bisa melakukannya!",
            "Teruslah maju! Impianmu ada di depanmu.",
            "Setiap langkah kecil yang kamu ambil akan membawamu lebih dekat pada tujuanmu.",
            "Terimalah tantangan sebagai peluang untuk bertumbuh.",
            "Percayalah pada dirimu sendiri, karena kamu mampu meraih kebesaran.",
            "Keberhasilan bukanlah tujuan akhir tetapi sebuah perjalanan. Nikmati setiap langkahnya.",
            "Tetaplah fokus, dan biarkan tekadmu membimbingmu menuju kesuksesan.",
            "Ketekunanmu akan membawamu meraih kemenangan.",
            "Jadilah tak kenal takut dalam mengejar apa yang membuatmu bersemangat.",
            "Percayalah pada proses, bahkan ketika terasa sulit.",
            "Kerja keras dan dedikasimu akan membuahkan hasil. Teruslah melangkah!",
            "Setiap kegagalan adalah awal dari kesuksesan berikutnya. Bangkitlah lebih kuat.",
            "Berjuanglah untuk kemajuan, bukan untuk kesempurnaan.",
            "Banggalah dengan pencapaianmu sejauh ini, dan percayalah pada potensimu yang tak terbatas.",
            "Batas keberhasilanmu hanya imajinasimu.",
            "Tantangan adalah peluang yang bersembunyi. Terimalah dengan keberanian.",
            "Ingatlah, bahwa setiap hari adalah kesempatan untuk menulis ulang ceritamu.",
            "Pola pikir positifmu akan membuka jalan menuju kesuksesanmu.",
            "Tetaplah menatap bintang-bintang, namun tetaplah berpijak pada tanah.",
            "Percayalah pada kekuatan mimpimu, dan kejarlah dengan gigih.",
            "Keberhasilan tidak ditentukan oleh berapa kali kamu jatuh, tetapi oleh berapa kali kamu bangkit.",
            "Tetaplah setia pada dirimu sendiri, dan biarkan keaslianmu bersinar terang.",
            "Dunia penuh dengan kemungkinan. Manfaatkan setiap kesempatan yang datang.",
            "Perjalananmu mungkin berat, tetapi begitu juga dirimu. Teruslah bergerak!",
            "Berani bermimpi besar, dan kerja keraslah untuk mewujudkannya.",
            "Fokuslah pada kemajuan, dan rayakan setiap kemenangan kecil di sepanjang jalan.",
            "Keuletanmu tidak memiliki batas. Teruslah bertahan!",
            "Kamu adalah arsitek takdirmu sendiri. Bangunlah dengan keberanian dan tekad.",
            "Percayalah pada kemampuanmu, karena kamu lebih kuat dari yang kamu kira.",
            "Tetaplah tegak, dan biarkan kepercayaanmu mendorongmu maju.",
            "Ingatlah, bahwa yang terbaik masih akan datang. Teruslah berusaha untuk kesempurnaan!",
            "It does not matter how slowly you go as long as you do not stop. - Confucius",
    "Hardships often prepare ordinary people for an extraordinary destiny. - C.S. Lewis",
    "Your limitation—it’s only your imagination.",
    "Push yourself, because no one else is going to do it for you.",
    "Great things never come from comfort zones.",
    "Dream it. Wish it. Do it.",
    "Success doesn’t just find you. You have to go out and get it.",
    "The harder you work for something, the greater you’ll feel when you achieve it.",
    "Dream bigger. Do bigger.",
    "Don’t stop when you’re tired. Stop when you’re done.",
    "Wake up with determination. Go to bed with satisfaction.",
    "Do something today that your future self will thank you for.",
    "Little things make big days.",
    "It’s going to be hard, but hard does not mean impossible.",
    "Don’t wait for opportunity. Create it.",
    "Sometimes we’re tested not to show our weaknesses, but to discover our strengths.",
    "The key to success is to focus on goals, not obstacles.",
    "Dream it. Believe it. Build it.",
    "Stay positive. Work hard. Make it happen.",
    "Prove them wrong.",
    "Success is not by chance, but by choice.",
    "Believe in yourself and all that you are. Know that there is something inside you that is greater than any obstacle.",
    "Challenges are what make life interesting. Overcoming them is what makes life meaningful.",
    "The only way to do great work is to love what you do.",
    "Success is not how high you have climbed, but how you make a positive difference to the world.",
    "Don’t limit your challenges. Challenge your limits.",
    "Be so good they can’t ignore you. - Steve Martin",
    "Success is not the key to happiness. Happiness is the key to success. If you love what you are doing, you will be successful. - Albert Schweitzer",
    "The only place where success comes before work is in the dictionary. - Vidal Sassoon",
    "Success is not in what you have, but who you are. - Bo Bennett",
    "Don't be pushed around by the fears in your mind. Be led by the dreams in your heart. - Roy T. Bennett",
    "The road to success and the road to failure are almost exactly the same. - Colin R. Davis",
    "Success is not just about making money. It's about making a difference. - Unknown",
    "The only limit to our realization of tomorrow will be our doubts of today. - Franklin D. Roosevelt",
    "You are never too old to set another goal or to dream a new dream. - C.S. Lewis"
        ];

        function getRandomMotivation() {
            const randomIndex = Math.floor(Math.random() * motivations.length);
            return motivations[randomIndex];
        }

        document.getElementById("content").innerText = getRandomMotivation();
    </script>

    <script>
        const sidebar = document.querySelector('.sidebar');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
        }
    </script>

</body>

</html>