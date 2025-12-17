<?php
require_once 'inc/koneksi.php';

$mode = isset($_GET['mode']) ? $_GET['mode'] : 'list';

// PERBAIKAN: Pastikan variabel query konsisten
$query = "SELECT * FROM products ORDER BY created_at DESC";
$result = pg_query($koneksi, $query); // Sebelumnya $sql

$products = [];
if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $products[] = $row;
    }
}

// Fungsi untuk deskripsi singkat
function deskripsiSingkat($text, $limit = 180) {
    $text = strip_tags($text);
    if (strlen($text) <= $limit) return $text;
    $text = substr($text, 0, $limit);
    $pos = strrpos($text, ' ');
    return substr($text, 0, $pos) . '...';
}
?>

<?php if ($mode === 'list'): ?>
    
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laboratorium Visi Cerdas dan Sistem Cerdas</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet"> </head>

<body class="bg-white">
    <?php include('inc/navbar.php');?>

    <section class="py-5 text-center" style="background-color: #F3EAE0; margin-top: 0;"> 
    <div class="container py-4">
        <h1 class="fw-bold display-5 mb-3 mt-0" style="color: #1d4052;">Produk Lab IVSS</h1>
        <p class="fs-5 mb-0" style="color: #1d4052;">Inovasi riset dan pengembangan di bidang Intelligent Vision dan Smart Systems.</p>
    </div>
</section>

    <section class="container py-5" id="produk-section">
        <div class="row g-4 justify-content-center">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $produk): ?>
                    <div class="col-12 col-xl-10">
                        <div class="card border-warning rounded-4 shadow-sm p-3" style="border-width: 1px; border-color: #ffc107 !important;">
                            <div class="row g-4 align-items-center">
                                <div class="col-md-5 col-lg-4">
                                    <div class="overflow-hidden rounded-3">
                                        <img src="admin/assets/img/<?= htmlspecialchars($produk['gambar']); ?>"
                                             alt="<?= htmlspecialchars($produk['judul']); ?>"
                                             class="img-fluid w-100" 
                                             style="height: 220px; object-fit: cover;">
                                    </div>
                                </div>
                                <div class="col-md-7 col-lg-8">
                                    <div class="card-body p-0">
                                        <h5 class="fw-bold mb-3"><?= htmlspecialchars($produk['judul']); ?></h5>
                                        <p class="text-muted mb-4" style="font-size: 0.95rem; text-align: justify;">
                                            <?= htmlspecialchars(deskripsiSingkat($produk['deskripsi'])); ?>
                                        </p>
                                        
                                        <?php 
                                            $link = !empty($produk['link']) ? htmlspecialchars($produk['link']) : "detailProduk.php?id=" . htmlspecialchars($produk['id']);
                                            $target = !empty($produk['link']) ? "_blank" : "_self";
                                            $label = !empty($produk['link']) ? "Kunjungi Produk" : "Detail";
                                        ?>
                                        
                                        <a href="<?= $link; ?>" target="<?= $target; ?>" class="btn btn-warning btn-sm fw-bold px-4 py-2 shadow-sm">
                                            <i class="bi bi-pencil-square me-1"></i> <?= $label; ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center border-0 shadow-sm">
                        Belum ada data produk tersedia.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php endif; ?>