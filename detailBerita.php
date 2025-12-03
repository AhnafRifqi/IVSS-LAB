<?php
// Pastikan file koneksi.php ada dan koneksi ke PostgreSQL berhasil dibuat
require_once 'inc/koneksi.php';

// 1. Ambil ID Berita dari URL
$berita_id = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $berita_id = (int)$_GET['id'];
}

// Inisialisasi variabel untuk detail berita
$berita_detail = null;

if ($berita_id) {
    // 2. Query untuk Mengambil Detail Berita dan Nama Penulis
    // ASUMSI: Nama penulis ada di tabel 'penulis' dengan kolom 'nama_penulis'
    // dan terhubung ke tabel 'berita' melalui 'penulis_id'.
    
    // Gunakan JOIN untuk menggabungkan data berita dan data penulis
    $sql = "SELECT 
                *
            FROM 
                berita
            WHERE 
                berita_id = $berita_id";

    $result = pg_query($koneksi, $sql);

    if ($result && pg_num_rows($result) > 0) {
        $berita_detail = pg_fetch_assoc($result);
        // Format tanggal
        $berita_detail['tanggal_formatted'] = date('d F Y', strtotime($berita_detail['tanggal']));
    }
}

// 3. Handle jika berita tidak ditemukan
if (!$berita_detail) {
    // Anda bisa mengarahkan ke halaman 404 atau menampilkan pesan error
    // header("Location: news.php?status=notfound");
    // exit;
    $berita_detail = [
        'judul' => 'Berita Tidak Ditemukan', 
        'nama_penulis' => 'System', 
        'tanggal_formatted' => '-', 
        'gambar' => 'assets/images/placeholder.jpg', 
        'isi' => '<p class="text-danger">Maaf, konten berita yang Anda cari tidak tersedia.</p>'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>Detail Berita | <?php echo htmlspecialchars($berita_detail['judul']); ?></title>

    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/svg" />

    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/lineicons.css" />
    <link rel="stylesheet" href="assets/css/tiny-slider.css" />
    <link rel="stylesheet" href="assets/css/glightbox.min.css" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <?php include('inc/navbar.php')?>

    <section class="news-details-section py-5" style="min-height: 100vh; margin-top: 100px">
        <div class="container pb-5">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-10">
                    <div class="mx-auto">
                        
                        <h1 class="fw-bold text-dark mb-1 fs-3">
                            <?php echo htmlspecialchars($berita_detail['judul']); ?>
                        </h1>
                        
                        <div class="border-top border-dark pt-2 mb-4"></div>

                        <div class="mb-4 shadow-sm rounded-3 overflow-hidden">
                            <img src="<?php echo htmlspecialchars($berita_detail['gambar']); ?>" 
                                alt="<?php echo htmlspecialchars($berita_detail['judul']); ?>" 
                                class="img-fluid w-100 rounded-3" 
                                style="max-height: 400px; object-fit: cover;">
                        </div>
                        
                        <div class="d-flex justify-content-start align-items-center mb-5 text-muted gap-5">
                            <span class="d-flex align-items-center me-3">
                                <i class="lni lni-pencil me-2 text-dark"></i> 
                                <?php echo htmlspecialchars($berita_detail['penulis_id']); ?>
                            </span>
                            <span class="d-flex align-items-center">
                                <i class="lni lni-calendar me-2 text-dark"></i> 
                                <?php echo htmlspecialchars($berita_detail['tanggal_formatted']); ?>
                            </span>
                        </div>

                        <div class="news-content fs-5 text-dark">
                            <?php 
                                // Konten berisi HTML (paragraf, list, dll.)
                                echo $berita_detail['isi']; 
                            ?>
                        </div>

                        <a href="news.php" class="btn btn-warning mt-5 rounded-pill fw-bold">
                            <i class="lni lni-arrow-left me-2"></i> Kembali ke Daftar Berita
                        </a>

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