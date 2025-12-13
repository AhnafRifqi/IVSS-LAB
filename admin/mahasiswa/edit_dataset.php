<?php
session_start();
include '../inc/koneksi.php';

// Pastikan hanya mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$dataset_id = $_GET['id'] ?? null;
if (!$dataset_id) {
    header('Location: dataset.php');
    exit;
}

// Ambil dataset, termasuk role uploader
$q = pg_query_params($koneksi, "SELECT d.*, u.role AS uploader_role FROM dataset d LEFT JOIN users u ON d.uploader_by = u.user_id WHERE d.dataset_id = $1", [$dataset_id]);
$dataset = pg_fetch_assoc($q);

if (!$dataset) {
    header('Location: dataset.php?msg=error&error_detail=' . urlencode('Dataset tidak ditemukan.'));
    exit;
}

// Tentukan apakah ini mode EDIT atau COPY
$is_owner = ($dataset['uploader_by'] == $user_id);
$is_ketua_dataset = ($dataset['uploader_role'] === 'ketua');

// Jika bukan pemilik DAN bukan dataset ketua, redirect.
if (!$is_owner && !$is_ketua_dataset) {
    header('Location: dataset.php?msg=error&error_detail=' . urlencode('Anda tidak memiliki izin untuk mengedit/menyalin dataset ini.'));
    exit;
}

// Tentukan action dan teks tombol untuk form
$action_mode = $is_owner ? 'update' : 'copy';
$submit_text = $is_owner ? 'Update' : 'Copy';
$title_text = $is_owner ? 'Edit Dataset' : 'Copy Dataset dari Ketua';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title><?= $title_text ?></title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary"><?= $title_text ?></span>
            </nav>
            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form method="POST" action="proses_dataset.php">
                            <input type="hidden" name="action" value="<?= $action_mode ?>">
                            <input type="hidden" name="dataset_id" value="<?= htmlspecialchars($dataset['dataset_id']) ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">Judul</label>
                                <input type="text" name="judul" class="form-control" required value="<?= htmlspecialchars($dataset['judul']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($dataset['deskripsi']) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">File (link)</label>
                                <input type="text" name="file_path" class="form-control" value="<?= htmlspecialchars($dataset['file_path']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Visibility</label>
                                <input type="text" class="form-control"
                                    value="<?= ucfirst(htmlspecialchars($dataset['visibility'])) ?>"
                                    readonly>
                                <input type="hidden" name="visibility" value="<?= htmlspecialchars($dataset['visibility']) ?>">
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> <?= $submit_text ?>
                            </button>
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