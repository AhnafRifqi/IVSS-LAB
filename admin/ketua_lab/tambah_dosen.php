<?php
session_start();
// 1. Cek Autentikasi dan Role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php'); 
    exit;
}

// 2. Ambil pesan notifikasi
$msg = $_GET['msg'] ?? '';
$detail = $_GET['detail'] ?? '';

$alert = '';
if ($msg === 'error' && $detail) {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . 
             '<strong>Error:</strong> ' . htmlspecialchars($detail) . 
             '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tambah Dosen</title>

    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.css" rel="stylesheet">
    
    <style>
        .img-thumb { 
            width: 100px; 
            height: 100px; 
            border-radius: 6px; 
            object-fit: cover; 
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
                <span class="h5 mb-0 text-primary">Tambah Dosen</span>
            </nav>

            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <?= $alert ?>

                        <form method="POST" enctype="multipart/form-data" action="proses_dosen.php">
                            <input type="hidden" name="action" value="add">

                            <h6 class="text-primary font-weight-bold mb-3">Data Identitas</h6>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nip">NIP</label>
                                    <input type="text" id="nip" name="nip" class="form-control" 
                                           value="<?= htmlspecialchars($_POST['nip'] ?? '') ?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nidn">NIDN</label>
                                    <input type="text" id="nidn" name="nidn" class="form-control" 
                                           value="<?= htmlspecialchars($_POST['nidn'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="nama">Nama Lengkap</label>
                                <input type="text" id="nama" name="nama" class="form-control" 
                                       value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" 
                                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="jabatan">Jabatan</label>
                                    <input type="text" id="jabatan" name="jabatan" class="form-control" 
                                           value="<?= htmlspecialchars($_POST['jabatan'] ?? '') ?>">
                                </div>
                            </div>

                            <hr class="my-4">
                            <h6 class="text-primary font-weight-bold mb-3">Informasi Lain</h6>

                            <div class="form-group">
                                <label>Foto Profil</label><br>
                                <img id="preview" src="" class="img-thumb" style="display:none;">
                                <input type="file" name="foto_file" class="form-control-file mt-2" 
                                       accept=".jpg,.jpeg,.png" 
                                       onchange="previewFile(event,'preview')">
                                <small class="form-text text-muted">Upload JPG/PNG/JPEG. Ukuran maksimum disarankan 2MB.</small>
                            </div>

                            <div class="form-group">
                                <label for="pendidikan">Riwayat Pendidikan</label>
                                <textarea name="pendidikan" id="pendidikan" class="form-control" rows="5"><?= htmlspecialchars($_POST['pendidikan'] ?? '') ?></textarea>
                            </div>
                            
                            <h6 class="text-primary font-weight-bold mb-3">Link Publikasi (Opsional)</h6>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="linkedin">LinkedIn (URL)</label>
                                    <input type="url" id="linkedin" name="linkedin" class="form-control" 
                                           value="<?= htmlspecialchars($_POST['linkedin'] ?? '') ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="google_scholar">Google Scholar (URL)</label>
                                    <input type="url" id="google_scholar" name="google_scholar" class="form-control" 
                                           value="<?= htmlspecialchars($_POST['google_scholar'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="sinta">Sinta (URL)</label>
                                    <input type="url" id="sinta" name="sinta" class="form-control" 
                                           value="<?= htmlspecialchars($_POST['sinta'] ?? '') ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="scopus">Scopus (URL)</label>
                                    <input type="url" id="scopus" name="scopus" class="form-control" 
                                           value="<?= htmlspecialchars($_POST['scopus'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="mt-4 d-flex gap-2">
                                <button class="btn btn-success" type="submit"><i class="fas fa-save"></i> Simpan Dosen</button>
                                <a href="dosen.php" class="btn btn-secondary ml-2">Batal</a>
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
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>

<script>
$(document).ready(function() {
    $('#pendidikan').summernote({ 
        height: 150, 
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview']]
        ] 
    });
    
    // Auto-hide alert after 5 seconds
    $(".alert").delay(5000).slideUp(300, function() {
        $(this).alert('close');
    });
});

// Fungsi Preview Gambar
function previewFile(e, id) {
    const f = e.target.files[0];
    const i = document.getElementById(id);
    
    if (!f) {
        i.src = '';
        i.style.display = 'none';
        return;
    }
    
    const r = new FileReader();
    r.onload = function(ev) {
        i.src = ev.target.result;
        i.style.display = 'block';
    };
    r.readAsDataURL(f);
}
</script>
</body>
</html>