<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DynaCareSIS - log in</title>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins';
        }
        body {
            font-family: 'Poppins';
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: none; /* Remove the image background */
            }

            .background-video {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            top: 0;
            left: 0;
        }

        .container {
            display: flex;
            width: 100%;
            max-width: 900px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }
        .left {
            background-color: white;
            padding: 60px;
            text-align: center;
            flex: 1;
        }
        .left img {
            width: 120px;
            margin-bottom: 30px;
        }
        .left h1, .left h2 {
            font-family: 'Poppins';
        }
        .left h1 {
            font-size: 80px;
            margin: 30px 0 15px;
            color:rgb(0, 0, 0);
        }
        .left h2 {
            font-size: 40px;
            color:rgb(0, 0, 0); !important
            margin: 0 0 30px;
        }
        .right {
            background-color: rgb(255, 179, 208);
            padding: 60px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .right h2 {
            color:rgb(0, 0, 0);
            margin-bottom: 30px;
            font-size: 28px;
        }
        .right input[type="text"], .right input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 15px 0;
            border: none;
            border-radius: 6px;
            font-size: 18px;
        }
        .right button {
            padding: 15px;
            background-color: white;
            border: none;
            border-radius: 6px;
            color:rgb(0, 0, 0);
            font-size: 18px;
            cursor: pointer;
        }
        .right button:hover {
            background-color:rgb(255, 158, 203);
        }
        .show-password {
            margin-top: 10px;
            color:rgb(0, 0, 0);
            display: block;
            font-size: 16px;
        }
        .alert {
            background-color:rgb(255, 99, 88);
            color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-size: 18px;
        }
    </style>
</head>
<body>

    <video autoplay muted loop id="background-video" class="background-video">
        <source src="assets/video/DYNACARE.mp4" type="video/mp4">
    </video>

    <!-- Your content goes here -->
    <div class="container">
        <div class="left">
            <h1 style="margin-top: 60px; font-weight: 900;">  DCSIS  </h1>
            <hr>
            <h2 style="margin-top: -10px;">HEALTH SOLUTION</h2>
        </div>
        <div class="right">
            <h2 style="letter-spacing: 2px; font-weight: 800;">LOGIN</h2>
            <div class="pt-4 pb-2">
                <?php
                session_start();
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                ?>
            </div>
            <form class="g-3 needs-validation row" novalidate method="POST" action="php/login.php">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span class="show-password"><input type="checkbox" onclick="togglePassword()"> Show Password</span>
                <button type="submit" style="margin-top: 20px;" style="letter-spacing: 6px; font-weight: 600;"><b>LOGIN<b></button>
            </form>
        </div>
    </div>
    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>
</body>
</html>
