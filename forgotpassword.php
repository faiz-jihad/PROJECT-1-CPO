<?php
session_start();
require 'config2.php'; // Koneksi ke database
require 'kirimsms.php'; // Panggil fungsi sendSMS()

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone']; // Ambil nomor telepon dari form

    // Cek apakah nomor telepon terdaftar
    $stmt = $pdo->prepare("SELECT id_pelanggan FROM pelanggan WHERE Phone = ?");
    $stmt->execute([$phone]);
    $user = $stmt->fetch();

    if ($user) {
        // Buat token unik
        $token = bin2hex(random_bytes(50));

        // Simpan token ke database
        $stmt = $pdo->prepare("INSERT INTO password_resets (Phone, token) VALUES (?, ?)");
        $stmt->execute([$phone, $token]);

        // Buat pesan reset password
        $resetLink = "http://yourwebsite.com/reset_password.php?token=$token";
        $message = "Gunakan tautan ini untuk reset password Anda: $resetLink";

        // Kirim SMS
        if (sendSMS($phone, $message)) {
            echo "Silakan cek SMS Anda untuk reset password.";
        } else {
            echo "Gagal mengirim SMS.";
        }
    } else {
        echo "Nomor telepon tidak ditemukan.";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi</title>
    <link rel="stylesheet" href="forgotpassword.css">
</head>
<body>
    <div class="container">
        <h2>Lupa Kata Sandi</h2>
        <p>Masukkan nomor telepon Anda untuk menerima SMS reset password.</p>
        <form action="forgotpassword.php" method="post">
            <input type="text" name="phone" placeholder="Masukkan nomor telepon" required>
            <button type="submit">Kirim SMS Reset</button>
        </form>
    </div>
</body>
</html>
