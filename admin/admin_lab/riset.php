<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$alert = '';

// Handle POST: hapus riset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['riset_id'])) {
    $riset_id = intval($_POST['riset_id']);

    if ($_POST['action'] === 'delete') {
        $del = pg_query_params($koneksi, "
            DELETE FROM riset 
            WHERE riset_id = $1 AND creator_id = $2
        ", [$riset_id, $user_id]);

        if ($del && pg_affected_rows($del) > 0) {
            $alert = '<div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> Riset berhasil dihapus.
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>';
        } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle"></i> Gagal menghapus riset.
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>';
        }
    }
}

// Ambil daftar riset admin (dosen)
$q = pg_query_params($koneksi, "
    SELECT r.riset_id, r.judul, r.deskripsi, r.status,
           r.tanggal_mulai, r.tanggal_selesai, r.created_at
    FROM riset r
    WHERE r.creator_id = $1
    ORDER BY r.created_at DESC
", [$user_id]);

$riset_list = $q ? pg_fetch_all($q) : [];
if ($riset_list === false) $riset_list = [];

// Badge status
function getStatusBadge($status)
{
    switch ($status) {
        case 'approve_dosen_pembimbing':
            return '<span class="badge badge-info">
                <i class="fas fa-paper-plane"></i> Diajukan ke Ketua Lab
            </span>';
        case 'approve_ketua_lab':
            return '<span class="badge badge-success">
                <i class="fas fa-check-circle"></i> Approved Ketua Lab
            </span>';
        case 'rejected':
            return '<span class="badge badge-danger">
                <i class="fas fa-times-circle"></i> Ditolak
            </span>';
        default:
            return '<span class="badge badge-secondary">' . htmlspecialchars($status) . '</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Riset Saya (Admin Lab)</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .table td { vertical-align: middle; }
        .riset-description {
            max-width: 280px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>

<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary font-weight-bold">
                    <i class="fas fa-flask mr-2"></i>Riset Saya (Admin Lab)
                </span>
            </nav>

            <div class="container-fluid">
                <?= $alert ?>

                <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle"></i> Riset berhasil diperbarui.
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-list mr-2"></i>Daftar Riset Admin
                        </h6>
                        <a href="tambah_riset.php" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah Riset
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="risetTable">
                                <thead class="thead-light text-center">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Judul</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (empty($riset_list)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block text-gray-300"></i>
                                            Belum ada riset.
                                        </td>
                                    </tr>
                                <?php else:
                                    foreach ($riset_list as $i => $r): ?>
                                        <tr>
                                            <td class="text-center"><?= $i + 1 ?></td>
                                            <td><strong><?= htmlspecialchars($r['judul']) ?></strong></td>
                                            <td>
                                                <div class="riset-description"
                                                     title="<?= htmlspecialchars($r['deskripsi'] ?? '') ?>">
                                                    <?= htmlspecialchars($r['deskripsi'] ?? '-') ?>
                                                </div>
                                            </td>
                                            <td class="text-center"><?= getStatusBadge($r['status']) ?></td>
                                            <td class="text-center">
                                                <?= date('d/m/Y', strtotime($r['created_at'])) ?>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-info mb-1"
                                                    onclick="toggleDetail(<?= $r['riset_id'] ?>)">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                <a href="edit_riset.php?id=<?= $r['riset_id'] ?>"
                                                   class="btn btn-sm btn-warning mb-1">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <button class="btn btn-sm btn-danger mb-1"
                                                    onclick="confirmDelete(<?= $r['riset_id'] ?>, '<?= htmlspecialchars($r['judul'], ENT_QUOTES) ?>')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Detail -->
                                        <tr id="detail-<?= $r['riset_id'] ?>" style="display:none;" class="bg-light">
                                            <td colspan="6">
                                                <div class="p-3">
                                                    <p><strong>Judul:</strong><br><?= htmlspecialchars($r['judul']) ?></p>
                                                    <p><strong>Deskripsi:</strong><br><?= nl2br(htmlspecialchars($r['deskripsi'] ?? '-')) ?></p>
                                                    <?php if ($r['tanggal_mulai'] && $r['tanggal_selesai']): ?>
                                                        <p><strong>Periode:</strong><br>
                                                            <?= date('d/m/Y', strtotime($r['tanggal_mulai'])) ?>
                                                            s/d
                                                            <?= date('d/m/Y', strtotime($r['tanggal_selesai'])) ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
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

<!-- Form Delete -->
<form id="deleteForm" method="POST" style="display:none;">
    <input type="hidden" name="riset_id" id="deleteRisetId">
    <input type="hidden" name="action" value="delete">
</form>

<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>

<script>
function toggleDetail(id) {
    const row = document.getElementById('detail-' + id);
    document.querySelectorAll('[id^="detail-"]').forEach(r => r.style.display = 'none');
    row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
}

function confirmDelete(id, judul) {
    if (confirm('Yakin ingin menghapus riset:\n"' + judul + '" ?')) {
        document.getElementById('deleteRisetId').value = id;
        document.getElementById('deleteForm').submit();
    }
}

$(function () {
    $('#risetTable').DataTable({
        order: [[4, 'desc']],
        pageLength: 10,
        columnDefs: [
            { orderable: false, targets: [2,5] }
        ]
    });

    setTimeout(() => $('.alert').fadeOut('slow'), 5000);
});
</script>
</body>
</html>
