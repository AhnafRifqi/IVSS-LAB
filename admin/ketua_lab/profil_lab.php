<?php
session_start();
include '../inc/koneksi.php';

// 1. Cek Autentikasi dan Role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php'); 
    exit;
}

// 2. Ambil Data Profil Lab
$result = pg_query($koneksi, "SELECT * FROM profil_lab ORDER BY id ASC");
$profil_lab_data = pg_fetch_all($result);
if ($profil_lab_data === false) $profil_lab_data = [];

// 3. Handle Notifikasi (jika ada)
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
    <title>Manajemen Profil Lab</title>

    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    
    <style>
        .truncate { 
            max-width: 400px; /* Lebar yang cukup untuk preview */
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
                <span class="h5 mb-0 text-primary font-weight-bold">Data Profil Lab</span>
            </nav>
            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Manajemen Profil Lab</h1>
                
                <?= $alert ?>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Profil Lab</h6>
                        <a href="tambah_profil_lab.php" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah Data
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="profilTable" width="100%" cellspacing="0">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Isi (Preview)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($profil_lab_data)): ?>
                                        <tr><td colspan="4" class="text-center text-muted">Belum ada data profil lab.</td></tr>
                                    <?php else: ?>
                                        <?php $no = 1; foreach($profil_lab_data as $row): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td><?= htmlspecialchars($row['judul']); ?></td>
                                            <td class="truncate"><?= $row['isi']; ?></td>
                                            <td class="text-center" style="min-width: 100px;">
                                                <a href="edit_profil_lab.php?id=<?= $row['id']; ?>" class="btn btn-primary btn-xs"><i class="fas fa-edit"></i> Edit</a>
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
    $('#profilTable').DataTable({ 
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