<?php
include '../inc/koneksi.php';

$member_lab_items = [
    [
        'name'      => "Mamluaul Hani'ah, S.Kom., M.Kom",
        'image_url' => 'assets/images/dosen-mamlua.png',
        'role'      => 'Member Lab', 
        'detail_link' => 'detail-dosen-mamlua.php'
    ],
    [
        'name'      => "Dr. Ely Setya Asluti, ST., MT.",
        'image_url' => 'assets/images/dosen-ely.png',
        'role'      => 'Member Lab',
        'detail_link' => 'detail-dosen-ely.php'
    ],
    [
        'name'      => "Mungki Astiningrum, ST., M.Kom.",
        'image_url' => 'assets/images/dosen-mungki.png',
        'role'      => 'Member Lab',
        'detail_link' => 'detail-dosen-mungki.php'
    ],
    [
        'name'      => "Prof. Dr. Eng. Rosa Andrie Asmara, ST., MT.",
        'image_url' => 'assets/images/dosen-rosa.png',
        'role'      => 'Member Lab',
        'detail_link' => 'detail-dosen-rosa.php'
    ],
    [
        'name'      => "Vivi Nur Wijayaningrum, S.Kom., M.Kom",
        'image_url' => 'assets/images/dosen-vivi.png',
        'role'      => 'Member Lab',
        'detail_link' => 'detail-dosen-vivi.php'
    ],
    [
        'name'      => "Wilda Imama Sabilla, S.Kom., M.Kom.",
        'image_url' => 'assets/images/dosen-wilda.png',
        'role'      => 'Member Lab',
        'detail_link' => 'detail-dosen-wilda.php'
    ],
];

// Data untuk Ketua Lab (dipisahkan untuk mempermudah tata letak)
$ketua_lab = [
    'name'      => "Dr. Ulla Delfana Rosiani, ST., MT.",
    'image_url' => 'assets/images/dosen-ulla.png',
    'role'      => 'Ketua Lab',
    'detail_link' => 'detail-dosen-ulla.php'
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>Daftar Dosen | Lab Visi Cerdas</title>

    <link rel="shortcut icon" href="../assets/images/favicon.svg" type="image/svg" />

    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />

    <link rel="stylesheet" href="assets/css/lineicons.css" />

    <link rel="stylesheet" href="assets/css/tiny-slider.css" />

    <link rel="stylesheet" href="assets/css/glightbox.min.css" />

    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <?php include('inc/navbar.php')?>

    <section class="blank-section py-5 bg-light" style="min-height: 100vh; margin-top: 100px">
        <div class="container">
            <h2 class="text-center mb-5 border-bottom border-warning border-3 pb-2 fw-bold" style="color: black;">
                Daftar Dosen Laboratorium Visi Cerdas dan Sistem Cerdas
            </h2>
            
            <h3 class="text-center mb-4" style="color: black;"">Ketua Laboratorium</h3>
            <div class="row justify-content-center mb-5">
                <div class="col-12 col-md-5 col-lg-3 mb-4">
                    <div class="card border-0 shadow-sm overflow-hidden text-center h-100 transition-hover-up" 
                         style="border-radius: 15px; transition: transform 0.2s; cursor: pointer;"
                         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 6px 12px rgba(0,0,0,0.1)'"
                         onmouseout="this.style.transform='none'; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)'">
                        
                        <div class="overflow-hidden" style="height: 250px;">
                            <img src="<?php echo $ketua_lab['image_url']; ?>" alt="<?php echo $ketua_lab['name']; ?>"
                                class="w-100 h-100 p-3" style="object-fit: cover;">
                        </div>
                        
                        <div class="card-body bg-white p-3">
                            <p class="text-secondary text-uppercase mb-1" style="font-size: 0.9rem;"><?php echo $ketua_lab['role']; ?></p>
                            <p class="fw-bold text-dark mb-2" style="min-height: 40px;"><?php echo $ketua_lab['name']; ?></p>
                            <a href="<?php echo $ketua_lab['detail_link']; ?>" class="btn btn-warning btn-sm fw-bold text-dark" style="padding: 5px 20px; font-size: 0.85rem; border-radius: 5px; text-transform: uppercase;">Detail</a>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="text-center mb-4" style="color: black;"">Anggota Laboratorium</h3>
            <div class="row justify-content-center">
                <?php foreach ($member_lab_items as $item): ?>
                <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex">
                    <div class="card border-0 shadow-sm overflow-hidden text-center flex-fill transition-hover-up"
                         style="border-radius: 15px; transition: transform 0.2s; cursor: pointer;"
                         onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 12px rgba(0,0,0,0.1)'"
                         onmouseout="this.style.transform='none'; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)'">
                        
                        <div class="overflow-hidden" style="height: 200px;">
                            <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>"
                                class="w-100 h-100 p-2" style="object-fit: cover;">
                        </div>
                        
                        <div class="card-body bg-light p-3">
                            <p class="text-secondary text-uppercase mb-1" style="font-size: 0.9rem;"><?php echo $item['role']; ?></p>
                            <p class="fw-bold text-dark mb-2" style="min-height: 40px;"><?php echo $item['name']; ?></p>
                            <a href="<?php echo $item['detail_link']; ?>" class="btn btn-warning btn-sm fw-bold text-dark" style="padding: 5px 20px; font-size: 0.85rem; border-radius: 5px; text-transform: uppercase;">Detail</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
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