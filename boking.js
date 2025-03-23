document
  .getElementById("booking-form")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // Mencegah form agar tidak disubmit secara default

    // Mengambil nilai dari form
    const nama = document.getElementById("nama").value;
    const tanggal = document.getElementById("tanggal").value;
    const jam = document.getElementById("jam").value;
    const durasi = document.getElementById("durasi").value;

    // Membuat pesan konfirmasi
    const confirmationMessage = `  
        Terima kasih, ${nama}!  
        Anda telah berhasil memesan lapangan futsal pada:  
        Tanggal: ${tanggal}  
        Jam: ${jam}  
        Durasi: ${durasi} jam.  
    `;

    // Menampilkan pesan konfirmasi
    const messageDiv = document.getElementById("confirmation-message");
    messageDiv.innerText = confirmationMessage;
    messageDiv.classList.remove("hidden");

    // Reset form setelah submit
    this.reset();
  });
