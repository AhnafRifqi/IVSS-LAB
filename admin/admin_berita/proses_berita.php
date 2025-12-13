<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'berita') {
    header('Location: ../login.php');
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$id     = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

function uploadGambar($old = null) {
    if (empty($_FILES['gambar_file']['name'])) return $old;

    $file = $_FILES['gambar_file'];
    if ($file['error'] !== UPLOAD_ERR_OK) return $old;
    if ($file['size'] > 3 * 1024 * 1024) return $old;

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif','webp'];
    if (!in_array($ext, $allowed)) return $old;

    $dir = '../assets/img/';
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $name = time().'_'.$file['name'];
    if (move_uploaded_file($file['tmp_name'], $dir.$name)) {
        return $name;
    }
    return $old;
}

/* ================= CREATE ================= */
if ($action === 'create') {
    $judul   = trim($_POST['judul']);
    $isi     = $_POST['isi'] ?? '';
    $penulis = trim($_POST['penulis']) ?: null;
    $tanggal = $_POST['tanggal'] ?: date('Y-m-d');

    if ($judul === '' || trim(strip_tags($isi)) === '') {
        header('Location: tambah_berita.php?err=1'); exit;
    }

    $gambar = uploadGambar();

    pg_query_params($koneksi,
        'INSERT INTO berita (judul, isi, gambar, penulis, tanggal)
         VALUES ($1,$2,$3,$4,$5)',
        [$judul, $isi, $gambar, $penulis, $tanggal]
    );

    header('Location: berita.php?msg=created');
    exit;
}

/* ================= UPDATE ================= */
if ($action === 'update' && $id > 0) {
    $old = pg_fetch_assoc(pg_query_params(
        $koneksi,
        'SELECT gambar FROM berita WHERE berita_id=$1',
        [$id]
    ));

    $judul   = trim($_POST['judul']);
    $isi     = $_POST['isi'] ?? '';
    $penulis = trim($_POST['penulis']) ?: null;
    $tanggal = $_POST['tanggal'] ?: null;

    if ($judul === '' || trim(strip_tags($isi)) === '') {
        header("Location: edit_berita.php?id=$id&err=1"); exit;
    }

    $gambar = uploadGambar($old['gambar'] ?? null);

    pg_query_params($koneksi,
        'UPDATE berita SET judul=$1, isi=$2, gambar=$3, penulis=$4, tanggal=$5
         WHERE berita_id=$6',
        [$judul, $isi, $gambar, $penulis, $tanggal, $id]
    );

    header('Location: berita.php?msg=updated');
    exit;
}

/* ================= DELETE ================= */
if ($action === 'delete' && $id > 0) {
    pg_query_params($koneksi,
        'DELETE FROM berita WHERE berita_id=$1',
        [$id]
    );
    header('Location: berita.php?msg=deleted');
    exit;
}

header('Location: berita.php');
