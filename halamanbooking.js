function bookingLapangan() {
  return {
      hargaSiang: 35000,
      hargaMalam: 40000,
      hargaLapangan: 35000,
      bookings: JSON.parse(localStorage.getItem('bookings')) || { 1: [], 2: [] },
      nomorTelepon: '',
      nama: '',
      lapanganDipilih: '',
      tanggalBooking: '',
      jamMulai: '',
      durasi: '1',
      jamSelesai: '',

      hitungJamSelesai() {
          if (this.jamMulai) {
              let [jam, menit] = this.jamMulai.split(':').map(Number);
              jam += parseInt(this.durasi);
              if (jam >= 24) jam -= 24;
              this.jamSelesai = `${String(jam).padStart(2, '0')}:${String(menit).padStart(2, '0')}`;

              let jamBooking = parseInt(this.jamMulai.split(':')[0]);
              this.hargaLapangan = (jamBooking >= 6 && jamBooking < 18) ? this.hargaSiang : this.hargaMalam;
          }
      },

      isSlotAvailable(jamMulai, jamSelesai, tanggal, lapangan) {
          return !this.bookings[lapangan].some(booking => {
              return booking.tanggal === tanggal && (jamMulai < booking.jamSelesai && jamSelesai > booking.jamMulai);
          });
      },

      isValidPhoneNumber(number) {
          const phoneRegex = /^[0-9]{10,15}$/;
          return phoneRegex.test(number);
      },

      isValidBooking() {
          const today = new Date();
          today.setHours(0, 0, 0, 0);
          const bookingDate = new Date(this.tanggalBooking);

          if (bookingDate < today) {
              alert("Tidak bisa melakukan booking untuk tanggal yang sudah lewat!");
              return false;
          }

          if (!this.jamMulai) {
              alert("Harap pilih jam mulai!");
              return false;
          }

          const [jam, menit] = this.jamMulai.split(':').map(Number);
          const bookingDateTime = new Date(this.tanggalBooking);
          bookingDateTime.setHours(jam, menit);

          if (bookingDateTime <= new Date()) {
              alert("Tidak bisa melakukan booking untuk waktu yang sudah lewat!");
              return false;
          }

          if (!this.isValidPhoneNumber(this.nomorTelepon)) {
              alert("Masukkan nomor telepon yang valid!");
              return false;
          }

          return true;
      },

      konfirmasiBooking() {
          if (!this.nama || !this.nomorTelepon || !this.jamMulai || !this.lapanganDipilih || !this.tanggalBooking) {
              swal('Gagal', 'Mohon lengkapi semua data!', 'error');
              return;
          }

          if (!this.isValidBooking()) {
              return;
          }

          this.hitungJamSelesai();
          
          if (!this.isSlotAvailable(this.jamMulai, this.jamSelesai, this.tanggalBooking, this.lapanganDipilih)) {
              alert("Waktu yang dipilih sudah dibooking! Pilih waktu lain.");
              return;
          }

          this.bookings[this.lapanganDipilih].push({
              nama: this.nama,
              tanggal: this.tanggalBooking,
              jamMulai: this.jamMulai,
              jamSelesai: this.jamSelesai,
          });

          localStorage.setItem("bookings", JSON.stringify(this.bookings));
          swal("Booking Berhasil", `Lapangan ${this.lapanganDipilih} berhasil dipesan!`, "success");
          setTimeout(() => {
              window.location.href = "jadwal.php";
          }, 1000);
      },
  };
}

// payment button

const paymentButton = document.getElementById(".pay-btn");
paymentButton.disabled = true;



// Sidebar
document.addEventListener("DOMContentLoaded", function () {
  const menuButton = document.getElementById("menu-button");
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("overlay");
  const closeButton = document.getElementById("close-button");

  // Buka sidebar
  menuButton.addEventListener("click", function () {
      sidebar.classList.add("open");
      overlay.classList.add("show");
  });

  // Tutup sidebar
  overlay.addEventListener("click", function () {
      sidebar.classList.remove("open");
      overlay.classList.remove("show");
  });

  document.querySelectorAll(".card-title").forEach((item) => {
      item.addEventListener("click", function () {
          const selectedField = this.textContent;
          localStorage.setItem("selectedField", selectedField);
          window.location.href = "#";
      });
  });
});

// Sub Menu
let subMenu = document.getElementById("subMenu");

function toggleMenu() {
  subMenu.classList.toggle("open-menu");
}

