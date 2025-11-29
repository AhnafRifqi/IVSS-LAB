<?php
$news_items = [
    [
        'id'        => 1,
        'image_url' => 'assets/images/blog/1.jpg',
        'date'      => '12 Nov 2025',
        'penulis'   => "Akmal",
        'title'     => 'Implementasi Computer Vision untuk Monitoring Lalu Lintas Real-time',
        'summary'   => 'Penelitian terbaru mengembangkan sistem monitoring lalu lintas menggunakan computer vision yang dapat mendeteksi kemacetan secara otomatis.',
        'link'      => 'detail-berita-1.php'
    ],
    [
        'id'        => 2,
        'image_url' => 'assets/images/blog/2.jpg',
        'date'      => '10 Nov 2025',
        'penulis'     => "Angga",
        'title'     => 'Workshop Machine Learning untuk Mahasiswa Baru',
        'summary'   => 'Lab menyelenggarakan workshop intensif mengenai dasar-dasar machine learning dan implementasinya dalam proyek nyata.',
        'link'      => 'detail-berita-2.php'
    ],
    [
        'id'        => 3,
        'image_url' => 'assets/images/blog/3.jpg',
        'date'      => '8 Nov 2025',
        'penulis'     => "rafa",
        'title'     => 'Pengembangan Chatbot Cerdas Menggunakan Natural Language Processing',
        'summary'   => 'Tim peneliti berhasil mengembangkan chatbot berbahasa Indonesia dengan kemampuan memahami konteks percakapan yang kompleks.',
        'link'      => 'detail-berita-3.php'
    ],
    [
        'id'        => 4,
        'image_url' => 'assets/images/blog/1.jpg',
        'date'      => '5 Nov 2025',
        'penulis'     => "Roda",
        'title'     => 'Publikasi Jurnal Internasional: Deep Learning untuk Deteksi Objek',
        'summary'   => 'Paper penelitian dari tim lab diterima di jurnal internasional bereputasi dengan impact factor tinggi.',
        'link'      => 'detail-berita-4.php'
    ],
    [
        'id'        => 5,
        'image_url' => 'assets/images/blog/2.jpg',
        'date'      => '3 Nov 2025',
        'penulis'     => "Jojo",
        'title'     => 'Kunjungan Industri ke Perusahaan Tech Startup Terkemuka',
        'summary'   => 'Mahasiswa lab melakukan kunjungan industri untuk mempelajari implementasi AI dalam produk komersial.',
        'link'      => 'detail-berita-5.php'
    ],
    [
        'id'        => 6,
        'image_url' => 'assets/images/blog/3.jpg',
        'date'      => '1 Nov 2025',
        'penulis'     => "Sila",
        'title'     => 'Kolaborasi dengan Universitas Luar Negeri dalam Proyek IoT',
        'summary'   => 'Lab menjalin kerja sama internasional untuk mengembangkan sistem IoT berbasis AI untuk smart city.',
        'link'      => 'detail-berita-6.php'
    ],
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Berita - Laboratorium Visi Cerdas dan Sistem Cerdas</title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/glightbox.min.css">
    <link rel="stylesheet" href="assets/css/lineicons.css">
    <link rel="stylesheet" href="assets/css/tiny-slider.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include('inc/navbar.php'); ?>
    <section>
        <section class="bg-primary text-white py-5"
            style="padding-top: 180px !important; padding-bottom: 100px !important; position: relative; overflow: hidden;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-10 mx-auto text-center">
                        <h1 class="fw-bold display-5 mb-3 text-white">Berita & Artikel Terbaru</h1>
                        <p class="fs-5 text-white">Ikuti perkembangan terbaru dari Laboratorium Visi Cerdas dan Sistem
                            Cerdas. Temukan artikel,
                            penelitian, dan pencapaian mahasiswa kami.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-light py-4" style="margin-top: -50px; margin-bottom: 60px;">
            <div class="container">
                <div class="mx-auto" style="max-width: 600px;">
                    <div class="input-group shadow-lg rounded-pill overflow-hidden">
                        <input type="text" class="form-control border-0 p-3" placeholder="Cari berita..."
                            aria-label="Cari berita">
                        <button class="btn btn-primary text-white px-4 fw-bold" type="button">
                            <i class="lni lni-search-alt"></i> Cari
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <div class="mb-5">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="row g-0">
                            <div class="col-lg-6">
                                <div class="position-relative overflow-hidden" style="height: 400px;">
                                    <img src="assets/images/blog/1.jpg" alt="Featured News"
                                        class="w-100 h-100 object-fit-cover">
                                </div>
                            </div>
                            <div class="col-lg-6 d-flex align-items-center">
                                <div class="card-body p-5">
                                    <div class="d-flex flex-wrap gap-3 mb-3 text-muted" style="font-size: 0.85rem;">
                                        <span><i class="lni lni-calendar text-primary me-1"></i> 15 November 2025</span>
                                        <span><i class="lni text-primary me-1"></i>
                                        Penulis: Jojo</span>
                                    </div>
                                    <h2 class="h3 fw-bold mb-3">
                                        <a href="#" class="text-dark text-decoration-none hover-primary">Mahasiswa Lab
                                            Visi Cerdas
                                            Raih Juara 1 Kompetisi AI Nasional 2025</a>
                                    </h2>
                                    <p class="text-muted mb-4">Tim mahasiswa dari Laboratorium Visi Cerdas dan Sistem
                                        Cerdas berhasil meraih juara pertama dalam kompetisi AI tingkat nasional. Proyek
                                        mereka menggunakan deep learning untuk deteksi dini penyakit tanaman dengan
                                        akurasi mencapai 97%.</p>
                                    <a href="#"
                                        class="text-primary fw-bold text-decoration-none d-inline-flex align-items-center">
                                        Baca Selengkapnya <i class="lni lni-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <?php foreach ($news_items as $item): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden d-flex flex-column">
                            <div class="position-relative overflow-hidden" style="height: 250px;">
                                <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['title']; ?>"
                                    class="w-100 h-100 object-fit-cover">
                            </div>
                            <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                <div class="d-flex flex-wrap gap-3 mb-2 text-muted" style="font-size: 0.8rem;">
                                    <span><i class="lni lni-calendar text-primary me-1"></i>
                                        <?php echo $item['date']; ?></span>
                                    <span><i class="lni  text-primary me-1"></i>
                                        Penulis: <?php echo $item['penulis']; ?></span>
                                </div>
                                <h4 class="h5 fw-bold mb-3">
                                    <a href="<?php echo $item['link']; ?>"
                                        class="text-dark text-decoration-none hover-primary">
                                        <?php echo $item['title']; ?>
                                    </a>
                                </h4>
                                <p class="text-muted small mb-4 flex-grow-1">
                                    <?php echo $item['summary']; ?>
                                </p>
                                <a href="<?php echo $item['link']; ?>"
                                    class="text-primary fw-bold text-decoration-none d-inline-flex align-items-center mt-auto">
                                    Baca Selengkapnya <i class="lni lni-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="d-flex justify-content-center mt-5">
                <nav aria-label="Page navigation">
                    <ul class="pagination gap-2">
                        <li class="page-item disabled">
                            <a class="page-link border-2 rounded-3 text-dark px-3 py-2" href="#" aria-label="Previous">
                                <span aria-hidden="true"><i class="lni lni-chevron-left"></i></span>
                            </a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link border-2 rounded-3 bg-primary text-white px-3 py-2" href="#">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link border-2 rounded-3 text-dark px-3 py-2" href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link border-2 rounded-3 text-dark px-3 py-2" href="#">3</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link border-2 rounded-3 text-dark px-3 py-2" href="#" aria-label="Next">
                                <span aria-hidden="true"><i class="lni lni-chevron-right"></i></span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </section>
    </section>

    <?php include('inc/footer.php'); ?>

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