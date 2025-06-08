<?php
session_start();
require "../db.php";
$page = "siswa";

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
}

$target_dir = "../uploads/";

// Prosedur Hapus Data
if (isset($_POST["delete"])) {
    $nis = mysqli_real_escape_string($kon, $_POST["nis"]);
    mysqli_query($kon, "DELETE FROM siswa WHERE nis = '$nis'");
    echo '<script>alert("DATA BERHASIL DIHAPUS !"); window.location = "";</script>';
}

if (isset($_POST["submit"])) {
    $nisn = mt_rand(1000000000, 9999999999);
    $nis = mysqli_real_escape_string($kon, $_POST["nis"]);
    $user = mysqli_real_escape_string($kon, $_POST["user"]);
    $pwd = mysqli_real_escape_string($kon, $_POST["pwd"]);
    $nama = mysqli_real_escape_string($kon, $_POST["nama"]);
    $nama_ortu = mysqli_real_escape_string($kon, $_POST["nama_ortu"]); // Add this line
    $id_kelas = $_POST["id_kelas"];
    $alamat = mysqli_real_escape_string($kon, $_POST["alamat"]);
    $no_telp = mysqli_real_escape_string($kon, $_POST["no_telp"]);
    $id_spp = $_POST["id_spp"];
    
    // Handling file upload
    $foto = "";
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0) {
        $foto = basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . $foto;
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
        $foto = "../uploads/" . $foto; // Simpan path relatif ke foto
    }

    if (empty($nis) or empty($user) or empty($pwd) or empty($nama) or empty($id_kelas) or empty($alamat) or empty($no_telp) or empty($foto) or empty($nama_ortu)) {
        echo '<script>alert("MAAF, DATA TERSEBUT MASIH KOSONG. SILAHKAN DI-ISI TERLEBIH DAHULU !"); window.location = "";</script>';
    } else {
        $kue = mysqli_query($kon, "SELECT * FROM siswa WHERE username = '$user' AND password = '$pwd'");
        $cek = mysqli_fetch_array($kue);

        if ($cek > 0) {
            echo '<script>alert("MAAF, DATA TERSEBUT SUDAH ADA. SILAHKAN ISI YANG LAIN !"); window.location = "";</script>';
        } else {
            mysqli_query($kon, "INSERT INTO siswa (nisn, nis, username, password, nama, nama_ortu, id_kelas, alamat, no_telp, id_spp, foto) VALUES ('$nisn', '$nis', '$user', '$pwd', '$nama', '$nama_ortu', '$id_kelas', '$alamat', '$no_telp', '$id_spp', '$foto')");
            echo '<script>alert("DATA BERHASIL DISIMPAN !"); window.location = "";</script>';
        }
    }
}


// Prosedur Update Data
if (isset($_POST["update"])) {
    $nis = mysqli_real_escape_string($kon, $_POST["nis"]);
    $user = mysqli_real_escape_string($kon, $_POST["user"]);
    $pwd = mysqli_real_escape_string($kon, $_POST["pwd"]);
    $nama = mysqli_real_escape_string($kon, $_POST["nama"]);
    $nama_ortu = mysqli_real_escape_string($kon, $_POST["nama_ortu"]); // Add this line
    $id_kelas = $_POST["id_kelas"];
    $alamat = mysqli_real_escape_string($kon, $_POST["alamat"]);
    $no_telp = mysqli_real_escape_string($kon, $_POST["no_telp"]);
    $id_spp = $_POST["id_spp"];
    
    // Handling file upload
    $foto = "";
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0) {
        $foto = basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . $foto;
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
        $foto = "../uploads/" . $foto; // Simpan path relatif ke foto
    } else {
        // Jika tidak ada file foto yang diupload, gunakan foto yang ada sebelumnya
        $foto = mysqli_real_escape_string($kon, $_POST["foto_lama"]);
    }

    if (empty($user) or empty($pwd) or empty($nama) or empty($id_kelas) or empty($alamat) or empty($no_telp) or empty($nama_ortu)) {
        echo '<script>alert("MAAF, DATA TERSEBUT MASIH KOSONG. SILAHKAN DI-ISI TERLEBIH DAHULU !"); window.location = "";</script>';
    } else {
        $update_query = "UPDATE siswa SET username = '$user', password = '$pwd', nama = '$nama', nama_ortu = '$nama_ortu', id_kelas = '$id_kelas', alamat = '$alamat', no_telp = '$no_telp', id_spp = '$id_spp', foto = '$foto' WHERE nis = '$nis'";
        
        mysqli_query($kon, $update_query);
        echo '<script>alert("DATA BERHASIL DI-UPDATE !"); window.location = "";</script>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>DATA SISWA</title>
    <meta name="robots" content="noindex, nofollow" />
    <meta content="" name="description" />
    <meta content="" name="keywords" />

    <?php include 'aset.php'; ?>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
</head>
<body>
    
    <!-- HEADER -->
    <?php require "atas.php"; ?>

    <!-- SIDEBAR -->
    <?php require "menu.php"; ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1><i class="bi bi-person"></i>&nbsp; SISWA</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">DASHBOARD</a></li>
                    <li class="breadcrumb-item active">SISWA</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <br>
                            
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahData"><i class="bi bi-plus"></i>&nbsp; TAMBAH SISWA</button>

                            <br><br>
                            
                            <table class="table datatable">
                                <tr>
                                    <th><center>NISN</center></th>
                                    <th><center>NIS</center></th>
                                    <th><center>USERNAME</center></th>
                                    <th><center>NAMA LENGKAP</center></th>
                                    <th><center>ORANG TUA</center></th>
                                    <th><center>KELAS</center></th>
                                    <th><center>ALAMAT</center></th>
                                    <th><center>NO. TELP</center></th>
                                    <th><center>NOMINAL SPP</center></th>
                                    <th><center>AKSI</center></th>
                                </tr>
                                
                                <?php
                                $sql = mysqli_query($kon, "SELECT * FROM siswa ORDER BY nis DESC");

                                while ($gb = mysqli_fetch_array($sql)) {
                                    $spp = mysqli_query($kon, "SELECT * FROM spp WHERE id_spp = " . $gb["id_spp"]);
                                    $sppnye = mysqli_fetch_array($spp);
                                    $nominal_spp = ($sppnye && isset($sppnye["nominal"])) ? $sppnye["nominal"] : 0;
                                ?>
                                <tr>
                                    <td><center><?= $gb["nisn"] ?></center></td>
                                    <td><center><?= $gb["nis"] ?></center></td>
                                    <td><center><?= $gb["username"] ?></center></td>
                                    <td><center><?= $gb["nama"] ?></center></td>
                                    <td><center><?= $gb["nama_ortu"] ?></center></td>
                                    <td>
                                        <center>
                                            <?php 
                                                $kelas = mysqli_query($kon, "SELECT * FROM kelas WHERE id_kelas = ". $gb["id_kelas"]);
                                                $kelasnya = mysqli_fetch_array($kelas);
                                                echo $kelasnya["nama_kelas"];
                                            ?>
                                        </center>
                                    </td>
                                    <td><center><?= $gb["alamat"] ?></center></td>
                                    <td><center><?= $gb["no_telp"] ?></center></td>
                                    <td>
                                        <center>
                                            Rp<?= number_format($nominal_spp, 0, "", ".") ?>,-
                                        </center>
                                    </td>
                                    <td>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#editData<?= $gb["nis"] ?>" class="btn btn-warning"><i class="bi bi-pencil-square"></i></button>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#hapusData<?= $gb["nis"] ?>" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>

                                <!-- EDIT DATA -->
                                <div class="modal fade" id="editData<?= $gb["nis"] ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"><i class="bi bi-pencil-square"></i>&nbsp; EDIT SISWA</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                
                                                <form method="post" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label>NISN</label>
                                                        <input name="nisn" id="nisn" class="form-control" type="number" placeholder="Masukkan NISN sekolah" value="<?= $gb["nisn"] ?>" disabled>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>NIS</label>
                                                        <input name="nis2" id="nis2" class="form-control" type="number" placeholder="Masukkan NIS sekolah" value="<?= $gb["nis"] ?>" disabled>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>USERNAME</label>
                                                        <input name="user" class="form-control" type="text" placeholder="Masukkan username" value="<?= $gb["username"] ?>" required>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>PASSWORD</label>
                                                        <input name="pwd" class="form-control" type="password" placeholder="Masukkan password" value="<?= $gb["password"] ?>" required>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>NAMA SISWA</label>
                                                        <input name="nama" class="form-control" type="text" placeholder="Masukkan nama siswa" value="<?= $gb["nama"] ?>" required>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>NAMA ORANG TUA</label>
                                                        <input name="nama_ortu" class="form-control" type="text" placeholder="Masukkan nama orang tua siswa" value="<?= $gb["nama_ortu"] ?>" required>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>KELAS</label>
                                                        <select class="form-select" name="id_kelas" required>
                                                            <option selected disabled>--- SILAHKAN PILIH ---</option>
                                                            <?php
                                                            $kelas = mysqli_query($kon, "SELECT * FROM kelas ORDER BY nama_kelas ASC");

                                                            while ($kls = mysqli_fetch_array($kelas)) {
                                                            ?>
                                                            <option 
                                                                <?php if($kls["id_kelas"] == $gb["id_kelas"]) { 
                                                                    echo "selected"; } 
                                                                ?> 
                                                                    value="<?= $gb["id_kelas"] ?>"><?= $kls["nama_kelas"] ?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>ALAMAT</label>
                                                        <textarea name="alamat" class="form-control" rows="7" placeholder="Masukkan alamat siswa" required><?= $gb["alamat"] ?></textarea>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>NO. TELP</label>
                                                        <input name="no_telp" class="form-control" type="number" placeholder="Masukkan nomor telepon siswa" value="<?= $gb["no_telp"] ?>" required>
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>FOTO</label>
                                                        <input name="foto" class="form-control" type="file">
                                                        <input type="hidden" name="foto_lama" value="<?= $gb["foto"] ?>">
                                                    </div>

                                                    <br>

                                                    <div class="form-group">
                                                        <label>NOMINAL SPP</label>
                                                        <?php
                                                        $sqlspp = mysqli_query($kon, "SELECT * FROM spp WHERE id_spp = " . $gb["id_spp"]);
                                                        $rowspp = mysqli_fetch_array($sqlspp);
                                                        $nominal_spp = isset($rowspp["nominal"]) ? $rowspp["nominal"] : 0;
                                                        ?>
                                                        <input type="text" name="nominal_spp" class="form-control" value="Rp<?= number_format($nominal_spp, 0, "", ".") ?>,-" disabled>
                                                        <input type="hidden" name="id_spp" value="<?= $rowspp["id_spp"] ?>">
                                                    </div>

                                                    <input type="hidden" name="nis" value="<?= $gb["nis"] ?>">
                                                
                                            </div>

                                            <div class="modal-footer">
                                                <button name="update" type="submit" class="btn btn-success"><i class="bi bi-check-circle-fill"></i>&nbsp; SAVE</button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!-- HAPUS DATA -->
                                <div class="modal fade" id="hapusData<?= $gb["nis"] ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"><i class="bi bi-trash"></i>&nbsp; HAPUS SISWA</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                
                                                <h2 class="text-center">
                                                    Apakah Anda yakin ingin menghapus data ini ?
                                                </h2>

                                                <form method="post">
                                                    <input type="hidden" name="nis" value="<?= $gb["nis"] ?>">
                                            </div>

                                            <div class="modal-footer">
                                                <button name="delete" type="submit" class="btn btn-danger"><i class="bi bi-check-circle-fill"></i>&nbsp; HAPUS</button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <?php } ?>

                            </table>

                            <!-- TAMBAH DATA -->
                            <div class="modal fade" id="tambahData" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><i class="bi bi-plus"></i>&nbsp; TAMBAH SISWA</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            
                                            <form method="post" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label>NISN</label>
                                                    <input name="nisn" id="nisn" class="form-control" type="number" placeholder="Masukkan NISN sekolah" required>
                                                </div>

                                                <br>

                                                <div class="form-group">
                                                    <label>NIS</label>
                                                    <input name="nis" id="nis" class="form-control" type="number" placeholder="Masukkan NIS sekolah" required>
                                                </div>

                                                <br>

                                                <div class="form-group">
                                                    <label>USERNAME</label>
                                                    <input name="user" class="form-control" type="text" placeholder="Masukkan username" required>
                                                </div>

                                                <br>

                                                <div class="form-group">
                                                    <label>PASSWORD</label>
                                                    <input name="pwd" class="form-control" type="password" placeholder="Masukkan password" required>
                                                </div>

                                                <br>

                                                <div class="form-group">
                                                    <label>NAMA LENGKAP</label>
                                                    <input name="nama" class="form-control" type="text" placeholder="Masukkan nama lengkap siswa" required>
                                                </div>

                                                <br>

                                                <div class="form-group">
                                                    <label>NAMA ORANG TUA</label>
                                                    <input name="nama_ortu" class="form-control" type="text" placeholder="Masukkan nama lengkap orang tua siswa" required>
                                                </div>

                                                <br>

                                                <div class="form-group">
                                                    <label>KELAS</label>
                                                    <select class="form-select" name="id_kelas" required>
                                                        <option selected disabled>--- SILAHKAN PILIH ---</option>
                                                        <?php
                                                        $kelas = mysqli_query($kon, "SELECT * FROM kelas ORDER BY nama_kelas ASC");

                                                        while ($kls = mysqli_fetch_array($kelas)) {
                                                        ?>
                                                        <option value="<?= $kls["id_kelas"] ?>"><?= $kls["nama_kelas"] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <br>

                                                <div class="form-group">
                                                    <label>ALAMAT</label>
                                                    <textarea name="alamat" class="form-control" rows="7" placeholder="Masukkan alamat siswa" required></textarea>
                                                </div>

                                                <br>

                                                <div class="form-group">
                                                    <label>NO. TELP</label>
                                                    <input name="no_telp" class="form-control" type="number" placeholder="Masukkan nomor telepon siswa" required>
                                                </div>

                                                <br>

                                                <div class="form-group">
                                                    <label>FOTO</label>
                                                    <input name="foto" class="form-control" type="file" required>
                                                </div>

                                                <br>

                                                <div class="form-group">
                                                    <label>NOMINAL SPP</label>
                                                    <span id="nominal_spp"></span>
                                                </div>
                                            
                                        </div>

                                        <div class="modal-footer">
                                            <button name="submit" type="submit" class="btn btn-success"><i class="bi bi-check-circle-fill"></i>&nbsp; SAVE</button>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <script>
        $(document).ready(function(){
            $("#nis").on('keyup', function(){
                var nis = $(this).val().substring(0, 4);

                if (nis == ""){
                    $("#nominal_spp").html('<input type="text" class="form-control" name="nominal_spp" id="nominal_spp" value="Rp. 0,-" disabled> <input type="hidden" name="id_spp" value="0">');
                } else {
                    $.ajax({
                        url: "nominal_spp.php",
                        method: "POST",
                        data: {sppnya: nis},
                        success: function(data){
                            $("#nominal_spp").html(data);
                        }
                    });
                }
            });

            // NOMINAL SPP DEFAULT
            $("#nominal_spp").html('<input type="text" class="form-control" name="nominal_spp" id="nominal_spp" value="Rp. 0,-" disabled>');
        });
    </script>

</body>
</html>
