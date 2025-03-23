<?php
require 'koneksi.php'; // Sesuaikan dengan file koneksi database
require_once dirname(__FILE__) . '/midtrans-php-master/Midtrans.php';

// Konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-t6cSjUymWvtBLx1ibfeO1rBx'; // Ganti dengan Server Key yang benar
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Ambil data dari Midtrans
$json_str = file_get_contents("php://input");
$data = json_decode($json_str, true);

// Log untuk debugging
file_put_contents('midtrans_callback.log', print_r($data, true), FILE_APPEND);

// Pastikan ada data
if (!$data) {
    http_response_code(400);
    exit("No data received");
}

// Ambil informasi transaksi
$order_id = $data['order_id'] ?? null;
$transaction_status = $data['transaction_status'] ?? null;
$payment_type = $data['payment_type'] ?? null;
$gross_amount = $data['gross_amount'] ?? null;

// Cek status transaksi
if ($transaction_status == "settlement") {
    // Update status di database
    $stmt = $conn->prepare("UPDATE booking SET status = 'Lunas' WHERE order_id = ?");
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $stmt->close();

    file_put_contents('midtrans_callback.log', "SUCCESS: Booking updated\n", FILE_APPEND);
    
    http_response_code(200);
    echo "Payment recorded successfully";
} else {
    file_put_contents('midtrans_callback.log', "ERROR: Transaction not settled\n", FILE_APPEND);
    http_response_code(400);
    echo "Transaction not settled yet";
}
?>
