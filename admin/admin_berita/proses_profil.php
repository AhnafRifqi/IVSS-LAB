<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'berita') {
    header('Location: ../index.php');
    exit;
}

$user_id = (int) $_SESSION['user_id'];

function hashPassword($password, $salt) {
    return hash('sha256', $salt . $password);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ubah_password.php');
    exit;
}

$old_password     = trim($_POST['old_password'] ?? '');
$new_password     = trim($_POST['new_password'] ?? '');
$confirm_password = trim($_POST['confirm_password'] ?? '');

if ($new_password !== $confirm_password) {
    header('Location: ubah_password.php?error=confirm');
    exit;
}

if (strlen($new_password) < 6) {
    header('Location: ubah_password.php?error=length');
    exit;
}

// ambil password lama
$q = pg_query_params(
    $koneksi,
    'SELECT password, salt FROM users WHERE user_id = $1',
    [$user_id]
);

if (!$q || pg_num_rows($q) !== 1) {
    header('Location: ubah_password.php?error=user');
    exit;
}

$row = pg_fetch_assoc($q);
pg_free_result($q);

$stored_password = $row['password'];
$salt            = $row['salt'] ?? '';

$valid_old = false;

if ($salt) {
    if (hashPassword($old_password, $salt) === $stored_password) {
        $valid_old = true;
    }
} else {
    if (hash_equals($stored_password, $old_password)) {
        $valid_old = true;
    }
}

if (!$valid_old) {
    header('Location: ubah_password.php?error=old');
    exit;
}

// generate password baru
$new_salt = bin2hex(random_bytes(8));
$new_hash = hashPassword($new_password, $new_salt);

$update = pg_query_params(
    $koneksi,
    'UPDATE users SET password=$1, salt=$2 WHERE user_id=$3',
    [$new_hash, $new_salt, $user_id]
);

if ($update) {
    header('Location: ubah_password.php?success=1');
} else {
    header('Location: ubah_password.php?error=update');
}
exit;
