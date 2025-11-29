<?php
$member_lab_items = [
    [
        'name'      => "Mamluaul Hani'ah, S.Kom., M.Kom",
        'image_url' => 'assets/images/dosen/Mamluatul-Hani.jpg',
        'role'      => 'Member Lab', // Atau 'Koordinator Lab', dll.
        'detail_link' => 'detail-dosen-mamlua.php'
    ],
    [
        'name'      => "Dr. Ely Setya Asluti, ST., MT.",
        'image_url' => 'assets/images/dosen/Ely-Setyo-Astuti.jpg',
        'role'      => 'Member Lab',
        'detail_link' => 'detail-dosen-ely.php'
    ],
    [
        'name'      => "Mungki Astiningrum, ST., M.Kom.",
        'image_url' => 'assets/images/dosen/Mungki-Astiningrum.jpg',
        'role'      => 'Member Lab',
        'detail_link' => 'detail-dosen-mungki.php'
    ],
    [
        'name'      => "Prof. Dr. Eng. Rosa Andrie Asmara, ST., MT.",
        'image_url' => 'assets/images/dosen/Rosa-Andrie-Asmara.jpg',
        'role'      => 'Member Lab',
        'detail_link' => 'detail-dosen-rosa.php'
    ],
    [
        'name'      => "Vivi Nur Wijayaningrum, S.Kom., M.Kom",
        'image_url' => 'assets/images/dosen/Vivi-Nur-Wijayaningrum.jpg',
        'role'      => 'Member Lab',
        'detail_link' => 'detail-dosen-vivi.php'
    ],
    [
        'name'      => "Wilda Imama Sabilla, S.Kom., M.Kom.",
        'image_url' => 'assets/images/dosen/Wilda-Imama-Sabilla.jpg',
        'role'      => 'Member Lab',
        'detail_link' => 'detail-dosen-wilda.php'
    ],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!--====== Required meta tags ======-->
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!--====== Title ======-->
    <title>Blank Page | Business Template</title>

    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon" href="../assets/images/favicon.svg" type="image/svg" />

    <!--====== Bootstrap css ======-->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />

    <!--====== Line Icons css ======-->
    <link rel="stylesheet" href="assets/css/lineicons.css" />

    <!--====== Tiny Slider css ======-->
    <link rel="stylesheet" href="assets/css/tiny-slider.css" />

    <!--====== gLightBox css ======-->
    <link rel="stylesheet" href="assets/css/glightbox.min.css" />

    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <?php include('inc/navbar.php')?>


    <section class="blank-section py-5" style="min-height: 80vh;">
        <div class="container text-center">
            <h2 class="mb-5">Daftar Dosen Laboratorium Visi Cerdas dan Sistem Cerdas</h2>
            <div class="row justify-content-center">
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="d-flex align-items-center p-3 border rounded shadow-sm">
                        <img src="assets/images/dosen/Ulla-Delfana-Rosiani.jpg" alt="Dr. Ulla Delfana Rosiani, ST., MT."
                            class="rounded-circle me-3"
                            style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #f0f0f0;">
                        <div class="text-start flex-grow-1">
                            <p class="fw-bold mb-0 text-uppercase" style="font-size: 1rem; color: #333;">Ketua Lab</p>
                            <p class="mb-1" style="font-size: 0.9rem;">Dr. Ulla Delfana Rosiani, ST., MT.</p>
                            <button class="btn btn-sm btn-primary">Detail</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="w-100 my-4 border-top border-2 border-primary"></div>
                <?php foreach ($member_lab_items as $item): ?>
                <div class="col-12 col-md-4 col-lg-3 mb-3">
                    <div class="d-flex align-items-center p-3 border rounded shadow-sm h-100">
                        <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>"
                            class="rounded-circle me-3"
                            style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #f0f0f0;">
                        <div class="text-start flex-grow-1">
                            <p class="fw-bold mb-0 text-uppercase" style="font-size: 1rem; color: #333;">
                                <?php echo $item['role']; ?>
                            </p>
                            <p class="mb-1" style="font-size: 0.9rem;">
                                <?php echo $item['name']; ?>
                            </p>
                            <a href="<?php echo $item['detail_link']; ?>" class="btn btn-sm btn-primary">Detail</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
    </section>

    <?php include('inc/footer.php')?>


    <a href="#" class="scroll-top btn-hover">
        <i class="lni lni-chevron-up"></i>
    </a>

    <!--====== js ======-->
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
