<?php
session_start();
include '../inc/koneksi.php';

// Proteksi halaman: Hanya role 'ketua' yang bisa mengakses manajemen fasilitas
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

// Ambil data fasilitas dan peralatan dari database
// Diurutkan berdasarkan tipe (Fasilitas dulu baru Peralatan) dan Nama
$res = pg_query($koneksi, 'SELECT id, nama, gambar, isi, tipe FROM fasilitas ORDER BY tipe ASC, nama ASC');
$fasilitas = $res ? pg_fetch_all($res) : [];
if ($fasilitas === false) $fasilitas = [];
if ($res) pg_free_result($res);

// Path folder gambar
$img_path = '../assets/img/';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Manajemen Fasilitas & Peralatan - Admin</title>

    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    
    <style>
        .img-thumb { 
            width: 100px; 
            height: 70px; 
            border-radius: 6px; 
            object-fit: cover; 
            border: 1px solid #ddd;
        }
        .truncate { 
            max-width: 400px; 
            white-space: nowrap; 
            overflow: hidden; 
            text-overflow: ellipsis; 
        }
        .btn-xs { 
            padding: .25rem .5rem; 
            font-size: .75rem; 
            border-radius: .2rem; 
        }
        .badge-peralatan { background-color: #17a2b8; color: white; }
        .badge-fasilitas { background-color: #6c757d; color: white; }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">

        <?php include 'inc/sidebar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <span class="h5 mb-0 text-primary font-weight-bold">Manajemen Inventaris Laboratorium</span>
                </nav>

                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Daftar Fasilitas & Peralatan</h1>
                        <a href="tambah_fasilitas.php" class="btn btn-success btn-sm shadow-sm">
                            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Data Baru
                        </a>
                    </div>

                    <?php
                    if (isset($_GET['msg'])) {
                        $m = $_GET['msg'];
                        if ($m === 'created') echo '<div class="alert alert-success alert-dismissible fade show">Data berhasil ditambahkan!<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
                        if ($m === 'updated') echo '<div class="alert alert-info alert-dismissible fade show">Data berhasil diperbarui.<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
                        if ($m === 'deleted') echo '<div class="alert alert-warning alert-dismissible fade show">Data telah dihapus.<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
                        if ($m === 'error') echo '<div class="alert alert-danger alert-dismissible fade show">Terjadi kesalahan: ' . htmlspecialchars($_GET['error_detail'] ?? 'N/A') . '<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
                    }
                    ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Inventaris</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="fasilitasTable" width="100%" cellspacing="0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="12%">Tipe</th>
                                            <th>Nama Item</th>
                                            <th width="15%">Foto</th>
                                            <th>Keterangan</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; if (!empty($fasilitas)): foreach ($fasilitas as $row): ?>
                                            <tr>
                                                <td class="text-center"><?= $no++; ?></td>
                                                <td class="text-center">
                                                    <?php if ($row['tipe'] === 'peralatan'): ?>
                                                        <span class="badge badge-peralatan">
                                                            <i class="fas fa-tools fa-xs mr-1"></i> Peralatan
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge badge-fasilitas">
                                                            <i class="fas fa-building fa-xs mr-1"></i> Fasilitas
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="font-weight-bold text-dark"><?= htmlspecialchars($row['nama']) ?></td>
                                                <td class="text-center">
                                                    <?php if (!empty($row['gambar']) && is_file($img_path . $row['gambar'])): ?>
                                                        <img src="<?= $img_path . htmlspecialchars($row['gambar']) ?>" class="img-thumb" alt="item">
                                                    <?php else: ?>
                                                        <small class="text-muted italic">Tidak ada foto</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="truncate" title="<?= htmlspecialchars($row['isi']) ?>">
                                                    <?= htmlspecialchars($row['isi']) ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="edit_fasilitas.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-xs">
                                                        <i class="fas fa-edit mr-1"></i> Edit
                                                    </a>
                                                    <a href="proses_fasilitas.php?action=hapus&id=<?= $row['id'] ?>" 
                                                       class="btn btn-danger btn-xs"
                                                       onclick="return confirm('Apakah Anda yakin ingin menghapus data ini? Foto juga akan dihapus dari server.');">
                                                        <i class="fas fa-trash mr-1"></i> Hapus
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">Belum ada data inventaris.</td>
                                            </tr>
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
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../assets/js/sb-admin-2.min.js"></script>
    
    <script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#fasilitasTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "columnDefs": [
                    { "orderable": false, "targets": [3, 5] } // Matikan sorting untuk kolom gambar dan aksi
                ],
                "order": [[ 1, "asc" ]] // Default sort berdasarkan Tipe
            });
        });
    </script>
</body>
</html>