<?php
require_once 'inc/koneksi.php';

$search_query = "";
$where_clause = "";
$batas_karakter = 500;

if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $search_query = pg_escape_string($koneksi, $_GET['keyword']);
    $where_clause = " WHERE 
        judul ILIKE '%{$search_query}%' OR 
        isi ILIKE '%{$search_query}%'
    ";
}

$sql = "SELECT * FROM berita {$where_clause} ORDER BY berita_id DESC";

$result = pg_query($koneksi, $sql);

$news_items = [];
if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $row['tanggal_formatted'] = date('d M Y', strtotime($row['tanggal']));
        $news_items[] = $row;
    }
} else {
    error_log("Database Query Error: " . pg_last_error($koneksi));
}

$limit = 7; 
$total_items = count($news_items);
$total_pages = ceil($total_items / $limit);

$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

if ($current_page < 1) {
    $current_page = 1;
} elseif ($current_page > $total_pages && $total_pages > 0) {
    $current_page = $total_pages;
} elseif ($total_pages === 0) {
    $current_page = 1;
}

$offset = ($current_page - 1) * $limit;

$paginated_items = array_slice($news_items, $offset, $limit);

$featured = null;
$other_items = [];

if (!empty($paginated_items)) {
    if ($current_page == 1 && count($news_items) > 0) {
        $featured = $news_items[0]; 
        
        $isi_featured = $featured['isi'];
        if (strlen($isi_featured) > $batas_karakter) {
            $potongan_isi = substr($isi_featured, 0, $batas_karakter);
            $featured['isi_tampilan'] = substr($potongan_isi, 0, strrpos($potongan_isi, ' ')) . '...';
        } else {
            $featured['isi_tampilan'] = $isi_featured;
        }

        $other_items = array_slice($paginated_items, 1);
    } else {
        $other_items = $paginated_items;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Berita - Laboratorium Visi Cerdas dan Sistem Cerdas</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/glightbox.min.css">
    <link rel="stylesheet" href="assets/css/lineicons.css">
    <link rel="stylesheet" href="assets/css/tiny-slider.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include('inc/navbar.php'); ?>

    <section>

        <section class="text-white py-5"
            style="padding-top: 178px !important; padding-bottom: 100px !important; position: relative; overflow: hidden; background-color: #1A1A37">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-10 mx-auto text-center">
                        <h1 class="fw-bold display-5 mb-3 text-white">Berita & Artikel Terbaru</h1>
                        <p class="fs-5 text-white">Ikuti perkembangan terbaru dari Laboratorium Visi Cerdas dan Sistem
                            Cerdas.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-light py-4" style="margin-top: -50px; margin-bottom: 60px;">
            <div class="container">
                <form action="" method="GET">
                    <div class="mx-auto" style="max-width: 600px;">
                        <div class="input-group shadow-lg rounded-pill overflow-hidden">
                            <input type="text" class="form-control border-0 p-3" placeholder="Cari berita..."
                                name="keyword" value="<?= htmlspecialchars($search_query); ?>" aria-label="Cari berita"
                                style="background-color: #FFBC3B;">
                            <button class="btn text-white px-4 fw-bold" type="submit"
                                style="background-color: #1A1A37;">
                                <i class="lni lni-search-alt"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <section class="py-5">
            <div class="container">

                <?php if (!empty($paginated_items)): ?>

                <?php if ($featured): ?>
                <div class="mb-5">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="row g-0">
                            <div class="col-lg-6">
                                <div class="position-relative overflow-hidden" style="height: 400px;">
                                    <img src="admin/assets/img/<?php echo htmlspecialchars($featured['gambar']); ?>"
                                        alt="<?= htmlspecialchars($featured['judul']); ?>"
                                        class="w-100 h-100 object-fit-cover">
                                </div>
                            </div>
                            <div class="col-lg-6 d-flex align-items-center">
                                <div class="card-body p-5">
                                    <div class="d-flex flex-wrap gap-3 mb-3 text-muted" style="font-size: 0.85rem;">
                                        <span><i class="lni lni-calendar text-primary me-1"></i>
                                            <?= $featured['tanggal_formatted']; ?></span>
                                        <span><i class="lni lni-pencil text-primary me-1"></i>
                                            penulis: <?= htmlspecialchars($featured['penulis']); ?></span>
                                    </div>
                                    <h2 class="h3 fw-bold mb-3">
                                        <a href="detailBerita.php?id=<?= htmlspecialchars($featured['berita_id']); ?>"
                                            class="text-dark text-decoration-none hover-primary">
                                            <?= htmlspecialchars($featured['judul']); ?>
                                        </a>
                                    </h2>
                                    <p class="text-muted mb-4">
                                        <?= htmlspecialchars($featured['isi_tampilan']); ?>
                                    </p>
                                    <a href="detailBerita.php?id=<?= htmlspecialchars($featured['berita_id']); ?>"
                                        class=" fw-bold text-decoration-none d-inline-flex align-items-center"
                                        style="color: #FFBC3B;">
                                        Baca Selengkapnya <i class="lni lni-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row">
                    <?php 
                        $batas_karakter_item = 500;

                        foreach ($other_items as $item): 
                            $isi_item = $item['isi'];
                            if (strlen($isi_item) > $batas_karakter_item) {
                                $potongan_isi_item = substr($isi_item, 0, $batas_karakter_item);
                                $isi_tampilan_item = substr($potongan_isi_item, 0, strrpos($potongan_isi_item, ' ')) . '...';
                            } else {
                                $isi_tampilan_item = $isi_item;
                            }
                    ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden d-flex flex-column">
                            <div class="position-relative overflow-hidden" style="height: 250px;">
                                <img src="admin/assets/img/<?php echo htmlspecialchars($item['gambar']); ?>"
                                    alt="<?= htmlspecialchars($item['judul']); ?>" class="w-100 h-100 object-fit-cover">
                            </div>
                            <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                <div class="d-flex flex-wrap gap-3 mb-2 text-muted" style="font-size: 0.8rem;">
                                    <span><i class="lni lni-calendar text-primary me-1"></i>
                                        <?= $item['tanggal_formatted']; ?></span>
                                    <span><i class="lni lni-pencil text-primary me-1"></i>
                                        penulis: <?= htmlspecialchars($item['penulis']); ?></span>
                                </div>
                                <h4 class="h5 fw-bold mb-3">
                                    <a href="detailBerita.php?id=<?= htmlspecialchars($item['berita_id']); ?>"
                                        class="text-dark text-decoration-none hover-primary">
                                        <?= htmlspecialchars($item['judul']); ?>
                                    </a>
                                </h4>
                                <p class="text-muted small mb-4 flex-grow-1">
                                    <?= htmlspecialchars($isi_tampilan_item); ?>
                                </p>
                                <a href="detailBerita.php?id=<?= htmlspecialchars($item['berita_id']); ?>"
                                    class=" fw-bold text-decoration-none d-inline-flex align-items-center mt-auto"
                                    style="color: #FFBC3B;">
                                    Baca Selengkapnya <i class="lni lni-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($total_pages > 1): ?>
                <nav aria-label="News page navigation" class="mt-5">
                    <ul class="pagination justify-content-center">

                        <li class="page-item <?= ($current_page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?= $current_page - 1; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= ($i == $current_page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?= $i; ?>"
                                style="<?= ($i == $current_page) ? 'background-color: #FFBC3B; border-color: #FFBC3B;' : 'color: #1A1A37;'; ?>">
                                <?= $i; ?>
                            </a>
                        </li>
                        <?php endfor; ?>

                        <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?= $current_page + 1; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
                <?php else: ?>
                <div class="alert alert-warning text-center" role="alert">
                    <h4 class="alert-heading">Berita Kosong</h4>
                    <p>Saat ini belum ada data berita yang tersedia.</p>
                </div>
                <?php endif; ?>

            </div>
        </section>
    </section>

    <?php include('inc/footer.php'); ?>
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