<?php
session_start();
include '../inc/koneksi.php';

//  role 'admin'
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$dataset_id = (int)($_GET['id'] ?? 0);
if ($dataset_id <= 0) {
    header('Location: dataset.php');
    exit;
}

// Ambil dataset untuk dicek ownership (READ)
$q = pg_query_params($koneksi, "SELECT * FROM dataset WHERE dataset_id = $1", [$dataset_id]);
$dataset = pg_fetch_assoc($q);
if ($q) pg_free_result($q);

if (!$dataset || $dataset['uploader_by'] != $user_id) {
    header('Location: dataset.php?msg=error&error_detail=' . urlencode('Dataset tidak ditemukan atau akses ditolak.'));
    exit;
}

// --- Penanganan Pesan Status dan Pengisian Ulang Form ---
$error = '';
$success = '';

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'updated') {
        $success = "Data berhasil diupdate.";
    } elseif ($_GET['msg'] === 'error') {
        $error = "Gagal update data: " . htmlspecialchars($_GET['error_detail'] ?? 'Terjadi kesalahan.');
        
        // Gunakan data POST dari kegagalan terakhir untuk mengisi ulang form
        $dataset['judul'] = $_POST['judul'] ?? $dataset['judul'];
        $dataset['deskripsi'] = $_POST['deskripsi'] ?? $dataset['deskripsi'];
        $dataset['file_path'] = $_POST['file_path'] ?? $dataset['file_path'];
        $dataset['visibility'] = $_POST['visibility'] ?? $dataset['visibility'];
    }
}
// Data awal (jika tidak ada error POST)
$judul_val = htmlspecialchars($dataset['judul'] ?? '');
$deskripsi_val = htmlspecialchars($dataset['deskripsi'] ?? '');
$file_path_val = htmlspecialchars($dataset['file_path'] ?? '');
$visibility_val = htmlspecialchars($dataset['visibility'] ?? 'privat');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Edit Dataset</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary">Edit Dataset</span>
            </nav>
            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php endif; ?>

                        <form method="POST" action="proses_dataset.php">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="dataset_id" value="<?= $dataset_id ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">Judul</label>
                                <input type="text" name="judul" class="form-control" required value="<?= $judul_val ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4"><?= $deskripsi_val ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">File (link)</label>
                                <input type="text" name="file_path" class="form-control" value="<?= $file_path_val ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Visibility</label>
                                <select name="visibility" class="form-control">
                                    <option value="publik" <?= $visibility_val=='publik'?'selected':'' ?>>Publik</option>
                                    <option value="privat" <?= $visibility_val=='privat'?'selected':'' ?>>Privat</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                            <a href="dataset.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
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