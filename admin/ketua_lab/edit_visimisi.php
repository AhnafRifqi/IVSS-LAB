<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$error = '';
$success = '';
$user_id = $_SESSION['user_id'];

// ambil password lama user dari DB
$q = pg_query_params($koneksi, 'SELECT password FROM users WHERE user_id = $1', [$user_id]);
if (!$q || pg_num_rows($q) === 0) {
    header('Location: profil.php?msg=user_not_found');
    exit;
}
$row = pg_fetch_assoc($q);
pg_free_result($q);

// handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password     = trim($_POST['old_password'] ?? '');
    $new_password     = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if ($new_password !== $confirm_password) {
        $error = 'Password baru dan konfirmasi tidak sama.';
    } elseif ($old_password !== $row['password']) { // kalo pake plain text, kalau hash pakai password_verify
        $error = 'Password lama salah.';
    } else {
        $update = pg_query_params($koneksi, 'UPDATE users SET password = $1 WHERE user_id = $2', [$new_password, $user_id]);
        if ($update) {
            $success = 'Password berhasil diubah.';
        } else {
            $error = 'Gagal mengubah password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Ubah Password</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary font-weight-bold">Ubah Password</span>
            </nav>

            <div class="container-fluid">
                <div class="card shadow-sm">
                    <div class="card-header"><strong>Ganti Password</strong></div>
                    <div class="card-body">

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="form-group">
                                <label>Password Lama</label>
                                <input type="password" name="old_password" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Password Baru</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Konfirmasi Password Baru</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Update</button>
                                <a href="profil.php" class="btn btn-light"><i class="fas fa-arrow-left"></i> Kembali</a>
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
