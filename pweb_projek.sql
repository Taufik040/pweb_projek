-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 30, 2024 at 01:27 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pweb_projek`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_data`
--

CREATE TABLE `admin_data` (
  `id_admin` int NOT NULL,
  `nama_admin` varchar(50) NOT NULL,
  `password_admin` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_data`
--

INSERT INTO `admin_data` (`id_admin`, `nama_admin`, `password_admin`) VALUES
(1, 'taufik', '$2y$10$fSnFF7bI6D8fw0gjy2nMjeE.FbprauOHQm7FU5.3yPItuoTHo93bO');

-- --------------------------------------------------------

--
-- Table structure for table `informasi`
--

CREATE TABLE `informasi` (
  `id_info` int NOT NULL,
  `nama_info` varchar(50) DEFAULT NULL,
  `gambar_info` varchar(255) NOT NULL,
  `tanggal_info` date DEFAULT NULL,
  `deskripsi_info` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `lokasi_info` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `informasi`
--

INSERT INTO `informasi` (`id_info`, `nama_info`, `gambar_info`, `tanggal_info`, `deskripsi_info`, `lokasi_info`) VALUES
(1, 'Gotong Royong', '1735564308_1734615276_gr.png', '2024-12-29', 'Kegiatan Gotong Royong warga RT 02', 'Lokasi : Balai Desa RW 01'),
(2, 'Panen Padi', '1735564508_1734617476_ms.png', '2024-12-26', 'Kegiatan warga RT 02 memanen padi di sawah.', 'Lokasi : Sawah dekat Balai Desa RW 01'),
(3, 'Kegiatan UMKM', '1735564685_umkm.png', '2024-12-07', 'Kegiatan UMKM yang dilakukan oleh warga RT 02, dalam rangka acara UMKM tahunan kota.', 'Lokasi : Gedung Pemuda');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_surat`
--

CREATE TABLE `jenis_surat` (
  `id_jenis_surat` int NOT NULL,
  `gambar_jenis_surat` varchar(255) DEFAULT NULL,
  `nama_jenis_surat` varchar(255) NOT NULL,
  `deskripsi_jenis_surat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `syarat_jenis_surat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jenis_surat`
--

INSERT INTO `jenis_surat` (`id_jenis_surat`, `gambar_jenis_surat`, `nama_jenis_surat`, `deskripsi_jenis_surat`, `syarat_jenis_surat`) VALUES
(1, '1735563984_1734766808_surat.png', 'Domisili', 'Surat yang digunakan untuk membuktikan tempat tinggal.', 'Syarat : E-KTP, KK'),
(2, '1735564105_1734766808_surat.png', 'Keterangan Miskin', 'Surat yang digunakan untuk memberikan keterangan miskin dari seseorang.', 'Syarat : E-KTP, KK'),
(3, '1735564218_1734766808_surat.png', 'Izin Keramaian', 'Surat yang digunakan untuk mendapat izin keramaian.', 'Syarat : E-KTP');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int NOT NULL,
  `nama_pengguna` varchar(50) NOT NULL,
  `email_pengguna` varchar(100) NOT NULL,
  `password_pengguna` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `nama_pengguna`, `email_pengguna`, `password_pengguna`) VALUES
(1, 'taufik', 'taufik@gmail.com', '$2y$10$P.UHHmMe2vQnOWArS.xt8.2ipQ1VURaGF1QpnIcj3NB2Y6Fj9/lw2'),
(2, 'Dhafa', 'dhafa@gmail.com', '$2y$10$VJk.CMdpUow9zrNSXQNvkucTfRr3DjRwhsbvJ9QRxhhnfDLFKa95e');

-- --------------------------------------------------------

--
-- Table structure for table `surat`
--

CREATE TABLE `surat` (
  `id_surat` int NOT NULL,
  `id_jenis_surat` int NOT NULL,
  `id_pengguna` int NOT NULL,
  `tanggal_surat` date NOT NULL,
  `status_surat` enum('belum diproses','sedang diproses','selesai diproses') NOT NULL,
  `file_surat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `surat`
--

INSERT INTO `surat` (`id_surat`, `id_jenis_surat`, `id_pengguna`, `tanggal_surat`, `status_surat`, `file_surat`) VALUES
(1, 3, 1, '2024-12-30', 'sedang diproses', '1735564826_KTP.jpeg'),
(2, 3, 2, '2024-12-30', 'belum diproses', '1735564895_Kel4_Skorsing__2_.pdf');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_data`
--
ALTER TABLE `admin_data`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `informasi`
--
ALTER TABLE `informasi`
  ADD PRIMARY KEY (`id_info`);

--
-- Indexes for table `jenis_surat`
--
ALTER TABLE `jenis_surat`
  ADD PRIMARY KEY (`id_jenis_surat`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `email_pengguna` (`email_pengguna`),
  ADD UNIQUE KEY `nama_pengguna` (`nama_pengguna`);

--
-- Indexes for table `surat`
--
ALTER TABLE `surat`
  ADD PRIMARY KEY (`id_surat`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_data`
--
ALTER TABLE `admin_data`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `informasi`
--
ALTER TABLE `informasi`
  MODIFY `id_info` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jenis_surat`
--
ALTER TABLE `jenis_surat`
  MODIFY `id_jenis_surat` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `surat`
--
ALTER TABLE `surat`
  MODIFY `id_surat` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
