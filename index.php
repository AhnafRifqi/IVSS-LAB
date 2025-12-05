<?php
include 'inc/koneksi.php';
$query_visi = "SELECT isi FROM visimisi WHERE nama = 'visi' LIMIT 1";
$query_misi = "SELECT isi FROM visimisi WHERE nama = 'misi' LIMIT 1";
$query_dosen = "SELECT * FROM dosen";
$query_peralatan_lab = "SELECT gambar, nama FROM fasilitas 
                        WHERE nama NOT IN ('Area Mushola', 'AC', 'Whiteboard', 'Locker')";
$query_fasilitas_umum = "SELECT gambar, nama FROM fasilitas WHERE nama = 'Area Mushola' OR nama = 'AC' OR nama = 'Whiteboard' OR nama = 'Locker'";
$query_berita_terbaru = "SELECT * FROM berita ORDER BY tanggal DESC LIMIT 3";



$result_visi = pg_query($koneksi, $query_visi);
$result_misi = pg_query($koneksi, $query_misi);
$result_dosen = pg_query($koneksi, $query_dosen);
$result_peralatan_lab = pg_query($koneksi, $query_peralatan_lab);
$result_fasilitas_umum = pg_query($koneksi, $query_fasilitas_umum);
$result_berita_terbaru = pg_query($koneksi, $query_berita_terbaru);

$data_visi = pg_fetch_assoc($result_visi);
$data_misi = pg_fetch_assoc($result_misi);
$data_dosen = pg_fetch_assoc($result_dosen);
$dosen_id_url = $data_dosen['nip'];

$isi_visi = $data_visi['isi'] ?? "Visi belum tersedia di database.";
$isi_misi = $data_misi['isi'] ?? "Misi belum tersedia di database.";
$batas_karakter = 150;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>Laboratorium Visi Cerdas dan Sistem Cerdas</title>

    <link rel="shortcut icon" href="../assets/images/favicon.svg" type="image/svg" />

    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />

    <link rel="stylesheet" href="assets/css/lineicons.css" />

    <link rel="stylesheet" href="assets/css/tiny-slider.css" />

    <link rel="stylesheet" href="assets/css/glightbox.min.css" />

    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <?php include('inc/navbar.php');?>

    <section id="hero-area" class="header-area header-eight" style="background-color: #1A1A37;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12 col-12">
                <div class="header-content">
                    <h1 style="color: #ffffff;"> Selamat Datang di Laboratorium Visi Cerdas dan Sistem Cerdas</h1>
                    
                    <div class="button mt-4">
                        <a href="admin/index.php" class="btn btn-primary me-3 fw-bold" 
                            style="background-color: #FFBC3B; border-color: #FFBC3B; color: #1A1A37;">
                            LOGIN
                        </a>
                    </div>
                    </div>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <div class="header-image">
                    <img src="assets/images/header/gambar1.jpg" alt="#" />
                </div>
            </div>
        </div>
    </div>
</section>
    <section id="fokus-riset" class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="fw-bold mb-3">Fokus Riset</h2>
                    <div style="width: 100px; height: 3px; background-color: #FFBC3B; margin: 0 auto 30px;"></div>
                </div>
            </div>
            <div class="row justify-content-center g-4">

                <div class="col-auto mx-4">
                    <div class="d-flex align-items-center position-relative">
                        <div class="shadow-sm rounded-pill py-3 ps-4 pe-5"
                            style="background-color: #1A1A37; color: #ffffff; z-index: 1;">
                            <span class="fw-bold fs-5">Intelligent Vision</span>
                        </div>
                        <div class="rounded-circle text-dark d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 70px; height: 70px; font-size: 36px; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); z-index: 2; background-color: #FFBC3B">
                            <i class="lni lni-eye"></i>
                        </div>
                    </div>
                </div>

                <div class="col-auto mx-4">
                    <div class="d-flex align-items-center position-relative">
                        <div class="shadow-sm rounded-pill py-3 ps-4 pe-5"
                            style="background-color: #1A1A37; color: #ffffff; z-index: 1;">
                            <span class="fw-bold fs-5">Smart System</span>
                        </div>
                        <div class="rounded-circle text-dark d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 70px; height: 70px; font-size: 36px; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); z-index: 2; background-color: #FFBC3B">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" transform="rotate(0 0 0)">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.61288 2.15759C10.4745 1.68444 12.3561 2.30712 13.5784 3.61827C14.5156 3.15767 15.6338 3.06578 16.6928 3.45537C18.4831 4.11402 19.5117 5.92951 19.2442 7.74502C20.4227 8.53784 21.1997 9.88466 21.1997 11.414C21.1997 13.8546 19.2212 15.8331 16.7806 15.8331H16.4983V18.249C16.4983 18.6632 16.8341 18.999 17.2483 18.999H17.7386C17.9667 18.6955 18.3297 18.4992 18.7385 18.4992H18.7485C19.4389 18.4992 19.9985 19.0589 19.9985 19.7492C19.9985 20.4396 19.4389 20.9992 18.7485 20.9992H18.7385C18.3295 20.9992 17.9663 20.8027 17.7383 20.499H17.2483C16.0057 20.499 14.9983 19.4916 14.9983 18.249V15.8331H12.7483V19.7417C13.0576 19.9692 13.2583 20.3358 13.2583 20.7492C13.2583 21.4396 12.6986 21.9992 12.0083 21.9992H11.9983C11.3079 21.9992 10.7483 21.4396 10.7483 20.7492C10.7483 20.3403 10.9447 19.9772 11.2483 19.7491V15.8331H8.99829V18.249C8.99829 19.4916 7.99093 20.499 6.74829 20.499H6.25857C6.03053 20.8027 5.66736 20.9992 5.25829 20.9992H5.24829C4.55793 20.9992 3.99829 20.4396 3.99829 19.7492C3.99829 19.0589 4.55793 18.4992 5.24829 18.4992H5.25829C5.66714 18.4992 6.03014 18.6955 6.2582 18.999H6.74829C7.1625 18.999 7.49829 18.6632 7.49829 18.249V15.8331H7.21593C4.77535 15.8331 2.79688 13.8546 2.79688 11.414C2.79688 9.85313 3.60618 8.48241 4.82582 7.69667C4.51439 5.19526 6.10638 2.79463 8.61288 2.15759ZM8.28493 14.3331C8.27279 14.3325 8.26058 14.3322 8.24829 14.3322C8.23601 14.3322 8.22379 14.3325 8.21165 14.3331H7.21593C5.60378 14.3331 4.29688 13.0262 4.29688 11.414C4.29688 10.2459 4.98306 9.23645 5.97763 8.7697C6.30019 8.61832 6.47171 8.2609 6.38801 7.91457C5.92891 6.01486 7.0829 4.09413 8.98236 3.61137C10.4633 3.23497 11.9653 3.84287 12.7935 5.02699C12.9097 5.19307 13.0881 5.30507 13.2882 5.33748C13.4882 5.36989 13.6929 5.31995 13.8556 5.19903C14.5014 4.7189 15.3662 4.56562 16.1748 4.86312C17.4081 5.31685 18.06 6.65513 17.6763 7.89564C17.5672 8.24848 17.7312 8.62826 18.0628 8.79076C19.0338 9.26645 19.6997 10.2632 19.6997 11.414C19.6997 13.0262 18.3928 14.3331 16.7806 14.3331H15.7849C15.7728 14.3325 15.7606 14.3322 15.7483 14.3322C15.736 14.3322 15.7238 14.3325 15.7117 14.3331H12.0349C12.0228 14.3325 12.0106 14.3322 11.9983 14.3322C11.986 14.3322 11.9738 14.3325 11.9617 14.3331H8.28493Z"
                                    fill="#343C54" />
                            </svg>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <section style="background-color: #ffffff; padding: 0;">
        <div class="row gx-0 align-items-stretch py-0">

            <div class="col-lg-4 col-md-12 px-0">
                <div class=" p-4 d-flex flex-column align-items-center text-center"
                    style="background-color: #FFBC3B; color: #000000; border-top-right-radius: 5px; height: 50vh; border-bottom-right-radius: 5px;">

                    <div class="fw-bold fs-5 pb-2 mb-3 border-bottom border-dark w-100"
                        style="border-width: 3px !important; color: #ffffff">
                        Ketua Lab
                    </div>

                    <img src="assets/img/<?php echo $data_dosen["foto"] ?>" alt="Foto Dr. Ulla Delfana Rosiani"
                        class="img-fluid rounded-1 mb-3 border border-3 border-dark"
                        style="width: 100%; max-width: 120px; height: auto;" />

                    <div class="mb-auto">
                        <p class="mb-1 small fw-bold" style="color: black;"><?php echo $data_dosen["nama"] ?></p>
                        <p class="mb-0 small" style="color: black;">Email: <?php echo $data_dosen["email"] ?></p>
                    </div>

                    <a href="detailDosen.php?id=<?php echo htmlspecialchars($dosen_id_url); ?>"
                        class="btn btn-sm rounded-pill text-white"
                        style="background-color: #1D264A; padding: 2px 8px; font-size: 0.75rem; margin-top:5px;">Detail</a>
                </div>
            </div>

            <div class="col-lg-8 col-md-12 px-0">
                <div class=" p-4"
                    style="background-color: #1A1A37;  border-top-left-radius: 5px; border-bottom-left-radius: 5px; height: 50vh">
                    <h3 class="fw-bold mb-3 border-bottom pb-2"
                        style="border-width: 3px !important; border-color: #FFBC3B !important; color: white">
                        Profil Laboratorium
                    </h3>

                    <p style="color: white">
                        Laboratorium Visi Cerdas dan Sistem Cerdas merupakan pusat riset dan pengembangan di bawah
                        Jurusan Teknologi Informasi Politeknik Negeri Malang yang berfokus pada bidang intelligent
                        vision dan smart system. Laboratorium ini menjadi wadah bagi dosen dan mahasiswa untuk melakukan
                        penelitian, pembelajaran, serta pelatihan dalam pengembangan sistem cerdas berbasis pengolahan
                        citra dan
                        kecerdasan buatan.
                    </p>
                    <p style="color: white; padding-top: 50px">
                        Penelitian di laboratorium ini mengintegrasikan computer vision, AI, dan IoT untuk
                        menciptakan solusi inovatif yang mampu mengenali, menganalisis, serta merespon lingkungan
                        secara mandiri.
                    </p>
                </div>
            </div>

        </div>
    </section>
    <section class="about-area about-five py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-12">
                    <div class="about-image-five">
                        <img src="assets/img/html-css-collage-concept-with-person-2-2048x1341.png" alt="about" />
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="about-five-content">
                        <h6 class="small-title text-lg">VISI & MISI</h6>
                        <h2 class="main-title fw-bold">LABORATORIUM INTELLIGENT AND SMART SYSTEM</h2>
                        <div class="about-five-tab">

                            <nav style="background-color: #1A1A37;">
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link active tab-custom-color" id="nav-vision-tab"
                                        data-bs-toggle="tab" data-bs-target="#nav-vision" type="button" role="tab"
                                        aria-controls="nav-vision" aria-selected="true">
                                        Visi
                                    </button>
                                    <button class="nav-link tab-custom-color" id="nav-history-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-history" type="button" role="tab"
                                        aria-controls="nav-history" aria-selected="false">
                                        Misi
                                    </button>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-vision" role="tabpanel"
                                    aria-labelledby="nav-vision-tab">
                                    <p><?php echo nl2br($isi_visi); ?></p>
                                </div>
                                <div class="tab-pane fade" id="nav-history" role="tabpanel"
                                    aria-labelledby="nav-history-tab">
                                    <p><?php echo nl2br($isi_misi); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="call-action" class="call-action" style="background: #1A1A37;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-9">
                    <div class="inner-content">
                        <h2>SOP dan Layanan</h2>
                        <p>
                            Lokasi: Gedung Jurusan Teknologi Informasi — Lantai 8 Barat<br>
                            Surel: –<br>
                            Jam layanan: Senin—Jumat 08.00—16.00
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="fasilitas-laboratorium" class="py-5 bg-light">
        <div class="container">

            <div class="row">
                <div class="col-12 text-center my-5 pt-4">
                    <h2 class="fw-bold">Fasilitas Laboratorium</h2>
                    <div style="width: 500px; height: 3px; background-color: #FFBC3B; margin: 0 auto 30px;"></div>
                </div>
            </div>

            <div class="row justify-content-center g-4 mb-5">
                <?php
    while ($data_fasilitas = pg_fetch_assoc($result_fasilitas_umum)) {
    ?>
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="card p-2 rounded-3 shadow-sm h-100 border-0"
                        style="background-color: #1A1A37; color: #ffffff;">

                        <img src="admin/assets/img/<?php echo $data_fasilitas['gambar']; ?>"
                            alt="<?php echo $data_fasilitas['nama']; ?>" class="img-fluid rounded-3 mb-2"
                            style="border: 2px solid #ccc; height: 150px; object-fit: cover;">

                        <div class="text-center">
                            <span class="badge rounded-pill text-dark px-3 py-2" style="background-color: #FFBC3B;">
                                <?php echo $data_fasilitas['nama']; ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php
    }
    ?>
            </div>

            <hr style="border-top: 3px solid #FFBC3B; opacity: 1;">

            <div class="row">
                <div class="col-12 text-center my-5 pt-4">
                    <h2 class="fw-bold">Peralatan Laboratorium</h2>
                    <div style="width: 500px; height: 3px; background-color: #FFBC3B; margin: 0 auto 30px;"></div>
                </div>
            </div>

            <div class="row justify-content-center g-4">
                <?php
    while ($data_alat = pg_fetch_assoc($result_peralatan_lab)) {
    ?>
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="card p-2 rounded-3 shadow-sm h-100 border-0"
                        style="background-color: #1A1A37; color: #ffffff;">

                        <img src="admin/assets/img/<?php echo $data_alat['gambar']; ?>"
                            alt="<?php echo $data_alat['nama']; ?>" class="img-fluid rounded-3 mb-2"
                            style="border: 2px solid #ccc; height: 150px; object-fit: cover;">

                        <div class="text-center">
                            <span class="badge rounded-pill text-dark px-3 py-2" style="background-color: #FFBC3B;">
                                <?php echo $data_alat['nama']; ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php
    }
    ?>
            </div>

        </div>
    </section>
    <section id="kegiatan-proyek" class="py-5" style="background-color: #1A1A37; color: #ffffff;">
        <div class="container">

            <!-- Judul Kegiatan & Proyek -->
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold mb-3 " style="color: #ffffff;">Kegiatan & Proyek</h2>
                    <div style="width: 300px; height: 3px; background-color: #FFBC3B; margin: 0 auto 30px;"></div>
                </div>
            </div>

            <!-- 3 Item Kegiatan -->
            <div class="row text-center g-4 mb-5 pb-4 justify-content-center">

                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="d-flex flex-column align-items-center px-4">
                        <div
                            style="border: 2px solid #FFBC3B; padding: 14px; border-radius: 10px; display: inline-block;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" transform="rotate(0 0 0)">
                                <path opacity="0.4"
                                    d="M7.49805 15.833V18.2489C7.49805 18.6631 7.16226 18.9989 6.74805 18.9989H6.25796C6.02989 18.6954 5.66689 18.4991 5.25805 18.4991H5.24805C4.55769 18.4991 3.99805 19.0588 3.99805 19.7491C3.99805 20.4395 4.55769 20.9991 5.24805 20.9991H5.25805C5.66711 20.9991 6.03028 20.8026 6.25832 20.4989H6.74805C7.99069 20.4989 8.99805 19.4915 8.99805 18.2489V15.833H7.49805ZM11.248 15.833V19.749C10.9444 19.9771 10.748 20.3402 10.748 20.7491C10.748 21.4395 11.3077 21.9991 11.998 21.9991H12.008C12.6984 21.9991 13.258 21.4395 13.258 20.7491C13.258 20.3357 13.0573 19.9691 12.748 19.7416V15.833H11.248ZM14.998 15.833V18.2489C14.998 19.4915 16.0054 20.4989 17.248 20.4989H17.738C17.966 20.8026 18.3292 20.9991 18.7383 20.9991H18.7483C19.4386 20.9991 19.9983 20.4395 19.9983 19.7491C19.9983 19.0588 19.4386 18.4991 18.7483 18.4991H18.7383C18.3294 18.4991 17.9664 18.6954 17.7384 18.9989H17.248C16.8338 18.9989 16.498 18.6631 16.498 18.2489V15.833H14.998Z"
                                    fill="#ffffff" />
                                <path
                                    d="M13.5784 3.61827C12.3561 2.30712 10.4745 1.68444 8.61288 2.15759C6.10638 2.79463 4.51439 5.19526 4.82582 7.69667C3.60618 8.48241 2.79688 9.85313 2.79688 11.414C2.79688 13.8546 4.77535 15.8331 7.21593 15.8331H7.49829H8.99829H11.2483H12.7483H14.9983H16.4983H16.7806C19.2212 15.8331 21.1997 13.8546 21.1997 11.414C21.1997 9.88466 20.4227 8.53784 19.2442 7.74502C19.5117 5.92951 18.4831 4.11402 16.6928 3.45537C15.6338 3.06578 14.5156 3.15767 13.5784 3.61827ZM7.21593 14.3331C5.60378 14.3331 4.29688 13.0262 4.29688 11.414C4.29688 10.2459 4.98306 9.23645 5.97763 8.7697C6.30019 8.61832 6.47171 8.2609 6.38801 7.91457C5.92891 6.01486 7.0829 4.09413 8.98236 3.61137C10.4633 3.23497 11.9653 3.84287 12.7935 5.02699C12.9097 5.19307 13.0881 5.30507 13.2882 5.33748C13.4882 5.36989 13.6929 5.31995 13.8556 5.19903C14.5014 4.7189 15.3662 4.56562 16.1748 4.86312C17.4081 5.31685 18.06 6.65513 17.6763 7.89564C17.5672 8.24848 17.7312 8.62826 18.0628 8.79076C19.0338 9.26645 19.6997 10.2632 19.6997 11.414C19.6997 13.0262 18.3928 14.3331 16.7806 14.3331H7.21593Z"
                                    fill="#ffffff" />
                            </svg>

                        </div>
                        <h4 class="fw-bold mt-3" style="color: #FFBC3B;">Sistem Cerdas</h4>
                        <p class="small text-white-75" style="color: #ffffff;">
                            Integrasi AI dengan sistem nyata untuk membantu pengambilan keputusan.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="d-flex flex-column align-items-center px-4">
                        <div
                            style="border: 2px solid #FFBC3B; padding: 14px; border-radius: 10px; display: inline-block;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" transform="rotate(0 0 0)">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M5.28636 3.71264C4.853 4.09212 4.5 4.60958 4.5 5.25V18.75C4.5 19.3904 4.853 19.9079 5.28636 20.2874C5.7212 20.6681 6.30718 20.9769 6.96654 21.2211C8.29107 21.7116 10.0708 22 12 22C13.9292 22 15.7089 21.7116 17.0335 21.2211C17.6928 20.9769 18.2788 20.6681 18.7136 20.2874C19.147 19.9079 19.5 19.3904 19.5 18.75V5.25C19.5 4.60958 19.147 4.09212 18.7136 3.71264C18.2788 3.33187 17.6928 3.02313 17.0335 2.77892C15.7089 2.28836 13.9292 2 12 2C10.0708 2 8.29107 2.28836 6.96654 2.77892C6.30718 3.02313 5.7212 3.33187 5.28636 3.71264ZM6.27454 4.84114C6.02476 5.05985 6 5.20007 6 5.25C6 5.29993 6.02476 5.44015 6.27454 5.65886C6.52284 5.87629 6.92537 6.10625 7.48751 6.31446C8.60601 6.72871 10.2013 7 12 7C13.7987 7 15.394 6.72871 16.5125 6.31446C17.0746 6.10625 17.4772 5.87629 17.7255 5.65886C17.9752 5.44015 18 5.29993 18 5.25C18 5.20007 17.9752 5.05985 17.7255 4.84114C17.4772 4.62371 17.0746 4.39375 16.5125 4.18554C15.394 3.77129 13.7987 3.5 12 3.5C10.2013 3.5 8.60601 3.77129 7.48751 4.18554C6.92537 4.39375 6.52284 4.62371 6.27454 4.84114ZM18 9.75V7.28202C17.7055 7.44688 17.3796 7.59287 17.0335 7.72108C15.7089 8.21164 13.9292 8.5 12 8.5C10.0708 8.5 8.29107 8.21164 6.96654 7.72108C6.62039 7.59287 6.29445 7.44688 6 7.28202V9.75C6 9.79993 6.02476 9.94015 6.27454 10.1589C6.52284 10.3763 6.92537 10.6063 7.48751 10.8145C8.60601 11.2287 10.2013 11.5 12 11.5C13.7987 11.5 15.394 11.2287 16.5125 10.8145C17.0746 10.6063 17.4772 10.3763 17.7255 10.1589C17.9752 9.94015 18 9.79993 18 9.75ZM6 11.782C6.29445 11.9469 6.62039 12.0929 6.96654 12.2211C8.29107 12.7116 10.0708 13 12 13C13.9292 13 15.7089 12.7116 17.0335 12.2211C17.3796 12.0929 17.7055 11.9469 18 11.782V14.25C18 14.2999 17.9752 14.4402 17.7255 14.6589C17.4772 14.8763 17.0746 15.1063 16.5125 15.3145C15.394 15.7287 13.7987 16 12 16C10.2013 16 8.60601 15.7287 7.48751 15.3145C6.92537 15.1063 6.52284 14.8763 6.27454 14.6589C6.02476 14.4402 6 14.2999 6 14.25V11.782ZM6 18.75V16.282C6.29445 16.4469 6.62039 16.5929 6.96654 16.7211C8.29107 17.2116 10.0708 17.5 12 17.5C13.9292 17.5 15.7089 17.2116 17.0335 16.7211C17.3796 16.5929 17.7055 16.4469 18 16.282V18.75C18 18.7999 17.9752 18.9401 17.7255 19.1589C17.4772 19.3763 17.0746 19.6063 16.5125 19.8145C15.394 20.2287 13.7987 20.5 12 20.5C10.2013 20.5 8.60601 20.2287 7.48751 19.8145C6.92537 19.6063 6.52284 19.3763 6.27454 19.1589C6.02476 18.9401 6 18.7999 6 18.75Z"
                                    fill="#ffffff" />
                            </svg>


                        </div>
                        <h4 class="fw-bold mt-3" style="color: #FFBC3B;">Machine Learning</h4>
                        <p class="small text-white-75" style="color: #ffffff;">
                            Pembelajaran mesin untuk klasifikasi, regresi, dan clustering menggunakan dataset nyata.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="d-flex flex-column align-items-center px-4">
                        <div
                            style="border: 2px solid #FFBC3B; padding: 14px; border-radius: 10px; display: inline-block;">
                            <svg width="48" height="48" viewBox="0 0 25 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" transform="rotate(0 0 0)">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M9.0625 18.0453C9.01679 16.914 8.26885 15.8509 7.26013 14.8658C5.86655 13.5048 5 11.6029 5 9.49987C5 5.35781 8.35781 2 12.4999 2C16.6419 2 19.9997 5.35781 19.9997 9.49987C19.9997 11.6027 19.1333 13.5046 17.7399 14.8655C16.6988 15.8824 15.9355 16.9822 15.9355 18.1544V19.75C15.9355 20.9926 14.9282 22 13.6855 22H11.3125C10.0699 22 9.0625 20.9926 9.0625 19.75V18.0453ZM6.5 9.49987C6.5 6.18624 9.18624 3.5 12.4999 3.5C15.8135 3.5 18.4997 6.18624 18.4997 9.49987C18.4997 11.1824 17.8082 12.7021 16.6918 13.7925C15.9506 14.5164 15.1802 15.4257 14.7589 16.5H10.2412C9.81983 15.4258 9.04933 14.5165 8.30817 13.7927C7.19168 12.7023 6.5 11.1825 6.5 9.49987ZM10.5625 18.2129C10.564 18.1938 10.5647 18.1744 10.5647 18.1549C10.5647 18.1088 10.564 18.063 10.5625 18.0175V18H14.4355V19.75C14.4355 20.1642 14.0998 20.5 13.6855 20.5H11.3125C10.8983 20.5 10.5625 20.1642 10.5625 19.75V18.2129Z"
                                    fill="#ffffff" />
                                <path
                                    d="M1.75 9.5C1.75 9.08579 2.08579 8.75 2.5 8.75H3.5C3.91421 8.75 4.25 9.08579 4.25 9.5C4.25 9.91421 3.91421 10.25 3.5 10.25H2.5C2.08579 10.25 1.75 9.91421 1.75 9.5Z"
                                    fill="#ffffff" />
                                <path
                                    d="M4.21484 3.85048C3.85612 3.64337 3.39743 3.76628 3.19032 4.125C2.98322 4.48372 3.10612 4.94241 3.46484 5.14952L4.33087 5.64952C4.68959 5.85663 5.14828 5.73372 5.35539 5.375C5.56249 5.01628 5.43959 4.55759 5.08087 4.35048L4.21484 3.85048Z"
                                    fill="#ffffff" />
                                <path
                                    d="M3.19032 14.875C2.98322 14.5163 3.10612 14.0576 3.46484 13.8505L4.33087 13.3505C4.68959 13.1434 5.14828 13.2663 5.35539 13.625C5.56249 13.9837 5.43959 14.4424 5.08087 14.6495L4.21484 15.1495C3.85612 15.3566 3.39743 15.2337 3.19032 14.875Z"
                                    fill="#ffffff" />
                                <path
                                    d="M21.5 8.75C21.0858 8.75 20.75 9.08579 20.75 9.5C20.75 9.91421 21.0858 10.25 21.5 10.25H22.5C22.9142 10.25 23.25 9.91421 23.25 9.5C23.25 9.08579 22.9142 8.75 22.5 8.75H21.5Z"
                                    fill="#ffffff" />
                                <path
                                    d="M19.6454 13.625C19.8525 13.2663 20.3112 13.1434 20.6699 13.3505L21.5359 13.8505C21.8947 14.0576 22.0176 14.5163 21.8105 14.875C21.6034 15.2337 21.1447 15.3566 20.7859 15.1495L19.9199 14.6495C19.5612 14.4424 19.4383 13.9837 19.6454 13.625Z"
                                    fill="#ffffff" />
                                <path
                                    d="M19.9199 4.35048C19.5612 4.55759 19.4383 5.01628 19.6454 5.375C19.8525 5.73372 20.3112 5.85663 20.6699 5.64952L21.5359 5.14952C21.8947 4.94241 22.0176 4.48372 21.8105 4.125C21.6034 3.76628 21.1447 3.64337 20.7859 3.85048L19.9199 4.35048Z"
                                    fill="#ffffff" />
                            </svg>

                        </div>
                        <h4 class="fw-bold mt-3" style="color: #FFBC3B;">Visi Komputer</h4>
                        <p class="small text-white-75" style="color: #ffffff;">
                            Penerapan teknik AI pada pengolahan citra/video untuk mendeteksi dan mengenali objek.
                        </p>
                    </div>
                </div>
            </div>
            <!-- PERKULIAHAN TERKAIT -->
            <div class="row">
                <div class="col-12 text-center mt-5 mb-5">
                    <h2 class="fw-bold mb-3" style="color: #ffffff;">Perkuliahan Terkait</h2>
                    <div style="width: 300px; height: 3px; background-color: #FFBC3B; margin: 0 auto 30px;"></div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10 col-12">

                    <!-- ITEM 1 (KIRI) -->
                    <div class="d-flex align-items-start mb-4">
                        <div class="me-4">
                            <svg width="48" height="48" viewBox="0 0 24 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg" transform="rotate(0 0 0)">
                                <path
                                    d="M20.5565 10.6338C21.0009 9.27575 20.8528 7.76958 20.1367 6.53501C19.0503 4.63378 16.8528 3.67081 14.7046 4.11526C13.7663 3.05353 12.3836 2.46094 10.9515 2.46094C8.75399 2.46094 6.82807 3.86835 6.13671 5.94242C4.7293 6.23872 3.51943 7.10291 2.80338 8.36217C1.71696 10.2634 1.96387 12.6338 3.42066 14.2634C2.97622 15.6461 3.14906 17.1276 3.8651 18.3622C4.95152 20.2634 7.14906 21.2511 9.2972 20.7819C10.2602 21.8437 11.6182 22.4609 13.0503 22.4609C15.2478 22.4609 17.1737 21.0535 17.8651 18.9795C19.2725 18.6832 20.4824 17.819 21.1984 16.5597C22.2849 14.6585 22.0379 12.2634 20.5565 10.6338ZM13.0503 21.1523C12.1614 21.1523 11.3219 20.856 10.6552 20.2881C10.6799 20.2634 10.754 20.2387 10.7787 20.214L14.754 17.9177C14.9515 17.7943 15.075 17.5967 15.075 17.3498V11.7449L16.754 12.7079C16.7787 12.7079 16.7787 12.7325 16.7787 12.7572V17.3992C16.8034 19.4733 15.1244 21.1523 13.0503 21.1523ZM5.00091 17.7202C4.55646 16.9548 4.40831 16.0659 4.55646 15.2017C4.58115 15.2264 4.63054 15.2511 4.67992 15.2758L8.65523 17.572C8.85276 17.6955 9.09967 17.6955 9.2972 17.572L14.1614 14.7572V16.7079C14.1614 16.7325 14.1614 16.7572 14.1367 16.7572L10.112 19.0782C8.33424 20.1153 6.03794 19.498 5.00091 17.7202ZM3.96387 9.02884C4.40831 8.26341 5.09967 7.69551 5.91449 7.37452V12.1153C5.91449 12.3375 6.03794 12.5597 6.23548 12.6832L11.0997 15.498L9.42066 16.4609C9.39597 16.4609 9.37128 16.4856 9.37128 16.4609L5.34659 14.1399C3.51943 13.1029 2.92683 10.8066 3.96387 9.02884ZM17.791 12.2387L12.9268 9.4239L14.6058 8.46094C14.6305 8.46094 14.6552 8.43625 14.6552 8.46094L18.6799 10.7819C20.4824 11.819 21.075 14.1153 20.0379 15.893C19.5935 16.6585 18.9021 17.2264 18.0873 17.5227V12.8066C18.112 12.5844 17.9886 12.3622 17.791 12.2387ZM19.4454 9.7202C19.4207 9.69551 19.3713 9.67081 19.3219 9.64612L15.3466 7.34983C15.1491 7.22637 14.9021 7.22637 14.7046 7.34983L9.84041 10.1646V8.21402C9.84041 8.18933 9.84041 8.16464 9.86511 8.16464L13.8898 5.84365C15.6923 4.80662 17.9639 5.4239 19.0009 7.22637C19.4454 7.96711 19.5935 8.856 19.4454 9.7202ZM8.92683 13.177L7.24782 12.214C7.22313 12.214 7.22313 12.1893 7.22313 12.1646V7.52267C7.22313 5.44859 8.90214 3.76958 10.9762 3.76958C11.8651 3.76958 12.7046 4.06588 13.3713 4.63378C13.3466 4.65847 13.2972 4.68316 13.2478 4.70785L9.27251 7.00415C9.07498 7.1276 8.95152 7.32514 8.95152 7.57205V13.177H8.92683ZM9.84041 11.2017L12.0133 9.94242L14.1861 11.2017V13.6955L12.0133 14.9548L9.84041 13.6955V11.2017Z"
                                    fill="#ffffff" />
                            </svg>

                        </div>
                        <div>
                            <h5 class="fw-bold mb-1" style="color: #FFBC3B;">Kecerdasan Artifisial</h5>
                            <p class="small text-white-75 mb-0" style="color: #ffffff;">
                                Teknologi yang fokus pada pengembangan sistem atau mesin yang dapat melakukan
                                tugas-tugas yang biasanya memerlukan kecerdasan manusia, seperti pengenalan pola,
                                pembelajaran, pemecahan masalah, dan pengambilan keputusan.
                            </p>
                        </div>
                    </div>

                    <!-- ITEM 2 (KANAN) -->
                    <div class="d-flex align-items-start flex-row-reverse text-end mb-4">
                        <div class="ms-4">
                            <svg width="48" height="48" viewBox="0 0 25 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" transform="rotate(0 0 0)">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M9.0625 18.0453C9.01679 16.914 8.26885 15.8509 7.26013 14.8658C5.86655 13.5048 5 11.6029 5 9.49987C5 5.35781 8.35781 2 12.4999 2C16.6419 2 19.9997 5.35781 19.9997 9.49987C19.9997 11.6027 19.1333 13.5046 17.7399 14.8655C16.6988 15.8824 15.9355 16.9822 15.9355 18.1544V19.75C15.9355 20.9926 14.9282 22 13.6855 22H11.3125C10.0699 22 9.0625 20.9926 9.0625 19.75V18.0453ZM6.5 9.49987C6.5 6.18624 9.18624 3.5 12.4999 3.5C15.8135 3.5 18.4997 6.18624 18.4997 9.49987C18.4997 11.1824 17.8082 12.7021 16.6918 13.7925C15.9506 14.5164 15.1802 15.4257 14.7589 16.5H10.2412C9.81983 15.4258 9.04933 14.5165 8.30817 13.7927C7.19168 12.7023 6.5 11.1825 6.5 9.49987ZM10.5625 18.2129C10.564 18.1938 10.5647 18.1744 10.5647 18.1549C10.5647 18.1088 10.564 18.063 10.5625 18.0175V18H14.4355V19.75C14.4355 20.1642 14.0998 20.5 13.6855 20.5H11.3125C10.8983 20.5 10.5625 20.1642 10.5625 19.75V18.2129Z"
                                    fill="#ffffff" />
                                <path
                                    d="M1.75 9.5C1.75 9.08579 2.08579 8.75 2.5 8.75H3.5C3.91421 8.75 4.25 9.08579 4.25 9.5C4.25 9.91421 3.91421 10.25 3.5 10.25H2.5C2.08579 10.25 1.75 9.91421 1.75 9.5Z"
                                    fill="#ffffff" />
                                <path
                                    d="M4.21484 3.85048C3.85612 3.64337 3.39743 3.76628 3.19032 4.125C2.98322 4.48372 3.10612 4.94241 3.46484 5.14952L4.33087 5.64952C4.68959 5.85663 5.14828 5.73372 5.35539 5.375C5.56249 5.01628 5.43959 4.55759 5.08087 4.35048L4.21484 3.85048Z"
                                    fill="#ffffff" />
                                <path
                                    d="M3.19032 14.875C2.98322 14.5163 3.10612 14.0576 3.46484 13.8505L4.33087 13.3505C4.68959 13.1434 5.14828 13.2663 5.35539 13.625C5.56249 13.9837 5.43959 14.4424 5.08087 14.6495L4.21484 15.1495C3.85612 15.3566 3.39743 15.2337 3.19032 14.875Z"
                                    fill="#ffffff" />
                                <path
                                    d="M21.5 8.75C21.0858 8.75 20.75 9.08579 20.75 9.5C20.75 9.91421 21.0858 10.25 21.5 10.25H22.5C22.9142 10.25 23.25 9.91421 23.25 9.5C23.25 9.08579 22.9142 8.75 22.5 8.75H21.5Z"
                                    fill="#ffffff" />
                                <path
                                    d="M19.6454 13.625C19.8525 13.2663 20.3112 13.1434 20.6699 13.3505L21.5359 13.8505C21.8947 14.0576 22.0176 14.5163 21.8105 14.875C21.6034 15.2337 21.1447 15.3566 20.7859 15.1495L19.9199 14.6495C19.5612 14.4424 19.4383 13.9837 19.6454 13.625Z"
                                    fill="#ffffff" />
                                <path
                                    d="M19.9199 4.35048C19.5612 4.55759 19.4383 5.01628 19.6454 5.375C19.8525 5.73372 20.3112 5.85663 20.6699 5.64952L21.5359 5.14952C21.8947 4.94241 22.0176 4.48372 21.8105 4.125C21.6034 3.76628 21.1447 3.64337 20.7859 3.85048L19.9199 4.35048Z"
                                    fill="#ffffff" />
                            </svg>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1" style="color: #FFBC3B;">Pengolahan Citra & Visi Komputer</h5>
                            <p class="small text-white-75 mb-0" style="color: #ffffff;">
                                Cabang dari kecerdasan artifisial yang fokus pada pengembangan algoritma yang
                                memungkinkan mesin belajar dari data untuk membuat prediksi atau keputusan tanpa
                                diprogram secara eksplisit.
                            </p>
                        </div>
                    </div>

                    <!-- ITEM 3 (KIRI) -->
                    <div class="d-flex align-items-start mb-4">
                        <div class="me-4">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" transform="rotate(0 0 0)">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M5.28636 3.71264C4.853 4.09212 4.5 4.60958 4.5 5.25V18.75C4.5 19.3904 4.853 19.9079 5.28636 20.2874C5.7212 20.6681 6.30718 20.9769 6.96654 21.2211C8.29107 21.7116 10.0708 22 12 22C13.9292 22 15.7089 21.7116 17.0335 21.2211C17.6928 20.9769 18.2788 20.6681 18.7136 20.2874C19.147 19.9079 19.5 19.3904 19.5 18.75V5.25C19.5 4.60958 19.147 4.09212 18.7136 3.71264C18.2788 3.33187 17.6928 3.02313 17.0335 2.77892C15.7089 2.28836 13.9292 2 12 2C10.0708 2 8.29107 2.28836 6.96654 2.77892C6.30718 3.02313 5.7212 3.33187 5.28636 3.71264ZM6.27454 4.84114C6.02476 5.05985 6 5.20007 6 5.25C6 5.29993 6.02476 5.44015 6.27454 5.65886C6.52284 5.87629 6.92537 6.10625 7.48751 6.31446C8.60601 6.72871 10.2013 7 12 7C13.7987 7 15.394 6.72871 16.5125 6.31446C17.0746 6.10625 17.4772 5.87629 17.7255 5.65886C17.9752 5.44015 18 5.29993 18 5.25C18 5.20007 17.9752 5.05985 17.7255 4.84114C17.4772 4.62371 17.0746 4.39375 16.5125 4.18554C15.394 3.77129 13.7987 3.5 12 3.5C10.2013 3.5 8.60601 3.77129 7.48751 4.18554C6.92537 4.39375 6.52284 4.62371 6.27454 4.84114ZM18 9.75V7.28202C17.7055 7.44688 17.3796 7.59287 17.0335 7.72108C15.7089 8.21164 13.9292 8.5 12 8.5C10.0708 8.5 8.29107 8.21164 6.96654 7.72108C6.62039 7.59287 6.29445 7.44688 6 7.28202V9.75C6 9.79993 6.02476 9.94015 6.27454 10.1589C6.52284 10.3763 6.92537 10.6063 7.48751 10.8145C8.60601 11.2287 10.2013 11.5 12 11.5C13.7987 11.5 15.394 11.2287 16.5125 10.8145C17.0746 10.6063 17.4772 10.3763 17.7255 10.1589C17.9752 9.94015 18 9.79993 18 9.75ZM6 11.782C6.29445 11.9469 6.62039 12.0929 6.96654 12.2211C8.29107 12.7116 10.0708 13 12 13C13.9292 13 15.7089 12.7116 17.0335 12.2211C17.3796 12.0929 17.7055 11.9469 18 11.782V14.25C18 14.2999 17.9752 14.4402 17.7255 14.6589C17.4772 14.8763 17.0746 15.1063 16.5125 15.3145C15.394 15.7287 13.7987 16 12 16C10.2013 16 8.60601 15.7287 7.48751 15.3145C6.92537 15.1063 6.52284 14.8763 6.27454 14.6589C6.02476 14.4402 6 14.2999 6 14.25V11.782ZM6 18.75V16.282C6.29445 16.4469 6.62039 16.5929 6.96654 16.7211C8.29107 17.2116 10.0708 17.5 12 17.5C13.9292 17.5 15.7089 17.2116 17.0335 16.7211C17.3796 16.5929 17.7055 16.4469 18 16.282V18.75C18 18.7999 17.9752 18.9401 17.7255 19.1589C17.4772 19.3763 17.0746 19.6063 16.5125 19.8145C15.394 20.2287 13.7987 20.5 12 20.5C10.2013 20.5 8.60601 20.2287 7.48751 19.8145C6.92537 19.6063 6.52284 19.3763 6.27454 19.1589C6.02476 18.9401 6 18.7999 6 18.75Z"
                                    fill="#ffffff" />
                            </svg>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1" style="color: #FFBC3B;">Machine Learning</h5>
                            <p class="small text-white-75 mb-0" style="color: #ffffff;">
                                Teknik untuk mengolah dan menganalisis gambar atau video menggunakan komputer, termasuk
                                deteksi objek, segmentasi, pengenalan pola, dan interpretasi citra untuk aplikasi
                                seperti pengenalan wajah dan kendaraan otomatis.
                            </p>
                        </div>
                    </div>

                    <!-- ITEM 4 (KANAN) -->
                    <div class="d-flex align-items-start flex-row-reverse text-end mb-4">
                        <div class="ms-4">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" transform="rotate(0 0 0)">
                                <path opacity="0.4"
                                    d="M7.49805 15.833V18.2489C7.49805 18.6631 7.16226 18.9989 6.74805 18.9989H6.25796C6.02989 18.6954 5.66689 18.4991 5.25805 18.4991H5.24805C4.55769 18.4991 3.99805 19.0588 3.99805 19.7491C3.99805 20.4395 4.55769 20.9991 5.24805 20.9991H5.25805C5.66711 20.9991 6.03028 20.8026 6.25832 20.4989H6.74805C7.99069 20.4989 8.99805 19.4915 8.99805 18.2489V15.833H7.49805ZM11.248 15.833V19.749C10.9444 19.9771 10.748 20.3402 10.748 20.7491C10.748 21.4395 11.3077 21.9991 11.998 21.9991H12.008C12.6984 21.9991 13.258 21.4395 13.258 20.7491C13.258 20.3357 13.0573 19.9691 12.748 19.7416V15.833H11.248ZM14.998 15.833V18.2489C14.998 19.4915 16.0054 20.4989 17.248 20.4989H17.738C17.966 20.8026 18.3292 20.9991 18.7383 20.9991H18.7483C19.4386 20.9991 19.9983 20.4395 19.9983 19.7491C19.9983 19.0588 19.4386 18.4991 18.7483 18.4991H18.7383C18.3294 18.4991 17.9664 18.6954 17.7384 18.9989H17.248C16.8338 18.9989 16.498 18.6631 16.498 18.2489V15.833H14.998Z"
                                    fill="#ffffff" />
                                <path
                                    d="M13.5784 3.61827C12.3561 2.30712 10.4745 1.68444 8.61288 2.15759C6.10638 2.79463 4.51439 5.19526 4.82582 7.69667C3.60618 8.48241 2.79688 9.85313 2.79688 11.414C2.79688 13.8546 4.77535 15.8331 7.21593 15.8331H7.49829H8.99829H11.2483H12.7483H14.9983H16.4983H16.7806C19.2212 15.8331 21.1997 13.8546 21.1997 11.414C21.1997 9.88466 20.4227 8.53784 19.2442 7.74502C19.5117 5.92951 18.4831 4.11402 16.6928 3.45537C15.6338 3.06578 14.5156 3.15767 13.5784 3.61827ZM7.21593 14.3331C5.60378 14.3331 4.29688 13.0262 4.29688 11.414C4.29688 10.2459 4.98306 9.23645 5.97763 8.7697C6.30019 8.61832 6.47171 8.2609 6.38801 7.91457C5.92891 6.01486 7.0829 4.09413 8.98236 3.61137C10.4633 3.23497 11.9653 3.84287 12.7935 5.02699C12.9097 5.19307 13.0881 5.30507 13.2882 5.33748C13.4882 5.36989 13.6929 5.31995 13.8556 5.19903C14.5014 4.7189 15.3662 4.56562 16.1748 4.86312C17.4081 5.31685 18.06 6.65513 17.6763 7.89564C17.5672 8.24848 17.7312 8.62826 18.0628 8.79076C19.0338 9.26645 19.6997 10.2632 19.6997 11.414C19.6997 13.0262 18.3928 14.3331 16.7806 14.3331H7.21593Z"
                                    fill="#ffffff" />
                            </svg>

                        </div>
                        <div>
                            <h5 class="fw-bold mb-1" style="color: #FFBC3B;">Sistem Cerdas</h5>
                            <p class="small text-white-75 mb-0" style="color: #ffffff;">
                                Pengembangan sistem yang dapat meniru atau melampaui kemampuan kognitif manusia, seperti
                                pengambilan keputusan otomatis, perencanaan, dan pemrosesan informasi dalam konteks
                                aplikasi nyata, seperti robotika dan sistem pakar.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
    </section>
    <section id="publikasi" class="pt-5 pb-0" style="background-color: #ffffff; color: #1A1A37;">
        <div class="container">

            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold" style="color: #1A1A37;">Publikasi</h2>
                    <div style="width: 300px; height: 3px; background-color: #FFBC3B; margin: 0 auto 30px;"></div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover rounded-3 overflow-hidden text-center"
                            style="border-collapse: separate; border-spacing: 0; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">

                            <thead style="background-color: #1A1A37;">
                                <tr style="color: #ffffff;">
                                    <th scope="col" class="py-3">Dosen</th>
                                    <th scope="col" class="py-3">Scopus</th>
                                    <th scope="col" class="py-3">Google Scholar</th>
                                    <th scope="col" class="py-3">Sinta</th>
                                </tr>
                            </thead>

                            <tbody style="background-color: #ffffff;">
                                <?php
                            if (isset($result_dosen) && pg_num_rows($result_dosen) > 0) {
                                $no = 1;
                                pg_result_seek($result_dosen, 0); 
                                while ($data_dosen = pg_fetch_assoc($result_dosen)) {
                                    $link_scopus = $data_dosen['scopus'] ?? '#';
                                    $link_scholar = $data_dosen['google_scholar'] ?? '#';
                                    $link_sinta = $data_dosen['sinta'] ?? '#';
                            ?>
                                <tr>
                                    <td class="text-start">
                                        <?php 
                                    echo $no++ . ". " . ($data_dosen['nama'] ?? 'Nama Dosen Tidak Ada'); 
                                    ?>
                                    </td>

                                    <td>
                                        <a href="<?php echo $link_scopus; ?>" target="_blank" class="btn btn-sm text-dark fw-bold 
                                        <?php echo ($link_scopus === '#') ? 'disabled' : ''; ?>"
                                            style="background-color: #FFBC3B;">
                                            Detail
                                        </a>
                                    </td>

                                    <td>
                                        <a href="<?php echo $link_scholar; ?>" target="_blank" class="btn btn-sm text-dark fw-bold 
                                        <?php echo ($link_scholar === '#') ? 'disabled' : ''; ?>"
                                            style="background-color: #FFBC3B;">
                                            Detail
                                        </a>
                                    </td>

                                    <td>
                                        <a href="<?php echo $link_sinta; ?>" target="_blank" class="btn btn-sm text-dark fw-bold 
                                        <?php echo ($link_sinta === '#') ? 'disabled' : ''; ?>"
                                            style="background-color: #FFBC3B;">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                }
                            } else {
                                echo '<tr><td colspan="4" class="text-center text-secondary py-3">Data dosen tidak ditemukan di database.</td></tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-2 pb-3 mt-4">
        <div id="blog" class="latest-news-area pt-0 pb-3">

            <div class="section-title-five py-0">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="content mb-0">
                                <h6 style="color: #FFBC3B; border: 2px solid #FFBC3B" class="mb-1">Berita</h6>
                                <h2 class="fw-bold mb-1">Berita terbaru</h2>
                                <p>
                                    Baca berita terbaru dari Laboratorium Visi Cerdas dan Sistem Cerdas.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row mt-n3">

                    <?php
                if ($result_berita_terbaru) {
                    if (isset($result_berita_terbaru)) { pg_result_seek($result_berita_terbaru, 0); }
                    
                    while ($data_berita = pg_fetch_assoc($result_berita_terbaru)) {
                        $berita_id = $data_berita['berita_id']; 
                        $berita_link = "detailBerita.php?id=" . htmlspecialchars($berita_id);
                        $tanggal_format = date('d F Y', strtotime($data_berita['tanggal']));
                        
                        $isi_lengkap = $data_berita['isi']; 
                        
                        if (strlen($isi_lengkap) > $batas_karakter) {
                            $potongan_isi = substr($isi_lengkap, 0, $batas_karakter);
                            $potongan_isi = substr($potongan_isi, 0, strrpos($potongan_isi, ' '));
                            $isi_tampilan = $potongan_isi . '...';
                            $show_button = true;
                        } else {
                            $isi_tampilan = $isi_lengkap;
                            $show_button = false;
                        }
                ?>
                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <div class="single-news">
                            <div class="image">
                                <a href="<?php echo $berita_link; ?>">
                                    <img class="thumb"
                                        src="admin/assets/img/<?php echo htmlspecialchars($data_berita['gambar']); ?>"
                                        alt="<?php echo htmlspecialchars($data_berita['judul']); ?>" />
                                </a>
                            </div>

                            <div class="content-body">

                                <div class="meta-info mb-3 d-flex align-items-center">
                                    <p class="date me-3 mb-0 py-2" style="color: #6c757d; font-size: 0.9rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-calendar me-1" viewBox="0 0 16 16">
                                            <path
                                                d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 1h8a1 1 0 0 1 1 1v1H3V2a1 1 0 0 1 1-1m1 3h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1m-2 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m3-2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m3-2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5" />
                                        </svg>
                                        <?php echo $tanggal_format; ?>
                                    </p>

                                    <p class="author mb-0" style="color: #6c757d; font-size: 0.9rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-pencil me-1" viewBox="0 0 16 16">
                                            <path
                                                d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.105L.82 15.75l-.75-.75L.146 12.146a.5.5 0 0 1 .105-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.793 4.793 9.5 3.5 9.5 3.5 11.207 9.207 5.5z" />
                                        </svg>
                                        penulis: -
                                    </p>
                                </div>
                                <h3 class="title fw-bold" style="font-size: 1.5rem; line-height: 1.2;">
                                    <a href="<?php echo $berita_link; ?>" style="color: #1A1A37;">
                                        <?php echo htmlspecialchars($data_berita['judul']); ?>
                                    </a>
                                </h3>

                                <p style="color: #495057;">
                                    <?php echo htmlspecialchars($isi_tampilan); ?>
                                </p>

                                <?php if ($show_button): ?>
                                <a href="<?php echo $berita_link; ?>" class="more-btn fw-bold"
                                    style="color: #FFBC3B; text-decoration: none;">
                                    Baca Selengkapnya &rarr;
                                </a>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                    <?php
                    }
                } else {
                    echo "<div class='col-12'><p class='text-center'>Tidak ada berita terbaru yang tersedia.</p></div>";
                }
                ?>
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