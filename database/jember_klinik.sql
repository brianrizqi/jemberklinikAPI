-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2019 at 01:18 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jember_klinik`
--

-- --------------------------------------------------------

--
-- Table structure for table `kuota`
--

CREATE TABLE `kuota` (
  `id_kuota` int(11) NOT NULL,
  `kuota` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_awal` time NOT NULL,
  `jam_akhir` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kuota`
--

INSERT INTO `kuota` (`id_kuota`, `kuota`, `tanggal`, `jam_awal`, `jam_akhir`) VALUES
(1, 20, '2019-05-16', '11:00:00', '12:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pemesanan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_penyakit` varchar(11) NOT NULL,
  `nama` varchar(191) NOT NULL,
  `umur` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status` varchar(20) NOT NULL,
  `nomor` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id_pemesanan`, `id_user`, `id_penyakit`, `nama`, `umur`, `tanggal`, `status`, `nomor`, `created_at`) VALUES
(29, 4, 'A2', 'Ivan', 22, '2019-05-24', 'menunggu', 2, '2019-05-15 12:59:07'),
(30, 5, 'A4', 'ivan', 20, '2019-05-24', 'menunggu', 3, '2019-05-16 04:02:23'),
(32, 3, 'A01', 'brian', 10, '2019-05-24', 'menunggu', 1, '2019-05-16 05:09:39');

-- --------------------------------------------------------

--
-- Table structure for table `penyakit`
--

CREATE TABLE `penyakit` (
  `id_penyakit` varchar(11) NOT NULL,
  `keluhan` varchar(191) NOT NULL,
  `bobot` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penyakit`
--

INSERT INTO `penyakit` (`id_penyakit`, `keluhan`, `bobot`) VALUES
('A01', 'sakit gigi yang sudah terdapat sakit cekot cekot secara spontan / tiba - tiba', 1),
('A10', 'sariawan, sakit pada jaringan lunak rongga mulut', 0.1),
('A2', 'gigi sudah mati namun masih ada mahkotanya', 0.9),
('A3', 'terdapat nanah yang keluar dari gusi', 0.8),
('A4', 'gusi sakit dan kemerahan dan bengkak', 0.7),
('A5', 'gigi goyang karena banyak karang gigi', 0.6),
('A6', 'gigi goyang', 0.5),
('A7', 'gusi bagian gigi yang tidak tumbuh bengkak dan sakit', 0.4),
('A8', 'ngilu karena gigi berlubang yang masih belum ada sakit spontan', 0.3),
('A9', 'tumbuh gigi di antara gigi atas bahagian depan', 0.2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `no_telp` varchar(13) NOT NULL,
  `jenis_kelamin` varchar(20) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `bpjs` varchar(16) NOT NULL,
  `level` varchar(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `alamat`, `no_telp`, `jenis_kelamin`, `tanggal_lahir`, `bpjs`, `level`, `created_at`) VALUES
(1, 'pasien', 'pasien@gmail.com', 'pasien', 'jember', '0867789', 'laki_laki', '1998-04-27', '1029098102', '2', '2019-02-05 01:16:12'),
(2, 'admin', 'admin@gmail.com', 'admin', 'jember', '0867789', 'laki_laki', '1998-04-27', '1029098102', '1', '2019-02-05 01:17:33'),
(3, 'brian', 'brian@gmail.com', 'brian', 'jember', '08523', 'laki_laki', '2019-02-06', '0184848', '2', '2019-02-06 12:35:53'),
(4, 'ivan', 'ivan@gmail.com', 'ivan', 'jember', '084545', 'laki_laki', '2019-02-05', '0484048', '2', '2019-02-06 12:36:46'),
(5, 'molly', 'molly@gmail.com', 'molly', 'jember', '686464', 'perempuan', '2019-02-07', '0485', '2', '2019-02-07 07:17:41'),
(6, 'dokter', 'dokter@gmail.com', 'dokter', 'Jember', '08987', 'laki_laki', '1997-03-03', '90920', '1', '2019-03-13 01:23:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kuota`
--
ALTER TABLE `kuota`
  ADD PRIMARY KEY (`id_kuota`),
  ADD UNIQUE KEY `tanggal` (`tanggal`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_penyakit` (`id_penyakit`),
  ADD KEY `id_penyakit_2` (`id_penyakit`);

--
-- Indexes for table `penyakit`
--
ALTER TABLE `penyakit`
  ADD PRIMARY KEY (`id_penyakit`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kuota`
--
ALTER TABLE `kuota`
  MODIFY `id_kuota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pemesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`id_penyakit`) REFERENCES `penyakit` (`id_penyakit`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
