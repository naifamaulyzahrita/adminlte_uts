-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 26, 2026 at 04:21 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db5e`
--

-- --------------------------------------------------------

--
-- Table structure for table `myguests`
--

CREATE TABLE `myguests` (
  `id` int UNSIGNED NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `myguests`
--

INSERT INTO `myguests` (`id`, `firstname`, `lastname`, `email`, `reg_date`) VALUES
(3, 'Felesia', 'Elita Quinn', 'felesia@gmail.com', '2026-10-08 07:55:54');

-- --------------------------------------------------------

--
-- Table structure for table `nilaimhsw`
--

CREATE TABLE `nilaimhsw` (
  `nim` char(10) NOT NULL,
  `namalengkap` varchar(50) NOT NULL,
  `nilaiakhir` decimal(5,2) NOT NULL,
  `nilaihuruf` char(1) DEFAULT NULL,
  `predikat` varchar(20) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nilaimhsw`
--

INSERT INTO `nilaimhsw` (`nim`, `namalengkap`, `nilaiakhir`, `nilaihuruf`, `predikat`, `created_date`, `modified_date`, `file_path`) VALUES
('3202416029', 'Galih Permana', 72.25, 'B', 'Memuaskan', '2026-10-21 09:56:23', '2026-10-21 09:56:23', NULL),
('3202416052', 'Dimas Prasetyo', 80.51, 'A', 'Sangat Memuaskan', '2026-10-21 09:46:50', '2026-10-21 09:46:50', NULL),
('3202416087', 'Budi Gunawan Hendra', 58.30, 'C', 'Cukup Memuaskan', '2026-10-21 10:05:33', '2026-10-21 10:05:33', NULL),
('3202416127', 'Ahmad Fauzi', 95.50, 'A', 'Sangat Memuaskan', '2026-10-21 09:45:35', '2026-10-21 09:45:35', NULL),
('3202416175', 'Budi Santoso', 91.20, 'A', 'Sangat Memuaskan', '2026-10-21 09:52:00', '2026-10-21 09:52:00', NULL),
('3202416201', 'Eko Susilo', 20.15, 'E', 'Tidak Lulus!', '2026-10-21 10:01:20', '2026-10-21 10:01:20', NULL),
('3202416246', 'Andi Wijaya', 72.25, 'B', 'Memuaskan', '2026-10-21 09:48:43', '2026-10-21 09:48:43', NULL),
('3202416310', 'Reza Mahendra', 65.50, 'C', 'Cukup Memuaskan', '2026-10-21 09:54:20', '2026-10-21 09:54:20', NULL),
('3202416340', 'Hendra Gunawan', 88.90, 'A', 'Sangat Memuaskan', '2026-10-21 10:03:03', '2026-10-21 10:03:03', NULL),
('3202416384', 'Siti Nurhaliza', 88.90, 'A', 'Sangat Memuaskan', '2026-10-21 09:46:16', '2026-10-21 09:46:16', NULL),
('3202416438', 'Nabila Azzahra', 45.00, 'D', 'Kurang Memuaskan', '2026-10-21 09:51:23', '2026-10-21 09:51:23', NULL),
('3202416469', 'Farhan Maulana', 0.00, 'E', 'Tidak Lulus!', '2026-10-21 10:06:36', '2026-10-21 10:06:36', NULL),
('3202416493', 'Rizky Hidayat', 34.51, 'D', 'Kurang Memuaskan', '2026-10-21 09:58:23', '2026-10-21 09:58:23', NULL),
('3202416555', 'Annisa Rahmawati', 100.00, 'A', 'Sangat Memuaskan', '2026-10-21 09:57:26', '2026-10-21 09:57:26', NULL),
('3202416593', 'Putri Ayu Lestari', 60.50, 'C', 'Cukup Memuaskan', '2026-10-21 09:49:23', '2026-10-21 09:49:23', NULL),
('3202416623', 'Dewi Indah ', 83.65, 'A', 'Sangat Memuaskan', '2026-10-21 10:07:11', '2026-10-21 10:07:11', NULL),
('3202416667', 'Citra Kirana', 88.90, 'A', 'Sangat Memuaskan', '2026-10-21 09:53:25', '2026-10-21 09:53:25', NULL),
('3202416678', 'Mega Puspita', 45.00, 'D', 'Kurang Memuaskan', '2026-10-21 10:02:07', '2026-10-21 10:02:07', NULL),
('3202416719', 'Rina Maharani', 78.40, 'B', 'Memuaskan', '2026-10-21 09:47:33', '2026-10-21 09:47:33', NULL),
('3202416752', 'Tiara Andini', 60.50, 'C', 'Cukup Memuaskan', '2026-10-21 10:06:12', '2026-10-21 10:06:12', NULL),
('3202416784', 'Dewi Lestari', 52.75, 'C', 'Cukup Memuaskan', '2026-10-21 09:55:03', '2026-10-21 09:55:03', NULL),
('3202416836', 'Rina Putri Sintia', 66.80, 'B', 'Memuaskan', '2026-10-21 10:00:37', '2026-10-21 10:00:37', NULL),
('3202416861', 'Fajar Ramadhan', 80.50, 'B', 'Memuaskan', '2026-10-21 09:50:24', '2026-10-21 09:50:24', NULL),
('3202416902', 'Yoga Pratama', 34.49, 'E', 'Tidak Lulus!', '2026-10-21 09:52:42', '2026-10-21 09:52:42', NULL),
('3202416915', 'Lia Amelia', 50.50, 'D', 'Kurang Memuaskan', '2026-10-21 10:04:04', '2026-10-21 10:04:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

CREATE TABLE `photo` (
  `id_photo` int UNSIGNED NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `uploaded_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `photo`
--

INSERT INTO `photo` (`id_photo`, `file_name`, `uploaded_date`) VALUES
(1, 'img/Grumpy Cat Energy 😾🐾 _ Ultra HD 4K.jpg', '2026-10-20 07:06:39'),
(2, 'img/download.jpg', '2026-10-20 07:06:46'),
(3, 'img/Use_Case.png', '2026-10-20 07:07:10'),
(4, 'img/me.png', '2026-10-20 07:09:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `myguests`
--
ALTER TABLE `myguests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nilaimhsw`
--
ALTER TABLE `nilaimhsw`
  ADD PRIMARY KEY (`nim`);

--
-- Indexes for table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id_photo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `myguests`
--
ALTER TABLE `myguests`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `photo`
--
ALTER TABLE `photo`
  MODIFY `id_photo` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
