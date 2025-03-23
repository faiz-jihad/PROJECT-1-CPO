<?php
require_once dirname(__FILE__) . '/midtrans-php-master/Midtrans.php';
file_put_contents('midtrans_callback.log', file_get_contents('php://input'), FILE_APPEND);

// Konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-t6cSjUymWvtBLx1ibfeO1rBx';
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Pastikan data dikirim melalui POST

$id_pelanggan   = isset($_POST['id_pelanggan']) ? $_POST['id_pelanggan'] : 'Unknown';
$nomorTelepon   = isset($_POST['nomorTelepon']) ? $_POST['nomorTelepon'] : '0000000000';
$no_lapangan    = isset($_POST['no_lapangan']) ? $_POST['no_lapangan'] : 'Tidak Diketahui';
$tanggal_transaksi = isset($_POST['tanggal_transaksi']) ? $_POST['tanggal_transaksi'] : date('Y-m-d');
$jamMulai       = isset($_POST['jamMulai']) ? $_POST['jamMulai'] : '00:00';
$durasi         = isset($_POST['durasi']) ? (int)$_POST['durasi'] : 1;

// Hitung harga berdasarkan jam pemesanan
$jamMulaiInt = (int)explode(":", $jamMulai)[0];
$hargaPerJam = ($jamMulaiInt >= 18) ? 40000 : 35000;
$totalHarga = $durasi * $hargaPerJam;

// Pastikan order ID unik
$order_id = 'ORDER-' . time();

$params = array(
    'transaction_details' => array(
        'order_id' => $order_id,
        'gross_amount' => $totalHarga,
    ),
    'customer_details' => array(
        'first_name' => $id_pelanggan,
        'phone' => $nomorTelepon,
    ),
    'item_details' => array(
        array(
            'id' => $no_lapangan,
            'price' => $hargaPerJam,
            'quantity' => $durasi,
            'name' => "Lapangan No " . $no_lapangan
        )
    ),
);

try {
    $snapToken = \Midtrans\Snap::getSnapToken($params);
    echo $snapToken;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
