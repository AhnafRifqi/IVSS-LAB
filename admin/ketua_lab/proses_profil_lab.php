<?php
session_start();
include '../inc/koneksi.php';

// Cek autentikasi dan role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

// Redirect default ke halaman daftar
$redirect_list = 'profil_lab.php';

// Tentukan aksi yang diminta
$action = $_REQUEST['action'] ?? '';

switch ($action) {
    
    // --- KASUS: TAMBAH DATA (CREATE) ---
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error_msg'] = 'Metode permintaan tidak valid.';
            header('Location: tambah_profil_lab.php');
            exit;
        }

        $judul = trim($_POST['judul'] ?? '');
        $isi   = $_POST['isi'] ?? ''; // Isi textarea tidak di-trim

        if ($judul === '' || $isi === '') {
            $_SESSION['error_msg'] = 'Judul dan Isi wajib diisi.';
            $_SESSION['old_post'] = ['judul' => $judul, 'isi' => $isi];
            header('Location: tambah_profil_lab.php');
            exit;
        } else {
            $res = pg_query_params($koneksi, 'INSERT INTO profil_lab(judul, isi) VALUES($1, $2)', array($judul, $isi));
            if ($res) {
                $_SESSION['success_msg'] = 'Data Profil Lab baru berhasil ditambahkan.';
                header('Location: ' . $redirect_list);
                exit;
            } else {
                $_SESSION['error_msg'] = 'Gagal menambahkan data. Error Database: ' . pg_last_error($koneksi);
                $_SESSION['old_post'] = ['judul' => $judul, 'isi' => $isi];
                header('Location: tambah_profil_lab.php');
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

        $edit_url = 'edit_profil_lab.php?id=' . $id;

        if ($id === '' || $judul === '' || $isi === '') {
            $_SESSION['error_msg'] = 'ID, Judul, dan Isi tidak boleh kosong.';
            $_SESSION['old_post'] = ['judul' => $judul, 'isi' => $isi];
            header('Location: ' . $edit_url);
            exit;
        } else {
            $res = pg_query_params($koneksi,
                'UPDATE profil_lab SET judul = $1, isi = $2 WHERE id = $3',
                array($judul, $isi, $id)
            );
            if ($res) {
                $_SESSION['success_msg'] = 'Data Profil Lab berhasil diupdate.';
                header('Location: ' . $redirect_list);
                exit;
            } else {
                $_SESSION['error_msg'] = 'Gagal mengupdate data. Error Database: ' . pg_last_error($koneksi);
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
            $_SESSION['error_msg'] = 'ID Profil Lab yang akan dihapus tidak ditemukan.';
            header('Location: ' . $redirect_list);
            exit;
        }

        $res = pg_query_params($koneksi, 'DELETE FROM profil_lab WHERE id = $1', array($id));
        if ($res) {
            $_SESSION['success_msg'] = 'Data Profil Lab berhasil dihapus.';
            header('Location: ' . $redirect_list);
            exit;
        } else {
            // Cek apakah error disebabkan foreign key constraint
            $error_message = pg_last_error($koneksi);
            if (strpos($error_message, 'foreign key constraint') !== false) {
                 $_SESSION['error_msg'] = 'Gagal menghapus data. Data profil lab ini masih digunakan di tabel lain.';
            } else {
                 $_SESSION['error_msg'] = 'Gagal menghapus data. Error Database: ' . $error_message;
            }
            header('Location: ' . $redirect_list);
            exit;
        }
        break;

    // --- KASUS DEFAULT ---
    default:
        $_SESSION['error_msg'] = 'Aksi tidak valid atau tidak dikenali.';
        header('Location: ' . $redirect_list);
        exit;
}
?>