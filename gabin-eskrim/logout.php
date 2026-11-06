<?php
session_start();      // Memulai session yang aktif
session_unset();      // Menghapus semua variabel session
session_destroy();    // Menghancurkan session admin

// Alihkan kembali ke halaman login setelah berhasil logout
header("Location: login.php");
exit;
?>