<?php
include 'inc/koneksi.php';

/* ===============================
   TENTUKAN MODE
=============================== */
$mode = isset($_GET['id']) ? 'detail' : 'list';

switch ($mode) {

/* =====================================================
   MODE: LIST BERITA
===================================================== */
case 'list':

    $search_query     = "";
    $where_clause     = "";
    $batas_karakter   = 500;
    $limit            = 7;

    /* ===============================
       1. LOGIKA PENCARIAN
    =============================== */
    if (!empty($_GET['keyword'])) {
        $search_query = pg_escape_string($koneksi, $_GET['keyword']);
        $where_clause = "
            WHERE judul ILIKE '%{$search_query}%'
               OR tipe::TEXT ILIKE '%{$search_query}%'
        ";
    }

    /* ===============================
       2. HITUNG TOTAL DATA
    =============================== */
    $sql_count   = "SELECT COUNT(*) AS total FROM berita {$where_clause}";
    $result_cnt = pg_query($koneksi, $sql_count);
    $row_cnt    = pg_fetch_assoc($result_cnt);
    $total_items = (int)$row_cnt['total'];

    /* ===============================
       3. PAGINATION
    =============================== */
    $total_pages = ($total_items > 0) ? ceil($total_items / $limit) : 1;

    $current_page = (isset($_GET['page']) && is_numeric($_GET['page']))
        ? (int)$_GET['page']
        : 1;

    if ($current_page < 1) $current_page = 1;
    if ($current_page > $total_pages) $current_page = $total_pages;

    $offset = ($current_page - 1) * $limit;

    /* ===============================
       4. FEATURED (BUKAN PELATIHAN)
    =============================== */
    $featured = null;

    if ($current_page == 1) {
        $sql_featured = "
            SELECT *
            FROM berita
            WHERE tipe != 'pelatihan'
            ORDER BY tanggal DESC
            LIMIT 1
        ";

        $q_featured = pg_query($koneksi, $sql_featured);

        if ($q_featured && pg_num_rows($q_featured) > 0) {
            $featured = pg_fetch_assoc($q_featured);

            // format tanggal
            $featured['tanggal_formatted'] =
                date('d M Y', strtotime($featured['tanggal']));

            // potong isi
            $isi_clean = strip_tags($featured['isi']);
            if (strlen($isi_clean) > $batas_karakter) {
                $potong = substr($isi_clean, 0, $batas_karakter);
                $featured['isi_tampilan'] =
                    substr($potong, 0, strrpos($potong, ' ')) . '...';
            } else {
                $featured['isi_tampilan'] = $isi_clean;
            }
        }
    }

    /* ===============================
       5. LIST BERITA
    =============================== */
    $sql_data = "
        SELECT *
        FROM berita
        {$where_clause}
        ORDER BY tanggal DESC
        LIMIT {$limit} OFFSET {$offset}
    ";

    $result_data = pg_query($koneksi, $sql_data);
    $other_items = [];

    if ($result_data) {
        while ($row = pg_fetch_assoc($result_data)) {

            // skip featured supaya tidak dobel
            if ($featured && $row['berita_id'] == $featured['berita_id']) {
                continue;
            }

            $row['tanggal_formatted'] =
                date('d M Y', strtotime($row['tanggal']));

            $other_items[] = $row;
        }
    }

    break;

/* =====================================================
   MODE: DETAIL BERITA
===================================================== */
case 'detail':

    if (!isset($_GET['id'])) {
        echo "<h3>Berita tidak ditemukan.</h3>";
        exit;
    }

    $id = (int)$_GET['id'];

    /* ===============================
       1. DETAIL BERITA
    =============================== */
    $sql = "
        SELECT *,
        TO_CHAR(tanggal, 'DD Month YYYY') AS tanggal_format
        FROM berita
        WHERE berita_id = $id
    ";

    $result = pg_query($koneksi, $sql);
    $berita = pg_fetch_assoc($result);

    if (!$berita) {
        echo "<h3>Berita tidak ditemukan.</h3>";
        exit;
    }

    /* ===============================
       2. SIDEBAR BERITA TERBARU
    =============================== */
    $latestBerita = [];

    $q = pg_query($koneksi, "
        SELECT berita_id, judul, gambar,
        TO_CHAR(tanggal, 'DD Mon YYYY') AS tanggal_format
        FROM berita
        WHERE berita_id != $id
        ORDER BY tanggal DESC
        LIMIT 5
    ");

    while ($row = pg_fetch_assoc($q)) {
        $latestBerita[] = $row;
    }

    break;
}
?>
