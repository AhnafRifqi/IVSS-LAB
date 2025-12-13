<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php'); exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$error = '';
$nip = trim($_POST['nip'] ?? $_GET['nip'] ?? '');

// validasi nip
if ($nip === '' && $action !== 'add') {
    header('Location: dosen.php?msg=error'); exit;
}

if ($action === 'delete') {
    // ambil foto dulu
    $q = pg_query_params($koneksi, 'SELECT foto FROM dosen WHERE nip=$1', [$nip]);
    if ($q && pg_num_rows($q) === 1) {
        $r = pg_fetch_assoc($q);
        $foto = $r['foto'];
        if ($foto && !preg_match('#^https?://#i', $foto)) {
            $path = '../assets/img/' . $foto;
            if (is_file($path)) @unlink($path);
        }
        pg_free_result($q);
    }
    // hapus record
    $res = pg_query_params($koneksi, 'DELETE FROM dosen WHERE nip=$1', [$nip]);
    if ($res && pg_affected_rows($res) > 0) {
        pg_free_result($res);
        header('Location: dosen.php?msg=deleted'); exit;
    } else {
        header('Location: dosen.php?msg=error'); exit;
    }
}

// ambil data POST untuk add/edit
$nama = trim($_POST['nama'] ?? '');
$nidn = trim($_POST['nidn'] ?? '');
$email = trim($_POST['email'] ?? '');
$jabatan = trim($_POST['jabatan'] ?? '');
$pendidikan = trim($_POST['pendidikan'] ?? '');
$linkedin = trim($_POST['linkedin'] ?? '');
$google_scholar = trim($_POST['google_scholar'] ?? '');
$sinta = trim($_POST['sinta'] ?? '');
$scopus = trim($_POST['scopus'] ?? '');

// validasi wajib
if ($action !== 'delete' && ($nip === '' || $nama === '' || $email === '')) {
    $error = 'NIP, Nama, dan Email wajib diisi.';
}

// handle upload foto
$foto_saved = null;
if ($error === '' && !empty($_FILES['foto_file']['name'])) {
    $up = $_FILES['foto_file'];
    $ext = strtolower(pathinfo($up['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png'];
    if (!in_array($ext, $allowed)) $error = 'Tipe file foto tidak diperbolehkan.';
    else {
        $targetDir = '../assets/img/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        $filename = basename($up['name']);
        $targetFile = $targetDir . $filename;
        if (move_uploaded_file($up['tmp_name'], $targetFile)) $foto_saved = $filename;
        else $error = 'Gagal mengunggah foto.';
    }
}

if ($error === '') {
    if ($action === 'add') {
        $sql = 'INSERT INTO dosen (nip,nama,nidn,email,jabatan,foto,linkedin,google_scholar,sinta,scopus,pendidikan)
                VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11)';
        $params = [
            $nip,
            $nama,
            $nidn !== '' ? $nidn : null,
            $email,
            $jabatan !== '' ? $jabatan : null,
            $foto_saved,
            $linkedin !== '' ? $linkedin : null,
            $google_scholar !== '' ? $google_scholar : null,
            $sinta !== '' ? $sinta : null,
            $scopus !== '' ? $scopus : null,
            $pendidikan !== '' ? $pendidikan : null
        ];
        $res = pg_query_params($koneksi, $sql, $params);
        if ($res) { pg_free_result($res); header('Location: dosen.php?msg=created'); exit; }
        else $error = 'Gagal menyimpan data (cek NIP).';
    } elseif ($action === 'edit') {
        // ambil foto lama jika tidak diupload
        if (!$foto_saved) {
            $q = pg_query_params($koneksi, 'SELECT foto FROM dosen WHERE nip=$1', [$nip]);
            $r = pg_fetch_assoc($q);
            $foto_saved = $r['foto'] ?? null;
            pg_free_result($q);
        }
        $sql = 'UPDATE dosen SET nama=$1,nidn=$2,email=$3,jabatan=$4,foto=$5,linkedin=$6,google_scholar=$7,sinta=$8,scopus=$9,pendidikan=$10 WHERE nip=$11';
        $params = [
            $nama,
            $nidn !== '' ? $nidn : null,
            $email,
            $jabatan !== '' ? $jabatan : null,
            $foto_saved,
            $linkedin !== '' ? $linkedin : null,
            $google_scholar !== '' ? $google_scholar : null,
            $sinta !== '' ? $sinta : null,
            $scopus !== '' ? $scopus : null,
            $pendidikan !== '' ? $pendidikan : null,
            $nip
        ];
        $res = pg_query_params($koneksi, $sql, $params);
        if ($res) { pg_free_result($res); header('Location: dosen.php?msg=updated'); exit; }
        else $error = 'Gagal mengupdate data.';
    }
}

if ($error) {
    $back = $action === 'add' ? 'tambah_dosen.php' : 'edit_dosen.php?nip=' . urlencode($nip);
    header('Location: '.$back.'&msg=error&detail='.urlencode($error));
    exit;
}
