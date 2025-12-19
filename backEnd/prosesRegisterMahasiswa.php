<?php
require_once 'inc/koneksi.php';

// Ambil logo laboratorium
$sql = "SELECT * FROM logo LIMIT 1";
$result = pg_query($koneksi, $sql);
$logo_data = pg_fetch_assoc($result);

// --- MODIFIKASI QUERY DOSEN ---
$sql_dosen = "
    SELECT 
        d.nip, 
        d.nama,
        u.role
    FROM 
        dosen d
    JOIN 
        users u ON d.user_id = u.user_id
    WHERE 
        u.role <> 'berita'
    ORDER BY 
        d.nama ASC
";
$result_dosen = pg_query($koneksi, $sql_dosen);

// Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $namaLengkap      = trim($_POST['nama']);
    $nim              = trim($_POST['nim']);
    $jenisKelamin     = trim($_POST['jenis_kelamin']);
    $noTelp           = trim($_POST['no_telp']);
    $email            = trim($_POST['email']);
    $keperluan        = trim($_POST['keperluan']);
    $dosenPembimbing  = trim($_POST['dosen_pembimbing']);
    
    // Default status, menggunakan ENUM yang paling awal:
    $status_mahasiswa = null;
    $status_pendaftaran = 'pending';

    // Validasi sederhana
    if ($namaLengkap && $nim && $jenisKelamin && $noTelp && $email && $keperluan && $dosenPembimbing) {
        
        // --- BYPASS STATUS ---
        // 1. Ambil user_id dosen yang dipilih
        $q_dosen_user = pg_query_params($koneksi, "SELECT user_id FROM dosen WHERE nip = $1", [$dosenPembimbing]);
        $dosen_user = pg_fetch_assoc($q_dosen_user);
        $dosen_user_id = $dosen_user['user_id'] ?? null;
        $dosen_role = null;

        if ($dosen_user_id) {
            // 2. Ambil role dosen dari tabel users
            $q_role = pg_query_params($koneksi, "SELECT role FROM users WHERE user_id = $1", [$dosen_user_id]);
            $row_role = pg_fetch_assoc($q_role);
            $dosen_role = $row_role['role'] ?? null;
        }

        // HANYA role 'ketua' yang mendapat bypass
        if ($dosen_role === 'ketua') {
            // Status langsung Disetujui Dosen Pembimbing
            $status_pendaftaran = 'acc_dosen';
        } 
        // Jika role lain (misalnya 'admin'), status tetap 'pending'
        // --- END BYPASS STATUS ---

        $sql_insert = "INSERT INTO mahasiswa
            (nama, nim, jenis_kelamin, no_telp, email, keperluan, dosen_pembimbing, tanggal_daftar, status_pendaftaran)
            VALUES ($1, $2, $3, $4, $5, $6, $7, CURRENT_DATE, $8)";
        $result_insert = pg_query_params($koneksi, $sql_insert, [
            $namaLengkap,
            $nim,
            $jenisKelamin,
            $noTelp,
            $email,
            $keperluan,
            $dosenPembimbing,
            $status_pendaftaran // Status yang sudah disesuaikan
        ]);

        if ($result_insert) {
            echo "<script>alert('Data berhasil disimpan! Status pendaftaran Anda saat ini: " . htmlspecialchars($status_pendaftaran) . "'); window.location='index.php';</script>";
            exit;
        } else {
            $error_msg = pg_last_error($koneksi);
            echo "<script>alert('Terjadi kesalahan saat menyimpan data. Error: " . htmlspecialchars($error_msg) . "');</script>";
        }
    } else {
        echo "<script>alert('Semua field wajib diisi!');</script>";
    }
}
?>