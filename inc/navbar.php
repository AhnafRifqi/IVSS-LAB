<?php
require_once 'koneksi.php';
$sql = "SELECT * FROM logo";
$result = pg_query($koneksi, $sql);
$logo_data = pg_fetch_assoc($result);

// Mendefinisikan warna kustom untuk konsistensi
$primary_color = "#1A1A37"; // Biru Tua/Navy
$secondary_color = "#FCB53B"; // Kuning/Warning
?>

<section class="navbar-area navbar-nine shadow-sm fixed-top" style="background-color: <?php echo $secondary_color; ?>; padding-top: 5px; padding-bottom: 5px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand me-auto" href="index.php">
                        <img src="admin/assets/img/<?php echo $logo_data['logo'] ?>" 
                             alt="Logo IVSS" 
                             style="max-width: 100px; height: 100px; object-fit: contain;" /> 
                    </a>
                    
                    <button class="navbar-toggler p-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNine" aria-controls="navbarNine" aria-expanded="false"
                        aria-label="Toggle navigation" style="border: 1px solid <?php echo $primary_color; ?>; border-radius: 5px;">
                        
                        <span class="toggler-icon" style="background-color: <?php echo $primary_color; ?>;"></span>
                        <span class="toggler-icon" style="background-color: <?php echo $primary_color; ?>;"></span>
                        <span class="toggler-icon" style="background-color: <?php echo $primary_color; ?>;"></span>
                    </button>

                    <div class="collapse navbar-collapse sub-menu-bar" id="navbarNine">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0"> 
                            
                            <li class="nav-item">
                                <a href="index.php" class="nav-link fw-bold p-3" 
                                   style="color: <?php echo $primary_color; ?>; transition: color 0.3s; font-size: 1.05rem;"
                                   onmouseover="this.style.color='white'"
                                   onmouseout="this.style.color='<?php echo $primary_color; ?>'">Beranda</a>
                            </li>
                            <li class="nav-item">
                                <a href="news.php" class="nav-link fw-bold p-3" 
                                   style="color: <?php echo $primary_color; ?>; transition: color 0.3s; font-size: 1.05rem;"
                                   onmouseover="this.style.color='white'"
                                   onmouseout="this.style.color='<?php echo $primary_color; ?>'">Berita</a>
                            </li>
                            <li class="nav-item">
                                <a href="daftarDosen.php" class="nav-link fw-bold p-3" 
                                   style="color: <?php echo $primary_color; ?>; transition: color 0.3s; font-size: 1.05rem;"
                                   onmouseover="this.style.color='white'"
                                   onmouseout="this.style.color='<?php echo $primary_color; ?>'">Daftar Dosen</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</section>