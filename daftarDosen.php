<?php
include 'inc/koneksi.php';

// Ambil data Member Lab 
$member_lab_res = pg_query($koneksi, "SELECT * FROM dosen WHERE jabatan!='Kepala Lab' ORDER BY nama ASC");
$member_lab_items = [];
while ($row = pg_fetch_assoc($member_lab_res)) {
    $member_lab_items[] = $row;
}
// Data untuk Ketua Lab (dipisahkan untuk mempermudah tata letak)
$result = pg_query($koneksi, "SELECT * FROM dosen WHERE jabatan='Kepala Lab' LIMIT 1");
$ketua_lab = pg_fetch_assoc($result);

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
            
            <h3 class="text-center mb-4 text-dark fw-normal"><i class="bi bi-person-badge-fill me-2 text-warning"></i> Ketua Laboratorium</h3>
            <div class="row justify-content-center mb-5">
                <div class="col-12 col-md-8 col-lg-4 mb-4 d-flex">
                    <div class="card border-0 shadow-lg overflow-hidden text-center flex-fill transition-hover-up"
                         style="border-radius: 20px; transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out; cursor: pointer;"
                         onmouseover="this.style.transform='translateY(-12px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.25)'"
                         onmouseout="this.style.transform='none'; this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,.15)!important'">
                        
                        <div class="overflow-hidden bg-white" 
                             style="height: 350px; display:flex; align-items:center; justify-content:center; border-top-left-radius: 20px; border-top-right-radius: 20px;">
                            <img src="admin/assets/img/<?php echo htmlspecialchars($ketua_lab['foto'] ?? 'default.png'); ?>" 
                                alt="<?php echo htmlspecialchars($ketua_lab['nama'] ?? 'Ketua Lab'); ?>"
                                class="w-100 h-100"
                                style="object-fit: contain; padding: 20px;">
                        </div>
                        
                        <div class="card-body d-flex flex-column p-4 text-white" 
                             style="background-color: #14123A; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                            <p class="text-warning text-uppercase mb-1 fw-bold text-start" style="font-size: 0.95rem;">
                                <?php echo htmlspecialchars($ketua_lab['jabatan']); ?>
                            </p>
                            <h4 class="fw-bolder mb-3 mt-1 text-white text-start" style="min-height: 40px;">
                                <?php echo htmlspecialchars($ketua_lab['nama']); ?>
                            </h4>
                            <div class="text-center mt-auto pt-2">
                                <a href="detailDosen.php?nidn=<?php echo urlencode($ketua_lab['nidn']); ?>"
                                    class="btn btn-warning fw-bold text-dark shadow"
                                    style="padding: 8px 35px; font-size: 0.9rem; border-radius: 50px; text-transform: uppercase; transition: background-color 0.2s;">
                                    DETAIL PROFIL
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-5 border-secondary opacity-25">

            <h3 class="text-center mb-4 text-dark fw-normal"><i class="bi bi-people-fill me-2 text-warning"></i> Anggota Laboratorium</h3>
            <div class="row justify-content-center">
                <?php foreach ($member_lab_items as $item): ?>
                <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex">
                    <div class="card border-0 shadow overflow-hidden text-center flex-fill transition-hover-up"
                         style="border-radius: 15px; transition: transform 0.2s ease-out, box-shadow 0.2s ease-out; cursor: pointer;"
                         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)'"
                         onmouseout="this.style.transform='none'; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)'">
                        
                        <div class="overflow-hidden bg-white" 
                             style="height: 350px; display:flex; align-items:center; justify-content:center; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                            <img src="admin/assets/img/<?php echo htmlspecialchars($item['foto'] ?? 'default.png'); ?>" 
                                alt="<?php echo htmlspecialchars($item['nama'] ?? 'Anggota Lab'); ?>"
                                class="w-100 h-100 p-0"
                                style="object-fit: cover; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                        </div>
                        
                        <div class="card-body d-flex flex-column p-4 text-white" 
                             style="background-color: #14123A; border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                            <p class="text-warning text-uppercase mb-1 fw-bold text-start" style="font-size: 0.9rem;">
                                <?php echo htmlspecialchars($item['jabatan']); ?>
                            </p>
                            <p class="fw-bolder mb-2 text-white text-start" style="min-height: 40px;">
                                <?php echo htmlspecialchars($item['nama']); ?>
                            </p>
                            <div class="text-center mt-auto pt-2">
                                <a href="detailDosen.php?nidn=<?php echo urlencode($item['nidn']); ?>"
                                    class="btn btn-warning fw-bold text-dark"
                                    style="padding: 8px 35px; font-size: 0.9rem; border-radius: 50px; text-transform: uppercase; width: auto;">
                                    DETAIL PROFIL
                                </a>
                            </div>
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