<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_stuntguard"; // Sesuaikan dengan nama database di phpMyAdmin kamu

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>