<?php
include 'koneksi.php';

if (isset($_POST['daftar'])) {

    $Nama = filter_var(trim($_POST['Nama']));
    $Username = filter_var(trim($_POST['Username']));
    $Phone = filter_var(trim($_POST['Phone']));
    $Password = $_POST['Password'];

    if (!preg_match('/^[0-9]{10,15}$/', $Phone)) {
        echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                swal({
                    title: 'Gagal!',
                    text: 'No HP tidak Valid!',
                    icon: 'error',
                    button: 'OK'
                }).then((value) => {
                    window.location.href = 'halamanregister.php';
                });
            });
            </script>";
        exit();
    }

    // Check if phone number is already in use
    $checkPhone = "SELECT * FROM pelanggan WHERE Phone = ?";
    $stmt = $conn->prepare($checkPhone);
    $stmt->bind_param("s", $Phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                swal({
                    title: 'Gagal!',
                    text: 'No HP Telah Di Gunakan!',
                    icon: 'error',
                    button: 'OK'
                }).then((value) => {
                    window.location.href = 'halamanregister.php';
                });
            });
            </script>";
        exit();
    } else {
        // Insert new user
        $insertQuery = "INSERT INTO pelanggan (Nama, Username, Phone, Password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssss", $Nama, $Username, $Phone, $Password);

        if ($stmt->execute()) {
            header("Location: halamanlogin.php"); // Redirect to landing page
            exit();
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='register.php';</script>";
        }
    }
}
?>
