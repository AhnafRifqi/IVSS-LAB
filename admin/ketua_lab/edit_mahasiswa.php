<?php
session_start();
include '../inc/koneksi.php';

// 1. Cek Autentikasi dan Role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

// 2. Ambil dan Validasi NIM dari URL
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

$nim = trim($_GET['nim'] ?? '');
if ($nim === '') { 
    header('Location: mahasiswa.php'); 
    exit; 
}

/* 3. Ambil data mahasiswa yang akan di-edit */
$q = pg_query_params($koneksi, 'SELECT * FROM mahasiswa WHERE nim=$1', [$nim]);
$row = pg_fetch_assoc($q);
pg_free_result($q);

if (!$row) { 
    // Data tidak ditemukan
    $_SESSION['error'] = "Data mahasiswa dengan NIM " . htmlspecialchars($nim) . " tidak ditemukan.";
    header('Location: mahasiswa.php'); 
    exit; 
}

/* 4. Ambil daftar dosen & users untuk tampilan info relasi */
$dosen_list = pg_fetch_all(pg_query($koneksi, "SELECT nip, nama FROM dosen ORDER BY nama ASC")) ?: [];
$users_list = pg_fetch_all(pg_query($koneksi, "SELECT user_id, username FROM users ORDER BY username ASC")) ?: [];

// Fungsi bantu untuk mencari nama dari list
function find_name($list, $key, $value, $return_key) {
    if (!$value || empty($list)) return null;
    foreach ($list as $item) {
        if ((string)$item[$key] === (string)$value) {
            return $item[$return_key];
        }
    }
    return null;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit Mahasiswa</title>

    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary">Edit Mahasiswa</span>
            </nav>

            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($error) ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="proses_mahasiswa.php">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="nim" value="<?= htmlspecialchars($row['nim']) ?>">

                            <h6 class="text-primary font-weight-bold mb-3">Data Pribadi</h6>

                            <div class="form-group">
                                <label for="nim_static">NIM</label>
                                <input type="text" id="nim_static" class="form-control" value="<?= htmlspecialchars($row['nim']) ?>" readonly>
                            </div>

                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" id="nama" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama']) ?>" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                    <input type="text" id="jenis_kelamin" class="form-control" value="<?= htmlspecialchars($row['jenis_kelamin'] ?? '-') ?>" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="no_telp">No. Telp</label>
                                    <input type="text" id="no_telp" name="no_telp" class="form-control" value="<?= htmlspecialchars($row['no_telp'] ?? '') ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="keperluan">Keperluan</label>
                                <textarea id="keperluan" name="keperluan" class="form-control" rows="3"><?= htmlspecialchars($row['keperluan'] ?? '') ?></textarea>
                            </div>

                            <hr class="my-4">
                            <h6 class="text-primary font-weight-bold mb-3">Data Akademik & Sistem</h6>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Dosen Pembimbing</label>
                                    <?php 
                                        $dosen_nip = $row['dosen_pembimbing'];
                                        $dosen_nama = find_name($dosen_list, 'nip', $dosen_nip, 'nama');
                                        $dosen_display = $dosen_nama ? htmlspecialchars($dosen_nama) . ' (' . htmlspecialchars($dosen_nip) . ')' : '-';
                                    ?>
                                    <input type="text" class="form-control" value="<?= $dosen_display ?>" readonly>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="status_mahasiswa">Status Mahasiswa</label>
                                    <select id="status_mahasiswa" name="status_mahasiswa" class="form-control">
                                        <option value="">-- pilih --</option>
                                        <option value="member" <?= (($row['status_mahasiswa'] ?? '') === 'member') ? 'selected' : '' ?>>Member</option>
                                        <option value="alumni" <?= (($row['status_mahasiswa'] ?? '') === 'alumni') ? 'selected' : '' ?>>Alumni</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Tanggal Daftar</label>
                                    <input type="date" class="form-control" value="<?= htmlspecialchars($row['tanggal_daftar'] ?? '') ?>" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Akun User Terkait</label>
                                <?php 
                                    $user_id_rel = $row['user_id'];
                                    $username = find_name($users_list, 'user_id', $user_id_rel, 'username');
                                    $user_display = $username ? htmlspecialchars($username) : '-';
                                ?>
                                <input type="text" class="form-control" value="<?= $user_display ?>" readonly>
                            </div>

                            <div class="mt-4 d-flex gap-2">
                                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Update Data</button>
                                <a href="mahasiswa.php" class="btn btn-secondary ml-2">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../inc/footer.php'; ?>
    </div>
</div>

<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script> 
</body>
</html>