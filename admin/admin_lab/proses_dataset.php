<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    // Jika tidak login, redirect ke halaman login
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? null;
$redirect_to = 'dataset.php'; // Default redirect setelah proses

if ($action) {
    try {
        switch ($action) {
            
            // --- Aksi CREATE (dari tambah_dataset.php) ---
            case 'tambah':
                $judul = trim($_POST['judul'] ?? '');
                $deskripsi = trim($_POST['deskripsi'] ?? '');
                $file_path = trim($_POST['file_path'] ?? '');
                $visibility = $_POST['visibility'] ?? 'privat'; // Default privat

                if ($judul === '') {
                    throw new Exception('Judul dataset wajib diisi.');
                }
                if (!in_array($visibility, ['publik', 'privat'])) {
                     throw new Exception('Nilai visibility tidak valid.');
                }

                $sql = 'INSERT INTO dataset (judul, deskripsi, file_path, uploader_by, visibility) VALUES ($1, $2, $3, $4, $5)';
                $res = pg_query_params($koneksi, $sql, [
                    $judul,
                    $deskripsi,
                    $file_path,
                    $user_id,
                    $visibility
                ]);

                if (!$res) {
                    throw new Exception('Gagal menyimpan data ke database.');
                }
                pg_free_result($res);
                header('Location: ' . $redirect_to . '?msg=created');
                exit;

            // --- Aksi UPDATE (dari edit_dataset.php) ---
            case 'edit':
                $dataset_id = (int)($_POST['dataset_id'] ?? 0);
                $judul = trim($_POST['judul'] ?? '');
                $deskripsi = trim($_POST['deskripsi'] ?? '');
                $file_path = trim($_POST['file_path'] ?? '');
                $visibility = $_POST['visibility'] ?? 'privat';

                if ($dataset_id <= 0) {
                    throw new Exception('ID Dataset tidak valid.');
                }
                if ($judul === '') {
                    throw new Exception('Judul dataset wajib diisi.');
                }
                if (!in_array($visibility, ['publik', 'privat'])) {
                     throw new Exception('Nilai visibility tidak valid.');
                }

                // Cek kepemilikan
                $q_check = pg_query_params($koneksi, 'SELECT uploader_by FROM dataset WHERE dataset_id=$1', [$dataset_id]);
                $row_check = $q_check ? pg_fetch_assoc($q_check) : null;
                if (!$row_check || $row_check['uploader_by'] != $user_id) {
                    throw new Exception('Akses ditolak atau Dataset tidak ditemukan.');
                }
                if ($q_check) pg_free_result($q_check);

                $sql = 'UPDATE dataset SET judul=$1, deskripsi=$2, file_path=$3, visibility=$4 WHERE dataset_id=$5 AND uploader_by=$6';
                $res = pg_query_params($koneksi, $sql, [
                    $judul,
                    $deskripsi,
                    $file_path,
                    $visibility,
                    $dataset_id,
                    $user_id
                ]);

                if (!$res) {
                    throw new Exception('Gagal mengupdate data di database.');
                }
                pg_free_result($res);
                
                // Redirect kembali ke halaman edit dengan pesan sukses
                header('Location: edit_dataset.php?id=' . $dataset_id . '&msg=updated');
                exit;

            // --- Aksi DELETE (dari dataset.php) ---
            case 'hapus':
                $dataset_id = (int)($_GET['id'] ?? 0);
                
                if ($dataset_id <= 0) {
                    throw new Exception('ID Dataset tidak valid untuk dihapus.');
                }

                // Cek kepemilikan sebelum hapus
                $q_check = pg_query_params($koneksi, 'SELECT uploader_by FROM dataset WHERE dataset_id=$1', [$dataset_id]);
                $row_check = $q_check ? pg_fetch_assoc($q_check) : null;
                if (!$row_check || $row_check['uploader_by'] != $user_id) {
                    throw new Exception('Akses ditolak atau Dataset tidak ditemukan.');
                }
                if ($q_check) pg_free_result($q_check);
                
                // Hapus data dari DB
                $sql = 'DELETE FROM dataset WHERE dataset_id=$1 AND uploader_by=$2';
                $res = pg_query_params($koneksi, $sql, [$dataset_id, $user_id]);

                if (!$res) {
                    throw new Exception('Gagal menghapus data dari database.');
                }
                pg_free_result($res);
                
                header('Location: ' . $redirect_to . '?msg=deleted');
                exit;

            default:
                throw new Exception('Aksi tidak dikenal.');
        }

    } catch (Exception $e) {
        $error_msg = urlencode($e->getMessage());
        
        // Menentukan URL redirect saat gagal berdasarkan aksi
        if ($action === 'tambah') {
            $redirect_url = 'tambah_dataset.php';
        } elseif ($action === 'edit' && isset($_POST['dataset_id'])) {
            $redirect_url = 'edit_dataset.php?id=' . $_POST['dataset_id'];
        } else {
            $redirect_url = $redirect_to;
        }

        header("Location: $redirect_url?msg=error&error_detail=$error_msg");
        exit;
    }
} else {
    // Jika tidak ada aksi, redirect ke daftar
    header('Location: ' . $redirect_to);
    exit;
}
?>