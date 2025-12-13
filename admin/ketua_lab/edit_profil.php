<?php
session_start();
include '../inc/koneksi.php';

// 1. Cek Autentikasi dan Role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// 2. Ambil data user dan dosen
$q = pg_query_params(
    $koneksi,
    'SELECT u.username, u.role, d.* FROM users u
     LEFT JOIN dosen d ON d.user_id = u.user_id
     WHERE u.user_id = $1',
    [$user_id]
);

if (!$q || pg_num_rows($q) === 0) {
    die("Data tidak ditemukan.");
}

$row = pg_fetch_assoc($q);
pg_free_result($q);

// 3. Ambil dan unset pesan dari session
$error   = $_SESSION['error']   ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit Profil Ketua</title>

    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.css" rel="stylesheet">
    
    <style>
        .img-thumb { 
            width: 100px; 
            border-radius: 8px; 
            object-fit: cover;
            height: 100px; /* Menambahkan tinggi agar kotak preview konsisten */
        }
    </style>
</head>
<body id="page-top">

<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 text-primary mb-0">Edit Profil Ketua</span>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="profil.php" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user mr-1"></i> Profil
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-body">

                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($error) ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($success) ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="proses_profil.php" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="update_profile">

                            <h5 class="text-primary"><i class="fas fa-user-circle"></i> Akun</h5>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="username">Username</label>
                                    <input type="text" id="username" name="username" class="form-control"
                                           value="<?= htmlspecialchars($row['username'] ?? '') ?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Role</label>
                                    <input type="text" class="form-control" value="ketua" readonly>
                                </div>
                            </div>

                            <hr>

                            <h5 class="text-primary"><i class="fas fa-address-card"></i> Data Dosen</h5>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nip">NIP</label>
                                    <input type="text" id="nip" name="nip" class="form-control"
                                           value="<?= htmlspecialchars($row['nip'] ?? '') ?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nidn">NIDN</label>
                                    <input type="text" id="nidn" name="nidn" class="form-control"
                                           value="<?= htmlspecialchars($row['nidn'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" id="nama" name="nama" class="form-control"
                                       value="<?= htmlspecialchars($row['nama'] ?? '') ?>" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control"
                                           value="<?= htmlspecialchars($row['email'] ?? '') ?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="jabatan">Jabatan</label>
                                    <input type="text" id="jabatan" name="jabatan" class="form-control"
                                           value="<?= htmlspecialchars($row['jabatan'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Foto</label><br>
                                <?php $photo_src = !empty($row['foto']) ? '../assets/img/' . htmlspecialchars($row['foto']) : ''; ?>
                                <img src="<?= $photo_src ?>" id="preview" class="img-thumb mb-2" style="display: <?= $photo_src ? 'block' : 'none' ?>">
                                
                                <input type="file" name="foto_file" class="form-control-file"
                                       accept=".jpg,.jpeg,.png"
                                       onchange="previewFile(event,'preview')">
                                <small class="form-text text-muted">Abaikan jika tidak ingin mengubah foto.</small>
                            </div>

                            <div class="form-group">
                                <label for="pendidikan">Pendidikan</label>
                                <textarea name="pendidikan" id="pendidikan"
                                          class="form-control"><?= htmlspecialchars($row['pendidikan'] ?? '') ?></textarea>
                            </div>

                            <hr>

                            <h5 class="text-primary"><i class="fas fa-link"></i> Link Publikasi</h5>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="linkedin">LinkedIn</label>
                                    <input type="url" id="linkedin" name="linkedin" class="form-control"
                                           value="<?= htmlspecialchars($row['linkedin'] ?? '') ?>" placeholder="https://linkedin.com/in/...">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="google_scholar">Google Scholar</label>
                                    <input type="url" id="google_scholar" name="google_scholar" class="form-control"
                                           value="<?= htmlspecialchars($row['google_scholar'] ?? '') ?>" placeholder="https://scholar.google.com/citations?user=...">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="sinta">Sinta</label>
                                    <input type="url" id="sinta" name="sinta" class="form-control"
                                           value="<?= htmlspecialchars($row['sinta'] ?? '') ?>" placeholder="https://sinta.ristekbrin.go.id/authors/detail?id=...">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="scopus">Scopus</label>
                                    <input type="url" id="scopus" name="scopus" class="form-control"
                                           value="<?= htmlspecialchars($row['scopus'] ?? '') ?>" placeholder="https://www.scopus.com/authid/detail.uri?authorId=...">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                            <a href="profil.php" class="btn btn-secondary ml-2">Batal</a>

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
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>

<script>
    // Inisialisasi Summernote
    $(document).ready(function() {
        $('#pendidikan').summernote({ 
            height: 150,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['view', ['codeview']]
            ]
        });
    });

    // Fungsi Preview Gambar
    function previewFile(e, id) {
        let f = e.target.files[0],
            img = document.getElementById(id);
        
        if (!f) {
            img.style.display = 'none';
            img.src = '';
            return;
        }
        
        let r = new FileReader();
        r.onload = x => {
            img.src = x.target.result;
            img.style.display = 'block';
        };
        r.readAsDataURL(f);
    }
</script>
</body>
</html>