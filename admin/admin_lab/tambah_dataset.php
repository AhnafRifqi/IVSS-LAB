<?php
session_start();
include '../inc/koneksi.php';

//  role 'admin'
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$error = '';
// Menampilkan pesan error dari proses_dataset.php
if (isset($_GET['msg']) && $_GET['msg'] === 'error') {
    $error = "Gagal menambah data: " . htmlspecialchars($_GET['error_detail'] ?? 'Terjadi kesalahan.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Tambah Dataset</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary">Tambah Dataset</span>
            </nav>
            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="proses_dataset.php">
                            <input type="hidden" name="action" value="tambah">

                            <div class="mb-3">
                                <label class="form-label">Judul</label>
                                <input type="text" name="judul" class="form-control" required 
                                    value="<?= htmlspecialchars($_POST['judul'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">File (link)</label>
                                <input type="text" name="file_path" class="form-control"
                                    value="<?= htmlspecialchars($_POST['file_path'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Visibility</label>
                                <select name="visibility" class="form-control">
                                    <option value="publik" <?= ($_POST['visibility'] ?? '')=='publik'?'selected':'' ?>>Publik</option>
                                    <option value="privat" <?= ($_POST['visibility'] ?? 'privat')=='privat'?'selected':'' ?>>Privat</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Tambah</button>
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