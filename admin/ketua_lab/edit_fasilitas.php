<?php
session_start();
include '../inc/koneksi.php';

// 1. Cek Autentikasi dan Role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php'); 
    exit;
}

// 2. Ambil dan Validasi ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: fasilitas.php');
    exit;
}

// 3. Ambil data existing Fasilitas
$q = pg_query_params($koneksi, 'SELECT * FROM fasilitas WHERE id=$1', [$id]);
$row = pg_fetch_assoc($q);
pg_free_result($q);

if (!$row) {
    // Data tidak ditemukan
    header('Location: fasilitas.php?msg=notfound');
    exit;
}

// 4. Ambil dan unset pesan dari session (menggunakan session untuk konsistensi notifikasi)
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

// 5. Ambil data yang mungkin dikirim melalui POST sebelum redirect (jika ada error)
$nama_value = htmlspecialchars($_POST['nama'] ?? $row['nama']);
$isi_value = htmlspecialchars($_POST['isi'] ?? $row['isi']);
$gambar_existing = htmlspecialchars($row['gambar'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit Fasilitas</title>

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
                <span class="h5 mb-0 text-primary">Edit Fasilitas</span>
            </nav>

            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <?= $alert ?>

                        <form method="POST" enctype="multipart/form-data" action="proses_fasilitas.php">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <input type="hidden" name="old_gambar" value="<?= $gambar_existing ?>">
                            
                            <div class="form-group">
                                <label for="nama">Nama Fasilitas</label>
                                <input type="text" id="nama" name="nama" class="form-control" 
                                       value="<?= $nama_value ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="foto_file">Foto</label><br>
                                <?php if (!empty($gambar_existing)): ?>
                                    <img id="preview" src="../assets/img/<?= $gambar_existing ?>" class="img-thumb" alt="Foto Fasilitas">
                                <?php else: ?>
                                    <img id="preview" class="img-thumb" style="display:none;" alt="Preview Foto">
                                <?php endif; ?>

                                <input type="file" id="foto_file" name="foto_file" class="form-control-file mt-2" 
                                       accept="image/*" 
                                       onchange="previewFile(event,'preview')">
                                <small class="form-text text-muted">Kosongi jika tidak ingin mengubah foto. Format: JPG/PNG/JPEG/GIF/WEBP — Maks. 3MB.</small>
                            </div>

                            <div class="form-group">
                                <label for="isi">Deskripsi / Keterangan</label>
                                <textarea id="isi" name="isi" rows="6" class="form-control"><?= $isi_value ?></textarea>
                            </div>

                            <div class="mt-4 d-flex gap-2">
                                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Update</button>
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
        // Jika file dibatalkan, kembali ke foto lama jika ada, atau sembunyikan
        img.src = '<?= $gambar_existing ? "../assets/img/" . $gambar_existing : "" ?>';
        img.style.display = '<?= $gambar_existing ? "block" : "none" ?>';
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