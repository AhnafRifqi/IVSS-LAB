<?php
session_start();

include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: logo.php?msg=error');
    exit;
}

// ambil data existing
$q = pg_query_params($koneksi, 'SELECT id, logo FROM logo WHERE id = $1', array($id));
if (!$q || pg_num_rows($q) === 0) {
    header('Location: logo.php?msg=error');
    exit;
}
$row = pg_fetch_assoc($q);
pg_free_result($q);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $logo_saved = $row['logo']; // default tetap yang lama

    // jika ada upload file
    if (!empty($_FILES['logo_file']['name'])) {
        $up = $_FILES['logo_file'];
        if ($up['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($up['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp','svg'];
            if (!in_array($ext, $allowed)) {
                $error = 'Tipe file tidak diperbolehkan. (jpg/png/gif/webp/svg)';
            } else {
                // pastikan folder uploads ada
                if (!is_dir('../uploads/logo')) mkdir('../uploads/logo', 0755, true);
                $fname = time() . '_' . preg_replace('/[^a-z0-9._-]/i','_', $up['name']);
                $dest = '../uploads/logo/' . $fname;
                if (move_uploaded_file($up['tmp_name'], $dest)) {
                    // hapus file lama jika file lokal (bukan URL)
                    if ($logo_saved && !preg_match('#^https?://#i', $logo_saved)) {
                        $old = '../uploads/logo/' . $logo_saved;
                        if (is_file($old)) @unlink($old);
                    }
                    $logo_saved = 'uploads/logo/' . $fname; // simpan path relatif agar konsisten
                } else {
                    $error = 'Gagal mengunggah file.';
                }
            }
        } else {
            $error = 'Error saat upload file.';
        }
    } else {
        // jika user isi URL
        $logo_url = trim($_POST['logo_url'] ?? '');
        if ($logo_url !== '') {
            // jika sebelumnya file lokal dan diganti dengan URL, hapus file lama
            if ($logo_saved && !preg_match('#^https?://#i', $logo_saved)) {
                $old = '../' . ltrim($logo_saved, '/'); // karena kita menyimpan 'uploads/logo/xxx'
                if (is_file($old)) @unlink($old);
            }
            $logo_saved = $logo_url;
        }
    }

    if ($error === '') {
        $res = pg_query_params($koneksi, 'UPDATE logo SET logo = $1 WHERE id = $2', array($logo_saved !== '' ? $logo_saved : null, $id));
        if ($res) {
            if (isset($res)) pg_free_result($res);
            header('Location: logo.php?msg=updated');
            exit;
        } else {
            $error = 'Gagal menyimpan ke database.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit Logo - Admin</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .img-thumb { width: 160px; height: auto; border-radius:6px; display:block; margin-bottom:8px; }
    </style>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <span class="h5 mb-0 text-primary">Edit Logo</span>
            </nav>

            <div class="container-fluid">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Preview Saat Ini</label><br>
                                <?php if (!empty($row['logo'])):
                                    $g = $row['logo'];
                                    // jika path relatif (uploads/...), tampilkan dengan ../ di depan
                                    $src = preg_match('#^https?://#i', $g) ? $g : '../' . ltrim($g, '/');
                                ?>
                                    <img src="<?= htmlspecialchars($src) ?>" alt="logo" class="img-thumb">
                                <?php else: ?>
                                    <div class="text-muted small">Belum ada logo</div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label>Ganti Logo (upload file)</label>
                                <input type="file" name="logo_file" class="form-control-file mb-2">
                                <small class="form-text text-muted">Format: jpg/png/gif/webp/svg. Jika upload, akan menggantikan logo lama.</small>
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Simpan</button>
                                <a href="logo.php" class="btn btn-light">Batal</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>

        <?php include '../inc/footer.php'; ?>
    </div>
</div>

<!-- Scripts -->
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
