<?php
session_start();
include '../inc/koneksi.php';

// Proteksi halaman Admin Lab
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$sql = "
    SELECT r.*, 
           COALESCE(m.nama, d.nama) AS nama_pengusul,
           COALESCE(m.nim, d.nip) AS identitas_pengusul,
           dp.nama AS nama_pembimbing
    FROM riset r
    LEFT JOIN mahasiswa m ON r.creator_id = m.user_id
    LEFT JOIN dosen d ON r.creator_id = d.user_id
    LEFT JOIN dosen dp ON r.approved_dosen_by = dp.user_id
    WHERE r.status = 'approve_ketua_lab' 
    ORDER BY r.created_at DESC
";

$q = pg_query($koneksi, $sql);
$riset_list = $q ? pg_fetch_all($q) : [];
if ($riset_list === false) $riset_list = [];

function getStatusLabel($status) {
    return "<span class='badge badge-success'>Disetujui</span>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Riset Disetujui</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <span class="h5 mb-0 text-gray-800 font-weight-bold">Arsip Riset (Status: Approved)</span>
            </nav>

            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Riset Mahasiswa & Dosen</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="risetTable" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Pengusul</th>
                                        <th>Judul Riset</th>
                                        <th>Pembimbing</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($riset_list as $i => $r): ?>
                                    <tr>
                                        <td class="text-center"><?= $i + 1 ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($r['nama_pengusul'] ?? 'N/A') ?></strong><br>
                                            <small class="text-muted"><?= htmlspecialchars($r['identitas_pengusul'] ?? '-') ?></small>
                                        </td>
                                        <td><?= htmlspecialchars($r['judul']) ?></td>
                                        <td><?= htmlspecialchars($r['nama_pembimbing'] ?? '-') ?></td>
                                        <td class="text-center"><?= getStatusLabel($r['status']) ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#detailModal<?= $r['riset_id'] ?>">
                                                <i class="fas fa-eye"></i> Detail
                                            </button>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="detailModal<?= $r['riset_id'] ?>" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title font-weight-bold">Informasi Detail Riset</h5>
                                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6 border-right">
                                                            <p class="mb-1 text-xs font-weight-bold text-uppercase text-muted">Pengusul</p>
                                                            <p class="h6 font-weight-bold"><?= htmlspecialchars($r['nama_pengusul']) ?></p>
                                                            <p class="text-muted small"><?= htmlspecialchars($r['identitas_pengusul']) ?></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-xs font-weight-bold text-uppercase text-muted">Pembimbing</p>
                                                            <p class="h6"><?= htmlspecialchars($r['nama_pembimbing'] ?? 'Tidak ada') ?></p>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <p class="mb-1 text-xs font-weight-bold text-uppercase text-muted">Judul Riset</p>
                                                    <p class="text-dark font-weight-bold"><?= htmlspecialchars($r['judul']) ?></p>
                                                    
                                                    <p class="mb-1 text-xs font-weight-bold text-uppercase text-muted">Deskripsi</p>
                                                    <div class="p-3 bg-light rounded" style="min-height: 100px;">
                                                        <?= nl2br(htmlspecialchars($r['deskripsi'] ?? 'Deskripsi kosong.')) ?>
                                                    </div>
                                                    
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-xs font-weight-bold text-uppercase text-muted">Durasi Penelitian</p>
                                                            <p class="small text-dark">
                                                                <i class="far fa-calendar-alt mr-1"></i> 
                                                                <?= $r['tanggal_mulai'] ? date('d/m/Y', strtotime($r['tanggal_mulai'])) : '-' ?> 
                                                                s/d 
                                                                <?= $r['tanggal_selesai'] ? date('d/m/Y', strtotime($r['tanggal_selesai'])) : '-' ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
<script src="../assets/js/sb-admin-2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#risetTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });
    });
</script>
</body>
</html>