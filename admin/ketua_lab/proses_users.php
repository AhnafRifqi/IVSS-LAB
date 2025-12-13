<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php'); 
    exit;
}

$action = $_GET['action'] ?? '';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$allowed_roles = ['ketua', 'admin', 'berita', 'mahasiswa'];

switch ($action) {

    case 'tambah':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $entity_type = trim($_POST['entity_type'] ?? '');
            $entity_id   = trim($_POST['entity_id'] ?? '');
            $username    = trim($_POST['username'] ?? '');
            $password    = trim($_POST['password'] ?? '');
            $role        = trim($_POST['role'] ?? '');

            if ($username === '' || $password === '' || $role === '') {
                header('Location: users.php?msg=error_empty');
                exit;
            }

            if (!in_array($role, $allowed_roles, true)) {
                header('Location: users.php?msg=error_role');
                exit;
            }

            // insert user
            $res = pg_query_params($koneksi, 'INSERT INTO users(username,password,role) VALUES($1,$2,$3) RETURNING user_id', [$username, $password, $role]);

            if ($res && pg_num_rows($res) === 1) {
                $new_user = pg_fetch_assoc($res);
                $new_id = (int)$new_user['user_id'];
                pg_free_result($res);

                // kaitkan ke entity
                if ($entity_type === 'dosen' && $entity_id !== '') {
                    pg_query_params($koneksi, 'UPDATE dosen SET user_id=$1 WHERE nip=$2', [$new_id, $entity_id]);
                } elseif ($entity_type === 'mahasiswa' && $entity_id !== '') {
                    pg_query_params($koneksi, 'UPDATE mahasiswa SET user_id=$1 WHERE nim=$2', [$new_id, $entity_id]);
                }

                header('Location: users.php?msg=created');
                exit;
            } else {
                header('Location: users.php?msg=error');
                exit;
            }
        }
        break;

    case 'hapus':
        if ($id <= 0) { header('Location: users.php?msg=error'); exit; }

        // hapus reference entity dulu
        pg_query_params($koneksi, 'UPDATE dosen SET user_id=NULL WHERE user_id=$1', [$id]);
        pg_query_params($koneksi, 'UPDATE mahasiswa SET user_id=NULL WHERE user_id=$1', [$id]);

        // hapus user
        $res = pg_query_params($koneksi, 'DELETE FROM users WHERE user_id=$1', [$id]);
        if ($res && pg_affected_rows($res) > 0) {
            pg_free_result($res);
            header('Location: users.php?msg=deleted');
            exit;
        } else {
            header('Location: users.php?msg=error');
            exit;
        }
        break;

    default:
        header('Location: users.php');
        exit;
}
