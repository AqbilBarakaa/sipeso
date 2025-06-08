-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2025 at 12:32 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sipeso`
--

-- --------------------------------------------------------

--
-- Table structure for table `beasiswa`
--

CREATE TABLE `beasiswa` (
  `id` int(64) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beasiswa`
--

INSERT INTO `beasiswa` (`id`, `judul`, `deskripsi`) VALUES
(1, 'Beasiswa Jalur Prestasi', 'Untuk siswa yang berpestrasi'),
(2, 'Beasiswa Kurang Mampu', 'Untuk siswa yang kurang mampu'),
(4, '', ''),
(5, '', ''),
(6, '', ''),
(7, '', ''),
(8, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `code_beasiswa`
--

CREATE TABLE `code_beasiswa` (
  `id` int(11) NOT NULL,
  `code` varchar(5) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `code_beasiswa`
--

INSERT INTO `code_beasiswa` (`id`, `code`, `discount_amount`, `used`) VALUES
(1, 'JWEBN', 100000.00, 1),
(2, 'BWMTL', 100000.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `nama_kelas` varchar(10) NOT NULL,
  `kompetensi_keahlian` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `kompetensi_keahlian`) VALUES
(1, '10 MIPA 1', 'IPA'),
(2, '10 IPS 2', 'IPS'),
(3, '11 MIPA 3', 'IPA'),
(4, '12 MIPA 1', 'IPA'),
(5, '12 IPS 2', 'IPS');

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id_notifikasi` int(11) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `id_petugas` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `status` enum('terkirim','dibaca') DEFAULT 'terkirim',
  `tanggal_kirim` datetime NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id_notifikasi`, `nisn`, `id_petugas`, `judul`, `pesan`, `status`, `tanggal_kirim`, `keterangan`) VALUES
(24, '8938461948', 2, 'Tunggakan SPP', 'Anda memiliki tunggakan SPP. Belum Membayar sejak bulan January ', 'dibaca', '2025-06-08 09:52:07', 'Belum Membayar sejak bulan January ');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_petugas` int(11) NOT NULL,
  `nisn` varchar(10) NOT NULL,
  `tgl_bayar` int(7) NOT NULL,
  `bulan_dibayar` varchar(15) NOT NULL,
  `tahun_dibayar` varchar(4) NOT NULL,
  `id_spp` int(11) NOT NULL,
  `jumlah_bayar` int(11) NOT NULL,
  `angsuran` int(11) DEFAULT NULL,
  `status` enum('paid','unpaid') NOT NULL DEFAULT 'paid',
  `payment_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_petugas`, `nisn`, `tgl_bayar`, `bulan_dibayar`, `tahun_dibayar`, `id_spp`, `jumlah_bayar`, `angsuran`, `status`, `payment_type`) VALUES
(2, 2, '3651355998', 8, '06', '2025', 0, 700000, 0, 'paid', 'bulanan'),
(3, 2, '9233330354', 8, '06', '2025', 0, 116667, 6, 'paid', 'bulanan'),
(4, 2, '9233330354', 8, '06', '2025', 0, 116667, 6, 'unpaid', 'bulanan'),
(5, 2, '9233330354', 8, '06', '2025', 0, 116667, 6, 'unpaid', 'bulanan'),
(6, 2, '9233330354', 8, '06', '2025', 0, 116667, 6, 'unpaid', 'bulanan'),
(7, 2, '9233330354', 8, '06', '2025', 0, 116667, 6, 'unpaid', 'bulanan'),
(8, 2, '9233330354', 8, '06', '2025', 0, 116667, 6, 'unpaid', 'bulanan'),
(9, 2, '2651590352', 8, '06', '2025', 0, 58333, 12, 'unpaid', 'bulanan'),
(10, 2, '2651590352', 8, '06', '2025', 0, 58333, 12, 'unpaid', 'bulanan'),
(11, 2, '2651590352', 8, '06', '2025', 0, 58333, 12, 'unpaid', 'bulanan');

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_keringanan`
--

CREATE TABLE `pengajuan_keringanan` (
  `id` int(11) NOT NULL,
  `nis` varchar(255) NOT NULL,
  `alasan` text NOT NULL,
  `status` enum('pending','disetujui','ditolak') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengajuan_keringanan`
--

INSERT INTO `pengajuan_keringanan` (`id`, `nis`, `alasan`, `status`, `created_at`) VALUES
(4, '3651355998', 'Saya ingin mengajukan keringanan. Dikarenakan, orang tua saya telah diPHK', 'pending', '2025-06-08 07:49:57');

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `id_petugas` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(32) NOT NULL,
  `nama_petugas` varchar(35) NOT NULL,
  `level` enum('admin','petugas') NOT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`id_petugas`, `username`, `password`, `nama_petugas`, `level`, `foto`) VALUES
(1, 'admin', '123', 'Roky Zofyan', 'admin', 'profile-img.jpg'),
(2, 'petugas', '123', 'Aqbil Baraka', 'petugas', 'profile-img.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `nisn` varchar(10) NOT NULL,
  `nis` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nama` varchar(35) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `alamat` text NOT NULL,
  `no_telp` varchar(13) NOT NULL,
  `id_spp` int(11) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `nama_ortu` varchar(255) NOT NULL,
  `pengajuan_baru` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`nisn`, `nis`, `username`, `password`, `nama`, `id_kelas`, `alamat`, `no_telp`, `id_spp`, `foto`, `nama_ortu`, `pengajuan_baru`) VALUES
('2651590352', '2025123', 'siswa4', '123', 'Asep', 1, 'Jl. Kamal', '081222342345', 3, '../uploads/paldo.png', 'Surya', 0),
('3651355998', '202311100073', 'siswa1', '123', 'Rypaldho', 2, 'Jl. Telang', '0812565737298', 1, 'paldo.png', 'Asep', 0),
('8938461948', '202411100006', 'siswa2', '123', 'Ridotua', 3, 'Jl. Trunojoyo', '0815373983928', 2, 'paldo.png', 'Eko', 0),
('9233330354', '202511100078', 'siswa3', '123', 'Hutagaol', 4, 'Jl. Graha', '081513099954', 3, 'paldo.png', 'Gatau', 0);

-- --------------------------------------------------------

--
-- Table structure for table `spp`
--

CREATE TABLE `spp` (
  `id_spp` int(11) NOT NULL,
  `tahun_ajaran` int(11) NOT NULL,
  `nominal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `spp`
--

INSERT INTO `spp` (`id_spp`, `tahun_ajaran`, `nominal`) VALUES
(1, 2023, 700000),
(2, 2024, 700000),
(3, 2025, 700000);

-- --------------------------------------------------------

--
-- Table structure for table `tanya_jawab`
--

CREATE TABLE `tanya_jawab` (
  `id` int(11) NOT NULL,
  `nisn` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `user_type` enum('admin','siswa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tanya_jawab`
--

INSERT INTO `tanya_jawab` (`id`, `nisn`, `parent_id`, `content`, `created_at`, `user_type`) VALUES
(13, '3651355998', NULL, 'Saya ingin bertanya pak', '2025-06-08 07:48:51', 'siswa'),
(18, '3651355998', 13, 'Iya, ada apa?', '2025-06-08 08:42:14', 'admin'),
(19, '3651355998', 13, 'Tidak jadi.', '2025-06-08 08:42:25', 'siswa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `beasiswa`
--
ALTER TABLE `beasiswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `code_beasiswa`
--
ALTER TABLE `code_beasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id_notifikasi`),
  ADD KEY `nisn` (`nisn`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `fk_pembayaran_siswa` (`nisn`),
  ADD KEY `fk_id_petugas` (`id_petugas`);

--
-- Indexes for table `pengajuan_keringanan`
--
ALTER TABLE `pengajuan_keringanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id_petugas`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`nisn`) USING BTREE,
  ADD UNIQUE KEY `nisn` (`nisn`);

--
-- Indexes for table `spp`
--
ALTER TABLE `spp`
  ADD PRIMARY KEY (`id_spp`);

--
-- Indexes for table `tanya_jawab`
--
ALTER TABLE `tanya_jawab`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `beasiswa`
--
ALTER TABLE `beasiswa`
  MODIFY `id` int(64) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `code_beasiswa`
--
ALTER TABLE `code_beasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id_notifikasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pengajuan_keringanan`
--
ALTER TABLE `pengajuan_keringanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id_petugas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `spp`
--
ALTER TABLE `spp`
  MODIFY `id_spp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tanya_jawab`
--
ALTER TABLE `tanya_jawab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`nisn`) REFERENCES `siswa` (`nisn`),
  ADD CONSTRAINT `notifikasi_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id_petugas`);

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_id_petugas` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id_petugas`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pembayaran_siswa` FOREIGN KEY (`nisn`) REFERENCES `siswa` (`nisn`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
