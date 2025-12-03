<?php
session_start();

include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../login.php');
    exit;
}

$error = '';
$nip = isset($_GET['nip']) ? trim($_GET['nip']) : '';
if ($nip === '') { header('Location: dosen.php'); exit; }

// ambil data
$q = pg_query_params($koneksi, 'SELECT * FROM dosen WHERE nip=$1', array($nip));
if (!$q || pg_num_rows($q) === 0) { header('Location: dosen.php'); exit; }
$row = pg_fetch_assoc($q);
pg_free_result($q);

// ambil users
$users = pg_query($koneksi, 'SELECT user_id, username FROM users ORDER BY username ASC');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $nidn = trim($_POST['nidn'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? '');
    $pendidikan = trim($_POST['pendidikan'] ?? '');
    $user_id = isset($_POST['user_id']) && $_POST['user_id'] !== '' ? (int)$_POST['user_id'] : null;
    $linkedin = trim($_POST['linkedin'] ?? '');
    $google_scholar = trim($_POST['google_scholar'] ?? '');
    $sinta = trim($_POST['sinta'] ?? '');
    $scopus = trim($_POST['scopus'] ?? '');

    if ($nama === '' || $email === '') {
        $error = 'Nama dan Email wajib diisi.';
    }

    $foto_saved = $row['foto'];
    if ($error === '') {
        if (!empty($_FILES['foto_file']['name'])) {
            $up = $_FILES['foto_file'];
            $ext = strtolower(pathinfo($up['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png'];
            if (!in_array($ext, $allowed)) {
                $error = 'Tipe file foto tidak diperbolehkan. (jpg, jpeg, png)';
            } else {
                $bin = @file_get_contents($up['tmp_name']);
                if ($bin === false) {
                    $error = 'Gagal membaca file foto.';
                } else {
                    $foto_saved = 'data:image/' . $ext . ';base64,' . base64_encode($bin);
                }
            }
        }
    }


    if ($error === '') {
        $sql = 'UPDATE dosen SET nama=$1, nidn=$2, email=$3, jabatan=$4, foto=$5, user_id=$6, linkedin=$7, google_scholar=$8, sinta=$9, scopus=$10, pendidikan=$11 WHERE nip=$12';
        $params = array(
            $nama,
            $nidn !== '' ? $nidn : null,
            $email,
            $jabatan !== '' ? $jabatan : null,
            $foto_saved !== '' ? $foto_saved : null,
            $user_id,
            $linkedin !== '' ? $linkedin : null,
            $google_scholar !== '' ? $google_scholar : null,
            $sinta !== '' ? $sinta : null,
            $scopus !== '' ? $scopus : null,
            $pendidikan !== '' ? $pendidikan : null,
            $nip
        );
        $res = pg_query_params($koneksi, $sql, $params);
        if ($res) {
            pg_free_result($res);
            header('Location: dosen.php?msg=updated');
            exit;
        } else {
            $error = 'Gagal mengupdate data.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Edit Dosen</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
<style>
.img-thumb { width:100px; height:auto; border-radius:6px; object-fit:cover; }
</style>
</head>
<body id="page-top">
<div id="wrapper">
<?php include 'inc/sidebar.php'; ?>

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <span class="h5 mb-0 text-primary">Edit Dosen</span>
        </nav>

        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">

                    <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>NIP (tidak bisa diubah)</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($row['nip']) ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($_POST['nama'] ?? $row['nama']) ?>" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6"><label>NIDN</label><input type="text" name="nidn" class="form-control" value="<?= htmlspecialchars($_POST['nidn'] ?? $row['nidn']) ?>"></div>
                            <div class="form-group col-md-6"><label>Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? $row['email']) ?>" required></div>
                        </div>

                        <div class="form-group">
                            <label>Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" value="<?= htmlspecialchars($_POST['jabatan'] ?? $row['jabatan']) ?>">
                        </div>

                        <div class="form-group">
                            <label>Foto</label><br>
                            <?php if (!empty($row['foto'])): ?>
                                <img src="<?= htmlspecialchars($row['foto'], ENT_QUOTES) ?>" class="img-thumb" alt="foto" style="display:block;margin-bottom:8px;">
                            <?php endif; ?>
                            <input type="file" name="foto_file" class="form-control mt-2" accept=".jpg,.jpeg,.png">
                            <small class="form-text text-muted">Upload akan menggantikan foto saat ini. Foto disimpan sebagai base64 di DB.</small>
                        </div>

                        <div class="form-group">
                            <label>Pendidikan</label>
                            <textarea name="pendidikan" class="form-control" rows="3"><?= htmlspecialchars($_POST['pendidikan'] ?? $row['pendidikan']) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>User Account (opsional)</label>
                            <select name="user_id" class="form-control">
                                <option value="">-- Pilih akun (optional) --</option>
                                <?php if ($users): while ($u = pg_fetch_assoc($users)): ?>
                                    <option value="<?= (int)$u['user_id']; ?>" <?= ((isset($_POST['user_id']) && $_POST['user_id'] == $u['user_id']) || (!isset($_POST['user_id']) && $row['user_id']==$u['user_id'])) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($u['username']); ?>
                                    </option>
                                <?php endwhile; pg_free_result($users); endif; ?>
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6"><label>LinkedIn</label><input type="text" name="linkedin" class="form-control" value="<?= htmlspecialchars($_POST['linkedin'] ?? $row['linkedin']) ?>"></div>
                            <div class="form-group col-md-6"><label>Google Scholar</label><input type="text" name="google_scholar" class="form-control" value="<?= htmlspecialchars($_POST['google_scholar'] ?? $row['google_scholar']) ?>"></div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6"><label>Sinta</label><input type="text" name="sinta" class="form-control" value="<?= htmlspecialchars($_POST['sinta'] ?? $row['sinta']) ?>"></div>
                            <div class="form-group col-md-6"><label>Scopus</label><input type="text" name="scopus" class="form-control" value="<?= htmlspecialchars($_POST['scopus'] ?? $row['scopus']) ?>"></div>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Update</button>
                            <a href="dosen.php" class="btn btn-light">Batal</a>
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
