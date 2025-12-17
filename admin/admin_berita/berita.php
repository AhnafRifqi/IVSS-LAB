<?php
session_start();
include '../inc/koneksi.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'berita') { header('Location: ../index.php'); exit; }

$msg = $_GET['msg'] ?? '';
$res = pg_query($koneksi, "SELECT * FROM berita ORDER BY tanggal DESC, berita_id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Berita - Admin</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        /* Membatasi teks preview agar tetap rapi dalam kolom */
        .preview-box {
            font-size: 0.8rem;
            line-height: 1.4;
            max-width: 300px;
            color: #858796;
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Maksimal 2 baris */
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
                <nav class="navbar navbar-light bg-white shadow mb-4">
                    <span class="h5 text-primary font-weight-bold">Manajemen Berita</span>
                </nav>
                <div class="container-fluid">
                    
                    <?php if($msg): ?>
                        <div class="alert alert-<?= $msg=='error'?'danger':'success' ?> alert-dismissible fade show">
                            <strong>Status:</strong> 
                            <?php
                                if($msg == 'created') echo "Berita berhasil diterbitkan!";
                                elseif($msg == 'updated') echo "Data berita telah diperbarui!";
                                elseif($msg == 'deleted') echo "Berita telah berhasil dihapus!";
                                else echo "Terjadi kesalahan sistem!";
                            ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Data Berita</h6>
                            <a href="tambah_berita.php" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Tambah</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th width="30">No</th>
                                            <th width="80">Gambar</th>
                                            <th>Judul Berita</th>
                                            <th>Preview Isi</th> <th width="100">Tipe</th>
                                            <th width="100">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no=1; while($row = pg_fetch_assoc($res)): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td class="text-center">
                                                <img src="../assets/img/<?= htmlspecialchars($row['gambar'] ?: 'default.jpg') ?>" 
                                                     style="width:70px; height:45px; object-fit:cover;" class="border rounded shadow-sm">
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($row['judul']) ?></strong><br>
                                                <small class="text-muted"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></small>
                                            </td>
                                            <td>
                                                <div class="preview-box">
                                                    <?= substr(strip_tags($row['isi']), 0, 150) ?>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-<?= $row['tipe']=='pelatihan'?'success':'info' ?> p-2">
                                                    <i class="fas <?= $row['tipe']=='pelatihan'?'fa-graduation-cap':'fa-newspaper' ?>"></i> 
                                                    <?= strtoupper($row['tipe']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="edit_berita.php?id=<?= $row['berita_id'] ?>" class="btn btn-primary btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="proses_berita.php?action=delete&id=<?= $row['berita_id'] ?>" 
                                                       class="btn btn-danger btn-sm" 
                                                       onclick="return confirm('Hapus berita ini?')" title="Hapus">
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
        });
    </script>
</body>
</html>