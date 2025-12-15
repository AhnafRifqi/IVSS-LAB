<?php
require_once 'inc/koneksi.php';

// Ambil logo laboratorium
$sql = "SELECT * FROM logo LIMIT 1";
$result = pg_query($koneksi, $sql);
$logo_data = pg_fetch_assoc($result);

// --- MODIFIKASI QUERY DOSEN ---
$sql_dosen = "
    SELECT 
        d.nip, 
        d.nama,
        u.role
    FROM 
        dosen d
    JOIN 
        users u ON d.user_id = u.user_id
    WHERE 
        u.role <> 'berita'
    ORDER BY 
        d.nama ASC
";
$result_dosen = pg_query($koneksi, $sql_dosen);

// Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $namaLengkap      = trim($_POST['nama']);
    $nim              = trim($_POST['nim']);
    $jenisKelamin     = trim($_POST['jenis_kelamin']);
    $noTelp           = trim($_POST['no_telp']);
    $email            = trim($_POST['email']);
    $keperluan        = trim($_POST['keperluan']);
    $dosenPembimbing  = trim($_POST['dosen_pembimbing']);
    
    // Default status, menggunakan ENUM yang paling awal:
    $status_mahasiswa = null;
    $status_pendaftaran = 'pending';

    // Validasi sederhana
    if ($namaLengkap && $nim && $jenisKelamin && $noTelp && $email && $keperluan && $dosenPembimbing) {
        
        // --- BYPASS STATUS ---
        // 1. Ambil user_id dosen yang dipilih
        $q_dosen_user = pg_query_params($koneksi, "SELECT user_id FROM dosen WHERE nip = $1", [$dosenPembimbing]);
        $dosen_user = pg_fetch_assoc($q_dosen_user);
        $dosen_user_id = $dosen_user['user_id'] ?? null;
        $dosen_role = null;

        if ($dosen_user_id) {
            // 2. Ambil role dosen dari tabel users
            $q_role = pg_query_params($koneksi, "SELECT role FROM users WHERE user_id = $1", [$dosen_user_id]);
            $row_role = pg_fetch_assoc($q_role);
            $dosen_role = $row_role['role'] ?? null;
        }

        // HANYA role 'ketua' yang mendapat bypass
        if ($dosen_role === 'ketua') {
            // Status langsung Disetujui Dosen Pembimbing
            $status_pendaftaran = 'acc_dosen';
        } 
        // Jika role lain (misalnya 'admin'), status tetap 'pending'
        // --- END BYPASS STATUS ---

        $sql_insert = "INSERT INTO mahasiswa
            (nama, nim, jenis_kelamin, no_telp, email, keperluan, dosen_pembimbing, tanggal_daftar, status_pendaftaran)
            VALUES ($1, $2, $3, $4, $5, $6, $7, CURRENT_DATE, $8)";
        $result_insert = pg_query_params($koneksi, $sql_insert, [
            $namaLengkap,
            $nim,
            $jenisKelamin,
            $noTelp,
            $email,
            $keperluan,
            $dosenPembimbing,
            $status_pendaftaran // Status yang sudah disesuaikan
        ]);

        if ($result_insert) {
            echo "<script>alert('Data berhasil disimpan! Status pendaftaran Anda saat ini: " . htmlspecialchars($status_pendaftaran) . "'); window.location='index.php';</script>";
            exit;
        } else {
            $error_msg = pg_last_error($koneksi);
            echo "<script>alert('Terjadi kesalahan saat menyimpan data. Error: " . htmlspecialchars($error_msg) . "');</script>";
        }
    } else {
        echo "<script>alert('Semua field wajib diisi!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <title>Laboratorium Visi Cerdas dan Sistem Cerdas</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link href="img/favicon.ico" rel="icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet">

    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">
</head>

<body>

<?php include('inc/navbar.php')?>

<section class="py-5" style="background-color: #f8f9fa; min-height: 100vh; margin-top: 50px;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 p-4 p-md-5" style="border-radius: 20px;">

                    <div class="text-center mb-5">
                        <h2 class="fw-bold text-dark mb-3" style="font-size: 1.8rem;">
                            Registrasi Akun Mahasiswa & Member Laboratorium
                        </h2>
                        <div class="mx-auto" style="width: 900px; height: 4px; background-color: #FFBC3B; margin: 15px auto 0;"></div>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-7">
                            <form action="#" method="POST">
                                <div class="mb-4">
                                    <label for="namaLengkap" class="form-label fw-bold text-dark">Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control form-control-lg border-0 shadow-sm" id="namaLengkap" 
                                            placeholder="Masukkan Nama Lengkap" required
                                            style="background-color: #e9ecef;  font-size: 0.9rem;">
                                </div>

                                <div class="mb-4">
                                    <label for="nim" class="form-label fw-bold text-dark">NIM</label>
                                    <input type="text" name="nim" class="form-control form-control-lg border-0 shadow-sm" id="nim" 
                                            placeholder="Masukkan NIM" required
                                            style="background-color: #e9ecef;  font-size: 0.9rem;">
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label for="jenisKelamin" class="form-label fw-bold text-dark">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="form-select form-control-lg border-0 shadow-sm" id="jenisKelamin" required
                                                style="background-color: #e9ecef;  font-size: 0.9rem;">
                                            <option selected disabled>Select</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="email" class="form-label fw-bold text-dark">Email</label>
                                        <input type="email" name="email" class="form-control form-control-lg border-0 shadow-sm" id="email" 
                                                placeholder="Masukkan Email" required
                                                style="background-color: #e9ecef;  font-size: 0.9rem;">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="noTelp" class="form-label fw-bold text-dark">No. Telp</label>
                                    <input type="tel" name="no_telp" class="form-control form-control-lg border-0 shadow-sm" id="noTelp" 
                                            placeholder="Masukkan No. Telp" required
                                            style="background-color: #e9ecef;  font-size: 0.9rem;">
                                </div>

                                <div class="mb-4">
                                    <label for="keperluan" class="form-label fw-bold text-dark">Keperluan yang Ingin Diajukan</label>
                                    <textarea name="keperluan" class="form-control border-0 shadow-sm" id="keperluan" rows="5" 
                                            placeholder="Deskripsi keperluan" required
                                            style="background-color: #e9ecef;  font-size: 0.9rem;"></textarea>
                                </div>

                                <div class="mb-5">
                                    <label for="dosenPembimbing" class="form-label fw-bold text-dark">Dosen Pembimbing</label>
                                    <select name="dosen_pembimbing" class="form-select form-control-lg border-0 shadow-sm" id="dosenPembimbing" required
                                            style="background-color: #e9ecef; font-size: 0.9rem;">
                                        <option selected disabled value="">Pilih Dosen Pembimbing</option>
                                        <?php
                                            pg_result_seek($result_dosen, 0); 
                                            if ($result_dosen && pg_num_rows($result_dosen) > 0) {
                                                while ($dosen = pg_fetch_assoc($result_dosen)) {
                                                    $nip = htmlspecialchars($dosen['nip']);
                                                    $nama_dosen = htmlspecialchars($dosen['nama']);
                                                    $keterangan_role = ($dosen['role'] === 'ketua') ? ' (Ketua)' : ''; 
                                                    echo "<option value=\"$nip\">$nama_dosen ($nip)$keterangan_role</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class="card shadow-lg border-0 p-4 mt-5" style="border-radius: 20px;">
                                    <div class="p-3">
                                        <div class="d-flex align-items-start mb-3">
                                            <span class="icon-warning me-3" style="font-size: 1.3rem; color: #FFBC3B;">
                                                <i class="lni lni-warning"></i>
                                            </span>
                                            <h5 class="fw-bold text-dark m-0">Proses Persetujuan Keanggotaan:</h5>
                                        </div>
                                        
                                        <ol style="padding-left: 20px;">
                                            <li class="mb-2">1. Pendaftaran Anda akan direview oleh <b>Dosen Pembimbing</b> yang telah Anda pilih.
                                                <br><small class="text-muted fw-bold">Jika Dospem adalah Ketua, tahap ini dianggap selesai (Status: Disetujui Dospem).</small>
                                            </li>
                                            <li class="mb-2">2. Setelah disetujui oleh Dosen Pembimbing, pengajuan akan dilanjutkan untuk direview oleh <b>Ketua Laboratorium</b> </li>
                                            <li>3. Setelah mendapatkan persetujuan dari kedua pihak, akun Anda akan diaktifkan sehingga Anda dapat melakukan <b>login</b>.</li>
                                        </ol>

                                    </div>
                                </div>
                                
                                <p class="mt-4 mb-3">
                                Sudah Registrasi? Silahkan cek status registrasi anda
                                <a href="cekRegistrasi.php">disini</a>.
                                </p>
                                
                                <div class="d-flex justify-content-start gap-3">
                                    <button type="button" class="btn btn-sm fw-bold shadow"
                                            style="background-color: #1A1A37; border-radius: 10px; color:white"
                                            onclick="window.location.href='index.php';">
                                        Kembali
                                    </button>
                                    <button type="submit" class="btn btn-sm fw-bold shadow"
                                            style="background-color: #1A1A37; border-radius: 10px; color:white">
                                        Kirim
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-5 d-flex flex-column align-items-center justify-content-center text-center">
                            <img src="admin/assets/img/<?php echo $logo_data['logo'] ?>"
                                alt="Logo IVSS" class="img-fluid mb-3" style="max-width: 400px;">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('inc/footer.php')?>
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

</body>
</html>