<?php
session_start();
include '../inc/koneksi.php';

// 1. Cek Autentikasi dan Role
if (
    !isset($_SESSION['user_id']) ||
    ($_SESSION['role'] ?? '') !== 'admin'
) {
    header('Location: ../index.php');
    exit;
}

// 2. Ambil dan Validasi ID Dataset
$id = (int) ($_GET['id'] ?? 0);

// Query data dataset
$q = pg_query_params($koneksi, "SELECT * FROM dataset WHERE dataset_id = $1", [$id]);
$d = pg_fetch_assoc($q);

// Cek apakah data ditemukan
if (!$d) {
    header('Location: dataset.php');
    exit;
}
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
                        <form action="proses_dataset.php" method="POST">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="dataset_id" value="<?= htmlspecialchars($d['dataset_id']) ?>">

                            <div class="form-group">
                                <label for="judul">Judul</label>
                                <input type="text" id="judul" name="judul" class="form-control"
                                       value="<?= htmlspecialchars($d['judul']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($d['deskripsi']) ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="file_path">File (link/URL)</label>
                                <input type="text" id="file_path" name="file_path" class="form-control"
                                       value="<?= htmlspecialchars($d['file_path']) ?>">
                            </div>

                            <div class="form-group">
                                <label for="visibility">Visibility</label>
                                <select id="visibility" name="visibility" class="form-control">
                                    <option value="publik" <?= ($d['visibility'] ?? '') == 'publik' ? 'selected' : '' ?>>Publik</option>
                                    <option value="privat" <?= ($d['visibility'] ?? '') == 'privat' ? 'selected' : '' ?>>Privat</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                            <a href="dataset.php" class="btn btn-secondary">Batal</a>

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
</body>
</html>