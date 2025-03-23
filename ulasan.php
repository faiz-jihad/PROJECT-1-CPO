<?php  
// Konfigurasi koneksi basis data  
$servername = "localhost"; // atau alamat server Anda  
$username = "root"; // ganti dengan username database Anda  
$password = ""; // ganti dengan password database Anda  
$dbname = "db_usercpo"; // ganti dengan nama database yang Anda buat  

// Membuat koneksi  
$conn = new mysqli($servername, $username, $password, $dbname);  

// Memeriksa koneksi  
if ($conn->connect_error) {  
    die("Koneksi gagal: " . $conn->connect_error);  
}  

// Memeriksa apakah metode yang digunakan adalah POST  
if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    // Mengambil dan melakukan sanitasi data  
    $komentar = htmlspecialchars(trim($_POST['komentar']));  

    if (!empty($komentar)) {  
        // Menyimpan data ke dalam tabel ulasan  
        $stmt = $conn->prepare("INSERT INTO ulasan (komentar) VALUES (?)");
        $stmt->bind_param("s", $komentar);

        if ($stmt->execute() && $stmt->affected_rows > 0) {  
            // Redirect ke landingpage.php setelah sukses
            header("Location: landingpage.php?success=1");
            exit();
        } else {  
            // Redirect ke landingpage.php dengan error
            header("Location: landingpage.php?error=1");
            exit();
        }  
    } else {
        // Jika komentar kosong, redirect dengan pesan error
        header("Location: landingpage.php?error=empty");
        exit();
    }
} else {  
    // Jika bukan metode POST, redirect
    header("Location: landingpage.php");  
    exit();  
}  

// Menutup koneksi  
$conn->close();  
?>
