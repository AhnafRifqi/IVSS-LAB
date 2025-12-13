<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php'); exit;
}

$action = $_GET['action'] ?? '';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$error = '';
$success = '';

switch ($action) {

    case 'tambah':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama'] ?? '');
            $isi  = trim($_POST['isi'] ?? '');

            if ($nama === '') $error = 'Nama fasilitas wajib diisi.';

            $foto_saved = null;
            if (empty($error) && !empty($_FILES['foto_file']['name'])) {
                $up = $_FILES['foto_file'];
                $allowed = ['jpg','jpeg','png','gif','webp'];
                $ext = strtolower(pathinfo($up['name'], PATHINFO_EXTENSION));

                if ($up['error'] !== UPLOAD_ERR_OK) {
                    $error = 'Gagal upload file.';
                } elseif (!in_array($ext, $allowed)) {
                    $error = 'Tipe file tidak diperbolehkan.';
                } else {
                    $targetDir = '../assets/img/';
                    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                    $targetFile = $targetDir . $up['name'];
                    if (move_uploaded_file($up['tmp_name'], $targetFile)) {
                        $foto_saved = $up['name'];
                    } else $error = 'Gagal menyimpan file.';
                }
            }

            if (empty($error)) {
                $sql = 'INSERT INTO fasilitas (nama, gambar, isi) VALUES ($1, $2, $3)';
                $res = pg_query_params($koneksi, $sql, [
                    $nama !== '' ? $nama : null,
                    $foto_saved !== null ? $foto_saved : null,
                    $isi !== '' ? $isi : null
                ]);
                if ($res) { pg_free_result($res); header('Location: fasilitas.php?msg=created'); exit; }
                else $error = 'Gagal menyimpan data.';
            }
        }
        break;

    case 'edit':
        if ($id <= 0) { header('Location: fasilitas.php'); exit; }

        // ambil data existing
        $q = pg_query_params($koneksi, 'SELECT id, nama, gambar, isi FROM fasilitas WHERE id=$1', [$id]);
        if (!$q || pg_num_rows($q) === 0) { header('Location: fasilitas.php'); exit; }
        $row = pg_fetch_assoc($q); pg_free_result($q);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama'] ?? '');
            $isi  = trim($_POST['isi'] ?? '');

            if ($nama === '') $error = 'Nama fasilitas wajib diisi.';

            $foto_saved = $row['gambar'];
            if (empty($error) && !empty($_FILES['foto_file']['name'])) {
                $up = $_FILES['foto_file'];
                $allowed = ['jpg','jpeg','png','gif','webp'];
                $ext = strtolower(pathinfo($up['name'], PATHINFO_EXTENSION));
                $targetDir = '../assets/img/';

                if ($up['error'] !== UPLOAD_ERR_OK) $error = 'Gagal upload file.';
                elseif (!in_array($ext, $allowed)) $error = 'Tipe file tidak diperbolehkan.';
                else {
                    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                    $targetFile = $targetDir . $up['name'];
                    if (move_uploaded_file($up['tmp_name'], $targetFile)) {
                        if ($foto_saved && is_file($targetDir . $foto_saved)) @unlink($targetDir . $foto_saved);
                        $foto_saved = $up['name'];
                    } else $error = 'Gagal menyimpan file.';
                }
            }

            if (empty($error)) {
                $sql = 'UPDATE fasilitas SET nama=$1, gambar=$2, isi=$3 WHERE id=$4';
                $res = pg_query_params($koneksi, $sql, [
                    $nama !== '' ? $nama : null,
                    $foto_saved !== '' ? $foto_saved : null,
                    $isi !== '' ? $isi : null,
                    $id
                ]);
                if ($res) { pg_free_result($res); header('Location: fasilitas.php?msg=updated'); exit; }
                else $error = 'Gagal mengupdate data.';
            }
        }
        break;

    case 'hapus':
        if ($id <= 0) { header('Location: fasilitas.php?msg=error'); exit; }

        // hapus file
        $q = pg_query_params($koneksi, 'SELECT gambar FROM fasilitas WHERE id=$1', [$id]);
        if ($q && pg_num_rows($q) === 1) {
            $r = pg_fetch_assoc($q);
            $g = $r['gambar'];
            if ($g && !preg_match('#^https?://#i', $g)) {
                $path = '../assets/img/' . $g;
                if (is_file($path)) @unlink($path);
            }
            pg_free_result($q);
        }

        // hapus record
        $res = pg_query_params($koneksi, 'DELETE FROM fasilitas WHERE id=$1', [$id]);
        if ($res && pg_affected_rows($res) > 0) { pg_free_result($res); header('Location: fasilitas.php?msg=deleted'); exit; }
        else header('Location: fasilitas.php?msg=error'); exit;
        break;

    default:
        header('Location: fasilitas.php'); exit;
}
