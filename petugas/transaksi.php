<?php
session_start();
require "../db.php";
$page = "transaksi";
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION["petugas"])) {
    header("Location: ../login.php");
}

// PROSEDUR SIMPAN DATA
if (isset($_POST["submit"])) {
    $id_petugas = mysqli_escape_string($kon, isset($_POST["id_petugas"]) ? $_POST["id_petugas"] : "");
    $nisn = mysqli_real_escape_string($kon, isset($_POST["nisn"]) ? $_POST["nisn"] : "");
    $tgl_bayar = mysqli_real_escape_string($kon, isset($_POST["tgl_bayar"]) ? $_POST["tgl_bayar"] : "");
    $bulan_dibayar = mysqli_real_escape_string($kon, isset($_POST["bulan_dibayar"]) ? $_POST["bulan_dibayar"] : "");
    $tahun_dibayar = mysqli_real_escape_string($kon, isset($_POST["tahun_dibayar"]) ? $_POST["tahun_dibayar"] : "");
    $payment_type = mysqli_real_escape_string($kon, isset($_POST["payment_type"]) ? $_POST["payment_type"] : "");
    $angsuran = mysqli_real_escape_string($kon, isset($_POST["angsuran"]) ? $_POST["angsuran"] : "");
    $id_spp = isset($_POST["id_spp"]) ? $_POST["id_spp"] : "";
    $jumlah_bayar = isset($_POST["jumlah_bayar"]) ? $_POST["jumlah_bayar"] : "";
    $promo_code = mysqli_real_escape_string($kon, isset($_POST["promo_code"]) ? $_POST["promo_code"] : "");

    // Check for promo code
    if ($promo_code != "") {
        $promo_query = "SELECT * FROM code_beasiswa WHERE code = '$promo_code' AND used = FALSE LIMIT 1";
        $promo_result = mysqli_query($kon, $promo_query);
        if (mysqli_num_rows($promo_result) > 0) {
            $promo = mysqli_fetch_assoc($promo_result);
            $discount_amount = $promo['discount_amount'];
            $jumlah_bayar -= $discount_amount;

            // Update promo status
            $update_promo_query = "UPDATE code_beasiswa SET used = TRUE WHERE code = '$promo_code'";
            mysqli_query($kon, $update_promo_query);
        } else {
            echo '<script>alert("Kode promo tidak valid atau sudah digunakan.");</script>';
            $jumlah_bayar = isset($_POST["jumlah_bayar"]) ? $_POST["jumlah_bayar"] : "";
        }
    }

    if (empty($nisn)) {
        echo '<script>alert("MAAF, DATA TERSEBUT MASIH KOSONG. SILAHKAN DI-ISI TERLEBIH DAHULU !"); window.location = "";</script>';
    } else {        
        mysqli_query($kon, "INSERT INTO pembayaran (id_petugas, nisn, tgl_bayar, bulan_dibayar, tahun_dibayar, id_spp, jumlah_bayar, payment_type, angsuran) VALUES ('$id_petugas', '$nisn', '$tgl_bayar', '$bulan_dibayar', '$tahun_dibayar', '$id_spp', '$jumlah_bayar', '$payment_type', '$angsuran')");
        echo '<script>alert("DATA BERHASIL DISIMPAN !"); window.location = "";</script>';
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
    <meta content="" name="description" />
    <meta content="" name="keywords" />
    
    <?php include 'aset.php'; ?>
</head>
<body>
    <!-- HEADER -->
    <?php require "atas.php"; ?>

    <!-- SIDEBAR -->
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
                            <form method="post">
                                <div class="form-group">
                                    <label>NAMA PETUGAS</label>
                                    <?php 
                                        if(isset($_SESSION["petugas"])) {
                                            $petugas = $_SESSION["petugas"];
                                        }

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
                                        <option selected disabled>--- SILAHKAN PILIH ---</option>
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
                                    <input type="text" name="bulan_dibayar" class="form-control" id="bulan_dibayar" value="<?= date('m') ?>" disabled>
                                    <input type="hidden" name="bulan_dibayar" value="<?= date("m") ?>">
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="">TAHUN BAYAR</label>
                                    <input name="tahun_dibayar" class="form-control" type="number" placeholder="Masukkan Tahun Bayar" value="<?= date('Y')?>" disabled required>
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
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#nisn").on('change', function(){
                var nisn = $(this).val();
                if (nisn == ""){
                    $("#jumlah_bayar").html('<input type="text" class="form-control" name="jumlah_bayar" value="Rp0,-" disabled> <input type="hidden" name="jumlah_bayar" value="0">');
                } else {
                    $.ajax({
                        url: "nisn.php",
                        method: "POST",
                        data: { nisnnya: nisn },
                        success: function(data){
                            $("#jumlah_bayar").html(data);
                            // Trigger payment type change to update the amount based on the payment type
                            $("#payment_type").trigger('change');
                        }
                    });
                }
            });

            $("#payment_type").on('change', function(){
                var paymentType = $(this).val();
                var nisn = $("#nisn").val();
                var angsuran = $("#angsuran").val();

                if (nisn == ""){
                    alert("Silakan pilih siswa terlebih dahulu.");
                } else {
                    if (paymentType === "bulanan") {
                        $("#angsuran_field").show();
                        $("#promo_code_field").hide();
                    } else if (paymentType === "semester") {
                        $("#angsuran_field").hide();
                        $("#promo_code_field").show();
                    } else {
                        $("#angsuran_field").hide();
                        $("#promo_code_field").hide();
                    }

                    $.ajax({
                        url: "payment_type.php",
                        method: "POST",
                        data: { payment_type: paymentType, nisn: nisn, angsuran: angsuran },
                        success: function(data){
                            $("#jumlah_bayar").html(data);
                        }
                    });
                }
            });

            $("#angsuran").on('change', function(){
                var paymentType = $("#payment_type").val();
                var nisn = $("#nisn").val();
                var angsuran = $(this).val();

                if (nisn == ""){
                    alert("Silakan pilih siswa terlebih dahulu.");
                } else {
                    $.ajax({
                        url: "payment_type.php",
                        method: "POST",
                        data: { payment_type: paymentType, nisn: nisn, angsuran: angsuran },
                        success: function(data){
                            $("#jumlah_bayar").html(data);
                        }
                    });
                }
            });

            $("#promo_code").on('change', function(){
                var promoCode = $(this).val();
                var paymentType = $("#payment_type").val();
                var nisn = $("#nisn").val();

                if (promoCode == "" || paymentType !== "semester") {
                    return;
                }

                $.ajax({
                    url: "validate_promo.php",
                    method: "POST",
                    data: { promo_code: promoCode, nisn: nisn },
                    success: function(data){
                        var response = JSON.parse(data);
                        if (response.valid) {
                            $("#jumlah_bayar").html('<input type="text" class="form-control" name="jumlah_bayar" value="Rp' + response.new_amount.toLocaleString('id-ID') + ',-" disabled> <input type="hidden" name="jumlah_bayar" value="' + response.new_amount + '">');
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });

            // NOMINAL SPP DEFAULT
            $("#jumlah_bayar").html('<input type="text" class="form-control" name="jumlah_bayar" value="Rp0,-" disabled> <input type="hidden" name="jumlah_bayar" value="0">');
        });
    </script>
</body>
</html>








