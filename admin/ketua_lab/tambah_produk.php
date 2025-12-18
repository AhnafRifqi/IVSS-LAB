<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php'); exit;
}

$msg = $_GET['msg'] ?? '';
$alert = ($msg === 'error') ? '<div class="alert alert-danger">Gagal menyimpan data. Cek kembali form Anda.</div>' : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tambah Produk - Admin</title>
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
                <span class="h5 mb-0 text-primary font-weight-bold">Tambah Produk Baru</span>
            </nav>

            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <?= $alert ?>
                        <form method="POST" enctype="multipart/form-data" action="proses_produk.php">
                            <input type="hidden" name="action" value="add">

                            <div class="form-group">
                                <label>Judul Produk</label>
                                <input type="text" name="judul" class="form-control" placeholder="Masukkan nama produk" required>
                            </div>

                            <div class="form-group">
                                <label>Deskripsi Produk</label>
                                <textarea name="deskripsi" id="summernote" class="form-control"></textarea>
                            </div>

                            <div class="form-group">
                                <label>Gambar Produk</label>
                                <input type="file" name="gambar_file" class="form-control-file" accept="image/*" required>
                                <small class="text-muted">Nama file akan disimpan sesuai nama asli image.</small>
                            </div>

                            <div class="form-group">
                                <label>Link Produk (Opsional)</label>
                                <input type="url" name="link" class="form-control" placeholder="https://...">
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Produk</button>
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
        placeholder: 'Tulis deskripsi detail produk di sini...',
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