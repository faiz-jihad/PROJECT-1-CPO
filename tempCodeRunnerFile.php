<?php  
session_start();
include 'koneksi.php';

if (isset($_COOKIE['cookie_Phone'])) {
    $cookie_Phone = $_COOKIE['cookie_Phone'];
    $cookie_Password = $_COOKIE['cookie_Password'];

    $stmt = $koneksi->prepare("SELECT * FROM pelanggan WHERE Phone = ?");
    $stmt->bind_param("s", $cookie_Phone);
    $stmt->execute();
    $result = $stmt->get_result();
    $r1 = $result->fetch_assoc();

    if ($r1 && password_verify($cookie_Password, $r1['Password'])) {
        $_SESSION['session_phone'] = $cookie_Phone;
        $_SESSION['session_password'] = $cookie_Password;
    }
}

if (isset($_SESSION['session_phone'])) {
    echo "<script>window.location.href='landingpage.php';</script>";
    exit();
}

$err = ''; // Initialize error variable

if (isset($_POST['login'])) {
    $Phone = $_POST['Phone'];
    $Password = $_POST['Password'];
    $Rememberme = isset($_POST['Rememberme']); // Check if Rememberme is set

    if ($Phone == '' || $Password == '') {
        $err .= '<li> Silahkan isi nomor hp dan password terlebih dahulu</li>';
    } else {
        $stmt = $koneksi->prepare("SELECT * FROM pelanggan WHERE Phone = ?");
        $stmt->bind_param("s", $Phone);
        $stmt->execute();
        $result = $stmt->get_result();
        $r1 = $result->fetch_assoc();

        if (!$r1) {
            $err .= '<li> Nomor hp tidak terdaftar</li>';
        } elseif (!password_verify($Password, $r1['Password'])) {
            $err .= '<li> Password salah</li>';
        }

        if (empty($err)) {
            $_SESSION['session_phone'] = $Phone;
            $_SESSION['session_password'] = $Password;

            if ($Rememberme) {
                setcookie("cookie_Phone", $Phone, time() + 60 * 60 * 24 * 30); // 30 days
                setcookie("cookie_Password", $Password, time() + 60 * 60 * 24 * 30); // 30 days
            }
            echo "<script>window.location.href='landingpage.php';</script>";
            exit();
        }
    }
}
?>