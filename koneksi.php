<?php
$host     = "localhost";
$user     = "root";
$password = "";
$database = "db_gabin_eskrim";

$koneksi = mysqli_connect($host, $user, $password, $database);

if (!$koneksi) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>