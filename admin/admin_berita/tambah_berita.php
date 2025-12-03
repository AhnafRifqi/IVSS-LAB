<?php
session_start();

include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'berita') {
    header('Location: ../login.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul   = trim($_POST['judul'] ?? '');
    $penulis = trim($_POST['penulis'] ?? '');
    $isi     = trim($_POST['isi'] ?? '');
    $tanggal = trim($_POST['tanggal'] ?? '') ?: date('Y-m-d');

    if ($judul === '') $error = 'Judul wajib diisi.';
    if ($isi === '') $error = $error ? $error . ' Isi juga wajib.' : 'Isi wajib diisi.';

    $foto_saved = null;
    if (empty($error) && !empty($_FILES['gambar_file']['name'])) {
        $up = $_FILES['gambar_file'];
        $maxBytes = 3 * 1024 * 1024;
        if ($up['error'] !== UPLOAD_ERR_OK) {
            $error = 'Gagal upload file.';
        } elseif ($up['size'] > $maxBytes) {
            $error = 'Ukuran file terlalu besar (maks 3MB).';
        } else {
            $imgInfo = @getimagesize($up['tmp_name']);
            if ($imgInfo === false) {
                $error = 'File bukan gambar valid.';
            } else {
                $raw = file_get_contents($up['tmp_name']);
                $foto_saved = 'data:' . $imgInfo['mime'] . ';base64,' . base64_encode($raw);
            }
        }
    }

    if (empty($error)) {
        $sql = 'INSERT INTO berita (judul, isi, gambar, penulis, tanggal) VALUES ($1,$2,$3,$4,$5)';
        $res = pg_query_params($koneksi, $sql, [
            $judul,
            $isi !== '' ? $isi : null,
            $foto_saved !== null ? $foto_saved : null,
            $penulis !== '' ? $penulis : null,
            $tanggal
        ]);
        if ($res) {
            pg_free_result($res);
            header('Location: berita.php?msg=created');
            exit;
        } else {
            $error = 'Gagal menyimpan berita. Periksa konfigurasi DB.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tambah Berita</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <style>.img-thumb{ width:180px; height:auto; border-radius:6px; object-fit:cover; display:block; margin-bottom:8px; }</style>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary">Tambah Berita</span>
            </nav>

            <div class="container-fluid">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Judul</label>
                                <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($_POST['judul'] ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Penulis</label>
                                <input type="text" name="penulis" class="form-control" value="<?= htmlspecialchars($_POST['penulis'] ?? '') ?>">
                            </div>

                            <div class="form-group">
                                <label>Gambar (opsional)</label>
                                <input type="file" name="gambar_file" class="form-control-file mb-2" accept="image/*" onchange="previewFile(event,'preview')">
                                <small class="form-text text-muted">jpg/jpeg/png/gif/webp — max 3MB.</small>
                                <img id="preview" class="img-thumb" style="display:none;" alt="preview">
                            </div>

                            <div class="form-group">
                                <label>Isi</label>
                                <textarea name="isi" rows="8" class="form-control"><?= htmlspecialchars($_POST['isi'] ?? '') ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" value="<?= htmlspecialchars($_POST['tanggal'] ?? date('Y-m-d')) ?>">
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-success" type="submit"><i class="fas fa-save"></i> Simpan</button>
                                <a href="berita.php" class="btn btn-secondary">Batal</a>
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
    if (!file) { img.style.display='none'; return; }
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
