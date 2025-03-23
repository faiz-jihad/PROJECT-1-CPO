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
    <title>Halaman Register</title>
    <link rel="stylesheet" href="boxicons.min.css">
    <link rel="stylesheet" href="halamanlogin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>

<body>
    <div data-aos="flip-left"
             data-aos-easing="ease-out-cubic"
             data-aos-duration="4000" class="wrapper">
        <form method="post" action="register.php" id="registerForm">
            <h1>Register</h1>

            <div class="input-box">
                <input type="text" name="Nama" id="fullname" placeholder="Nama Lengkap" required>
                <i class='bx bxs-user'></i>
            </div>

            <div class="input-box">
                <input type="text" id="username" name="Username" placeholder="Username" required>
                <i class='bx bxs-user'></i>
            </div>

            <div class="input-box">
                <input type="tel" id="phone" name="Phone" placeholder="Nomor Telepon" required>
                <i class='bx bxs-phone'></i>
            </div>

            <div class="input-box">
                <input type="password" name="Password" id="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt' id="toggleButton" style="cursor: pointer;"></i>
            </div>

            <button type="submit" name="daftar" class="buton">Daftar</button>

            <div class="register-link">
                <p>Sudah punya akun? <a href="halamanlogin.php">Login</a></p>
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