<?php
session_start();
require_once 'koneksi.php';


if (isset($_POST['login'])) {
    $phone = trim($_POST['Phone']);
    $password = trim($_POST['Password']);

    if (empty($phone) || empty($password)) {
        echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                swal({
                    title: 'Gagal!',
                    text: 'Nomor dan Password Wajib di isi',
                    icon: 'error',
                    button: 'OK'
                }).then((value) => {
                    window.location.href = 'halamanlogin.php';
                });
            });
            </script>";
        exit();
    }

    // Menggunakan Prepared Statement agar lebih aman
    $query = "SELECT id_pelanggan, Username, Phone, Password FROM pelanggan WHERE Phone = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Bandingkan password secara langsung (karena tanpa hash)
        if ($password === $user['Password']) { 
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $user['id_pelanggan'];
            $_SESSION['username'] = $user['Username'];

            header("Location: landingpage.php");
            exit();
        } else {
            echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                swal({
                    title: 'Password Salah!',
                    text: 'Password yang anda masukan salah',
                    icon: 'error',
                    button: 'OK'
                }).then((value) => {
                    window.location.href = 'halamanlogin.php';
                });
            });
            </script>";
        }
    } else {
        echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                swal({
                    title: 'No HP Salah!',
                    text: 'No HP tidak terdaftar!',
                    icon: 'error',
                    button: 'OK'
                }).then((value) => {
                    window.location.href = 'halamanlogin.php';
                });
            });
            </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
