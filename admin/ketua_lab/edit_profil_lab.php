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
    header('Location: profil_lab.php'); 
    exit; 
}

// 3. Ambil data existing dari database
$q = pg_query_params($koneksi, 'SELECT * FROM profil_lab WHERE id = $1', array($id));
if (!$q || pg_num_rows($q) === 0) { 
    // Data tidak ditemukan, set error message di session dan redirect
    $_SESSION['error_msg'] = "Data Profil Lab dengan ID " . htmlspecialchars($id) . " tidak ditemukan.";
    header('Location: profil_lab.php'); 
    exit; 
}
$row = pg_fetch_assoc($q); 
pg_free_result($q);

// 4. Ambil dan unset pesan notifikasi dari sesi
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

// 5. Ambil data form lama dari sesi (jika ada error dari handler)
$form_data = $_SESSION['old_post'] ?? null;
unset($_SESSION['old_post']);

// 6. Jika ada data form yang gagal, timpa data database dengan data post
if ($form_data) {
    // Gunakan nilai dari form_data jika tersedia, jika tidak gunakan nilai dari database ($row)
    $row['judul'] = $form_data['judul'] ?? $row['judul'];
    $row['isi']   = $form_data['isi'] ?? $row['isi'];
}

// 7. Tentukan nilai untuk ditampilkan di form
$judul_display = htmlspecialchars($row['judul'] ?? '');
$isi_display = $row['isi'] ?? ''; // Isi tanpa htmlspecialchars agar Summernote merender HTML-nya
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit Profil Lab</title>

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
                <span class="h5 mb-0 text-primary font-weight-bold">Edit Profil Lab</span>
            </nav>
            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Edit Profil Lab</h1>
                
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <?= $alert ?>
                        
                        <form method="POST" action="proses_profil_lab.php">
                            <input type="hidden" name="action" value="edit">

                            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

                            <div class="form-group">
                                <label for="judul">Judul</label>
                                <input type="text" id="judul" name="judul" class="form-control" 
                                       value="<?= $judul_display ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="summernote">Isi</label>
                                <textarea id="summernote" name="isi" required><?= $isi_display ?></textarea>
                            </div>

                            <div class="mt-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                                <a href="profil_lab.php" class="btn btn-secondary ml-2"><i class="fas fa-arrow-left"></i> Kembali</a>
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