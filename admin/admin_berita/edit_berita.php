<?php
session_start();
include '../inc/koneksi.php';

// Cek autentikasi dan role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'berita') {
    header('Location: ../login.php');
    exit;
}

// 1. Ambil dan validasi ID Berita dari URL
$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: berita.php');
    exit;
}

// 2. Ambil data berita berdasarkan ID menggunakan prepared statement
$query = pg_query_params(
    $koneksi,
    'SELECT * FROM berita WHERE berita_id = $1',
    [$id]
);
$berita = pg_fetch_assoc($query);

// 3. Cek apakah berita ditemukan
if (!$berita) {
    header('Location: berita.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit Berita - <?= htmlspecialchars($berita['judul'] ?? 'Berita') ?></title>

    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.css" rel="stylesheet">

    <style>
        .img-thumb {
            width: 180px;
            border-radius: 6px;
        }
    </style>
</head>
<body id="page-top">

    <div id="wrapper">
        <?php include 'inc/sidebar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <nav class="navbar navbar-light bg-white shadow mb-4">
                    <span class="h5 text-primary">Edit Berita</span>
                </nav>
                <div class="container-fluid">
                    <div class="card shadow">
                        <div class="card-body">

                            <form method="POST" action="proses_berita.php" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($berita['berita_id']) ?>">

                                <div class="form-group">
                                    <label for="judul">Judul</label>
                                    <input type="text" id="judul" name="judul" class="form-control"
                                           value="<?= htmlspecialchars($berita['judul']) ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="penulis">Penulis</label>
                                    <input type="text" id="penulis" name="penulis" class="form-control"
                                           value="<?= htmlspecialchars($berita['penulis']) ?>">
                                </div>

                                <div class="form-group">
                                    <label for="gambar_file">Gambar Baru</label>
                                    <input type="file" id="gambar_file" name="gambar_file" class="form-control-file" accept="image/*">
                                    
                                    <?php if (!empty($berita['gambar'])): ?>
                                        <div class="mt-2">
                                            <small class="text-muted">Gambar saat ini:</small><br>
                                            <img src="../assets/img/<?= htmlspecialchars($berita['gambar']) ?>" class="img-thumb">
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label for="isi">Isi Berita</label>
                                    <textarea id="isi" name="isi"><?= htmlspecialchars($berita['isi']) ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <input type="date" id="tanggal" name="tanggal" class="form-control"
                                           value="<?= htmlspecialchars($berita['tanggal']) ?>">
                                </div>

                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <a href="berita.php" class="btn btn-secondary">Batal</a>

                            </form>

                        </div>
                    </div>
                </div>

            </div>
            </div>
    </div>
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#isi').summernote({
                height: 300,
                placeholder: 'Edit isi berita...',
                // Toolbar yang disesuaikan
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ]
            });
        });
    </script>

</body>
</html>