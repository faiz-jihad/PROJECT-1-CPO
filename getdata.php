<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$database = "db_usercpo";

$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die(json_encode(["error" => "Koneksi gagal: " . $conn->connect_error]));
}

// Ambil transaksi terbaru dari tabel booking
$query = "SELECT * FROM booking ORDER BY no_transaksi DESC LIMIT 1";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(["error" => "Tidak ada data ditemukan"]);
}

$conn->close();
?>
