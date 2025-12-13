<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['action'] ?? '') !== 'update_profile') {
    header('Location: edit_profil.php');
    exit;
}

// ambil data lama
$old = pg_query_params(
    $koneksi,
    'SELECT u.username, u.role, d.* FROM users u 
     LEFT JOIN dosen d ON d.user_id=u.user_id WHERE u.user_id=$1',
    [$user_id]
);
$row = pg_fetch_assoc($old);
pg_free_result($old);

// input
$username = trim($_POST['username'] ?? '');
$nip      = trim($_POST['nip'] ?? '');
$nidn     = trim($_POST['nidn'] ?? '');
$nama     = trim($_POST['nama'] ?? '');
$email    = trim($_POST['email'] ?? '');
$jabatan  = trim($_POST['jabatan'] ?? '');
$pend     = trim($_POST['pendidikan'] ?? '');
$linkedin = trim($_POST['linkedin'] ?? '');
$gs       = trim($_POST['google_scholar'] ?? '');
$sinta    = trim($_POST['sinta'] ?? '');
$scopus   = trim($_POST['scopus'] ?? '');

if ($username===''||$nip===''||$nama===''||$email==='') {
    $_SESSION['error'] = 'Username, NIP, Nama, dan Email wajib diisi.';
    header('Location: edit_profil.php'); exit;
}

// cek nip unik
$cek = pg_query_params($koneksi,
    'SELECT 1 FROM dosen WHERE nip=$1 AND user_id<>$2',
    [$nip,$user_id]
);
if ($cek && pg_num_rows($cek)>0) {
    $_SESSION['error'] = 'NIP sudah digunakan.';
    header('Location: edit_profil.php'); exit;
}
pg_free_result($cek);

// foto
$foto = $row['foto'];
if (!empty($_FILES['foto_file']['name'])) {
    $ext = strtolower(pathinfo($_FILES['foto_file']['name'],PATHINFO_EXTENSION));
    if (!in_array($ext,['jpg','jpeg','png'])) {
        $_SESSION['error']='Format foto harus jpg/png.';
        header('Location: edit_profil.php'); exit;
    }
    $name=time().'_'.preg_replace('/[^a-z0-9_.-]/i','',$_FILES['foto_file']['name']);
    move_uploaded_file($_FILES['foto_file']['tmp_name'],'../assets/img/'.$name);
    $foto=$name;
}

// update
pg_query_params($koneksi,
    'UPDATE users SET username=$1 WHERE user_id=$2',
    [$username,$user_id]
);

pg_query_params($koneksi,
    'UPDATE dosen SET nip=$1,nidn=$2,nama=$3,email=$4,jabatan=$5,foto=$6,
     pendidikan=$7,linkedin=$8,google_scholar=$9,sinta=$10,scopus=$11
     WHERE user_id=$12',
    [$nip,$nidn,$nama,$email,$jabatan,$foto,$pend,$linkedin,$gs,$sinta,$scopus,$user_id]
);

$_SESSION['username']=$username;
$_SESSION['success']='Profil berhasil diperbarui.';
header('Location: edit_profil.php');
