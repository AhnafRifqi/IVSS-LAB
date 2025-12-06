<?php
include '../inc/koneksi.php';

// Data detail dosen yang akan ditampilkan di halaman ini
// AMBIL ID DARI URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("ID dosen tidak valid");
}


// QUERY DOSEN
$stmt = $conn->prepare("SELECT * FROM dosen WHERE id = ?");
$stmt->execute([$id]);
$dosen_detail = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>Detail Dosen | <?= htmlspecialchars($dosen_detail['name']) ?></title>

    <link rel="shortcut icon" href="../assets/images/favicon.svg" type="image/svg" />

    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />

    <link rel="stylesheet" href="assets/css/lineicons.css" />

    <link rel="stylesheet" href="assets/css/tiny-slider.css" />

    <link rel="stylesheet" href="assets/css/glightbox.min.css" />

    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <?php include('inc/navbar.php')?>

    <section class="blank-section pt-5" style="min-height: 100vh; margin-top: 100px">
        <div class="container mt-4 pb-5">
            <div class="row">
                
                <!-- FOTO DOSEN -->
                <div class="col-md-4 d-flex flex-column align-items-center align-items-md-start">
                    
                    <div class="mb-4 shadow-sm rounded overflow-hidden bg-white"
                        style="width: 200px; height: 250px; display:flex; justify-content:center; align-items:center;">
                        
                        <img src="admin/assets/img/<?= htmlspecialchars($dosen_detail['foto']); ?>"
                            alt="<?= htmlspecialchars($dosen_detail['name']); ?>"
                            class="w-100 h-100"
                            style="object-fit: contain; padding: 15px;">
                    </div>
                    
                    <div class="mt-2 text-center text-md-start">
                        <h5 class="fw-bold text-dark mb-2">Informasi Kontak</h5>
                        <ul class="list-unstyled">
                            <?php if (!empty($dosen_detail['email'])): ?>
                            <li class="d-flex align-items-center mb-1">
                                <i class="lni lni-envelope me-2 text-dark"></i> Email: 
                                <a href="mailto:<?php echo $dosen_detail['email']; ?>" class="text-dark ms-1" style="text-decoration: none;"><?php echo $dosen_detail['email']; ?></a>
                            </li>
                            <?php endif; ?>
                            <?php if (!empty($dosen_detail['linkedin'])): ?>
                            <li class="d-flex align-items-center">
                                <i class="lni lni-linkedin me-2 text-dark"></i> LinkedIn: 
                                <a href="https://<?php echo $dosen_detail['linkedin']; ?>" target="_blank" class="text-dark ms-1" style="text-decoration: none;"><?php echo $dosen_detail['linkedin']; ?></a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <div class="col-md-8 mt-4 mt-md-0">
                    
                    <h1 class="fw-bold text-dark border-bottom border-dark pb-2 mb-4" style="font-size: 2rem;"><?php echo $dosen_detail['name']; ?></h1>

                    <h4 class="fw-bold text-dark">Profil Dosen</h4>
                    <hr class="mt-0 mb-4 border-dark" style="border-width: 2px !important;">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex mb-1">
                                <span class="fw-normal text-dark" style="width: 150px;">NIP</span>: 
                                <span class="fw-bold text-dark ms-1"><?php echo $dosen_detail['nip']; ?></span>
                            </div>
                            <div class="d-flex mb-1">
                                <span class="fw-normal text-dark" style="width: 150px;">NIDN</span>: 
                                <span class="fw-bold text-dark ms-1"><?php echo $dosen_detail['nidn']; ?></span>
                            </div>
                            <div class="d-flex mb-1">
                                <span class="fw-normal text-dark" style="width: 150px;">Program Studi</span>: 
                                <span class="fw-bold text-dark ms-1"><?php echo $dosen_detail['program_studi']; ?></span>
                            </div>
                            <div class="d-flex mb-1">
                                <span class="fw-normal text-dark" style="width: 150px;">Jabatan</span>: 
                                <span class="fw-bold text-dark ms-1"><?php echo $dosen_detail['jabatan']; ?></span>
                            </div>
                        </div>
                    </div>

                    <h4 class="fw-bold text-dark mt-4">Pendidikan</h4>
                    <hr class="mt-0 mb-4 border-dark" style="border-width: 2px !important;">
                    <ul class="list-unstyled ps-3">
                        <?php foreach ($dosen_detail['pendidikan'] as $edu): ?>
                        <li class="mb-1" style="text-indent: -1em;"><span class="me-2">&bullet;</span><?php echo $edu; ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <h4 class="fw-bold text-dark mt-4">Sorotan Publikasi</h4>
                    <hr class="mt-0 mb-4 border-dark" style="border-width: 2px !important;">
                    <div class="d-flex flex-wrap">
                        <?php if (!empty($dosen_detail['scopus_profile'])): ?>
                        <a href="<?php echo $dosen_detail['scopus_profile']; ?>" target="_blank" class="btn btn-warning rounded-pill fw-bold me-2 mb-2">Scopus Profile</a>
                        <?php endif; ?>
                        <?php if (!empty($dosen_detail['google_scholar_profile'])): ?>
                        <a href="<?php echo $dosen_detail['google_scholar_profile']; ?>" target="_blank" class="btn btn-warning rounded-pill fw-bold me-2 mb-2">Google Scholar Profile</a>
                        <?php endif; ?>
                        <?php if (!empty($dosen_detail['sinta_profile'])): ?>
                        <a href="<?php echo $dosen_detail['sinta_profile']; ?>" target="_blank" class="btn btn-warning rounded-pill fw-bold me-2 mb-2">Sinta Profile</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include('inc/footer.php')?>


    <a href="#" class="scroll-top btn-hover">
        <i class="lni lni-chevron-up"></i>
    </a>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/glightbox.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/tiny-slider.js"></script>

    <script>
        // Navbar toggle
        let navbarTogglerNine = document.querySelector(".navbar-nine .navbar-toggler");
        navbarTogglerNine.addEventListener("click", function () {
            navbarTogglerNine.classList.toggle("active");
        });

        // Sidebar toggle
        let sidebarLeft = document.querySelector(".sidebar-left");
        let overlayLeft = document.querySelector(".overlay-left");
        let sidebarClose = document.querySelector(".sidebar-close .close");

        overlayLeft.addEventListener("click", function () {
            sidebarLeft.classList.toggle("open");
            overlayLeft.classList.toggle("open");
        });
        sidebarClose.addEventListener("click", function () {
            sidebarLeft.classList.remove("open");
            overlayLeft.classList.remove("open");
        });

        // Side menu
        let sideMenuLeftNine = document.querySelector(".navbar-nine .menu-bar");
        sideMenuLeftNine.addEventListener("click", function () {
            sidebarLeft.classList.add("open");
            overlayLeft.classList.add("open");
        });
    </script>
</body>

</html>