<?php
session_start();

include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'berita') {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Ambil nama penulis dari dosen kalau ada
$q = pg_query_params($koneksi, 'SELECT nama FROM dosen WHERE user_id = $1', [$user_id]);
if ($q && pg_num_rows($q) > 0) {
    $row = pg_fetch_assoc($q);
    $nama_penulis = $row['nama'];
    pg_free_result($q);
} else {
    $nama_penulis = $username; // fallback ke username
}

// ambil semua berita
$q = pg_query($koneksi, 'SELECT berita_id, judul, isi, gambar, penulis, tanggal FROM berita ORDER BY berita_id DESC');
$beritas = $q ? pg_fetch_all($q) : [];
if ($beritas === false) $beritas = [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Manajemen Berita</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .img-thumb { width:100px; height:auto; border-radius:6px; object-fit:cover; }
        .truncate { max-width:420px; white-space: nowrap; overflow:hidden; text-overflow:ellipsis; }
    </style>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary">Data Berita</span>
            </nav>

            <div class="container-fluid">
                <div class="mb-3 text-right">
                    <a href="tambah_berita.php" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Tambah Berita</a>
                </div>

                <?php if (isset($_GET['msg'])):
                    $m = $_GET['msg'];
                    if ($m === 'created') echo '<div class="alert alert-success">Berita berhasil ditambahkan.</div>';
                    if ($m === 'updated') echo '<div class="alert alert-success">Berita berhasil diubah.</div>';
                    if ($m === 'deleted') echo '<div class="alert alert-warning">Berita berhasil dihapus.</div>';
                endif; ?>

                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Daftar Berita</h6></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="beritaTable" width="100%" cellspacing="0">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Gambar</th>
                                        <th>Isi (preview)</th>
                                        <th>Penulis</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (empty($beritas)): ?>
                                    <tr><td colspan="7" class="text-center text-muted">Belum ada data berita.</td></tr>
                                <?php else: foreach ($beritas as $i => $b): ?>
                                    <tr>
                                        <td class="text-center"><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($b['judul']) ?></td>
                                        <td class="text-center">
                                            <?php if (!empty($b['gambar'])): ?>
                                                <img src="<?= htmlspecialchars($b['gambar'], ENT_QUOTES) ?>" class="img-thumb" alt="gambar">
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="truncate" title="<?= htmlspecialchars($b['isi']) ?>"><?= htmlspecialchars(mb_strimwidth($b['isi'],0,120,'...')) ?></td>
                                        <td><?= htmlspecialchars($b['penulis'] ?? '-') ?></td>
                                        <td class="text-center"><?= htmlspecialchars($b['tanggal']) ?></td>
                                        <td class="text-center">
                                            <a href="edit_berita.php?id=<?= urlencode($b['berita_id']) ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                            <a href="hapus_berita.php?id=<?= urlencode($b['berita_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus berita ini?');"><i class="fas fa-trash"></i> Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div><!-- /.container -->
        </div><!-- /#content -->

        <?php include '../inc/footer.php'; ?>
    </div>
</div>

<!-- scripts -->
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>
<script>
$(document).ready(function(){
    $('#beritaTable').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": [2,3,6] }
        ]
    });
});
</script>
</body>
</html>
