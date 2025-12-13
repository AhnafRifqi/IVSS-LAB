<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$role    = $_SESSION['role'] ?? '';
$action  = $_POST['action'] ?? $_GET['action'] ?? '';

/* ================= TAMBAH ================= */
if ($action === 'create') {
    $judul      = trim($_POST['judul']);
    $deskripsi  = trim($_POST['deskripsi']);
    $file_path  = trim($_POST['file_path']);
    $visibility = $_POST['visibility'];

    $q = pg_query_params($koneksi, "
        INSERT INTO dataset (judul, deskripsi, file_path, uploader_by, visibility)
        VALUES ($1,$2,$3,$4,$5)
    ", [$judul, $deskripsi, $file_path, $user_id, $visibility]);

    header('Location: dataset.php?msg=' . ($q ? 'created' : 'error'));
    exit;
}

/* ================= UPDATE ================= */
if ($action === 'update') {
    $dataset_id = (int) $_POST['dataset_id'];
    $judul      = trim($_POST['judul']);
    $deskripsi  = trim($_POST['deskripsi']);
    $file_path  = trim($_POST['file_path']);
    $visibility = $_POST['visibility'];

    $q = pg_query_params($koneksi, "
        UPDATE dataset
        SET judul=$1, deskripsi=$2, file_path=$3, visibility=$4
        WHERE dataset_id=$5 AND uploader_by=$6
    ", [$judul, $deskripsi, $file_path, $visibility, $dataset_id, $user_id]);

    header('Location: dataset.php?msg=' . ($q ? 'updated' : 'error'));
    exit;
}

/* ================= DELETE ================= */
if ($action === 'delete') {
    $dataset_id = (int) $_GET['id'];

    $q = pg_query_params($koneksi, "
        DELETE FROM dataset
        WHERE dataset_id=$1 AND uploader_by=$2
    ", [$dataset_id, $user_id]);

    header('Location: dataset.php?msg=' . ($q ? 'deleted' : 'error'));
    exit;
}

/* ================= SET PUBLIK (KETUA) ================= */
if ($action === 'set_public') {
    if ($role !== 'ketua') {
        header('Location: dataset.php?msg=forbidden'); exit;
    }

    $dataset_id = (int) $_POST['dataset_id'];

    $cek = pg_query_params($koneksi, "
        SELECT d.visibility, m.user_id AS mahasiswa_id
        FROM dataset d
        LEFT JOIN mahasiswa m ON d.uploader_by = m.user_id
        WHERE d.dataset_id=$1
    ", [$dataset_id]);

    $row = pg_fetch_assoc($cek);

    if (!$row) {
        header('Location: dataset.php?msg=error'); exit;
    }
    if ($row['visibility'] !== 'privat') {
        header('Location: dataset.php?msg=already_public'); exit;
    }
    if (empty($row['mahasiswa_id'])) {
        header('Location: dataset.php?msg=forbidden_uploader'); exit;
    }

    pg_query_params($koneksi,
        "UPDATE dataset SET visibility='publik' WHERE dataset_id=$1",
        [$dataset_id]
    );

    header('Location: dataset.php?msg=set_public_ok');
    exit;
}

header('Location: dataset.php');
exit;
?>
