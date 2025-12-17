<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php'); exit;
}

$id = $_GET['id'] ?? '';
$q = pg_query_params($koneksi, "SELECT * FROM products WHERE id=$1", [$id]);
$row = pg_fetch_assoc($q);

if (!$row) { header('Location: produk.php'); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit Produk - Admin</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary font-weight-bold">Edit Produk: <?= htmlspecialchars($row['judul']) ?></span>
            </nav>

            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" action="proses_produk.php">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="existing_gambar" value="<?= $row['gambar'] ?>">

                            <div class="form-group">
                                <label>Judul Produk</label>
                                <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($row['judul']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Deskripsi Produk</label>
                                <textarea name="deskripsi" id="summernote" class="form-control"><?= $row['deskripsi'] ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Gambar Produk</label><br>
                                <img src="../assets/img/<?= $row['gambar'] ?>" class="img-thumbnail mb-2" style="max-width: 200px;">
                                <input type="file" name="gambar_file" class="form-control-file" accept="image/*">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar. Nama file asli akan tetap dipertahankan.</small>
                            </div>

                            <div class="form-group">
                                <label>Link Produk</label>
                                <input type="url" name="link" class="form-control" value="<?= htmlspecialchars($row['link']) ?>">
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Produk</button>
                                <a href="produk.php" class="btn btn-secondary">Batal</a>
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
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>

<script>
$(document).ready(function() {
    $('#summernote').summernote({
        height: 200,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview']]
        ]
    });
});
</script>
</body>
</html>