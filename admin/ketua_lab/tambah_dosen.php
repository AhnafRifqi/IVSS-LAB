<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = trim($_POST['nip'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $nidn = trim($_POST['nidn'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? '');
    $pendidikan = trim($_POST['pendidikan'] ?? '');
    $linkedin = trim($_POST['linkedin'] ?? '');
    $google_sch = trim($_POST['google_sch'] ?? '');
    $sinta = trim($_POST['sinta'] ?? '');
    $scopus = trim($_POST['scopus'] ?? '');

    if ($nip === '' || $nama === '' || $email === '') {
        $error = 'NIP, Nama, dan Email wajib diisi.';
    }

    $foto_saved = null;
    if ($error === '' && !empty($_FILES['foto_file']['name'])) {
        $up = $_FILES['foto_file'];
        $ext = strtolower(pathinfo($up['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png'];
        if (!in_array($ext, $allowed)) $error = 'Tipe file foto tidak diperbolehkan. (jpg, jpeg, png)';
        else {
            $bin = @file_get_contents($up['tmp_name']);
            if ($bin === false) $error = 'Gagal membaca file foto.';
            else $foto_saved = 'data:image/' . $ext . ';base64,' . base64_encode($bin);
        }
    }

    if ($error === '') {
        $sql = 'INSERT INTO dosen (nip, nama, nidn, email, jabatan, foto, linkedin, google_scholar, sinta, scopus, pendidikan)
                VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11)';
        $params = [
            $nip,
            $nama,
            $nidn !== '' ? $nidn : null,
            $email,
            $jabatan !== '' ? $jabatan : null,
            $foto_saved !== '' ? $foto_saved : null,
            $linkedin !== '' ? $linkedin : null,
            $google_sch !== '' ? $google_sch : null,
            $sinta !== '' ? $sinta : null,
            $scopus !== '' ? $scopus : null,
            $pendidikan !== '' ? $pendidikan : null
        ];
        $res = pg_query_params($koneksi, $sql, $params);
        if ($res) {
            pg_free_result($res);
            header('Location: dosen.php?msg=created');
            exit;
        } else {
            $error = 'Gagal menyimpan data (cek NIP).';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tambah Dosen</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <style>.img-thumb { width:100px; height:auto; border-radius:6px; object-fit:cover; margin-bottom:8px; }</style>
</head>
<body id="page-top">
<div id="wrapper">
<?php include 'inc/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
<div id="content">
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
    <span class="h5 mb-0 text-primary">Tambah Dosen</span>
</nav>
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-body">
            <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>NIP</label>
                        <input type="text" name="nip" class="form-control" value="<?= htmlspecialchars($_POST['nip'] ?? '') ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>NIDN</label>
                        <input type="text" name="nidn" class="form-control" value="<?= htmlspecialchars($_POST['nidn'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" value="<?= htmlspecialchars($_POST['jabatan'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Foto</label>
                    <input type="file" name="foto_file" class="form-control mt-2" accept=".jpg,.jpeg,.png">
                    <small class="form-text text-muted">Upload JPG/PNG; akan disimpan sebagai base64.</small>
                </div>

                <div class="form-group">
                    <label>Pendidikan</label>
                    <textarea name="pendidikan" class="form-control" rows="3"><?= htmlspecialchars($_POST['pendidikan'] ?? '') ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6"><label>LinkedIn</label><input type="text" name="linkedin" class="form-control" value="<?= htmlspecialchars($_POST['linkedin'] ?? '') ?>"></div>
                    <div class="form-group col-md-6"><label>Google Scholar</label><input type="text" name="google_sch" class="form-control" value="<?= htmlspecialchars($_POST['google_sch'] ?? '') ?>"></div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6"><label>Sinta</label><input type="text" name="sinta" class="form-control" value="<?= htmlspecialchars($_POST['sinta'] ?? '') ?>"></div>
                    <div class="form-group col-md-6"><label>Scopus</label><input type="text" name="scopus" class="form-control" value="<?= htmlspecialchars($_POST['scopus'] ?? '') ?>"></div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-success" type="submit"><i class="fas fa-save"></i> Simpan</button>
                    <a href="dosen.php" class="btn btn-secondary">Batal</a>
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
</body>
</html>
