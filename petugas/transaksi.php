<?php
session_start();
require "../db.php";
$page = "transaksi";
if (!isset($_SESSION["petugas"])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['submit'])) {
    $id_petugas = $_POST['id_petugas'];
    $nisn = $_POST['nisn'];
    $tgl_bayar = $_POST['tgl_bayar'];
    $bulan_dibayar = $_POST['bulan_dibayar'];
    $tahun_dibayar = $_POST['tahun_dibayar'];
    $id_spp = $_POST['id_spp'];
    $jumlah_bayar = $_POST['jumlah_bayar'];
    $payment_type = $_POST['payment_type'];
    $angsuran = isset($_POST['angsuran']) ? (int)$_POST['angsuran'] : null;

    // === VALIDASI GLOBAL: CEK JIKA ADA ANGSURAN BERJALAN ===
    $cek_angsuran_aktif = mysqli_query($kon, "SELECT * FROM pembayaran 
        WHERE nisn='$nisn' 
        AND bulan_dibayar='$bulan_dibayar' 
        AND tahun_dibayar='$tahun_dibayar' 
        AND payment_type='bulanan' 
        AND (angsuran=6 OR angsuran=12)");

    if (mysqli_num_rows($cek_angsuran_aktif) > 0 && $payment_type != 'bulanan') {
        echo '<script>alert("Siswa sedang melakukan angsuran bulanan. Tidak bisa melakukan pembayaran dengan metode lain sebelum angsuran selesai."); window.location = "";</script>';
        exit;
    }

    // === VALIDASI UNTUK PEMBAYARAN BULANAN DENGAN ANGSURAN ===
    if ($payment_type == 'bulanan') {
        $cek_angsuran = mysqli_query($kon, "SELECT * FROM pembayaran WHERE nisn='$nisn' AND bulan_dibayar='$bulan_dibayar' AND tahun_dibayar='$tahun_dibayar' AND payment_type='bulanan'");

        $jumlah_transaksi = 0;
        $jenis_angsuran_berjalan = "";

        while ($data = mysqli_fetch_assoc($cek_angsuran)) {
            if ($data['angsuran'] == 6) {
                $jumlah_transaksi++;
                $jenis_angsuran_berjalan = 6;
            } elseif ($data['angsuran'] == 12) {
                $jumlah_transaksi++;
                $jenis_angsuran_berjalan = 12;
            } elseif (empty($data['angsuran']) || $data['angsuran'] == 0) {
                $jenis_angsuran_berjalan = "lunas";
            }
        }

        // Jika sudah lunas
        if ($jenis_angsuran_berjalan === "lunas") {
            echo '<script>alert("Siswa sudah membayar lunas untuk bulan ini!"); window.location = "";</script>';
            exit;
        }

        // Tidak boleh ubah metode angsuran
        if ($jenis_angsuran_berjalan === 6 && $angsuran != 6) {
            echo '<script>alert("Siswa sedang melakukan angsuran 6x. Tidak bisa mengubah metode pembayaran!"); window.location = "";</script>';
            exit;
        }
        if ($jenis_angsuran_berjalan === 12 && $angsuran != 12) {
            echo '<script>alert("Siswa sedang melakukan angsuran 12x. Tidak bisa mengubah metode pembayaran!"); window.location = "";</script>';
            exit;
        }

        // Tidak bisa bayar lunas jika ada angsuran berjalan
        if (($jenis_angsuran_berjalan === 6 || $jenis_angsuran_berjalan === 12) && empty($angsuran)) {
            echo '<script>alert("Siswa sedang dalam angsuran. Tidak bisa membayar lunas sebelum angsuran selesai!"); window.location = "";</script>';
            exit;
        }

        // Maksimal 6/12 kali
        if ($angsuran == 6 && $jumlah_transaksi >= 6) {
            echo '<script>alert("Angsuran 6x sudah lunas untuk bulan ini."); window.location = "";</script>';
            exit;
        }
        if ($angsuran == 12 && $jumlah_transaksi >= 12) {
            echo '<script>alert("Angsuran 12x sudah lunas untuk bulan ini."); window.location = "";</script>';
            exit;
        }
    }

    // === INSERT KE TABEL PEMBAYARAN ===
    $query = "INSERT INTO pembayaran (id_petugas, nisn, tgl_bayar, bulan_dibayar, tahun_dibayar, id_spp, jumlah_bayar, payment_type, angsuran)
              VALUES ('$id_petugas', '$nisn', '$tgl_bayar', '$bulan_dibayar', '$tahun_dibayar', '$id_spp', '$jumlah_bayar', '$payment_type', " . ($angsuran !== null ? "'$angsuran'" : "NULL") . ")";

    if (mysqli_query($kon, $query)) {
        echo '<script>alert("Pembayaran berhasil."); window.location="transaksi.php";</script>';
    } else {
        echo '<script>alert("Terjadi kesalahan saat menyimpan."); window.location="transaksi.php";</script>';
    }

    // Tandai kode promo sebagai digunakan jika ada
    if (!empty($_POST['promo_code'])) {
        $promo_code = $_POST['promo_code'];
        $update_promo_query = "UPDATE code_beasiswa SET used = TRUE WHERE code = '$promo_code'";
        mysqli_query($kon, $update_promo_query);
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>TRANSAKSI</title>
    <meta name="robots" content="noindex, nofollow" />
    <?php include 'aset.php'; ?>
</head>
<body>
    <?php require "atas.php"; ?>
    <?php require "menu.php"; ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1><i class="bi bi-receipt-cutoff"></i>&nbsp; TRANSAKSI</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">DASHBOARD</a></li>
                    <li class="breadcrumb-item active">TRANSAKSI</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- ✅ Tambahkan onsubmit untuk validasi JavaScript -->
                            <form method="post" onsubmit="return validateForm();">
                                <div class="form-group">
                                    <label>NAMA PETUGAS</label>
                                    <?php 
                                        $petugas = $_SESSION["petugas"];
                                        $kue_petugas = mysqli_query($kon, "SELECT * FROM petugas WHERE id_petugas = '$petugas'");
                                        $pts = mysqli_fetch_array($kue_petugas);
                                    ?>
                                    <input name="nama_petugas" type="text" class="form-control" value="<?= $pts['nama_petugas'] ?>" disabled>
                                    <input type="hidden" name="id_petugas" value="<?= $pts['id_petugas'] ?>">
                                </div>
                                <br>
                                <div class="form-group">
                                    <label>NAMA SISWA</label>
                                    <select class="form-select" name="nisn" id="nisn" required>
                                        <option value="" selected disabled>--- SILAHKAN PILIH ---</option>
                                        <?php
                                        $nisn_siswa = mysqli_query($kon, "SELECT * FROM siswa ORDER BY nama ASC");
                                        while ($nisn = mysqli_fetch_array($nisn_siswa)) {
                                        ?>
                                        <option value="<?= $nisn["nisn"] ?>"><?= $nisn["nama"] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="bulan_dibayar">BULAN BAYAR</label>
                                    <input type="text" name="bulan_dibayar" class="form-control" value="<?= date('m') ?>" disabled>
                                    <input type="hidden" name="bulan_dibayar" value="<?= date("m") ?>">
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="">TAHUN BAYAR</label>
                                    <input name="tahun_dibayar" class="form-control" type="number" value="<?= date('Y')?>" disabled required>
                                    <input type="hidden" name="tahun_dibayar" value="<?= date("Y") ?>">
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="payment_type">JENIS PEMBAYARAN</label>
                                    <select class="form-select" name="payment_type" id="payment_type" required>
                                        <option value="bulanan">Bulanan</option>
                                        <option value="semester">Semester</option>
                                    </select>
                                </div>
                                <br>
                                <div class="form-group" id="angsuran_field" style="display:none;">
                                    <label for="angsuran">ANGSURAN</label>
                                    <select class="form-select" name="angsuran" id="angsuran">
                                        <option value="">Tidak ingin melakukan angsuran</option>
                                        <option value="6">6x / Bulan</option>
                                        <option value="12">12x / Bulan</option>
                                    </select>
                                </div>
                                <br>
                                <div class="form-group" id="promo_code_field" style="display:none;">
                                    <label for="promo_code">Kode Promo</label>
                                    <input type="text" name="promo_code" id="promo_code" class="form-control" placeholder="Masukkan Kode Promo">
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="jumlah_bayar">JUMLAH BAYAR</label>
                                    <span id="jumlah_bayar"></span>
                                </div>
                                <input name="tgl_bayar" class="form-control" type="hidden" value="<?=date('d') ?>" readonly>
                                <center>
                                    <div class="mt-3">
                                        <button name="submit" type="submit" class="btn btn-success px-5"><i class="bi bi-check-circle-fill"></i>&nbsp; SAVE</button>
                                    </div>
                                </center>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- SCRIPTS -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        function validateForm() {
            var nisn = document.getElementById("nisn").value;
            if (!nisn) {
                alert("Silakan pilih siswa terlebih dahulu.");
                return false;
            }
            return true;
        }

        $(document).ready(function(){
            function toggleSubmitButton() {
                const nisn = $("#nisn").val();
                $("button[name='submit']").prop("disabled", !nisn);
            }

            toggleSubmitButton();
            $("#nisn").on("change", function() {
                toggleSubmitButton();
            });

            $("#nisn").on('change', function(){
                var nisn = $(this).val();
                if (nisn == ""){
                    $("#jumlah_bayar").html('<input type="text" class="form-control" name="jumlah_bayar" value="Rp0,-" disabled> <input type="hidden" name="jumlah_bayar" value="0">');
                } else {
                    $.post("nisn.php", { nisnnya: nisn }, function(data){
                        $("#jumlah_bayar").html(data);
                        $("#payment_type").trigger('change');
                    });
                }
            });

            $("#payment_type").on('change', function(){
                var paymentType = $(this).val();
                var nisn = $("#nisn").val();
                var angsuran = $("#angsuran").val();

                if (!nisn){
                    alert("Silakan pilih siswa terlebih dahulu.");
                } else {
                    if (paymentType === "bulanan") {
                        $("#angsuran_field").show();
                        $("#promo_code_field").hide();
                    } else if (paymentType === "semester") {
                        $("#angsuran_field").hide();
                        $("#promo_code_field").show();
                    }

                    $.post("payment_type.php", { payment_type: paymentType, nisn: nisn, angsuran: angsuran }, function(data){
                        $("#jumlah_bayar").html(data);
                    });
                }
            });

            $("#angsuran").on('change', function(){
                var paymentType = $("#payment_type").val();
                var nisn = $("#nisn").val();
                var angsuran = $(this).val();

                if (!nisn){
                    alert("Silakan pilih siswa terlebih dahulu.");
                } else {
                    $.post("payment_type.php", { payment_type: paymentType, nisn: nisn, angsuran: angsuran }, function(data){
                        $("#jumlah_bayar").html(data);
                    });
                }
            });

            $("#promo_code").on('change', function(){
                var promoCode = $(this).val();
                var paymentType = $("#payment_type").val();
                var nisn = $("#nisn").val();

                if (!promoCode || paymentType !== "semester") return;

                $.post("validate_promo.php", { promo_code: promoCode, nisn: nisn }, function(data){
                    var response = JSON.parse(data);
                    if (response.valid) {
                        $("#jumlah_bayar").html('<input type="text" class="form-control" name="jumlah_bayar" value="Rp' + response.new_amount.toLocaleString('id-ID') + ',-" disabled> <input type="hidden" name="jumlah_bayar" value="' + response.new_amount + '">');
                    } else {
                        alert(response.message);
                    }
                });
            });

            $("#jumlah_bayar").html('<input type="text" class="form-control" name="jumlah_bayar" value="Rp0,-" disabled> <input type="hidden" name="jumlah_bayar" value="0">');
        });
    </script>
</body>
</html>
