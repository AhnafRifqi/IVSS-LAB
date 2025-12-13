<?php
session_start();
include '../inc/koneksi.php';

// Cek autentikasi dan role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'berita') {
    header('Location: ../index.php');
    exit;
}

// Ambil berita (READ ONLY)
$q = pg_query($koneksi, '
    SELECT berita_id, judul, isi, gambar, penulis, tanggal
    FROM berita
    ORDER BY berita_id DESC
');

// Ambil hasil query, pastikan handle kasus kegagalan
$beritas = $q ? pg_fetch_all($q) : [];
if ($beritas === false) {
    $beritas = [];
}
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
        .img-thumb {
            width: 100px;
            border-radius: 6px;
        }
    </style>
</head>
<body id="page-top">

    <div id="wrapper">
        <?php include 'inc/sidebar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <nav class="navbar navbar-light bg-white shadow mb-4">
                    <span class="h5 mb-0 text-primary">Data Berita</span>
                </nav>

                <div class="container-fluid">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="font-weight-bold text-primary">Daftar Berita</h6>
                            <a href="tambah_berita.php" class="btn btn-sm btn-success">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="beritaTable">
                                    <thead class="table-primary text-center">
                                        <tr>
                                            <th>No</th>
                                            <th>Judul</th>
                                            <th>Gambar</th>
                                            <th>Penulis</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($beritas)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">Belum ada berita</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($beritas as $i => $b): ?>
                                                <tr>
                                                    <td class="text-center"><?= $i + 1 ?></td>
                                                    <td><?= htmlspecialchars($b['judul']) ?></td>
                                                    <td class="text-center">
                                                        <?php if ($b['gambar']): ?>
                                                            <img src="../assets/img/<?= htmlspecialchars($b['gambar']) ?>" class="img-thumb">
                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($b['penulis'] ?? '-') ?></td>
                                                    <td class="text-center"><?= htmlspecialchars($b['tanggal']) ?></td>
                                                    <td class="text-center">
                                                        <a href="edit_berita.php?id=<?= $b['berita_id'] ?>" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="proses_berita.php?action=delete&id=<?= $b['berita_id'] ?>"
                                                           onclick="return confirm('Hapus berita ini?')"
                                                           class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <?php include '../inc/footer.php'; ?>
        </div>
    </div>

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#beritaTable').DataTable();
        });
    </script>

</body>
</html>