<?php
session_start();
include '../inc/koneksi.php';

// 1. Cek Autentikasi
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// --- AKSI TAMBAH & EDIT ---
if ($action === 'add' || $action === 'edit') {
    $id         = $_POST['id'] ?? null;
    $judul      = $_POST['judul'] ?? '';
    $deskripsi  = $_POST['deskripsi'] ?? '';
    $link       = $_POST['link'] ?? '';
    
    // Default gambar menggunakan nama lama (untuk edit)
    $gambar_name = $_POST['existing_gambar'] ?? '';

    // Jika ada file baru yang diupload
    if (isset($_FILES['gambar_file']) && $_FILES['gambar_file']['error'] === 0) {
        // Menggunakan nama asli file
        $gambar_name = basename($_FILES['gambar_file']['name']);
        $dest = '../assets/img/' . $gambar_name;

        // Proses upload
        if (move_uploaded_file($_FILES['gambar_file']['tmp_name'], $dest)) {
            // Jika aksi edit dan gambar berubah, hapus gambar lama dari folder
            if ($action === 'edit' && !empty($_POST['existing_gambar']) && $_POST['existing_gambar'] !== $gambar_name) {
                @unlink('../assets/img/' . $_POST['existing_gambar']);
            }
        } else {
            // Jika gagal upload
            header('Location: produk.php?msg=error&detail=Gagal_upload_gambar');
            exit;
        }
    }

    if ($action === 'add') {
        $sql = "INSERT INTO products (judul, deskripsi, gambar, link) VALUES ($1, $2, $3, $4)";
        $res = pg_query_params($koneksi, $sql, [$judul, $deskripsi, $gambar_name, $link]);
        $msg = $res ? 'created' : 'error';
    } else {
        $sql = "UPDATE products SET judul=$1, deskripsi=$2, gambar=$3, link=$4, updated_at=CURRENT_TIMESTAMP WHERE id=$5";
        $res = pg_query_params($koneksi, $sql, [$judul, $deskripsi, $gambar_name, $link, $id]);
        $msg = $res ? 'updated' : 'error';
    }
    
    header("Location: produk.php?msg=$msg");
    exit;
} 

// --- AKSI HAPUS ---
elseif ($action === 'delete') {
    $id = $_GET['id'] ?? '';
    
    // Ambil info gambar dulu untuk dihapus filenya
    $q = pg_query_params($koneksi, "SELECT gambar FROM products WHERE id=$1", [$id]);
    $row = pg_fetch_assoc($q);
    
    if ($row) {
        // Hapus file fisik
        @unlink('../assets/img/' . $row['gambar']);
        
        // Hapus data di database
        $res = pg_query_params($koneksi, "DELETE FROM products WHERE id=$1", [$id]);
        header('Location: produk.php?msg=' . ($res ? 'deleted' : 'error'));
    } else {
        header('Location: produk.php?msg=error');
    }
    exit;
}