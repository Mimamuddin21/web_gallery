<?php
include "koneksi.php";
session_start();
$userid = $_SESSION['userid'];
if (!isset($_SESSION['userid'])) {
    header("location:login.php");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>web gallery foto</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <style>
        .navbar-brand {
          color: #ffffff;
   
         }

        .navbar {
        background-color: blue;
        color: #ffffff !important;
        }

       .navbar-nav .nav-link {
       color: #ffffff !important;
       }

       .navbar-nav .nav-link:hover {
        color: #f8f9fa !important;
       }

    </style>
  
</head>

<body>
   <nav class="navbar navbar-expand-lg bg-body-primary">
    <div class="container d-flex justify-content-between">
        <a class="navbar-brand" href="index.php">Website Gallery Foto</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ms-auto">
                <?php
                if (!isset($_SESSION['userid'])) {
                ?>
                <a href="register.php" class="btn btn-outline-primary m-1">Register</a>
                <a href="login.php" class="btn btn-outline-primary m-1">Login</a>
                <?php
                } else {
                ?>
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
                <?php
                }
                ?>
            </div>
        </div>
    </div>
   </nav>

   <div class="container mt-3">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Tambah Data
    </button>
    <div class="card mt-2 mb-5">
        <div class="card-header text-bg-primary">Data Gambar</div>
        <div class="card-body">
            <div class="table-responsive">
            <table id="example" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Tanggal Unggah</th>
                            <th>Gambar</th>
                            <th>Album</th>
                            <th>Disukai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include "koneksi.php";
                        $userid = $_SESSION['userid'];
                        $no = 1;
                        $sql = mysqli_query($conn, "select * from foto,album where foto.userid='$userid' and foto.albumid=album.albumid ORDER BY foto.fotoid DESC");
                        while ($data = mysqli_fetch_array($sql)) {
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $data['judulfoto'] ?></td>
                            <td><?= $data['deskripsifoto'] ?></td>
                            <td><?= $data['tanggalunggah'] ?></td>
                            <td>
                                <img src="gambar/<?= $data['lokasifile'] ?>" width="200px">
                            </td>
                            <td><?= $data['namaalbum'] ?></td>
                            <td>
                                <?php
                                    $fotoid = $data['fotoid'];
                                    $sql2 = mysqli_query($conn, "select * from likefoto where fotoid='$fotoid'");
                                    echo mysqli_num_rows($sql2);
                                ?>
                            </td>
                            <td>
                                <a href="hapus_foto.php?fotoid=<?= $data['fotoid'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data?')">Hapus</a>
                                <a href="#" class="btn btn-warning" data-bs-toggle="modal"data-bs-target="#edit<?= $data['fotoid'] ?>">Edit</a>
                            </td>
                        </tr>
                       
                        <div class="modal fade" id="edit<?= $data['fotoid'] ?>" data-bs-backdrop="static" data-bs-keyboard="false"
                            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Edit Foto</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="update_foto.php" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="fotoid" value="<?= $data['fotoid'] ?>">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="judul" class="form-label">Judul Foto</label>
                                                <input type="text" class="form-control" id="judul" name="judulfoto"
                                                    value="<?= $data['judulfoto'] ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                                <input type="text" class="form-control" id="deskripsi"
                                                    name="deskripsifoto" value="<?= $data['deskripsifoto'] ?>">
                                            </div>
                                            <?php if (!empty($data['lokasifile'])) : ?>
                                            <div class="mb-3">
                                                <label class="form-label">Gambar Sebelumnya</label>
                                                <div class="d-flex align-items-start">

                                                    <img src="gambar/<?= $data['lokasifile'] ?>" class="img-fluid ms-3"
                                                        style="max-width: 100px; max-height: 150px;">
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            <div class="mb-3">
                                                <label for="gambar" class="form-label">Gambar Baru</label>
                                                <input type="file" class="form-control" id="gambar" name="lokasifile">
                                            </div>

                                            <div class="mb-3">
                                                <label for="album" class="form-label">Album</label>
                                                <select class="form-select" id="album" name="albumid">
                                                    <?php
                                                        $userid = $_SESSION['userid'];
                                                        $sql2 = mysqli_query($conn, "select * from album where userid='$userid'");
                                                        while ($data2 = mysqli_fetch_array($sql2)) {
                                                        ?>
                                                    <option value="<?= $data2['albumid'] ?>"
                                                        <?= $data2['albumid'] == $data['albumid'] ? 'selected' : '' ?>>
                                                        <?= $data2['namaalbum'] ?>
                                                    </option>
                                                    <?php
                                                        }
                                                        ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Ubah</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

   
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah album</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="tambah_foto.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <label class="form-label">Judul foto</label>
                        <input type="text" name="judulfoto" class="form-control" required>
                        <label class="form-label">deskripsi foto</label>
                        <input type="text" name="deskripsifoto" class="form-control" required>
                        <label class="form-label">Gambar</label>
                        <input type="file" name="lokasifile" class="form-control" required>
                        <label class="form-label">Album</label>
                        <select name="albumid" class="form-select">
                            <?php
                            include "koneksi.php";
                            $userid = $_SESSION['userid'];
                            $sql = mysqli_query($conn, "select * from album where userid='$userid'");
                            while ($data = mysqli_fetch_array($sql)) {
                            ?>
                            <option value="<?= $data['albumid'] ?>"><?= $data['namaalbum'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <footer class="d-flex justify-content-center border-top mt-3 bg-light fixed-bottom">
        <p>UKK 2024</p>
    </footer>

    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
    new DataTable('#example');
    </script>
</body>

</html>

