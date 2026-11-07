-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2026 at 08:44 PM
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
-- Database: `grace_beauty_salon`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `salonist_id` int(10) UNSIGNED DEFAULT NULL,
  `service_id` int(10) UNSIGNED NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `customer_id`, `salonist_id`, `service_id`, `appointment_date`, `appointment_time`, `notes`, `status`, `created_at`) VALUES
(1, 13, NULL, 1, '2026-11-21', '02:26:00', 'Let me not queue kindly', 'pending', '2026-11-07 18:27:57'),
(2, 13, NULL, 2, '2026-11-14', '22:48:00', 'I know it is late but am coming!', 'pending', '2026-11-07 18:49:34');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `duration_minutes` smallint(5) UNSIGNED NOT NULL DEFAULT 60,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `duration_minutes`, `price`, `active`) VALUES
(1, 'Signature Manicure', 45, 1800.00, 1),
(2, 'Classic Pedicure', 60, 2500.00, 1),
(3, 'Wash and Blow Dry', 60, 3000.00, 1),
(4, 'Braids', 180, 8000.00, 1),
(5, 'Soft Glam Makeup', 90, 6000.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('customer','salonist','admin') NOT NULL DEFAULT 'customer',
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `otp_hash` varchar(255) DEFAULT NULL,
  `otp_expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password_hash`, `role`, `email_verified`, `otp_hash`, `otp_expires_at`, `created_at`) VALUES
(1, 'Yegon Caroline', 'yegonjcaroline@gmail.com', '0706081500', '$2y$10$akNhVeZb5C.QGP5k0IA27eHlb5WVWEJ6cspwzMhsvJm6GXLzmSzjO', 'admin', 1, NULL, NULL, '2026-11-07 15:58:13'),
(3, 'Wanjiru Mwangi', 'wanjiru.mwangi01@example.com', '0706081500', '$2y$10$5hph5oRPonp2BVkNtQF1UOdcIBaWwI.a5hP5MQF.wtAbkboob/wWO', 'salonist', 1, NULL, NULL, '2026-11-07 17:42:58'),
(5, 'Akinyi Odhiambo', 'akinyi.odhiambo02@example.com', '0700 000 102', '$2y$10$mLGojntGy/31M8JXH8XwXuFeLyMrgkaKyLn5RmkGiVvAUsPOhFWNG', 'salonist', 1, NULL, NULL, '2026-11-07 17:45:13'),
(6, 'Naliaka Wekesa', 'naliaka.wekesa03@example.com', '0700 000 103', '$2y$10$81G4RZDzZaxvBDBcCQpMk.DnQJ.wCEEprUmOr9COYvbZvfT9.zDvO', 'salonist', 1, NULL, NULL, '2026-11-07 17:46:05'),
(7, 'Wambui Kamau', 'wambui.kamau04@example.com', '0700 000 105', '$2y$10$MPC4liqBmfPR/WGxpuMtFelUp8ifwkpVkX7a5Ah9CiCHMaBFFOylC', 'customer', 0, '$2y$10$AY.xADrklkbZs09PBPcWfu7i0gvd1svjyAZ7R30dUlLXtkFq.kKzy', '2026-11-07 20:58:31', '2026-11-07 17:48:31'),
(8, 'Chebet Kiptoo', 'chebet.kiptoo05@example.com', '0700 000 105', '$2y$10$zywqdnNSUf0lLp/S7iKEV.dt/WmXoxY8meF.59hWf3VD6ggzYRfn6', 'customer', 0, '$2y$10$2Lj9D9rtmSwG5yYjbJgH0ulY6tYvA6pRwq39D/EpU.UwmXlrlBufO', '2026-11-07 21:02:25', '2026-11-07 17:52:25'),
(9, 'Amina Hassan', 'amina.hassan06@example.com', '0700 000 106', '$2y$10$GwPg3DPxk7gaZRkwZY3NcOkEt9E2a7VEe5mWIUWb3ppLwoZSFP3Lq', 'customer', 0, '$2y$10$lOUnVboQdUPPGKvnqDNGgeYQF5Q/E.vgWa7Bun9Ol7EVWYTYpX4wW', '2026-11-07 21:03:59', '2026-11-07 17:53:59'),
(10, 'Wairimu Kilonzo', 'wairimu.kilonzo07@example.com', '0700 000 107', '$2y$10$KDqB1Y5EJp0EHMu58.Ehv.hyd.qsRGwmDZGuxedt3jdwMfM7X6Pwm', 'customer', 0, '$2y$10$pwaliduOua/xkJJ7nenJG.QZFnysoyxYK6fPGlIGjF9KEc3GOkanK', '2026-11-07 21:04:53', '2026-11-07 17:54:53'),
(11, 'Muthoni Gichuru', 'muthoni.gichuru09@example.com', '0700 000 109', '$2y$10$9jiL0k7EFLvmW55vr6tjLOoXMVOcdEbnt.bc5FIzZ/bA.yvGCXqRe', 'customer', 0, '$2y$10$zzR2nogTsh6ksBU549qOjOIxLsjvSENArWsTAHA5cMsdxpWDvK/Qi', '2026-11-07 21:11:46', '2026-11-07 18:01:46'),
(12, 'Kiptoo Rotich', 'kiptoo.rotich10@example.com', '0700 000 110', '$2y$10$TwSiLk32XBua3zPvedtdbOZKL1n3V5zRPWIP38BlkrSkTMQauD4em', 'customer', 0, '$2y$10$L4N1J.dxO/VB5cHHl7BG.OJ/k/Ibbtb.YRYp.5sGYN4/4I8g0BjAW', '2026-11-07 21:14:22', '2026-11-07 18:04:22'),
(13, 'jane kangogo', 'janekangogo53@gmail.com', '0706081500', '$2y$10$QmSfA9c2ZRqopx8CCnFeoOLm6toooIzE4EnP4M9S8kWWneo3/bu8m', 'customer', 1, NULL, NULL, '2026-11-07 18:23:05'),
(14, 'jbwot y', 'jyegon@kabarak.ac.ke', '6572834', '$2y$10$7b7BiYY1mJ/G6Dc5sRVSdeWoK/dAelH.MmFk0q6BcMDhE8/EJTFCG', 'customer', 1, NULL, NULL, '2026-11-07 18:51:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_appointment_customer` (`customer_id`),
  ADD KEY `fk_appointment_salonist` (`salonist_id`),
  ADD KEY `fk_appointment_service` (`service_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `fk_appointment_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_appointment_salonist` FOREIGN KEY (`salonist_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_appointment_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
