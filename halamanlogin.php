<?php
session_start();
if (isset($_SESSION['login'])) {
    header("location:landingpage.php");
};
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>halaman login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css">


    <link rel="stylesheet" href="halamanlogin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>

<body>
    <div data-aos="flip-left"
             data-aos-easing="ease-out-cubic"
             data-aos-duration="4000" class="wrapper">
        <form action="login.php" method="POST" name="login" id="loginForm">

            <h1>Masuk</h1>
            <div class="input-box">
                <input type="text" name="Phone" id="phone" placeholder="Phone" required pattern="[0-9]{10,15}">

                <i class='bx bxs-user'></i>
            </div>

            <div class="input-box">
                <input type="password" name="Password" id="password" placeholder="Password" required autocomplete="off">

                <i class='bx bxs-lock-alt' id="toggleButton" style="cursor: pointer;"></i>
            </div>

            <div class="container">
                <input type="checkbox" name="Rememberme" id="cbx" style="display: none;">
                <label for="cbx" class="check">
                    <svg width="18px" height="18px" viewBox="0 0 18 18">
                        <path d="M1,9 L1,3.5 C1,2 2,1 3.5,1 L14.5,1 C16,1 17,2 17,3.5 L17,14.5 C17,16 16,17 14.5,17 L3.5,17 C2,17 1,16 1,14.5 L1,9 Z"></path>
                        <polyline points="1 9 7 14 15 4"></polyline>
                    </svg>
                </label>Remember Me
            </div>

            <button type="submit" name="login" class="button">Masuk</button>

            <div class="register-link">
                <p><a href="forgot_password.php">Lupa kata sandi?</a></p>
                <p>Tidak punya akun?<a href="halamanregister.php"> Daftar</a></p>
            </div>
        </form>
    </div>
    <script>
        const toggleButton = document.getElementById('toggleButton');
        const passwordField = document.getElementById('password');

        toggleButton.addEventListener('click', () => {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
        });
    </script>
    <script>
        AOS.init();
    </script>
</body>

</html>