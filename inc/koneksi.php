<?php
$host = "localhost";
$user = "postgres"; // default user PostgreSQL
<<<<<<< HEAD
$pass = "nauraPostgreSQL"; // ganti dengan password PostgreSQL kamu
=======
$pass = "1234"; // ganti dengan password PostgreSQL kamu
>>>>>>> a36b504cd0409b577a2525e0c637f6ea4371e65b
$db   = "pbl_ivss";
$port = "5432"; // port default PostgreSQL

// Membuat koneksi ke PostgreSQL
$koneksi = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass");

// Mengecek koneksi
if (!$koneksi) {
    die("Koneksi ke PostgreSQL gagal: " . pg_last_error());
} else {
    // echo "Koneksi berhasil"; // opsional, untuk uji koneksi
}
?>
