<?php
include 'koneksi.php';
session_start();


// cek login user
if (!isset($_SESSION['user_id'])) {
    echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                swal({
                    title: 'Warning!',
                    text: 'Harap Login Terlebih Dahulu',
                    icon: 'warning',
                    button: 'OK'
                }).then((value) => {
                    window.location.href = 'halamanlogin.php';
                });
            });
            </script>";
    exit();
}


// Default username untuk pengguna yang belum login
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "Tamu";


?>
<!DOCTYPE html>
<html lang="id" x-data="bookingLapangan()">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Lapangan</title>
    <link rel="stylesheet" href="halamanbooking.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    </script>
</head>

<body>
    <div class="container">
        <nav class="navbar">
            <img class="logo-cpo" src="logo-cpo.png" alt="">
            <button id="menu-button" class="menu-button">☰</button>
            <div class="navbar-right">
                <ul class="navbar-menu">
                    <li class="menu-item"><a href="landingpage.php">Beranda</a></li>
                    <li class="menu-item"><a href="">Pemesanan</a></li>
                    <li class="menu-item"><a href="notapenyewaan.php">Bukti Pesanan</a></li>
                    <li class="menu-item"><a href="jadwal.php">Jadwal</a></li>
                </ul>
            </div>
            <img src="icon.jpeg" class="profile-pic" onclick="toggleMenu()">
            <div class="sub-menu-wrap" id="subMenu">
                <div class="sub-menu">
                    <div class="user-info">
                        <img src="icon.jpeg">
                        <h3><?php echo $username; ?></h3>
                    </div>
                    <hr>

                    <a href="profileuser.php" class="sub-menu-link">
                        <img class="submenu-foto" src="setting.png">
                        <p>Edit Profile</p>
                        <span>></span>
                    </a>
                    <a href="logout.php" class="sub-menu-link">
                        <img class="submenu-foto" src="logout.png">
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
                <li class="menu-item"><a href="#">Pemesanan</a></li>
                <li class="menu-item"><a href="notapenyewaan.php">Bukti Pesanan</a></li>
                <li class="menu-item"><a href="jadwal.php">Jadwal</a></li>
            </ul>
        </div>

        <!-- Overlay -->
        <div id="overlay" class="overlay"></div>
    </div>

    <div class="booking-container">
        <div class="booking">
            <h1>Booking Lapangan</h1>

            <form action="booking.php" method="POST" id="booking">
                <label>Nama</label>
                <input type="text" name="id_pelanggan" x-model="nama" placeholder="masukan nama anda" required>

                <label>Nomor Telepon</label>
                <input type="text" name="nomorTelepon" x-model="nomorTelepon" placeholder="masukan nomer telepon" required>

                <label>Pilih Lapangan</label>
                <select name="no_lapangan" x-model="lapanganDipilih" required>
                    <option value="">-- Pilih Lapangan --</option>
                    <option value="1">Lapangan 1</option>
                    <option value="2">Lapangan 2</option>
                </select>

                <label>Tanggal Pemesanan</label>
                <input type="date" name="tanggal_transaksi" x-model="tanggalBooking" required>

                <label>Jam Mulai</label>
                <input type="time" name="jamMulai" x-model="jamMulai" @input="hitungJamSelesai()" required>

                <label>Durasi (jam)</label>
                <input type="number" name="durasi" x-model="durasi" min="1" @input="hitungJamSelesai()" required>

                <label>Jam Selesai</label>
                <input type="text" name="jamSelesai" x-model="jamSelesai" readonly>

                <div class="kategori-harga">
                    <p><strong>Kategori Harga</strong></p>
                    <ul>
                        <li>Siang (07:00 - 17:59) → Rp 35.000 / jam</li>
                        <li>Malam (18:00 - 23:59) → Rp 40.000 / jam</li>
                    </ul>
                </div>

                <div class="harga-container">
                    <p>Total Harga: <span x-text="formatRupiah(hitungTotalHarga())"></span></p>
                </div>
                <button type="submit" class="pay-btn disabled" id="pay-btn">Booking</button>
            </form>
            <p class="note">*note: pastikan datang sebelum waktu dimulai</p>
            <br>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("#booking");
            const paymentButton = document.querySelector("#pay-btn");
            const totalHargaSpan = document.querySelector("#total-harga");

            function hitungTotalHarga() {
                const jamMulai = form.querySelector("[name='jamMulai']").value;
                const durasi = parseInt(form.querySelector("[name='durasi']").value) || 0;
                if (!jamMulai || durasi <= 0) {
                    totalHargaSpan.textContent = "Rp 0";
                    return;
                }

                let jamMulaiInt = parseInt(jamMulai.split(":")[0]);
                let hargaPerJam = (jamMulaiInt >= 18) ? 40000 : 35000;
                let total = durasi * hargaPerJam;
                totalHargaSpan.textContent = `Rp ${total.toLocaleString("id-ID")}`;
            }

            function checkFormValidity() {
                let isValid = true;
                form.querySelectorAll("[required]").forEach(input => {
                    if (input.value.trim() === "") {
                        isValid = false;
                    }
                });

                paymentButton.disabled = !isValid;
                paymentButton.classList.toggle('disabled', !isValid);
            }

            form.addEventListener("input", function() {
                checkFormValidity();
                hitungTotalHarga();
            });

            checkFormValidity();
        });



        window.addEventListener("scroll", function() {
            var navbar = document.querySelector(".navbar");
            if (window.scrollY > 50) {
                navbar.classList.add("shrink");
            } else {
                navbar.classList.remove("shrink");
            }
        });

        // sub menu
        const subMenu = document.getElementById('subMenu');
        const profilePic = document.querySelector('.profile-pic');

        document.addEventListener('click', (event) => {
            if (event.target === profilePic) {
                subMenu.classList.toggle('open-menu');

            } else if (!subMenu.contains(event.target)) {
                subMenu.classList.remove('open-menu');
            }
        });

        // fungsi sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.getElementById('menu-button');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const closeButton = document.getElementById('close-button');

            // Buka sidebar
            menuButton.addEventListener('click', function() {
                sidebar.classList.add('open');
                overlay.classList.add('show');
            });

            // Tutup sidebar
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
            });

            closeButton.addEventListener('click', function() {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
            });
        });

        // fungsi booking
        function bookingLapangan() {
            return {
                nama: '',
                nomorTelepon: '',
                lapanganDipilih: '',
                tanggalBooking: '',
                jamMulai: '',
                jamSelesai: '',
                durasi: 1,

                hitungJamSelesai() {
                    if (this.jamMulai) {
                        let jamMulaiDate = new Date(`2023-01-01T${this.jamMulai}`);
                        jamMulaiDate.setHours(jamMulaiDate.getHours() + parseInt(this.durasi));
                        this.jamSelesai = jamMulaiDate.toTimeString().slice(0, 5);
                    }
                },

                hitungTotalHarga() {
                    if (!this.jamMulai) return 0;

                    let jamMulaiInt = parseInt(this.jamMulai.split(":")[0]);
                    let hargaPerJam = (jamMulaiInt >= 18) ? 40000 : 35000;

                    return this.durasi * hargaPerJam;
                },

                formatRupiah(angka) {
                    return new Intl.NumberFormat("id-ID", {
                        style: "currency",
                        currency: "IDR"
                    }).format(angka);
                },

                konfirmasiBooking() {
                    if (!this.nama || !this.nomorTelepon || !this.lapanganDipilih || !this.tanggalBooking || !this.jamMulai || !this.durasi) {
                        swal("Gagal!", "Semua kolom harus diisi!", "error");
                        return;
                    }

                    let formData = new FormData();
                    formData.append('nama', this.nama);
                    formData.append('nomorTelepon', this.nomorTelepon);
                    formData.append('lapanganDipilih', this.lapanganDipilih);
                    formData.append('tanggalBooking', this.tanggalBooking);
                    formData.append('jamMulai', this.jamMulai);
                    formData.append('durasi', this.durasi);

                    fetch('booking.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.text())
                        .then(result => {
                            if (result.includes("sudah dibooking")) {
                                swal("Gagal!", result, "error");
                            } else {
                                swal("Berhasil!", "Booking berhasil dibuat!", "success")
                                    .then(() => {
                                        window.location.href = 'notapenyewaan.php';
                                    });
                            }
                        })
                        .catch(error => {
                            swal("Error!", "Terjadi kesalahan, coba lagi.", "error");
                            console.error('Error:', error);
                        });

                }
            };
        }
    </script>
</body>

</html>