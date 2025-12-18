<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'berita') { header('Location: ../index.php'); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tambah Berita</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include 'inc/sidebar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-light bg-white shadow mb-4 text-primary font-weight-bold p-3">Tambah Berita Baru</nav>
                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <form method="POST" action="proses_berita.php" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="create">
                                
                                <div class="form-group">
                                    <label class="font-weight-bold">Judul Berita</label>
                                    <input type="text" name="judul" class="form-control" placeholder="Masukkan judul berita..." required>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Tipe Berita</label>
                                    <select name="tipe" class="form-control">
                                        <option value="umum">Umum</option>
                                        <option value="pelatihan">Pelatihan</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Isi Berita</label>
                                    <textarea id="isi" name="isi"></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Penulis</label>
                                    <input type="text" name="penulis" class="form-control" placeholder="Nama penulis...">
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Upload Gambar</label>
                                    <input type="file" name="gambar_file" class="form-control-file" accept="image/*">
                                    <small class="text-muted">Format: JPG, PNG, JPEG.</small>
                                </div>

                                <hr>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Berita</button>
                                    <a href="berita.php" class="btn btn-secondary">Batal</a>
                                </div>
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
    <script>$(document).ready(function() { $('#isi').summernote({height: 300}); });</script>
</body>
</html>