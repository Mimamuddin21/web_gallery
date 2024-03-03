<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['userid']) || !isset($_GET['id']) || empty($_GET['id'])) {
    header("location:index.php");
    exit();
}

$komentar_id = $_GET['id'];
$fotoid = isset($_GET['fotoid']) ? $_GET['fotoid'] : '';

$user_id = $_SESSION['userid'];
$sql_delete_comment = "DELETE FROM komentarfoto WHERE komentarid='$komentar_id' AND userid='$user_id'";
$result_delete_comment = mysqli_query($conn, $sql_delete_comment);

if ($result_delete_comment) {
    header("location:komentar.php?fotoid=$fotoid");
} else {
    echo "Gagal menghapus komentar.";
}
?>
