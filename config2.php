<?php
// Konfigurasi database
$host = "localhost";  // Sesuaikan dengan server database Anda
$dbname = "db_usercpo"; // Ganti dengan nama database Anda
$username = "root";  // Biasanya "root" jika menggunakan Laragon/XAMPP
$password = "";  // Kosongkan jika tanpa password

try {
    // Membuat koneksi menggunakan PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
