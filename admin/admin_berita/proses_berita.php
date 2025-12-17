<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'berita') {
    header('Location: ../login.php'); exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$id     = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

function uploadGambar($old = null) {
    if (empty($_FILES['gambar_file']['name'])) return $old;
    $file = $_FILES['gambar_file'];
    if ($file['error'] !== UPLOAD_ERR_OK) return $old;
    $dir = '../assets/img/';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $name = basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $dir.$name)) return $name;
    return $old;
}

if ($action === 'create') {
    $judul   = trim($_POST['judul']);
    $isi     = $_POST['isi'] ?? '';
    $tipe    = $_POST['tipe'] ?? 'umum';
    $penulis = trim($_POST['penulis']) ?: null;
    $tanggal = $_POST['tanggal'] ?: date('Y-m-d');
    $gambar  = uploadGambar();
    $sql = 'INSERT INTO berita (judul, isi, gambar, penulis, tanggal, tipe) VALUES ($1,$2,$3,$4,$5,$6)';
    $res = pg_query_params($koneksi, $sql, [$judul, $isi, $gambar, $penulis, $tanggal, $tipe]);
    header('Location: berita.php?msg=' . ($res ? 'created' : 'error'));
    exit;

} elseif ($action === 'update' && $id > 0) {
    $old = pg_fetch_assoc(pg_query_params($koneksi, 'SELECT gambar FROM berita WHERE berita_id=$1', [$id]));
    $judul   = trim($_POST['judul']);
    $isi     = $_POST['isi'] ?? '';
    $tipe    = $_POST['tipe'] ?? 'umum';
    $penulis = trim($_POST['penulis']) ?: null;
    $tanggal = $_POST['tanggal'] ?: null;
    $gambar  = uploadGambar($old['gambar'] ?? null);
    $sql = 'UPDATE berita SET judul=$1, isi=$2, gambar=$3, penulis=$4, tanggal=$5, tipe=$6 WHERE berita_id=$7';
    $res = pg_query_params($koneksi, $sql, [$judul, $isi, $gambar, $penulis, $tanggal, $tipe, $id]);
    header('Location: berita.php?msg=' . ($res ? 'updated' : 'error'));
    exit;

} elseif ($action === 'delete' && $id > 0) {
    $res = pg_query_params($koneksi, 'DELETE FROM berita WHERE berita_id=$1', [$id]);
    header('Location: berita.php?msg=' . ($res ? 'deleted' : 'error'));
    exit;
}
header('Location: berita.php');