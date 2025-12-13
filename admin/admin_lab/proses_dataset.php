<?php
session_start();
include '../inc/koneksi.php';

if (
    !isset($_SESSION['user_id']) ||
    ($_SESSION['role'] ?? '') !== 'admin'
) {
    header('Location: ../index.php');
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$action  = $_POST['action'] ?? '';

if ($action === 'create') {
    $judul      = trim($_POST['judul'] ?? '');
    $deskripsi  = trim($_POST['deskripsi'] ?? '');
    $file_path  = trim($_POST['file_path'] ?? '');
    $visibility = $_POST['visibility'] ?? 'publik';

    if ($judul === '') {
        header('Location: tambah_dataset.php?msg=error');
        exit;
    }

    pg_query_params($koneksi, "
        INSERT INTO dataset (judul, deskripsi, file_path, uploader_by, visibility)
        VALUES ($1,$2,$3,$4,$5)
    ", [$judul, $deskripsi, $file_path, $user_id, $visibility]);

    header('Location: dataset.php?msg=created');
    exit;
}

if ($action === 'update') {
    $id         = (int) ($_POST['dataset_id'] ?? 0);
    $judul      = trim($_POST['judul'] ?? '');
    $deskripsi  = trim($_POST['deskripsi'] ?? '');
    $file_path  = trim($_POST['file_path'] ?? '');
    $visibility = $_POST['visibility'] ?? 'publik';

    pg_query_params($koneksi, "
        UPDATE dataset
        SET judul=$1, deskripsi=$2, file_path=$3, visibility=$4
        WHERE dataset_id=$5 AND uploader_by=$6
    ", [$judul, $deskripsi, $file_path, $visibility, $id, $user_id]);

    header('Location: dataset.php?msg=updated');
    exit;
}

if ($action === 'delete') {
    $id = (int) ($_POST['dataset_id'] ?? 0);

    pg_query_params(
        $koneksi,
        "DELETE FROM dataset WHERE dataset_id=$1 AND uploader_by=$2",
        [$id, $user_id]
    );

    header('Location: dataset.php?msg=deleted');
    exit;
}

header('Location: dataset.php?msg=error');
exit;
