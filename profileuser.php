<?php
ob_start(); // Mulai output buffering
include 'koneksi.php';
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('Anda harus login terlebih dahulu!');
            window.location.href='halamanlogin.php';
          </script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
$query = "SELECT * FROM pelanggan WHERE id_pelanggan = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $fetch = $result->fetch_assoc();
} else {
    echo "<script>
            alert('Data pengguna tidak ditemukan!');
            window.location.href='halamanlogin.php';
          </script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $update_nama = $_POST['update_nama'];
    $update_username = $_POST['update_username'];
    $update_phone = $_POST['update_phone'];
    $update_password = $_POST['update_password'];

    // Cek apakah password diisi
    if (!empty($update_password)) {
        // Jika password diisi, update langsung TANPA HASH
        $query = "UPDATE pelanggan SET Nama = ?, Username = ?, Phone = ?, Password = ? WHERE id_pelanggan = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $update_nama, $update_username, $update_phone, $update_password, $user_id);
    } else {
        // Jika password tidak diubah, update tanpa menyentuh kolom password
        $query = "UPDATE pelanggan SET Nama = ?, Username = ?, Phone = ? WHERE id_pelanggan = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $update_nama, $update_username, $update_phone, $user_id);
    }

    // Jalankan query update
    if ($stmt->execute()) {
        echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                swal({
                    title: 'Sukses!',
                    text: 'Data berhasil diperbarui!',
                    icon: 'success',
                    button: 'OK'
                }).then((value) => {
                    window.location.href = 'landingpage.php';
                });
            });
            </script>";
    } else {
        echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                swal({
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan: " . $stmt->error . "',
                    icon: 'error',
                    button: 'Coba Lagi'
                });
            });
            </script>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="profileuser.css">
    <style>
        /* Agar checkbox dan label sejajar */
        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="profile-box">
            <h2>Profil Pengguna</h2>
            <form action="profileuser.php" method="post">
                <div class="input-group">
                    <label>Nama Lengkap:</label>
                    <input type="text" name="update_nama" value="<?php echo htmlspecialchars($fetch['Nama']); ?>" required>
                </div>

                <div class="input-group">
                    <label>Username:</label>
                    <input type="text" name="update_username" value="<?= htmlspecialchars($fetch['Username']); ?>" required>
                </div>

                <div class="input-group">
                    <label>Nomor HP:</label>
                    <input type="text" name="update_phone" value="<?= htmlspecialchars($fetch['Phone']); ?>" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" id="password" name="update_password" value="<?= htmlspecialchars($fetch['Password']); ?>">
                </div>

                <div class="checkbox-container">
                    <input type="checkbox" id="togglePassword">
                    <label for="togglePassword">Tampilkan Password</label>
                </div>

                <button type="submit" class="btn">Simpan Perubahan & Kembali</button>
            </form>
        </div>
    </div>

    <script>
        // Tampilkan atau sembunyikan password
        document.getElementById('togglePassword').addEventListener('change', function() {
            const passwordInput = document.getElementById('password');
            passwordInput.type = this.checked ? 'text' : 'password';
        });

        function checkScreenSize() {
            if (window.innerWidth <= 768) { // Jika layar HP (≤ 768px)
                document.querySelector('.container').classList.add('show');
            } else {
                document.querySelector('.container').classList.add('show');
            }
        }

        // Cek ukuran layar saat halaman dimuat
        document.addEventListener("DOMContentLoaded", checkScreenSize);

        // Cek ukuran layar saat jendela diubah
        window.addEventListener("resize", checkScreenSize);
    </script>

</body>

</html>