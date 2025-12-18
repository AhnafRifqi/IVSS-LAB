<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$error = '';
if (isset($_GET['msg']) && $_GET['msg'] === 'error') {
    $error = "Gagal menambah data: " . htmlspecialchars($_GET['error_detail'] ?? 'Terjadi kesalahan.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tambah Fasilitas / Peralatan</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <style>.img-thumb { width:140px; height:auto; border-radius:6px; object-fit:cover; display:none; margin-bottom:8px; }</style>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary font-weight-bold">Tambah Data</span>
            </nav>
            <div class="container-fluid">
                <div class="card shadow-sm border-left-primary">
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST" action="proses_fasilitas.php" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="tambah"> 

                            <div class="form-group">
                                <label class="font-weight-bold">Tipe Data</label>
                                <select name="tipe" class="form-control" required>
                                    <option value="fasilitas">Fasilitas</option>
                                    <option value="peralatan">Peralatan</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Nama</label>
                                <input type="text" name="nama" class="form-control" required placeholder="Contoh: Lab Komputer 1 / Mikroskop Cahaya">
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Foto (opsional)</label>
                                <input type="file" name="foto_file" class="form-control-file mb-2" accept="image/*" onchange="previewFile(event, 'preview')">
                                <img id="preview" class="img-thumb" alt="preview">
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Keterangan / Spesifikasi</label>
                                <textarea name="isi" rows="6" class="form-control" placeholder="Jelaskan detail fasilitas atau spesifikasi alat..."></textarea>
                            </div>

                            <hr>
                            <div class="d-flex">
                                <button class="btn btn-success mr-2" type="submit"><i class="fas fa-save"></i> Simpan Data</button>
                                <a href="fasilitas.php" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../inc/footer.php'; ?>
    </div>
</div>

<script>
function previewFile(e, id) {
    const file = e.target.files[0];
    const img = document.getElementById(id);
    if (!file) { img.style.display = 'none'; return; }
    const reader = new FileReader();
    reader.onload = function(ev) { img.src = ev.target.result; img.style.display = 'block'; };
    reader.readAsDataURL(file);
}
</script>
</body>
</html>