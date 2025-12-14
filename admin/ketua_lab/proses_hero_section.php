<?php
session_start();
include '../inc/koneksi.php';

// Cek autentikasi dan role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

// Redirect default ke halaman daftar
$redirect_list = 'hero_section.php';

// Tentukan aksi yang diminta
$action = $_REQUEST['action'] ?? '';

switch ($action) {
    
    // --- KASUS: TAMBAH DATA (CREATE) ---
    case 'create':
        // Pastikan metode request adalah POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error_msg'] = 'Metode permintaan tidak valid.';
            header('Location: tambah_hero_section.php');
            exit;
        }

        $judul = trim($_POST['judul'] ?? '');
        $isi   = trim($_POST['isi'] ?? '');

        if ($judul === '' || $isi === '') {
            $_SESSION['error_msg'] = 'Judul dan Isi Hero Section wajib diisi.';
            $_SESSION['old_post'] = ['judul' => $judul, 'isi' => $isi];
            header('Location: tambah_hero_section.php');
            exit;
        } else {
            $res = pg_query_params($koneksi, 'INSERT INTO hero_section(judul, isi) VALUES($1, $2)', array($judul, $isi));
            if ($res) {
                $_SESSION['success_msg'] = 'Data Hero Section baru berhasil ditambahkan.';
                header('Location: ' . $redirect_list);
                exit;
            } else {
                $_SESSION['error_msg'] = 'Gagal menambahkan data Hero Section ke database. Silakan coba lagi.';
                $_SESSION['old_post'] = ['judul' => $judul, 'isi' => $isi];
                header('Location: tambah_hero_section.php');
                exit;
            }
        }
        break;

    // --- KASUS: EDIT DATA (UPDATE) ---
    case 'edit':
        // Pastikan metode request adalah POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error_msg'] = 'Metode permintaan tidak valid.';
            header('Location: ' . $redirect_list);
            exit;
        }
        
        $id    = trim($_POST['id'] ?? '');
        $judul = trim($_POST['judul'] ?? '');
        $isi   = trim($_POST['isi'] ?? '');

        $edit_url = 'edit_hero_section.php?id=' . $id;

        if ($id === '' || $judul === '' || $isi === '') {
            $_SESSION['error_msg'] = 'ID, Judul, dan Isi tidak boleh kosong.';
            $_SESSION['old_post'] = ['judul' => $judul, 'isi' => $isi];
            header('Location: ' . $edit_url);
            exit;
        } else {
            $res = pg_query_params($koneksi,
                'UPDATE hero_section SET judul = $1, isi = $2 WHERE id = $3',
                array($judul, $isi, $id)
            );
            if ($res) {
                $_SESSION['success_msg'] = 'Data Hero Section berhasil diupdate.';
                header('Location: ' . $redirect_list);
                exit;
            } else {
                $_SESSION['error_msg'] = 'Gagal mengupdate data. Terjadi kesalahan pada database atau koneksi.';
                $_SESSION['old_post'] = ['judul' => $judul, 'isi' => $isi];
                header('Location: ' . $edit_url);
                exit;
            }
        }
        break;
        
    // --- KASUS: HAPUS DATA (DELETE) ---
    case 'delete':
        // Pastikan metode request adalah GET (dari link)
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $_SESSION['error_msg'] = 'Metode permintaan tidak valid untuk aksi hapus.';
            header('Location: ' . $redirect_list);
            exit;
        }

        $id = trim($_GET['id'] ?? '');

        if ($id === '') {
            $_SESSION['error_msg'] = 'ID data yang akan dihapus tidak ditemukan.';
            header('Location: ' . $redirect_list);
            exit;
        }

        $res = pg_query_params($koneksi, 'DELETE FROM hero_section WHERE id = $1', array($id));
        if ($res) {
            $_SESSION['success_msg'] = 'Data Hero Section berhasil dihapus.';
            header('Location: ' . $redirect_list);
            exit;
        } else {
            $_SESSION['error_msg'] = 'Gagal menghapus data Hero Section. Data mungkin masih terkait dengan tabel lain.';
            header('Location: ' . $redirect_list);
            exit;
        }
        break;

    // --- KASUS DEFAULT: Aksi tidak valid atau kosong ---
    default:
        $_SESSION['error_msg'] = 'Aksi yang diminta tidak valid atau tidak dikenali.';
        header('Location: ' . $redirect_list);
        exit;
}
?>