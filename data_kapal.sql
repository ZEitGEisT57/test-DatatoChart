-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 02:30 PM
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
-- Database: `data_kapal`
--

-- --------------------------------------------------------

--
-- Table structure for table `kapal`
--

CREATE TABLE `kapal` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kapal`
--

INSERT INTO `kapal` (`id`, `nama`) VALUES
(1, 'KMP MUYU'),
(2, 'KMP BAMBIT'),
(3, 'KMP BINAR'),
(4, 'KMP KOKONAO');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expire` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `username`, `email`, `password`, `role`, `reset_token`, `reset_expire`) VALUES
(3, 'Iselin', '', '$2y$10$2yXx99hsYr4yXAI.wA8GIegMtj2Kscul2F4Y13jayMygHS8Mu2XSC', 'admin', NULL, NULL),
(4, 'dq', '', '$2y$10$BDFlS5ufVHeChy5AJz19quGnZDaRvlH7lEy.1vsJCT3glpf/EnEA6', 'admin', NULL, NULL),
(5, 'wiby', 'shutupfag@got.com', '$2y$10$aWVKPV9aX0mKoJrcPEeiM.TcoU6Vd/YDFpifkrkP20Ek1AEZA13ai', 'admin', 'ad5af7654a7d54fa193e74c74111415f', '2025-05-19 03:32:21'),
(6, 'caitlyn', 'elonmusksbuttcheek@trump.us', '$2y$10$zFRoXAJbKCKETbLOyz3zl.cu0p/Gjg/RTTH4IvfLTHE1PJrgr5GDu', 'admin', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `produksi`
--

CREATE TABLE `produksi` (
  `id` int(11) NOT NULL,
  `kapal_id` int(11) DEFAULT NULL,
  `jenis_tiket` varchar(100) DEFAULT NULL,
  `jumlah_produksi` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produksi`
--

INSERT INTO `produksi` (`id`, `kapal_id`, `jenis_tiket`, `jumlah_produksi`, `tanggal`) VALUES
(96, 4, '- Golongan V Pnp', 23, '2025-05-19'),
(97, 4, '- Golongan V Brg', 0, '2025-05-19'),
(98, 4, '- Golongan VI Pnp', 12, '2025-05-19'),
(99, 4, '- Golongan VI Brg', 0, '2025-05-19'),
(100, 4, '- Golongan VII', 7, '2025-05-19'),
(101, 4, '- Golongan VIII', 0, '2025-05-19'),
(102, 4, '- Golongan IX', 0, '2025-05-19'),
(103, 3, '- Eksekutif Dewasa', 0, '2025-05-19'),
(104, 3, '- Eksekutif Anak', 6, '2025-05-19'),
(105, 3, '- Bisnis Dewasa', 5, '2025-05-19'),
(106, 3, '- Bisnis Anak', 0, '2025-05-19'),
(107, 3, '- Ekonomi Dewasa', 20, '2025-05-19'),
(108, 3, '- Ekonomi Anak', 370, '2025-05-19'),
(109, 3, '- Penumpang Dalam kendaraan', 219, '2025-05-19'),
(110, 3, '- Golongan I', 0, '2025-05-19'),
(111, 3, '- Golongan II', 0, '2025-05-19'),
(112, 3, '- Golongan III', 1678, '2025-05-19'),
(113, 3, '- Golongan IV Pnp', 0, '2025-05-19'),
(114, 3, '- Golongan IV Brg', 12, '2025-05-19'),
(115, 3, '- Golongan V Pnp', 6, '2025-05-19'),
(116, 3, '- Golongan V Brg', 0, '2025-05-19'),
(117, 3, '- Golongan VI Pnp', 0, '2025-05-19'),
(118, 3, '- Golongan VI Brg', 0, '2025-05-19'),
(119, 3, '- Golongan VII', 0, '2025-05-19'),
(120, 3, '- Golongan VIII', 0, '2025-05-19'),
(121, 3, '- Golongan IX', 0, '2025-05-19'),
(122, 4, 'Ekonomi Dewasa', 420, '2025-04-22'),
(123, 1, '- Eksekutif Dewasa', 280, '2025-05-21'),
(124, 1, '- Eksekutif Anak', 0, '2025-05-21'),
(125, 1, '- Bisnis Dewasa', 5, '2025-05-21'),
(126, 1, '- Bisnis Anak', 0, '2025-05-21'),
(127, 1, '- Ekonomi Dewasa', 172, '2025-05-21'),
(128, 1, '- Ekonomi Anak', 40, '2025-05-21'),
(129, 1, '- Penumpang Dalam kendaraan', 0, '2025-05-21'),
(130, 1, '- Golongan I', 123, '2025-05-21'),
(131, 1, '- Golongan II', 0, '2025-05-21'),
(132, 1, '- Golongan III', 65, '2025-05-21'),
(133, 1, '- Golongan IV Pnp', 0, '2025-05-21'),
(134, 1, '- Golongan IV Brg', 6, '2025-05-21'),
(135, 1, '- Golongan V Pnp', 12, '2025-05-21'),
(136, 1, '- Golongan V Brg', 0, '2025-05-21'),
(137, 1, '- Golongan VI Pnp', 0, '2025-05-21'),
(138, 1, '- Golongan VI Brg', 0, '2025-05-21'),
(139, 1, '- Golongan VII', 0, '2025-05-21'),
(140, 1, '- Golongan VIII', 3, '2025-05-21'),
(141, 1, '- Golongan IX', 0, '2025-05-21'),
(142, 2, '- Eksekutif Dewasa', 0, '2025-05-21'),
(143, 2, '- Eksekutif Anak', 6, '2025-05-21'),
(144, 2, '- Bisnis Dewasa', 5, '2025-05-21'),
(145, 2, '- Bisnis Anak', 0, '2025-05-21'),
(146, 2, '- Ekonomi Dewasa', 20, '2025-05-21'),
(147, 2, '- Ekonomi Anak', 370, '2025-05-21'),
(148, 2, '- Penumpang Dalam kendaraan', 219, '2025-05-21'),
(149, 2, '- Golongan I', 0, '2025-05-21'),
(150, 2, '- Golongan II', 0, '2025-05-21'),
(151, 2, '- Golongan III', 1678, '2025-05-21'),
(152, 2, '- Golongan IV Pnp', 0, '2025-05-21'),
(153, 2, '- Golongan IV Brg', 12, '2025-05-21'),
(154, 2, '- Golongan V Pnp', 6, '2025-05-21'),
(155, 2, '- Golongan V Brg', 0, '2025-05-21'),
(156, 2, '- Golongan VI Pnp', 0, '2025-05-21'),
(157, 2, '- Golongan VI Brg', 0, '2025-05-21'),
(158, 2, '- Golongan VII', 0, '2025-05-21'),
(159, 2, '- Golongan VIII', 0, '2025-05-21'),
(160, 2, '- Golongan IX', 0, '2025-05-21'),
(161, 4, '- Eksekutif Dewasa', 280, '2025-05-21'),
(162, 4, '- Eksekutif Anak', 0, '2025-05-21'),
(163, 4, '- Bisnis Dewasa', 5, '2025-05-21'),
(164, 4, '- Bisnis Anak', 0, '2025-05-21'),
(165, 4, '- Ekonomi Dewasa', 172, '2025-05-21'),
(166, 4, '- Ekonomi Anak', 40, '2025-05-21'),
(167, 4, '- Penumpang Dalam kendaraan', 0, '2025-05-21'),
(168, 4, '- Golongan I', 123, '2025-05-21'),
(169, 4, '- Golongan II', 0, '2025-05-21'),
(170, 4, '- Golongan III', 65, '2025-05-21'),
(171, 4, '- Golongan IV Pnp', 0, '2025-05-21'),
(172, 4, '- Golongan IV Brg', 6, '2025-05-21'),
(173, 4, '- Golongan V Pnp', 12, '2025-05-21'),
(174, 4, '- Golongan V Brg', 0, '2025-05-21'),
(175, 4, '- Golongan VI Pnp', 0, '2025-05-21'),
(176, 4, '- Golongan VI Brg', 0, '2025-05-21'),
(177, 4, '- Golongan VII', 0, '2025-05-21'),
(178, 4, '- Golongan VIII', 3, '2025-05-21'),
(179, 4, '- Golongan IX', 0, '2025-05-21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kapal`
--
ALTER TABLE `kapal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `produksi`
--
ALTER TABLE `produksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kapal_id` (`kapal_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kapal`
--
ALTER TABLE `kapal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `produksi`
--
ALTER TABLE `produksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `produksi`
--
ALTER TABLE `produksi`
  ADD CONSTRAINT `produksi_ibfk_1` FOREIGN KEY (`kapal_id`) REFERENCES `kapal` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
