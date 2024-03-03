<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("location:login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Komentar</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/style.css">
</head>

<body>
    <div class="page-container">
      
        <div class="card post-container">
            <div class="card-body">
                <?php
                include "koneksi.php";
                $fotoid = $_GET['fotoid'];
                $sql = mysqli_query($conn, "SELECT foto.*, user.username FROM foto INNER JOIN user ON foto.userid=user.userid WHERE foto.fotoid='$fotoid'");
                while ($data = mysqli_fetch_array($sql)) {
                ?>
               
                <div class="post-image">
                    <h2 class="post-title"><?= $data['judulfoto'] ?></h2>
                    <img src="gambar/<?= $data['lokasifile'] ?>" class="img-fluid" alt="<?= $data['judulfoto'] ?>">
                </div>
                <p class="post-description"><?= $data['deskripsifoto'] ?></p>
                <p>Tanggal Unggah: <?= $data['tanggalunggah'] ?> Diunggah oleh: <?= $data['username'] ?></p>
                <?php
                }
                ?>
            </div>
        </div>

       
        <div class="card mb-3">
            <div class="card-body">
                <form action="tambah_komentar.php" method="post">
                    <input type="text" name="fotoid" value="<?= $_GET['fotoid'] ?>" hidden>
                    <div class="form-group mb-3">
                        <textarea class="form-control" name="isikomentar" rows="3"
                            placeholder="Tambah komentar" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Komentar</button>
                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
     
        <div class="card">
            <div class="card-body">
                <?php
                $fotoid = $_GET['fotoid'];
                $sql = mysqli_query($conn, "SELECT komentarfoto.*, user.namalengkap FROM komentarfoto INNER JOIN user ON komentarfoto.userid=user.userid WHERE komentarfoto.fotoid='$fotoid'");
                while ($row = mysqli_fetch_array($sql)) {
                ?>
                <div class="comment-item">
                    
                    <h5><?= $row['namalengkap'] ?></h5>
                    <p><?= $row['isikomentar'] ?></p>
                    <p class="text-muted"><?= $row['tanggalkomentar'] ?></p>
                    <?php
                        if ($row['userid'] == $_SESSION['userid']) {
                            echo "<a href='hapus_komentar.php?id=" . $row['komentarid'] . "&fotoid=" . $row['fotoid'] . "
                            'class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus komentar?\")'>Hapus</a>";
                        }
                    ?>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>
