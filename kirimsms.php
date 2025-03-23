<?php
require 'vendor/autoload.php'; // Pastikan Composer sudah menginstall Twilio SDK
use Twilio\Rest\Client;

function sendSMS($phone, $message) {
    // Ganti dengan kredensial dari Twilio Dashboard
    $sid = "AC939f8d4e25a6175b04ca9989613eba92"; 
    $token = "ddaa617c359f1e9f57b1996db806edb2"; 
    $twilio_number = "+6285846801239"; // Nomor dari Twilio (misal: +12025550123)

    $client = new Client($sid, $token);
    $client->messages->create(
        $phone, // Nomor penerima (gunakan format internasional, misal: +6281234567890)
        [
            'from' => $twilio_number,
            'body' => $message
        ]
    );

    return true;
}
?>
