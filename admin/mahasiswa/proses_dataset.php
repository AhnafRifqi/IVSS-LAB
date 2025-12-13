<?php
session_start();
include '../inc/koneksi.php';

// Cek autentikasi dan role (Mahasiswa)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? $_POST['action'] ?? null;
$redirect_to = 'dataset.php';
$msg = 'error'; // Default message error

if ($action) {
    try {
        switch ($action) {
            case 'tambah':
                // --- Tambah Dataset (CREATE) ---
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $judul = trim($_POST['judul'] ?? '');
                    $deskripsi = trim($_POST['deskripsi'] ?? '');
                    $file_path = trim($_POST['file_path'] ?? '');
                    // Visibility default 'privat' untuk dataset yang diupload mahasiswa
                    $visibility = $_POST['visibility'] ?? 'privat'; 

                    if (empty($judul)) {
                        throw new Exception("Judul tidak boleh kosong.");
                    }

                    $insert = pg_query_params($koneksi, "
                        INSERT INTO dataset (judul, deskripsi, file_path, uploader_by, visibility)
                        VALUES ($1, $2, $3, $4, $5)
                    ", [$judul, $deskripsi, $file_path, $user_id, $visibility]);

                    if ($insert) {
                        $msg = 'created';
                    } else {
                        throw new Exception("Gagal menambahkan dataset ke database.");
                    }
                } else {
                    throw new Exception("Metode request tidak valid untuk aksi tambah.");
                }
                break;

            case 'update':
            case 'copy':
                // --- Edit/Update Dataset (UPDATE) atau Copy Dataset (CREATE BARU) ---
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $dataset_id = $_POST['dataset_id'] ?? null;
                    $judul = trim($_POST['judul'] ?? '');
                    $deskripsi = trim($_POST['deskripsi'] ?? '');
                    $file_path = trim($_POST['file_path'] ?? '');
                    $visibility_from_form = $_POST['visibility'] ?? 'privat';

                    if (!$dataset_id || empty($judul)) {
                        throw new Exception("ID Dataset atau Judul tidak valid.");
                    }
                    
                    // Ambil dataset asli untuk cek owner/role
                    $q_check = pg_query_params($koneksi, "SELECT d.*, u.role AS uploader_role FROM dataset d LEFT JOIN users u ON d.uploader_by = u.user_id WHERE d.dataset_id = $1", [$dataset_id]);
                    $dataset_original = pg_fetch_assoc($q_check);

                    if (!$dataset_original) {
                        throw new Exception("Dataset tidak ditemukan.");
                    }
                    
                    $is_owner = ($dataset_original['uploader_by'] == $user_id);

                    if ($action === 'update') {
                        // Hanya pemilik yang boleh mengupdate
                        if (!$is_owner) {
                            throw new Exception("Anda tidak memiliki izin untuk mengupdate dataset ini.");
                        }
                        
                        $update = pg_query_params($koneksi, "
                            UPDATE dataset
                            SET judul = $1, deskripsi = $2, file_path = $3, visibility = $4
                            WHERE dataset_id = $5 AND uploader_by = $6
                        ", [$judul, $deskripsi, $file_path, $visibility_from_form, $dataset_id, $user_id]);

                        if ($update) {
                            $msg = 'updated';
                        } else {
                            throw new Exception("Gagal update dataset.");
                        }

                    } elseif ($action === 'copy') {
                        // Hanya dataset Ketua yang bisa dicopy (untuk mahasiswa)
                        if ($dataset_original['uploader_role'] !== 'ketua') {
                            throw new Exception("Dataset ini tidak bisa dicopy.");
                        }
                        
                        // Buat entri baru (copy) milik mahasiswa yang sedang login
                        $insert_copy = pg_query_params($koneksi, "
                            INSERT INTO dataset (judul, deskripsi, file_path, uploader_by, visibility)
                            VALUES ($1, $2, $3, $4, $5)
                        ", [$judul, $deskripsi, $file_path, $user_id, $visibility_from_form]);

                        if ($insert_copy) {
                            $msg = 'copied';
                        } else {
                            throw new Exception("Gagal membuat copy dataset.");
                        }
                    }
                } else {
                    throw new Exception("Metode request tidak valid untuk aksi update/copy.");
                }
                break;

            case 'hapus':
                // --- Hapus Dataset (DELETE) ---
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $dataset_id = $_GET['id'] ?? null;

                    if (!$dataset_id) {
                        throw new Exception("ID Dataset tidak valid.");
                    }

                    // Hapus hanya dataset milik sendiri
                    $del = pg_query_params($koneksi, "DELETE FROM dataset WHERE dataset_id = $1 AND uploader_by = $2", [$dataset_id, $user_id]);
                    
                    if (pg_affected_rows($del) > 0) {
                        $msg = 'deleted';
                    } else {
                        throw new Exception("Gagal menghapus dataset atau Anda tidak memiliki izin untuk dataset ini.");
                    }
                } else {
                    throw new Exception("Metode request tidak valid untuk aksi hapus.");
                }
                break;

            default:
                throw new Exception("Aksi tidak dikenal.");
        }
    } catch (Exception $e) {
        // Jika terjadi exception, kita akan redirect dengan pesan error
        $msg = 'error&error_detail=' . urlencode($e->getMessage());
    }
}

// Redirect ke halaman dataset.php dengan pesan status
header("Location: $redirect_to?msg=$msg");
exit;
?>