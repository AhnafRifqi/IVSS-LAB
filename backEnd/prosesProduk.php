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