<?php
session_start();
include '../inc/koneksi.php';

// 1. Cek Autentikasi
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php'); 
    exit;
}

$user_id = $_SESSION['user_id'];
$dataset_id = (int) ($_GET['id'] ?? 0);

// 2. Ambil dan Validasi Data Dataset (Termasuk Kepemilikan)
$q = pg_query_params($koneksi,
    "SELECT * FROM dataset WHERE dataset_id = $1", 
    [$dataset_id]
);

$dataset = pg_fetch_assoc($q);
pg_free_result($q);

// Cek data ditemukan DAN uploader harus sama dengan user yang sedang login
if (!$dataset || (string)$dataset['uploader_by'] !== (string)$user_id) {
    // Jika data tidak ada atau bukan milik user ini, redirect
    header('Location: dataset.php');
    exit;
}

// 3. Ambil dan unset pesan dari session jika ada (untuk menampilkan notifikasi)
$alert = '';
if (isset($_SESSION['error_msg'])) {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . 
             htmlspecialchars($_SESSION['error_msg']) . 
             '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    unset($_SESSION['error_msg']);
}
if (isset($_SESSION['success_msg'])) {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">' . 
             htmlspecialchars($_SESSION['success_msg']) . 
             '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    unset($_SESSION['success_msg']);
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
                        <?= $alert ?>
                        
                        <form method="POST" action="proses_dataset.php">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="dataset_id" value="<?= htmlspecialchars($dataset_id) ?>">

                            <div class="form-group">
                                <label for="judul">Judul</label>
                                <input type="text" id="judul" name="judul" class="form-control" 
                                       required 
                                       value="<?= htmlspecialchars($dataset['judul']) ?>">
                            </div>

                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($dataset['deskripsi']) ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="file_path">File / Link (URL)</label>
                                <input type="url" id="file_path" name="file_path" class="form-control" 
                                       value="<?= htmlspecialchars($dataset['file_path']) ?>"
                                       placeholder="Contoh: https://link-ke-file-data.csv">
                            </div>

                            <div class="form-group">
                                <label for="visibility">Visibility</label>
                                <select id="visibility" name="visibility" class="form-control">
                                    <option value="publik" <?= ($dataset['visibility'] ?? '') == 'publik' ? 'selected' : '' ?>>Publik</option>
                                    <option value="privat" <?= ($dataset['visibility'] ?? '') == 'privat' ? 'selected' : '' ?>>Privat</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update
                            </button>
                            <a href="dataset.php" class="btn btn-secondary ml-2">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
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