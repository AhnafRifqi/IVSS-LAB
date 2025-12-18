<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
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
    ORDER BY r.created_at DESC
";

$q = pg_query($koneksi, $sql);
$riset_list = $q ? pg_fetch_all($q) : [];
if ($riset_list === false) $riset_list = [];

function getStatusLabel($status) {
    $class = 'badge-secondary';
    if ($status == 'approved') $class = 'badge-success';
    if ($status == 'pending') $class = 'badge-warning';
    if ($status == 'rejected') $class = 'badge-danger';
    return "<span class='badge $class'>" . ucfirst($status) . "</span>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Semua Riset</title>
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
                <span class="h5 mb-0 text-gray-800">Monitoring Semua Riset (Dosen & Mahasiswa)</span>
            </nav>

            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="risetTable" width="100%">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Pengusul</th>
                                        <th>Judul Riset</th>
                                        <th>Pembimbing</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($riset_list as $i => $r): ?>
                                    <tr>
                                        <td class="text-center"><?= $i + 1 ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($r['nama_pengusul'] ?? 'Akun Dihapus') ?></strong><br>
                                            <small class="text-muted"><?= htmlspecialchars($r['identitas_pengusul'] ?? '-') ?></small>
                                        </td>
                                        <td><?= htmlspecialchars($r['judul']) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($r['nama_pembimbing'] ?? '-') ?></td>
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
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">Detail Informasi Riset</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Nama Pengusul:</p>
                                                            <h6 class="font-weight-bold"><?= htmlspecialchars($r['nama_pengusul']) ?></h6>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">NIM/NIP:</p>
                                                            <h6><?= htmlspecialchars($r['identitas_pengusul']) ?></h6>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="mb-3">
                                                        <p class="mb-1 text-muted">Judul:</p>
                                                        <h6 class="font-weight-bold text-dark"><?= htmlspecialchars($r['judul']) ?></h6>
                                                    </div>
                                                    <div class="mb-3">
                                                        <p class="mb-1 text-muted">Deskripsi:</p>
                                                        <div class="p-3 bg-light border rounded">
                                                            <?= nl2br(htmlspecialchars($r['deskripsi'] ?? 'Tidak ada deskripsi.')) ?>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Periode:</p>
                                                            <p><?= $r['tanggal_mulai'] ?? '-' ?> s/d <?= $r['tanggal_selesai'] ?? '-' ?></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Status:</p>
                                                            <?= getStatusLabel($r['status']) ?>
                                                        </div>
                                                    </div>
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
    </div>
</div>

<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script>$(document).ready(function(){ $('#risetTable').DataTable(); });</script>
</body>
</html>