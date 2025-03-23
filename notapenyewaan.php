<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: inherit;
            /* Pakai background asli */
            filter: blur(10px);
            /* Blur background */
            z-index: -1;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: url('lapangan_CPO.jpeg') no-repeat;
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px;
            max-width: 100%;
            display: none;
        }

        h2 {
            font-size: 22px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        #nota {
            border-top: 3px solid #600000;
            margin-top: 15px;
            padding-top: 15px;
            text-align: left;
        }

        p {
            margin: 8px 0;
            font-size: 15px;
            color: #555;
            line-height: 1.4;
        }

        b {
            color: #222;
        }

        #qrcode {
            margin-top: 15px;
            display: flex;
            justify-content: center;
            padding: 10px;
            border: 1px dashed #600000;
            border-radius: 8px;
            background: #f9f9f9;
        }

        .button-container {
            margin-top: 15px;
            text-align: center;
        }

        button {
            background: #600000;
            color: white;
            border: none;
            padding: 12px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: bold;
            transition: all 0.3s ease;
            width: 200px;
            margin: 10px;
        }

        button:hover {
            background: #800000;
            box-shadow: 0 4px 10px rgba(96, 0, 0, 0.3);
            transform: scale(1.05);
        }

        @media (max-width: 600px) {
            .container {
                width: 100%;
            }
        }

        @media (max-width: 600px) {
            .container {
                width: 90%;
                padding: 15px;
            }

            #nota p {
                font-size: 14px;
            }

            button {
                width: 100%;
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container" id="container">
        <h2>Bukti Pembayaran</h2>
        <div id="nota">
            <h3></h3>
            <p><b>Nama Penyewa:</b> <span id="outNama"></span></p>
            <p><b>Nomor Transaksi:</b> <span id="outTransaksi"></span></p>
            <p><b>Nomor Telepon:</b> <span id="outTelepon"></span></p>
            <p><b>Tanggal Transaksi:</b> <span id="outTanggal"></span></p>
            <p><b>Nomor Lapangan:</b> <span id="outLapangan"></span></p>
            <p><b>Jam Mulai:</b> <span id="outJamMulai"></span></p>
            <p><b>Jam Selesai:</b> <span id="outJamSelesai"></span></p>
            <p><b>Durasi:</b> <span id="outDurasi"></span></p>
            <div id="qrcode"></div>
        </div>
    </div>
    <div class="button-container">
        <button onclick="cetakJPG()">Cetak JPG</button>
        <a href="landingpage.php"><button>Lanjut</button></a>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch("getData.php")
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak Ada Transaksi',
                            text: data.error,
                            confirmButtonText: 'Pesan Sekarang'
                        }).then(() => {
                            window.location.href = "halamanbooking.php";
                        });
                        return;
                    }

                    let container = document.getElementById('container');
                    if (container) {
                        container.style.display = 'block';
                    }

                    // Masukkan data ke dalam bukti pembayaran
                    let fields = [{
                            id: 'outNama',
                            value: data.username
                        },
                        {
                            id: 'outTransaksi',
                            value: data.no_transaksi
                        },
                        {
                            id: 'outTelepon',
                            value: data.nomor_telepon
                        },
                        {
                            id: 'outTanggal',
                            value: data.tanggal_transaksi
                        },
                        {
                            id: 'outLapangan',
                            value: data.no_lapangan
                        },
                        {
                            id: 'outJamMulai',
                            value: data.jam_mulai
                        },
                        {
                            id: 'outJamSelesai',
                            value: data.jam_selesai
                        },
                        {
                            id: 'outDurasi',
                            value: data.durasi
                        }
                    ];

                    fields.forEach(item => {
                        let element = document.getElementById(item.id);
                        if (element) element.innerText = item.value;
                    });

                    // Generate QR Code
                    let qrData = `Nama: ${data.username}\nNo Transaksi: ${data.no_transaksi}\nLapangan: ${data.no_lapangan}\nDurasi: ${data.durasi} jam \nTanggal: ${data.tanggal_transaksi}\nWaktu Mulai: ${data.jam_mulai}\nWaktu Selesai: ${data.jam_selesai}`;
                    let qrcodeElement = document.getElementById("qrcode");
                    if (qrcodeElement) {
                        qrcodeElement.innerHTML = "";
                        new QRCode(qrcodeElement, {
                            text: qrData,
                            width: 120,
                            height: 120
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Anda belum Melakukan Transaksi'
                    }).then(() => {
                        window.location.href = "halamanbooking.php";
                    });
                });
        });

        function cetakJPG() {
            let element = document.getElementById('container');

            if (!element || element.style.display === "none") {
                Swal.fire("Error", "Bukti pembayaran belum muncul!", "error");
                return;
            }

            Swal.fire({
                title: "Cetak Bukti Pembayaran?",
                text: "File akan diunduh sebagai gambar",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#600000",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Cetak!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    html2canvas(element).then(canvas => {
                        let imgData = canvas.toDataURL("image/png");
                        let link = document.createElement('a');
                        link.href = imgData;
                        link.download = "buktibooking.jpg";
                        link.click();
                        Swal.fire("Berhasil!", "Bukti pembayaran telah diunduh.", "success");
                    }).catch(error => {
                        Swal.fire("Gagal!", "Terjadi kesalahan saat mencetak.", "error");
                        console.error(error);
                    });
                }
            });
        }
    </script>
</body>

</html>