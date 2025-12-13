<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

$action  = $_POST['action'] ?? $_GET['action'] ?? '';
$user_id = (int) $_SESSION['user_id'];

/* ================= UPDATE ================= */
if ($action === 'update') {
    $nim              = trim($_POST['nim'] ?? '');
    $nama             = trim($_POST['nama'] ?? '');
    $no_telp          = trim($_POST['no_telp'] ?? null);
    $email            = trim($_POST['email'] ?? null);
    $keperluan        = trim($_POST['keperluan'] ?? null);
    $status_mahasiswa = $_POST['status_mahasiswa'] ?? null;

    if ($nim === '' || $nama === '') {
        header('Location: mahasiswa.php?msg=error'); 
        exit;
    }

    $q = pg_query_params($koneksi, "
        UPDATE mahasiswa
        SET nama=$1, no_telp=$2, email=$3, keperluan=$4, status_mahasiswa=$5
        WHERE nim=$6
    ", [$nama, $no_telp, $email, $keperluan, $status_mahasiswa, $nim]);

    header('Location: mahasiswa.php?msg=' . ($q ? 'updated' : 'error'));
    exit;
}

/* ================= DELETE ================= */
if ($action === 'delete') {
    $nim = trim($_GET['nim'] ?? '');
    if ($nim === '') {
        header('Location: mahasiswa.php?msg=error'); 
        exit;
    }

    $q = pg_query_params($koneksi, "
        DELETE FROM mahasiswa
        WHERE nim=$1
    ", [$nim]);

    header('Location: mahasiswa.php?msg=' . ($q ? 'deleted' : 'error'));
    exit;
}

header('Location: mahasiswa.php');
exit;
