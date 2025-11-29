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


    <!--====== BLANK SECTION START ======-->
    <section class="py-5">
        <div class="container text-center">
            <div class="row justify-content-center align-items-center" style="min-height: 100vh; margin-top: 100px">
                <div class="col-lg-8">
                    <img src="assets/images/header/gambar1.jpg" alt="Under Construction" class="img-fluid mb-4"
                        style="max-width: 300px;">
                    <h2 class="fw-semibold mb-3">Halaman Sedang Dalam Pengembangan</h2>
                    <p class="text-muted mb-4">
                        Kami sedang menyiapkan sesuatu yang menarik untuk Anda.
                        Silakan kembali lagi nanti atau kunjungi halaman utama untuk melihat konten lainnya.
                    </p>
                    <a href="index.php" class="btn btn-primary">
                        <i class="lni lni-home me-1"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!--====== BLANK SECTION END ======-->


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
