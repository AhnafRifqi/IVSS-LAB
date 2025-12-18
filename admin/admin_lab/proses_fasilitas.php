<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$redirect_to = 'fasilitas.php';

if ($action) {
    try {
        function deleteOldFile($filename, $dir = '../assets/img/') {
            if ($filename && is_file($dir . $filename)) @unlink($dir . $filename);
        }
        
        function handleFileUpload($old_file = null) {
            if (empty($_FILES['foto_file']['name'])) return $old_file;
            $up = $_FILES['foto_file'];
            $allowed = ['jpg','jpeg','png','gif','webp'];
            $ext = strtolower(pathinfo($up['name'], PATHINFO_EXTENSION));
            $targetDir = '../assets/img/';

            if ($up['error'] !== UPLOAD_ERR_OK) throw new Exception('Gagal upload file.');
            if (!in_array($ext, $allowed)) throw new Exception('Ekstensi tidak diizinkan.');
            
            if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
            $filename = time() . '_' . $up['name']; // Menambah timestamp agar unik
            $targetFile = $targetDir . $filename;
            
            if (move_uploaded_file($up['tmp_name'], $targetFile)) {
                if ($old_file && $old_file !== $filename) deleteOldFile($old_file, $targetDir);
                return $filename;
            } else {
                throw new Exception('Gagal menyimpan file.');
            }
        }

        switch ($action) {
            case 'tambah':
                $nama = trim($_POST['nama'] ?? '');
                $isi  = trim($_POST['isi'] ?? '');
                $tipe = $_POST['tipe'] ?? 'fasilitas'; // AMBIL TIPE

                if ($nama === '') throw new Exception('Nama wajib diisi.');
                $foto_saved = handleFileUpload();

                $sql = 'INSERT INTO fasilitas (nama, gambar, isi, tipe) VALUES ($1, $2, $3, $4)';
                $res = pg_query_params($koneksi, $sql, [$nama, $foto_saved, $isi, $tipe]);
                
                if (!$res) throw new Exception('Database Error.');
                header('Location: ' . $redirect_to . '?msg=created');
                exit;
            
            case 'edit':
                $id = (int)($_POST['id'] ?? 0);
                $nama = trim($_POST['nama'] ?? '');
                $isi  = trim($_POST['isi'] ?? '');
                $tipe = $_POST['tipe'] ?? 'fasilitas'; // AMBIL TIPE
                $foto_lama = $_POST['foto_lama'] ?? null;

                if ($id <= 0 || $nama === '') throw new Exception('Data tidak valid.');
                $foto_saved = handleFileUpload($foto_lama);

                $sql = 'UPDATE fasilitas SET nama=$1, gambar=$2, isi=$3, tipe=$4 WHERE id=$5';
                $res = pg_query_params($koneksi, $sql, [$nama, $foto_saved, $isi, $tipe, $id]);

                if (!$res) throw new Exception('Gagal update database.');
                header('Location: edit_fasilitas.php?id=' . $id . '&msg=updated');
                exit;
            
            case 'hapus':
                $id = (int)($_GET['id'] ?? 0);
                $q_file = pg_query_params($koneksi, 'SELECT gambar FROM fasilitas WHERE id=$1', [$id]);
                $row_file = pg_fetch_assoc($q_file);
                $res = pg_query_params($koneksi, 'DELETE FROM fasilitas WHERE id=$1', [$id]);
                if ($res) deleteOldFile($row_file['gambar'] ?? null);
                header('Location: ' . $redirect_to . '?msg=deleted');
                exit;
        }
    } catch (Exception $e) {
        $err = urlencode($e->getMessage());
        $url = ($action === 'tambah') ? 'tambah_fasilitas.php' : (($action === 'edit') ? 'edit_fasilitas.php?id='.$_POST['id'] : $redirect_to);
        header("Location: $url?msg=error&error_detail=$err");
        exit;
    }
}