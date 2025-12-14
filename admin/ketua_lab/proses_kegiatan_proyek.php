<?php
session_start();
include '../inc/koneksi.php';

// Cek autentikasi dan role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

// Redirect default ke halaman daftar
$redirect_list = 'kegiatan_proyek.php'; 

// Tentukan aksi yang diminta
$action = $_REQUEST['action'] ?? '';

switch ($action) {
    
    // --- KASUS: TAMBAH DATA (CREATE) ---
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error_msg'] = 'Metode permintaan tidak valid.';
            header('Location: tambah_kegiatan_proyek.php');
            exit;
        }

        $judul = trim($_POST['judul'] ?? '');
        $isi   = $_POST['isi'] ?? ''; 

        if ($judul === '' || $isi === '') {
            $_SESSION['error_msg'] = 'Judul dan Isi wajib diisi.';
            $_SESSION['old_post'] = ['judul' => $judul, 'isi' => $isi];
            header('Location: tambah_kegiatan_proyek.php');
            exit;
        } else {
            // Menggunakan tabel kegiatan_proyek, kolom judul dan isi
            $res = pg_query_params($koneksi, 'INSERT INTO kegiatan_proyek(judul, isi) VALUES($1, $2)', array($judul, $isi));
            if ($res) {
                $_SESSION['success_msg'] = 'Kegiatan Proyek baru berhasil ditambahkan.';
                header('Location: ' . $redirect_list);
                exit;
            } else {
                $_SESSION['error_msg'] = 'Gagal menambahkan data. Error Database.';
                $_SESSION['old_post'] = ['judul' => $judul, 'isi' => $isi];
                header('Location: tambah_kegiatan_proyek.php');
                exit;
            }
        }
        break;

    // --- KASUS: EDIT DATA (UPDATE) ---
    case 'edit':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error_msg'] = 'Metode permintaan tidak valid.';
            header('Location: ' . $redirect_list);
            exit;
        }
        
        $id    = trim($_POST['id'] ?? '');
        $judul = trim($_POST['judul'] ?? '');
        $isi   = $_POST['isi'] ?? '';

        $edit_url = 'edit_kegiatan_proyek.php?id=' . $id;

        if ($id === '' || $judul === '' || $isi === '') {
            $_SESSION['error_msg'] = 'ID, Judul, dan Isi tidak boleh kosong.';
            $_SESSION['old_post'] = ['judul' => $judul, 'isi' => $isi];
            header('Location: ' . $edit_url);
            exit;
        } else {
            // Menggunakan tabel kegiatan_proyek, kolom judul dan isi
            $res = pg_query_params($koneksi,
                'UPDATE kegiatan_proyek SET judul = $1, isi = $2 WHERE id = $3',
                array($judul, $isi, $id)
            );
            if ($res) {
                $_SESSION['success_msg'] = 'Data Kegiatan Proyek berhasil diupdate.';
                header('Location: ' . $redirect_list);
                exit;
            } else {
                $_SESSION['error_msg'] = 'Gagal mengupdate data. Error Database.';
                $_SESSION['old_post'] = ['judul' => $judul, 'isi' => $isi];
                header('Location: ' . $edit_url);
                exit;
            }
        }
        break;
        
    // --- KASUS: HAPUS DATA (DELETE) ---
    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $_SESSION['error_msg'] = 'Metode permintaan tidak valid untuk aksi hapus.';
            header('Location: ' . $redirect_list);
            exit;
        }

        $id = trim($_GET['id'] ?? '');

        if ($id === '') {
            $_SESSION['error_msg'] = 'ID Kegiatan Proyek yang akan dihapus tidak ditemukan.';
            header('Location: ' . $redirect_list);
            exit;
        }

        // Menggunakan tabel kegiatan_proyek
        $res = pg_query_params($koneksi, 'DELETE FROM kegiatan_proyek WHERE id = $1', array($id));
        if ($res) {
            $_SESSION['success_msg'] = 'Data Kegiatan Proyek berhasil dihapus.';
            header('Location: ' . $redirect_list);
            exit;
        } else {
            $_SESSION['error_msg'] = 'Gagal menghapus data. Data mungkin terkait dengan tabel lain.';
            header('Location: ' . $redirect_list);
            exit;
        }
        break;

    // --- KASUS DEFAULT ---
    default:
        $_SESSION['error_msg'] = 'Aksi yang diminta tidak valid atau tidak dikenali.';
        header('Location: ' . $redirect_list);
        exit;
}
?>