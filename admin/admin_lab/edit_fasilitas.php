<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: fasilitas.php'); exit; }

// Ambil data (termasuk kolom tipe)
$q = pg_query_params($koneksi, 'SELECT id, nama, gambar, isi, tipe FROM fasilitas WHERE id=$1', [$id]);
if (!$q || pg_num_rows($q) === 0) {
    header('Location: fasilitas.php?msg=error&error_detail=' . urlencode('Data tidak ditemukan.'));
    exit;
}
$row = pg_fetch_assoc($q);

$error = (isset($_GET['msg']) && $_GET['msg'] === 'error') ? "Gagal update: " . htmlspecialchars($_GET['error_detail'] ?? '') : '';
$success = (isset($_GET['msg']) && $_GET['msg'] === 'updated') ? "Data berhasil diupdate." : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit Fasilitas / Peralatan</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <style>.img-thumb { width:160px; height:auto; border-radius:6px; object-fit:cover; display:block; margin-bottom:8px; border: 1px solid #ddd; }</style>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary font-weight-bold">Edit Data</span>
            </nav>
            <div class="container-fluid">
                <div class="card shadow-sm border-left-info">
                    <div class="card-body">
                        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
                        <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

                        <form method="POST" action="proses_fasilitas.php" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="edit"> 
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($row['gambar'] ?? '') ?>">

                            <div class="form-group">
                                <label class="font-weight-bold">Tipe Data</label>
                                <select name="tipe" class="form-control" required>
                                    <option value="fasilitas" <?= $row['tipe'] == 'fasilitas' ? 'selected' : '' ?>>Fasilitas (Ruangan/Gedung)</option>
                                    <option value="peralatan" <?= $row['tipe'] == 'peralatan' ? 'selected' : '' ?>>Peralatan (Alat Lab/Inventaris)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Nama</label>
                                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Foto</label><br>
                                <?php 
                                    $src = !empty($row['gambar']) ? "../assets/img/".htmlspecialchars($row['gambar']) : "";
                                    $display = empty($row['gambar']) ? "none" : "block";
                                ?>
                                <img id="preview" src="<?= $src ?>" class="img-thumb" style="display:<?= $display ?>;" alt="foto">
                                <input type="file" name="foto_file" class="form-control-file mt-2" accept="image/*" onchange="previewFile(event,'preview')">
                                <small class="text-muted italic">Abaikan jika tidak ingin mengubah foto.</small>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Keterangan / Spesifikasi</label>
                                <textarea name="isi" rows="6" class="form-control"><?= htmlspecialchars($row['isi']) ?></textarea>
                            </div>

                            <hr>
                            <div class="d-flex">
                                <button class="btn btn-primary mr-2" type="submit"><i class="fas fa-save"></i> Simpan Perubahan</button>
                                <a href="fasilitas.php" class="btn btn-secondary">Kembali</a>
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
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(ev) { img.src = ev.target.result; img.style.display = 'block'; };
    reader.readAsDataURL(file);
}
</script>
</body>
</html>