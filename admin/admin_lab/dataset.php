<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$q = pg_query_params($koneksi, "
    SELECT 
        d.*, 
        COALESCE(m.nama, dos.nama, u.username) AS uploader_name
    FROM dataset d
    LEFT JOIN users u ON d.uploader_by = u.user_id
    LEFT JOIN mahasiswa m ON u.user_id = m.user_id AND u.role = 'mahasiswa'
    LEFT JOIN dosen dos ON u.user_id = dos.user_id AND u.role = 'dosen'
    WHERE d.visibility = 'publik' OR d.uploader_by = $1
    ORDER BY d.tanggal_upload DESC
", [$user_id]);

$datasets = $q ? pg_fetch_all($q) : [];
if ($datasets === false) {
    $datasets = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dataset</title>

    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">

    <?php include 'inc/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary">Data Dataset</span>
            </nav>

            <div class="container-fluid">

                <?php if (isset($_GET['msg'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Aksi berhasil.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="card shadow mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center py-3">
                        <h6 class="font-weight-bold text-primary mb-0">Daftar Dataset</h6>
                        <a href="tambah_dataset.php" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="datasetTable" width="100%" cellspacing="0">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Deskripsi</th>
                                        <th>File</th>
                                        <th>Uploader</th>
                                        <th>Visibility</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($datasets as $i => $d): ?>
                                        <tr>
                                            <td class="text-center"><?= $i + 1 ?></td>
                                            <td><?= htmlspecialchars($d['judul']) ?></td>
                                            <td><?= htmlspecialchars(mb_strimwidth($d['deskripsi'] ?? '', 0, 60, '...')) ?></td>
                                            <td class="text-center">
                                                <?= $d['file_path'] ? '<a href="' . htmlspecialchars($d['file_path']) . '" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-download"></i> Link</a>' : '-' ?>
                                            </td>
                                            <td><?= htmlspecialchars($d['uploader_name']) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($d['visibility']) ?></td>
                                            <td class="text-center">
                                                <a href="edit_dataset.php?id=<?= $d['dataset_id'] ?>" class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="proses_dataset.php" method="POST" class="d-inline">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="dataset_id" value="<?= $d['dataset_id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus dataset ini?')" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        $('#datasetTable').DataTable();
    });
</script>
</body>
</html>