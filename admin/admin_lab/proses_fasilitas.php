<?php
session_start();
include '../inc/koneksi.php';

// Pastikan user sudah login dan memiliki role 'ketua'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$redirect_to = 'fasilitas.php'; // Default redirect setelah proses

if ($action) {
    try {
        // --- FUNGSI HELPER ---
        // Fungsi untuk menghapus file lama
        function deleteOldFile($filename, $dir = '../assets/img/') {
            if ($filename && is_file($dir . $filename)) {
                // Menggunakan @ untuk menekan error jika gagal menghapus file
                @unlink($dir . $filename); 
            }
        }
        
        // Fungsi untuk menangani upload file
        function handleFileUpload($old_file = null) {
            if (empty($_FILES['foto_file']['name'])) {
                return $old_file; // Jika tidak ada file baru, kembalikan nama file lama
            }

            $up = $_FILES['foto_file'];
            $allowed = ['jpg','jpeg','png','gif','webp'];
            $ext = strtolower(pathinfo($up['name'], PATHINFO_EXTENSION));
            $targetDir = '../assets/img/';

            if ($up['error'] !== UPLOAD_ERR_OK) {
                // Menangkap error upload spesifik
                throw new Exception('Gagal upload file (Error code: ' . $up['error'] . ').');
            } 
            if (!in_array($ext, $allowed)) {
                throw new Exception('Tipe file tidak diperbolehkan. (jpg,jpeg,png,gif,webp)');
            }
            
            // Cek dan buat folder jika belum ada
            if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
            
            // Menggunakan nama file asli
            $filename = $up['name']; 
            $targetFile = $targetDir . $filename;
            
            if (move_uploaded_file($up['tmp_name'], $targetFile)) {
                // Jika ada file lama (saat UPDATE) dan nama file berbeda, hapus file lama di server
                if ($old_file && $old_file !== $filename) {
                     deleteOldFile($old_file, $targetDir);
                }
                return $filename; // Kembalikan nama file baru
            } else {
                throw new Exception('Gagal menyimpan file ke server.');
            }
        }
        // --- END FUNGSI HELPER ---

        switch ($action) {
            
            // --- Aksi CREATE ---
            case 'tambah':
                $nama = trim($_POST['nama'] ?? '');
                $isi  = trim($_POST['isi'] ?? '');

                if ($nama === '') {
                    throw new Exception('Nama fasilitas wajib diisi.');
                }

                $foto_saved = handleFileUpload();

                $sql = 'INSERT INTO fasilitas (nama, gambar, isi) VALUES ($1, $2, $3)';
                $res = pg_query_params($koneksi, $sql, [
                    $nama,
                    $foto_saved,
                    $isi
                ]);

                if (!$res) {
                    throw new Exception('Gagal menyimpan data ke database.');
                }
                pg_free_result($res);
                header('Location: ' . $redirect_to . '?msg=created');
                exit;
            
            // --- Aksi UPDATE ---
            case 'edit':
                $id = (int)($_POST['id'] ?? 0);
                $nama = trim($_POST['nama'] ?? '');
                $isi  = trim($_POST['isi'] ?? '');
                $foto_lama = $_POST['foto_lama'] ?? null; // Nama file foto lama dari hidden input

                if ($id <= 0) {
                    throw new Exception('ID fasilitas tidak valid.');
                }
                if ($nama === '') {
                    throw new Exception('Nama fasilitas wajib diisi.');
                }
                
                // Cek data existing untuk memverifikasi ID
                $q_check = pg_query_params($koneksi, 'SELECT gambar FROM fasilitas WHERE id=$1', [$id]);
                if (!$q_check || pg_num_rows($q_check) === 0) {
                    throw new Exception('Data fasilitas tidak ditemukan.');
                }
                pg_free_result($q_check);

                // Handle upload foto baru (akan menghapus yang lama jika berhasil upload baru)
                $foto_saved = handleFileUpload($foto_lama);

                $sql = 'UPDATE fasilitas SET nama=$1, gambar=$2, isi=$3 WHERE id=$4';
                $res = pg_query_params($koneksi, $sql, [
                    $nama,
                    $foto_saved,
                    $isi,
                    $id
                ]);

                if (!$res) {
                    throw new Exception('Gagal mengupdate data di database.');
                }
                pg_free_result($res);
                
                // Redirect ke halaman edit_fasilitas lagi dengan pesan sukses
                header('Location: edit_fasilitas.php?id=' . $id . '&msg=updated');
                exit;
            
            // --- Aksi DELETE ---
            case 'hapus':
                $id = (int)($_GET['id'] ?? 0);
                
                if ($id <= 0) {
                    throw new Exception('ID fasilitas tidak valid untuk dihapus.');
                }

                // Ambil nama file gambar sebelum dihapus
                $q_file = pg_query_params($koneksi, 'SELECT gambar FROM fasilitas WHERE id=$1', [$id]);
                $row_file = $q_file ? pg_fetch_assoc($q_file) : null;
                $file_to_delete = $row_file['gambar'] ?? null;
                if ($q_file) pg_free_result($q_file);
                
                // Hapus data dari DB
                $sql = 'DELETE FROM fasilitas WHERE id=$1';
                $res = pg_query_params($koneksi, $sql, [$id]);

                if (!$res) {
                    throw new Exception('Gagal menghapus data dari database.');
                }
                pg_free_result($res);
                
                // Hapus file di server
                deleteOldFile($file_to_delete);
                
                header('Location: ' . $redirect_to . '?msg=deleted');
                exit;

            default:
                throw new Exception('Aksi tidak dikenal.');
        }

    } catch (Exception $e) {
        $error_msg = urlencode($e->getMessage());
        
        // Menentukan URL redirect saat gagal berdasarkan aksi
        if ($action === 'tambah') {
            $redirect_url = 'tambah_fasilitas.php';
        } elseif ($action === 'edit' && isset($_POST['id'])) {
            $redirect_url = 'edit_fasilitas.php?id=' . $_POST['id'];
        } else {
            $redirect_url = $redirect_to; // Default fasilitas.php
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