<?php
session_start();

// Cek autentikasi dan role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'berita') {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tambah Berita</title>

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
                    <span class="h5 text-primary">Tambah Berita</span>
                </nav>
                <div class="container-fluid">
                    <div class="card shadow">
                        <div class="card-body">

                            <form method="POST" action="proses_berita.php" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="create">

                                <div class="form-group">
                                    <label for="judul">Judul</label>
                                    <input type="text" id="judul" name="judul" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="penulis">Penulis</label>
                                    <input type="text" id="penulis" name="penulis" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="gambar_file">Gambar</label>
                                    <input type="file" id="gambar_file" name="gambar_file" class="form-control-file" accept="image/*">
                                </div>

                                <div class="form-group">
                                    <label for="isi">Isi Berita</label>
                                    <textarea id="isi" name="isi"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>

                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Simpan
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
                placeholder: 'Tulis isi berita di sini...',
                // Toolbar yang disesuaikan
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ]
            });
        });
    </script>

</body>
</html>