<?php
session_start();
include '../inc/koneksi.php';

// 1. Cek Autentikasi dan Role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php'); 
    exit;
}

// 2. Ambil Data Perkuliahan Terkait
$result = pg_query($koneksi, "SELECT * FROM perkuliahan_terkait ORDER BY id ASC");
$perkuliahan_data = pg_fetch_all($result);
if ($perkuliahan_data === false) $perkuliahan_data = [];

// 3. Handle Notifikasi (mengambil dari sesi)
$alert = '';
if (isset($_SESSION['success_msg'])) {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">' . 
             htmlspecialchars($_SESSION['success_msg']) . 
             '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    unset($_SESSION['success_msg']);
} elseif (isset($_SESSION['error_msg'])) {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . 
             htmlspecialchars($_SESSION['error_msg']) . 
             '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    unset($_SESSION['error_msg']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Manajemen Perkuliahan Terkait</title>

    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    
    <style>
        .truncate { 
            max-width: 400px; /* Batasi lebar kolom Isi */
            white-space: nowrap; 
            overflow: hidden; 
            text-overflow: ellipsis; 
        } 
        .btn-xs { 
            padding: .25rem .5rem; 
            font-size: .75rem; 
            border-radius: .2rem; 
        }
    </style>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <span class="h5 mb-0 text-primary font-weight-bold">Data Perkuliahan Terkait</span>
            </nav>
            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Admin - Manajemen Perkuliahan Terkait</h1>
                
                <?= $alert ?>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Perkuliahan Terkait</h6>
                        <a href="tambah_perkuliahan_terkait.php" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah Data
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="perkuliahanTable" width="100%" cellspacing="0">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Isi (Preview)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; if (empty($perkuliahan_data)): ?>
                                        <tr><td colspan="4" class="text-center text-muted">Belum ada data perkuliahan terkait.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($perkuliahan_data as $row): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td><?= htmlspecialchars($row['judul']); ?></td>
                                            <td class="truncate"><?= $row['isi']; ?></td>
                                            <td class="text-center" style="min-width: 150px;">
                                                <a href="edit_perkuliahan_terkait.php?id=<?= $row['id']; ?>" class="btn btn-primary btn-xs mb-1"><i class="fas fa-edit"></i> Edit</a>
                                                <a href="proses_perkuliahan_terkait.php?action=delete&id=<?= $row['id']; ?>" 
                                                   class="btn btn-danger btn-xs mb-1"
                                                   onclick="return confirm('Anda yakin ingin menghapus data perkuliahan: <?= htmlspecialchars($row['judul']) ?>?');">
                                                    <i class="fas fa-trash"></i> Hapus
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
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>

<script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() { 
    $('#perkuliahanTable').DataTable({ 
        "columnDefs": [
            { "orderable": false, "targets": [2, 3] } // Kolom Isi dan Aksi tidak dapat diurutkan
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
        }
    }); 

    // Auto-hide alert setelah 5 detik
    $(".alert").delay(5000).slideUp(300, function() {
        $(this).alert('close');
    });
});
</script>
</body>
</html>