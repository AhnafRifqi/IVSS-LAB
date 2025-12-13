<?php
session_start();
include '../inc/koneksi.php';

// 1. Cek Autentikasi dan Role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php'); 
    exit;
}

// 2. Ambil dan Validasi ID
$id = isset($_GET['id']) ? trim($_GET['id']) : '';
if ($id === '') { 
    header('Location: kegiatan_proyek.php'); 
    exit; 
}

// 3. Ambil data existing
$q = pg_query_params($koneksi, 'SELECT * FROM kegiatan_proyek WHERE id = $1', array($id));
if (!$q || pg_num_rows($q) === 0) { 
    header('Location: kegiatan_proyek.php?msg=notfound'); 
    exit; 
}
$row = pg_fetch_assoc($q);
pg_free_result($q);

$alert = '';

// 4. Handle Form Submission (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    // Tidak menggunakan trim() pada $isi jika Summernote digunakan, karena mungkin mengandung HTML.
    $isi = $_POST['isi'] ?? ''; 

    if ($judul === '' || $isi === '') {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong> Judul dan Isi wajib diisi.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>';
    } else {
        // Lakukan pembaruan data
        $res = pg_query_params($koneksi, 'UPDATE kegiatan_proyek SET judul = $1, isi = $2 WHERE id = $3', array($judul, $isi, $id));
        
        if ($res) { 
            // Sukses
            header('Location: kegiatan_proyek.php?msg=updated'); 
            exit; 
        } else { 
            // Gagal Update Database
            $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error:</strong> Gagal mengupdate data. Silakan coba lagi.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      </div>';
        }
    }
    // Pertahankan nilai input yang gagal di-submit
    $row['judul'] = htmlspecialchars($judul);
    $row['isi']   = $isi; // Simpan isi tanpa htmlspecialchars untuk Summernote
}

// 5. Tentukan nilai untuk ditampilkan di form
$judul_display = htmlspecialchars($row['judul'] ?? '');
$isi_display = $row['isi'] ?? ''; // Isi harus ditampilkan tanpa htmlspecialchars agar Summernote merender HTML-nya
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit Kegiatan Proyek</title>

    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <span class="h5 mb-0 text-primary font-weight-bold">Edit Kegiatan Proyek</span>
            </nav>
            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Edit Kegiatan Proyek</h1>
                
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <?= $alert ?>
                        
                        <form method="POST">
                            <div class="form-group">
                                <label for="id_static">ID Kegiatan</label>
                                <input type="text" id="id_static" class="form-control" 
                                       value="<?= htmlspecialchars($id) ?>" disabled>
                                <small class="form-text text-muted">ID ini tidak dapat diubah.</small>
                            </div>

                            <div class="form-group">
                                <label for="judul">Judul</label>
                                <input type="text" id="judul" name="judul" class="form-control" 
                                       value="<?= $judul_display ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="summernote">Isi / Detail Kegiatan</label>
                                <textarea id="summernote" name="isi" required><?= $isi_display ?></textarea>
                            </div>

                            <div class="mt-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                                <a href="kegiatan_proyek.php" class="btn btn-secondary ml-2"><i class="fas fa-arrow-left"></i> Kembali</a>
                            </div>
                        </form>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>

<script>
$(document).ready(function() {
    // Inisialisasi Summernote
    $('#summernote').summernote({
        height: 250,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['fontsize', 'color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['codeview']]
        ]
    });
    
    // Auto-hide alert setelah 5 detik
    $(".alert").delay(5000).slideUp(300, function() {
        $(this).alert('close');
    });
});
</script>
</body>
</html>