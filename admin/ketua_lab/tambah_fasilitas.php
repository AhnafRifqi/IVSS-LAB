<?php
session_start();
// 1. Cek Autentikasi dan Role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php'); 
    exit;
}

// 2. Ambil dan unset pesan dari session jika ada (menggunakan session untuk POST-redirect-GET pattern)
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

// Mengambil nilai POST sebelumnya jika terjadi error (tidak ada di skrip asli, tapi baik untuk UX)
$old_nama = $_SESSION['old_post']['nama'] ?? '';
$old_isi = $_SESSION['old_post']['isi'] ?? '';
unset($_SESSION['old_post']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tambah Fasilitas</title>

    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    
    <style>
        .img-thumb { 
            width: 140px; 
            height: 100px; /* Ditetapkan tinggi agar proporsional */
            border-radius: 6px; 
            object-fit: cover; 
            display: block; 
            margin-bottom: 8px; 
            border: 1px solid #ddd;
        }
    </style>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary">Tambah Fasilitas</span>
            </nav>

            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <?= $alert ?>

                        <form method="POST" enctype="multipart/form-data" action="proses_fasilitas.php">
                            <input type="hidden" name="action" value="create">
                            
                            <div class="form-group">
                                <label for="nama">Nama Fasilitas</label>
                                <input type="text" id="nama" name="nama" class="form-control" 
                                       value="<?= htmlspecialchars($old_nama) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="foto_file">Foto (Opsional)</label>
                                <input type="file" id="foto_file" name="foto_file" class="form-control-file mb-2" 
                                       accept="image/*" 
                                       onchange="previewFile(event,'preview')">
                                <small class="form-text text-muted">Format: JPG/PNG/JPEG/GIF/WEBP — Maks. 3MB. Disimpan di folder `assets/img/`.</small>
                                
                                <img id="preview" class="img-thumb mt-2" style="display:none;" alt="Preview Foto">
                            </div>

                            <div class="form-group">
                                <label for="isi">Deskripsi / Keterangan</label>
                                <textarea id="isi" name="isi" rows="6" class="form-control"><?= htmlspecialchars($old_isi) ?></textarea>
                            </div>

                            <div class="mt-4 d-flex gap-2">
                                <button class="btn btn-success" type="submit"><i class="fas fa-save"></i> Simpan</button>
                                <a href="fasilitas.php" class="btn btn-secondary ml-2">Kembali</a>
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

<script>
// Fungsi Preview Gambar
function previewFile(e, id) {
    const file = e.target.files[0];
    const img = document.getElementById(id);
    
    if(!file) {
        img.src = '';
        img.style.display = 'none';
        return; 
    }
    
    const reader = new FileReader();
    reader.onload = ev => { 
        img.src = ev.target.result; 
        img.style.display = 'block'; 
    };
    reader.readAsDataURL(file);
}

// Auto-hide alert setelah 5 detik
$(document).ready(function() {
    $(".alert").delay(5000).slideUp(300, function() {
        $(this).alert('close');
    });
});
</script>
</body>
</html>