<?php
session_start();
include '../inc/koneksi.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') { header('Location: ../index.php'); exit; }

$alert='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $judul=trim($_POST['judul']??'');
    $isi=trim($_POST['isi']??'');
    if($judul===''||$isi===''){ $alert='<div class="alert alert-danger">Judul dan Isi wajib diisi.</div>'; }
    else{
        $res=pg_query_params($koneksi,'INSERT INTO profil_lab(judul,isi) VALUES($1,$2)',array($judul,$isi));
        if($res){ header('Location: profil_lab.php?msg=added'); exit; }
        else{ $alert='<div class="alert alert-danger">Gagal menambahkan data.</div>'; }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Tambah Profil Lab - Admin</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
<?php include 'inc/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
<div id="content">
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow"><span class="h5 mb-0 text-primary font-weight-bold">Tambah Profil Lab</span></nav>
<div class="container-fluid">
<h1 class="h3 mb-4 text-gray-800">Tambah Profil Lab</h1>
<div class="card shadow-sm mb-4">
<div class="card-body">
<?= $alert ?>
<form method="POST">
<div class="form-group">
<label>Judul</label>
<input type="text" name="judul" class="form-control" required>
</div>
<div class="form-group">
<label>Isi</label>
<textarea id="summernote" name="isi" required></textarea>
</div>
<div class="d-flex gap-2">
<button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Tambah</button>
<a href="profil_lab.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
</form>
</div></div></div></div>
<?php include '../inc/footer.php'; ?>
</div></div>
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
<script>$(document).ready(function(){$('#summernote').summernote({height:250,toolbar:[['style',['bold','italic','underline','clear']],['font',['fontsize','color']],['para',['ul','ol','paragraph']],['insert',['link']],['view',['codeview']]]});});</script>
</body>
</html>
