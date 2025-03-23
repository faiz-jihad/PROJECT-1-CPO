<?php
session_start();
$conn = new mysqli("localhost", "root", "", "db_usercpo");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID pengguna dari sesi
$user_id = $_SESSION['user_id'] ?? null;
$fetch = ['Username' => 'Tamu'];

if ($user_id) {
    $stmt = $conn->prepare("SELECT Username FROM pelanggan WHERE id_pelanggan = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $fetch = $result->fetch_assoc();
    }
    $stmt->close();
}

// Proses Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: halamanlogin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Jadwal</title>
    <link rel="stylesheet" href="jadwal.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>

<body>
    <div class="container">
        <nav class="navbar">
            <img class="logo-cpo" src="images/logo-cpo.png" alt="Logo CPO">
            <button id="menu-button" class="menu-button">☰</button>
            <div class="navbar-right">
                <ul class="navbar-menu">
                    <li class="menu-item"><a href="landingpage.php">Beranda</a></li>
                    <li class="menu-item"><a href="halamanbooking.php">Pemesanan</a></li>
                    <li class="menu-item"><a href="notapenyewaan.php">Bukti Pesanan</a></li>
                </ul>
            </div>
            <a href="#"><img src="images/icon.jpeg" class="profile-pic" onclick="toggleMenu()"></a>
            <div class="sub-menu-wrap" id="subMenu">
                <div class="sub-menu">
                    <div class="user-info">
                        <img src="images/icon.jpeg">
                        <h3><?php echo htmlspecialchars($fetch['Username']); ?></h3>
                    </div>
                    <hr>
                    <a href="profileuser.php" class="sub-menu-link">
                        <img src="images/setting.png">
                        <p>Edit Profil</p>
                        <span>></span>
                    </a>
                    <a href="?logout=true" class="sub-menu-link">
                        <img src="images/logout.png">
                        <p>Logout</p>
                        <span>></span>
                    </a>
                </div>
            </div>
        </nav>

        <div id="sidebar" class="sidebar">
            <button id="close-button" class="close-button">&times;</button>
            <h2 class="sidebar-title">Menu</h2>
            <ul class="sidebar-menu">
                <li class="menu-item"><a href="landingpage.php">Beranda</a></li>
                <li class="menu-item"><a href="halamanbooking.php">Pemesanan</a></li>
                <li class="menu-item"><a href="notapenyewaan.php">Bukti Pemesanan</a></li>
            </ul>
        </div>
        <div id="overlay" class="overlay"></div>
    </div>

    <div  class="table-container">
        <div class="table">
            <h2>Jadwal yang Sudah Dipesan</h2>
            <table class="booking-table" border="1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Penyewa</th>
                        <th>Lapangan</th>
                        <th>Tanggal</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query = "SELECT username, no_lapangan, tanggal_transaksi, jam_mulai, jam_selesai FROM booking";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($tampildata = $result->fetch_assoc()) {
                            echo "
                            <tr>
                                <td>" . htmlspecialchars($no) . "</td>
                                <td>" . htmlspecialchars($tampildata['username']) . "</td>
                                <td>" . htmlspecialchars($tampildata['no_lapangan']) . "</td>
                                <td>" . htmlspecialchars($tampildata['tanggal_transaksi']) . "</td>
                                <td>" . htmlspecialchars($tampildata['jam_mulai']) . "</td>
                                <td>" . htmlspecialchars($tampildata['jam_selesai']) . "</td>
                            </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align: center;'>Tidak ada data jadwal tersedia</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="jadwal.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>

<?php
$conn->close();
?>