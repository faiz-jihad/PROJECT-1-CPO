<?php
session_start();
session_destroy();
header("Location: halamanlogin.php"); // ke halaman login
exit();
?>
