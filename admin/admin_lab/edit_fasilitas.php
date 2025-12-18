<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: fasilitas.php');
    exit;
}

// ambil data existing (READ only)
$q = pg_query_params($koneksi, 'SELECT id, nama, gambar, isi FROM fasilitas WHERE id=$1', [$id]);
if (!$q || pg_num_rows($q) === 0) {
    header('Location: fasilitas.php?msg=error&error_detail=' . urlencode('Data fasilitas tidak ditemukan.'));
    exit;
}
$row = pg_fetch_assoc($q);
pg_free_result($q);

// --- Penanganan Pesan Status dari Redirect ---
$error = '';
$success = '';

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'updated') {
        $success = "Data berhasil diupdate.";
    } elseif ($_GET['msg'] === 'error') {
        $error = "Gagal update data: " . htmlspecialchars($_GET['error_detail'] ?? 'Terjadi kesalahan.');
        
        // Opsional: Jika terjadi error, gunakan data POST yang dikirimkan terakhir
        // untuk mengisi ulang form agar user tidak perlu mengetik ulang
        $row['nama'] = $_POST['nama'] ?? $row['nama'];
        $row['isi'] = $_POST['isi'] ?? $row['isi'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Edit Fasilitas</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
<style>
.img-thumb { width:140px; height:auto; border-radius:6px; object-fit:cover; display:block; margin-bottom:8px; }
</style>
</head>
<body id="page-top">
<div id="wrapper">
<?php include 'inc/sidebar.php'; ?>

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
        <span class="h5 mb-0 text-primary">Edit Fasilitas</span>
    </nav>

    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <form method="POST" action="proses_fasilitas.php" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="edit"> 
                    <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                    <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($row['gambar'] ?? '') ?>">

                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Foto</label><br>
                        <?php if (!empty($row['gambar'])): ?>
                            <img id="preview" src="../assets/img/<?= htmlspecialchars($row['gambar']) ?>" class="img-thumb" alt="foto">
                        <?php else: ?>
                            <img id="preview" src="" class="img-thumb" style="display:none;" alt="preview">
                        <?php endif; ?>

                        <input type="file" name="foto_file" class="form-control-file mt-2" accept="image/*" onchange="previewFile(event,'preview')">
                    </div>

                    <div class="form-group">
                        <label>Isi</label>
                        <textarea name="isi" rows="6" class="form-control"><?= htmlspecialchars($row['isi']) ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Update</button>
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
    if (!file) { img.style.display = 'none'; return; }
    const reader = new FileReader();
    reader.onload = function(ev) {
        img.src = ev.target.result;
        img.style.display = 'block';
    };
    reader.readAsDataURL(file);
}
</script>

<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>