<?php
include 'koneksi.php';
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                swal('Warning!', 'Harap Login Terlebih Dahulu!', 'warning')
                .then(() => { window.location.href = 'halamanlogin.php'; });
            });
         </script>";
    exit();
}

$id_pelanggan = $_SESSION['user_id'];
$nomor_telepon = $_POST['nomorTelepon'] ?? '';
$no_lapangan = $_POST['no_lapangan'] ?? '';
$tanggal_transaksi = $_POST['tanggal_transaksi'] ?? '';
$jam_mulai = $_POST['jamMulai'] ?? '';
$durasi = isset($_POST['durasi']) ? intval($_POST['durasi']) : 0;
$id_admin = NULL;
$no_transaksi = uniqid('TRX_');

// Validasi input wajib
if (empty($nomor_telepon) || empty($no_lapangan) || empty($tanggal_transaksi) || empty($jam_mulai) || $durasi <= 0) {
    echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                swal('Error!', 'Semua kolom harus diisi!', 'error')
                .then(() => { window.location.href = 'halamanbooking.php'; });
            });
            </script>";
    exit();
}

// Hitung jam selesai
$jam_selesai = date("H:i:s", strtotime($jam_mulai) + ($durasi * 3600));

// Cek apakah ada booking di jam yang sama
$query = "SELECT COUNT(*) FROM booking WHERE no_lapangan = ? AND tanggal_transaksi = ? 
          AND ((jam_mulai < ? AND jam_selesai > ?) OR (jam_mulai < ? AND jam_selesai > ?) OR (jam_mulai >= ? AND jam_selesai <= ?))";
$cek_booking = $conn->prepare($query);
$cek_booking->bind_param("ssssssss", $no_lapangan, $tanggal_transaksi, $jam_selesai, $jam_mulai, $jam_mulai, $jam_selesai, $jam_mulai, $jam_selesai);
$cek_booking->execute();
$cek_booking->bind_result($jumlah_booking);
$cek_booking->fetch();
$cek_booking->close();

if ($jumlah_booking > 0) {
    echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                swal('Error!', 'Lapangan sudah dibooking di jam tersebut!', 'error')
                .then(() => { window.location.href = 'halamanbooking.php'; });
            });
         </script>";
    exit();
}

// Ambil username berdasarkan user_id
$cek_pelanggan = $conn->prepare("SELECT username FROM pelanggan WHERE id_pelanggan = ?");
$cek_pelanggan->bind_param("s", $id_pelanggan);
$cek_pelanggan->execute();
$cek_pelanggan->bind_result($username);
$cek_pelanggan->fetch();
$cek_pelanggan->close();

if (empty($username)) {
    echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                swal('Warning!', 'Username Tidak Ditemukan', 'warning')
                .then(() => { window.location.href = 'halamanbooking.php'; });
            });
         </script>";
    exit();
}

// Simpan booking ke database
$stmt = $conn->prepare("INSERT INTO booking (no_transaksi, id_pelanggan, username, nomor_telepon, no_lapangan, tanggal_transaksi, jam_mulai, durasi, jam_selesai, idAdmin) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssi", $no_transaksi, $id_pelanggan, $username, $nomor_telepon, $no_lapangan, $tanggal_transaksi, $jam_mulai, $durasi, $jam_selesai, $id_admin);

if ($stmt->execute()) {
    echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                swal('Berhasil!', 'Booking Berhasil', 'success')
                .then(() => { window.location.href = 'notapenyewaan.php'; });
            });
        </script>";
} else {
    $error_message = htmlspecialchars($stmt->error);
    echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                swal('Error!', 'Terjadi kesalahan saat booking: $error_message', 'error')
                .then(() => { window.location.href = 'halamanbooking.php'; });
            });
         </script>";
}

$stmt->close();
$conn->close();
