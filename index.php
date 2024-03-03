<?php
session_start();
include "koneksi.php";
$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>web gallery foto</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/style.css">

</head>

<body>
   <nav class="navbar navbar-expand-lg bg-body-primary">
    <div class="container d-flex justify-content-between">
        <a class="navbar-brand" href="index.php">Website Gallery Foto</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (!isset($_SESSION['userid'])) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="album.php">Album</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="foto.php">Foto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
   </nav>

   <div class="container mt-3">
        <form action="index.php" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari foto..." name="keyword">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </form>
        <h5 class="mt-5">Semua Foto</h5>
        <div class="row">
            <?php
            $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
            $sql = mysqli_query($conn, "SELECT * FROM foto INNER JOIN user ON foto.userid=user.userid INNER JOIN album ON foto.albumid=album.albumid WHERE judulfoto LIKE '%$keyword%' OR deskripsifoto LIKE '%$keyword%' ORDER BY fotoid DESC ");
            while ($data = mysqli_fetch_array($sql)) {
            ?>
            <div class="col-md-3">
                <div class="card mb-5 mt-2">
                    <img src="gambar/<?= $data['lokasifile'] ?>" class="card-img-top" style="height: 16rem;" alt="">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $data['judulfoto']; ?></h5>
                        <p class="post-description"><?= $data['deskripsifoto'] ?></p>
                        <p>Tanggal Unggah: <?= $data['tanggalunggah'] ?> Diunggah oleh: <?= $data['username'] ?></p>
                    </div>
                    
                    <div class="card-footer text-center">
                        <?php
                            $fotoid = $data['fotoid'];
                            $ceksuka = mysqli_query($conn, "SELECT * FROM likefoto WHERE fotoid='$fotoid' AND userid='$userid'");
                            if (mysqli_num_rows($ceksuka) == 1) { ?>
                        <a href="like.php?fotoid=<?= $data['fotoid'] ?>" name="batalsuka"><i
                                class="fa fa-heart"></i></a>
                        <?php } else { ?>
                        <a href="like.php?fotoid=<?= $data['fotoid'] ?>" name="suka"><i
                                class="fa-regular fa-heart"></i></a>
                        <?php }
                            
                            $like = mysqli_query($conn, "SELECT * FROM likefoto WHERE fotoid='$fotoid'");
                            echo mysqli_num_rows($like) . ' Suka';
                            ?>
                        <a href="komentar.php?fotoid=<?php echo $data['fotoid'] ?>"><i
                                class="fa-regular fa-comment"></i></a>
                        <?php
                            $jmlkomentar = mysqli_query($conn, "SELECT * FROM komentarfoto WHERE fotoid='$fotoid'");
                            echo mysqli_num_rows($jmlkomentar) . ' Komentar';
                            ?>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>


    <footer class="d-flex justify-content-center border-top mt-3 bg-light fixed-bottom">
        <p>UKK 2024</p>
    </footer>

    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>