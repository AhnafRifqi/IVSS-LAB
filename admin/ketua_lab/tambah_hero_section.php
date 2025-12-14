<?php
session_start();
include '../inc/koneksi.php'; // Digunakan hanya untuk kebutuhan koneksi/autentikasi dasar

// 1. Cek Autentikasi dan Role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php'); 
    exit;
}

// 2. Ambil dan unset pesan notifikasi dan data form lama dari sesi
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

$form_data = $_SESSION['old_post'] ?? ['judul' => '', 'isi' => ''];
unset($_SESSION['old_post']);

$judul_value = htmlspecialchars($form_data['judul']);
// Isi harus ditampilkan apa adanya (tanpa htmlspecialchars) agar Summernote merender HTML-nya
$isi_value = $form_data['isi']; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tambah Hero Section</title>

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
                <span class="h5 mb-0 text-primary font-weight-bold">Tambah Hero Section</span>
            </nav>
            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Tambah Hero Section</h1>
                
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <?= $alert ?>
                        
                        <form method="POST" action="proses_hero_section.php">
                            <input type="hidden" name="action" value="create">

                            <div class="form-group">
                                <label for="judul">Judul</label>
                                <input type="text" id="judul" name="judul" class="form-control" 
                                        value="<?= $judul_value ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="summernote">Isi / Detail Hero Section</label>
                                <textarea id="summernote" name="isi" required><?= $isi_value ?></textarea>
                            </div>

                            <div class="mt-4 d-flex gap-2">
                                <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Tambah</button>
                                <a href="hero_section.php" class="btn btn-secondary ml-2"><i class="fas fa-arrow-left"></i> Kembali</a>
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