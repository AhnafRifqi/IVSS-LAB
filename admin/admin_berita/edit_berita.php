<?php
session_start();
include '../inc/koneksi.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'berita') { header('Location: ../index.php'); exit; }

$id = (int)$_GET['id'];
$row = pg_fetch_assoc(pg_query_params($koneksi, "SELECT * FROM berita WHERE berita_id=$1", [$id]));
if(!$row) { header('Location: berita.php'); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit Berita</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include 'inc/sidebar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-light bg-white shadow mb-4 text-primary font-weight-bold p-3">Edit Berita</nav>
                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <form method="POST" action="proses_berita.php" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?= $row['berita_id'] ?>">
                                
                                <div class="form-group">
                                    <label class="font-weight-bold">Judul Berita</label>
                                    <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($row['judul']) ?>" required>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Tipe Berita</label>
                                    <select name="tipe" class="form-control">
                                        <option value="umum" <?= $row['tipe'] == 'umum' ? 'selected' : '' ?>>Umum</option>
                                        <option value="pelatihan" <?= $row['tipe'] == 'pelatihan' ? 'selected' : '' ?>>Pelatihan</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Isi Berita</label>
                                    <textarea id="isi" name="isi"><?= $row['isi'] ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Penulis</label>
                                    <input type="text" name="penulis" class="form-control" value="<?= htmlspecialchars($row['penulis']) ?>">
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" value="<?= $row['tanggal'] ?>">
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Gambar Saat Ini</label><br>
                                    <img src="../assets/img/<?= htmlspecialchars($row['gambar'] ?: 'default.jpg') ?>" width="200" class="img-thumbnail mb-2">
                                    <br>
                                    <label class="font-weight-bold text-primary">Ganti Gambar</label>
                                    <input type="file" name="gambar_file" class="form-control-file" accept="image/*">
                                </div>

                                <hr>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Berita</button>
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