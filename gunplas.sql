-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 03, 2025 at 07:36 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gunpla_webapi`
--

-- --------------------------------------------------------

--
-- Table structure for table `gunplas`
--

CREATE TABLE `gunplas` (
  `id` int(10) UNSIGNED NOT NULL,
  `sku` varchar(32) NOT NULL,
  `name` varchar(200) NOT NULL,
  `grade` varchar(50) NOT NULL,
  `series` varchar(150) NOT NULL,
  `scale` varchar(10) NOT NULL,
  `price` decimal(10,2) NOT NULL CHECK (`price` >= 0),
  `stock` int(11) NOT NULL DEFAULT 0 CHECK (`stock` >= 0),
  `release_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gunplas`
--

INSERT INTO `gunplas` (`id`, `sku`, `name`, `grade`, `series`, `scale`, `price`, `stock`, `release_date`, `created_at`, `updated_at`) VALUES
(1, 'HG-010', 'RX-78-2 Gundam', 'HG', 'Mobile Suit Gundam', '1/144', 650.00, 30, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(2, 'RG-001', 'RX-78-2 Gundam', 'RG', 'Mobile Suit Gundam', '1/144', 1800.00, 15, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(3, 'MG-001', 'RX-78-2 Gundam Ver.3.0', 'MG', 'Mobile Suit Gundam', '1/100', 4500.00, 8, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(4, 'PG-001', 'RX-78-2 Gundam', 'PG', 'Mobile Suit Gundam', '1/60', 12000.00, 3, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(5, 'HG-200', 'Gundam Aerial', 'HG', 'Mobile Suit Gundam: The Witch from Mercury', '1/144', 800.50, 42, NULL, '2025-10-03 05:04:00', '2025-10-03 05:33:07'),
(6, 'MG-190', 'Freedom Gundam Ver.2.0', 'MG', 'Gundam SEED', '1/100', 5200.00, 12, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(7, 'RG-030', 'Nu Gundam', 'RG', 'Mobile Suit Gundam: Char\'s Counterattack', '1/144', 2900.00, 20, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(8, 'SD-050', 'Exia Gundam', 'SD', 'Gundam 00', 'Non-Scale', 350.00, 50, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(9, 'HG-191', 'Barbatos Lupus Rex', 'HG', 'Gundam Iron-Blooded Orphans', '1/144', 1200.00, 25, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(10, 'MG-222', 'Sazabi Ver. Ka', 'MG', 'Mobile Suit Gundam: Char\'s Counterattack', '1/100', 9800.00, 5, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(11, 'RG-025', 'Unicorn Gundam', 'RG', 'Mobile Suit Gundam Unicorn', '1/144', 2500.00, 18, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(12, 'HG-090', 'Wing Gundam Zero', 'HG', 'Gundam W', '1/144', 700.00, 35, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(13, 'MG-100', 'Zeta Gundam Ver.2.0', 'MG', 'Mobile Suit Z Gundam', '1/100', 4900.00, 7, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(14, 'SDEX-003', 'Strike Freedom Gundam', 'SD', 'Gundam SEED Destiny', 'Non-Scale', 400.00, 45, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(15, 'RG-035', 'Hi-Nu Gundam', 'RG', 'Mobile Suit Gundam: Char\'s Counterattack', '1/144', 3500.00, 10, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(16, 'HG-210', 'Lfrith', 'HG', 'Mobile Suit Gundam: The Witch from Mercury', '1/144', 850.00, 28, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(17, 'MG-200', 'Gundam Epyon (EW)', 'MG', 'Gundam W', '1/100', 4800.00, 9, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(18, 'PG-060', 'Unicorn Gundam', 'PG', 'Mobile Suit Gundam Unicorn', '1/60', 16000.00, 2, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(19, 'HG-120', 'Zaku II (Char)', 'HG', 'Mobile Suit Gundam', '1/144', 600.00, 32, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(20, 'RG-005', 'Zaku II (Char)', 'RG', 'Mobile Suit Gundam', '1/144', 1950.00, 14, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(21, 'MG-088', 'Gundam F91', 'MG', 'Gundam F91', '1/100', 3800.00, 11, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(22, 'SDCS-005', 'Crossbone Gundam X1', 'SD', 'Crossbone Gundam', 'Non-Scale', 550.00, 22, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(23, 'HG-055', 'Gundam Exia', 'HG', 'Gundam 00', '1/144', 800.00, 38, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(24, 'RG-017', 'Wing Gundam Zero Custom', 'RG', 'Gundam W: Endless Waltz', '1/144', 2200.00, 16, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(25, 'MG-150', 'Sengoku Astray Gundam', 'MG', 'Gundam Build Fighters', '1/100', 5500.00, 6, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(26, 'HG-220', 'Darilbalde', 'HG', 'Mobile Suit Gundam: The Witch from Mercury', '1/144', 900.00, 27, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(27, 'RG-040', 'Sazabi', 'RG', 'Mobile Suit Gundam: Char\'s Counterattack', '1/144', 3900.00, 13, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(28, 'MG-180', 'Gundam Heavyarms Custom (EW)', 'MG', 'Gundam W: Endless Waltz', '1/100', 4200.00, 10, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(29, 'HG-075', 'God Gundam', 'HG', 'G Gundam', '1/144', 1100.00, 24, NULL, '2025-10-03 05:04:00', '2025-10-03 05:04:00'),
(31, 'FM-001', 'Full Mechanics 1/100 Aerial', 'FM', 'Mobile Suit Gundam: The Witch from Mercury', '1/100', 3200.00, 15, NULL, '2025-10-03 05:31:42', '2025-10-03 05:31:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gunplas`
--
ALTER TABLE `gunplas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gunplas`
--
ALTER TABLE `gunplas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
