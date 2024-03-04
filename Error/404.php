<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('img/goku.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: 'Arial', sans-serif;
            text-align: center;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.7);
            width: 100%;
            margin: 20px 20px 20px 20px;
            padding: 20px 20px 20px 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 36px;
            color: #333;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            color: #666;
            margin-bottom: 20px;
        }

        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #f0000f;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #d44a85;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>404 - Page Not Found</h1>
        <p>The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
        <a href="/hotel-project">Situ Mau Ngapain? Sana Balik</a>
    </div>


    <script>
document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'F12' || e.keyCode === 123) {
        e.preventDefault();
    }
});
</script>
</body>

</html>
