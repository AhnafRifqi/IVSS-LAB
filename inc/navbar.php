<?php
require_once 'koneksi.php';
$sql = "SELECT * FROM logo";
$result = pg_query($koneksi, $sql);
$logo_data = pg_fetch_assoc($result);

?>
<section class="navbar-area navbar-nine" style="background-color: #FCB53B; padding-top: 5px; padding-bottom: 5px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand" href="index.php">
                        <img src="admin/assets/img/<?php echo $logo_data['logo'] ?>" alt="Logo IVSS" style="max-width: 100px; height: 100px;" /> 
                    </a>
                    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNine" aria-controls="navbarNine" aria-expanded="false"
                        aria-label="Toggle navigation" style="border-color: #1A1A37;">
                        <span class="toggler-icon" style="background-color: #1A1A37;"></span>
                        <span class="toggler-icon" style="background-color: #1A1A37;"></span>
                        <span class="toggler-icon" style="background-color: #1A1A37;"></span>
                    </button>

                    <div class="collapse navbar-collapse sub-menu-bar" id="navbarNine">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0"> 
                            <li class="nav-item">
                                <a href="index.php" style="color: #1A1A37; font-weight: bold;">Beranda</a>
                            </li>
                            <li class="nav-item">
                                <a href="aboutUs.php" style="color: #1A1A37; font-weight: bold;">Tentang Kami</a>
                            </li>
                            <li class="nav-item">
                                <a href="news.php" style="color: #1A1A37; font-weight: bold;">Berita</a>
                            </li>
                            <li class="nav-item">
                                <a href="daftarDosen.php" style="color: #1A1A37; font-weight: bold;">Daftar Dosen</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</section>