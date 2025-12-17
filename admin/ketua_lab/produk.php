<?php
session_start();
include '../inc/koneksi.php';

// 1. Cek Autentikasi dan Role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php'); 
    exit;
}

// 2. Ambil data produk
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$res = pg_query($koneksi, $sql);

// 3. Ambil pesan notifikasi (Alert Msg)
$msg = $_GET['msg'] ?? '';
$detail = $_GET['detail'] ?? '';

$alert = '';
if ($msg === 'created') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> Produk baru telah ditambahkan.
                <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>';
} elseif ($msg === 'updated') {
    $alert = '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> Data produk telah diperbarui.
                <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>';
} elseif ($msg === 'deleted') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> Produk telah dihapus.
                <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>';
} elseif ($msg === 'error') {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Gagal!</strong> Terjadi kesalahan sistem. '.htmlspecialchars($detail).'
                <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Manajemen Produk - Admin</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .img-product { width: 70px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }
        .btn-xs { padding: .25rem .5rem; font-size: .75rem; }
        /* Style untuk kolom preview agar tetap rapi */
        .preview-box {
            font-size: 0.8rem;
            color: #858796;
            line-height: 1.4;
            max-width: 250px; /* Batasi lebar kolom */
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Maksimal 2 baris teks */
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary font-weight-bold">Manajemen Produk</span>
            </nav>

            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Daftar Produk</h1>
                    <a href="tambah_produk.php" class="btn btn-sm btn-success shadow-sm">
                        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Produk
                    </a>
                </div>

                <?= $alert ?>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Produk Lab</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead class="bg-light text-center">
                                    <tr>
                                        <th width="40">No</th>
                                        <th width="80">Gambar</th>
                                        <th>Nama Produk</th>
                                        <th>Deskripsi Preview</th>
                                        <th>Link</th>
                                        <th width="120">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    while ($row = pg_fetch_assoc($res)): 
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="text-center">
                                            <img src="../assets/img/<?= htmlspecialchars($row['gambar'] ?: 'default-product.jpg') ?>" class="img-product">
                                        </td>
                                        <td><strong><?= htmlspecialchars($row['judul']) ?></strong></td>
                                        <td>
                                            <div class="preview-box">
                                                <?= substr(strip_tags($row['deskripsi'] ?? $row['isi'] ?? ''), 0, 150) ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php if(!empty($row['link'])): ?>
                                                <a href="<?= htmlspecialchars($row['link']) ?>" target="_blank" class="badge badge-info p-2">
                                                    <i class="fas fa-external-link-alt"></i> Kunjungi
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small">No Link</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="edit_produk.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm btn-xs" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="proses_produk.php?action=delete&id=<?= $row['id'] ?>" 
                                                   class="btn btn-danger btn-sm btn-xs" 
                                                   onclick="return confirm('Hapus produk ini?')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
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
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });

        // Rules Refactor: Auto-hide alert
        $(".alert").delay(5000).slideUp(300, function() {
            $(this).alert('close');
        });
    });
</script>
</body>
</html>