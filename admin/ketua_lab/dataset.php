<?php
session_start();
include '../inc/koneksi.php';

// 1. Cek Autentikasi
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$role    = $_SESSION['role'] ?? '';

// 2. Query Data Dataset sesuai Role dan Visibility
// Query ini menggabungkan Users, Mahasiswa, dan Dosen untuk mendapatkan nama uploader yang paling spesifik.
$base_query_select = "
    SELECT d.dataset_id, d.judul, d.deskripsi, d.file_path, d.uploader_by,
           d.tanggal_upload, d.visibility,
           u.username,
           COALESCE(m.nama, dos.nama, u.username) AS uploader_name,
           CASE WHEN m.user_id IS NOT NULL THEN 'mahasiswa'
                WHEN dos.user_id IS NOT NULL THEN 'dosen'
                ELSE 'user' END AS uploader_type
    FROM dataset d
    LEFT JOIN users u ON d.uploader_by = u.user_id
    LEFT JOIN mahasiswa m ON u.user_id = m.user_id
    LEFT JOIN dosen dos ON u.user_id = dos.user_id
";

if ($role === 'ketua') {
    // KETUA melihat: 1. Semua yang Publik, 2. Semua yang Privat (hanya jika di-upload oleh Mahasiswa/Dosen/User lain, tidak hanya dirinya sendiri)
    // Catatan: Query asli Anda ('privat' AND m.user_id IS NOT NULL) membatasi privat hanya ke mahasiswa. Saya pertahankan logikanya.
    $query_where = "WHERE d.visibility='publik' OR (d.visibility='privat' AND m.user_id IS NOT NULL)";
    $q = pg_query($koneksi, $base_query_select . $query_where . " ORDER BY d.tanggal_upload DESC");
} else {
    // ROLE LAIN melihat: 1. Semua yang Publik, 2. Semua yang di-upload oleh dirinya sendiri (uploader_by=$1)
    $query_where = "WHERE d.visibility='publik' OR d.uploader_by=$1";
    $q = pg_query_params($koneksi, $base_query_select . $query_where . " ORDER BY d.tanggal_upload DESC", [$user_id]);
}

$datasets = $q ? pg_fetch_all($q) : [];
if ($datasets === false) $datasets = [];
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
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Dataset</h6>
                            <a href="tambah_dataset.php" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Tambah Dataset</a>
                        </div>

                        <?php
                        if (isset($_GET['msg'])):
                            $msg = $_GET['msg'];
                            $alert_type = 'info';
                            $message = '';

                            if ($msg === 'created') { $alert_type = 'success'; $message = 'Dataset berhasil ditambahkan.'; }
                            elseif ($msg === 'updated') { $alert_type = 'success'; $message = 'Dataset berhasil diupdate.'; }
                            elseif ($msg === 'deleted') { $alert_type = 'success'; $message = 'Dataset berhasil dihapus.'; }
                            elseif ($msg === 'set_public_ok') { $alert_type = 'success'; $message = 'Dataset berhasil diset menjadi publik.'; }
                            elseif ($msg === 'forbidden') { $alert_type = 'warning'; $message = 'Aksi dilarang. Anda tidak memiliki izin untuk melakukan ini.'; }
                            elseif ($msg === 'forbidden_uploader') { $alert_type = 'warning'; $message = 'Aksi dilarang. Hanya dataset mahasiswa/dosen yang dapat diubah visibilitasnya oleh ketua.'; }
                            elseif ($msg === 'already_public') { $alert_type = 'info'; $message = 'Dataset sudah publik.'; }
                            elseif ($msg === 'error') { $alert_type = 'danger'; $message = 'Terjadi kesalahan saat memproses data.'; }
                            
                            if ($message): ?>
                                <div class="alert alert-<?= $alert_type ?> alert-dismissible fade show" role="alert">
                                    <?= htmlspecialchars($message) ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif;
                        endif;
                        ?>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="datasetTable" width="100%" cellspacing="0">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Deskripsi</th>
                                        <th>File</th>
                                        <th>Uploader</th>
                                        <th>Tgl. Upload</th>
                                        <th>Visibility</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($datasets)): ?>
                                        <tr><td colspan="8" class="text-center text-muted">Belum ada dataset yang dapat Anda lihat.</td></tr>
                                    <?php else: foreach ($datasets as $i => $d): ?>
                                        <tr>
                                            <td class="text-center"><?= $i + 1 ?></td>
                                            <td><?= htmlspecialchars($d['judul']) ?></td>
                                            <td><?= htmlspecialchars(mb_strimwidth($d['deskripsi'] ?? '', 0, 80, '...')) ?></td>
                                            <td class="text-center">
                                                <?php if (!empty($d['file_path'])): ?>
                                                    <a href="<?= htmlspecialchars($d['file_path'], ENT_QUOTES) ?>" target="_blank" class="btn btn-sm btn-info" title="Lihat File"><i class="fas fa-external-link-alt"></i></a>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($d['uploader_name']) ?>
                                                <small class="text-muted">(<?= htmlspecialchars(ucfirst($d['uploader_type'])) ?>)</small>
                                            </td>
                                            <td class="text-center"><?= htmlspecialchars($d['tanggal_upload']) ?></td>
                                            <td class="text-center"><?= htmlspecialchars(ucfirst($d['visibility'])) ?></td>
                                            <td class="text-center" style="min-width: 150px;">
                                                <?php 
                                                    $is_uploader = ($d['uploader_by'] == $user_id);
                                                    $canSetPublic = ($role === 'ketua' && $d['visibility'] === 'privat' && $d['uploader_type'] === 'mahasiswa');
                                                ?>

                                                <?php if ($is_uploader): // Hanya uploader yang bisa Edit/Hapus ?>
                                                    <a href="edit_dataset.php?id=<?= $d['dataset_id'] ?>" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                                    <a href="proses_dataset.php?action=delete&id=<?= $d['dataset_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus dataset ini?')" title="Hapus"><i class="fas fa-trash"></i></a>
                                                
                                                <?php elseif ($canSetPublic): // Ketua bisa set publik dataset mahasiswa privat ?>
                                                    <form method="POST" action="proses_dataset.php" style="display:inline-block;" onsubmit="return confirm('Set dataset ini menjadi publik?')">
                                                        <input type="hidden" name="action" value="set_public">
                                                        <input type="hidden" name="dataset_id" value="<?= (int)$d['dataset_id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-success" title="Set Publik"><i class="fas fa-globe"></i> Set Publik</button>
                                                    </form>
                                                
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; endif; ?>
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
<script src="../assets/js/sb-admin-2.min.js"></script>

<script>
$(document).ready(function(){
    $('#datasetTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Indonesian.json"
        },
        // Menonaktifkan sorting untuk kolom Deskripsi, File, dan Aksi
        "columnDefs": [ { "orderable": false, "targets": [2, 3, 7] } ]
    });

    // Menutup alert setelah 5 detik
    $(".alert").delay(5000).slideUp(300, function() {
        $(this).alert('close');
    });
});
</script>
</body>
</html>