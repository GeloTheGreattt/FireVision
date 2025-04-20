-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2025 at 03:02 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `firevision`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `username`, `created_at`, `password`) VALUES
(3, 'Admin', 'admin123', '2025-04-02 03:03:49', '$2y$10$YXopzN8KyLQetU9r1OPKlezXR/6oKUv/r9n69hObRXsOV/SyQ3k6y'),
(4, 'Gelo', 'gelo', '2025-04-20 12:32:35', '$2y$10$gUz.nZeXyPscZxPCYIeHpeiSTu.q2d5lyZmjA/nM6lJRyFkOjesRG');

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `id` int(11) NOT NULL,
  `type` enum('fire','smoke') NOT NULL,
  `confidence` decimal(5,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `alerts`
--

INSERT INTO `alerts` (`id`, `type`, `confidence`, `created_at`, `user_id`) VALUES
(1, 'fire', '0.75', '2025-02-05 01:09:06', NULL),
(2, 'fire', '0.84', '2025-02-05 01:13:02', NULL),
(3, 'smoke', '0.75', '2025-02-05 01:16:38', NULL),
(4, 'smoke', '0.75', '2025-02-05 01:21:57', NULL),
(5, 'fire', '0.51', '2025-02-06 05:50:10', NULL),
(6, 'fire', '0.51', '2025-02-06 05:57:23', NULL),
(7, 'fire', '0.51', '2025-02-06 05:58:10', NULL),
(8, 'fire', '0.51', '2025-02-06 05:58:12', NULL),
(9, 'fire', '0.51', '2025-02-06 05:58:13', NULL),
(10, 'fire', '0.51', '2025-02-06 05:58:14', NULL),
(11, 'fire', '0.51', '2025-02-06 05:59:22', NULL),
(12, 'fire', '0.82', '2025-03-11 03:25:15', NULL),
(13, 'fire', '0.70', '2025-04-02 04:04:44', NULL),
(14, 'fire', '0.70', '2025-04-02 04:14:55', NULL),
(15, 'fire', '0.80', '2025-04-02 05:42:37', NULL),
(16, 'fire', '1.00', '2025-04-04 01:16:33', NULL),
(17, 'fire', '1.00', '2025-04-04 01:48:24', NULL),
(18, 'fire', '1.00', '2025-04-04 01:51:13', NULL),
(19, 'fire', '1.00', '2025-04-04 01:54:18', NULL),
(20, 'fire', '1.00', '2025-04-04 01:56:38', NULL),
(21, 'fire', '1.00', '2025-04-04 02:09:00', NULL),
(22, 'fire', '1.00', '2025-04-04 02:13:50', NULL),
(23, 'fire', '1.00', '2025-04-04 02:18:43', NULL),
(24, 'fire', '0.93', '2025-04-07 03:09:39', NULL),
(25, 'smoke', '0.66', '2025-04-07 03:15:37', NULL),
(26, 'fire', '1.00', '2025-04-07 03:21:45', NULL),
(27, 'fire', '1.00', '2025-04-07 03:21:45', NULL),
(28, 'fire', '1.00', '2025-04-07 03:21:45', NULL),
(29, 'fire', '1.00', '2025-04-07 03:21:45', NULL),
(30, 'fire', '1.00', '2025-04-07 03:21:45', NULL),
(31, 'fire', '1.00', '2025-04-07 03:21:45', NULL),
(32, 'fire', '1.00', '2025-04-07 03:21:48', NULL),
(33, 'fire', '1.00', '2025-04-07 03:21:48', NULL),
(34, 'fire', '1.00', '2025-04-07 03:21:50', NULL),
(35, 'fire', '1.00', '2025-04-07 03:21:50', NULL),
(36, 'fire', '1.00', '2025-04-07 03:21:51', NULL),
(37, 'fire', '1.00', '2025-04-07 03:21:51', NULL),
(38, 'fire', '1.00', '2025-04-07 03:21:53', NULL),
(39, 'fire', '1.00', '2025-04-07 03:21:53', NULL),
(40, 'fire', '1.00', '2025-04-07 03:21:55', NULL),
(41, 'fire', '1.00', '2025-04-07 03:21:56', NULL),
(42, 'fire', '1.00', '2025-04-07 03:21:56', NULL),
(43, 'fire', '1.00', '2025-04-07 03:21:56', NULL),
(44, 'fire', '1.00', '2025-04-07 03:22:01', NULL),
(45, 'fire', '1.00', '2025-04-07 03:22:01', NULL),
(46, 'fire', '1.00', '2025-04-07 03:22:01', NULL),
(47, 'fire', '1.00', '2025-04-07 03:22:02', NULL),
(48, 'fire', '1.00', '2025-04-07 03:22:05', NULL),
(49, 'fire', '1.00', '2025-04-07 03:22:05', NULL),
(50, 'fire', '1.00', '2025-04-07 03:22:22', NULL),
(51, 'fire', '1.00', '2025-04-07 03:22:22', NULL),
(52, 'fire', '1.00', '2025-04-07 03:22:25', NULL),
(53, 'fire', '1.00', '2025-04-07 03:22:26', NULL),
(54, 'fire', '1.00', '2025-04-07 03:22:26', NULL),
(55, 'fire', '1.00', '2025-04-07 03:22:29', NULL),
(56, 'fire', '1.00', '2025-04-07 03:22:31', NULL),
(57, 'fire', '1.00', '2025-04-07 03:22:33', NULL),
(58, 'fire', '1.00', '2025-04-07 03:22:34', NULL),
(59, 'fire', '1.00', '2025-04-07 03:22:35', NULL),
(60, 'fire', '1.00', '2025-04-07 03:27:02', NULL),
(61, 'fire', '1.00', '2025-04-07 03:27:02', NULL),
(62, 'fire', '1.00', '2025-04-07 03:27:02', NULL),
(63, 'fire', '1.00', '2025-04-07 03:27:02', NULL),
(64, 'fire', '1.00', '2025-04-07 03:27:02', NULL),
(65, 'fire', '1.00', '2025-04-07 03:27:02', NULL),
(66, 'fire', '1.00', '2025-04-07 03:27:06', NULL),
(67, 'fire', '1.00', '2025-04-07 03:27:06', NULL),
(68, 'fire', '1.00', '2025-04-07 03:27:06', NULL),
(69, 'fire', '1.00', '2025-04-07 03:27:07', NULL),
(70, 'fire', '1.00', '2025-04-07 03:27:09', NULL),
(71, 'fire', '1.00', '2025-04-07 03:27:09', NULL),
(72, 'fire', '1.00', '2025-04-07 03:27:09', NULL),
(73, 'fire', '1.00', '2025-04-07 03:40:49', NULL),
(74, 'fire', '1.00', '2025-04-07 03:40:49', NULL),
(75, 'fire', '1.00', '2025-04-07 03:40:49', NULL),
(76, 'fire', '1.00', '2025-04-07 03:40:50', NULL),
(77, 'fire', '1.00', '2025-04-07 03:40:50', NULL),
(78, 'fire', '1.00', '2025-04-07 03:40:50', NULL),
(79, 'fire', '1.00', '2025-04-07 03:41:03', NULL),
(80, 'fire', '1.00', '2025-04-07 03:41:04', NULL),
(81, 'fire', '1.00', '2025-04-07 03:44:43', NULL),
(82, 'fire', '1.00', '2025-04-10 12:15:44', NULL),
(83, 'fire', '1.00', '2025-04-16 15:17:38', NULL),
(84, 'fire', '1.00', '2025-04-17 04:02:23', NULL),
(85, 'fire', '1.00', '2025-04-20 11:30:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `added_by_admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `created_at`, `added_by_admin_id`) VALUES
(2, 'Rey Vera', 'revyasama@gmail.com', '09453767010', '2025-02-04 16:03:41', NULL),
(5, 'Angelo Carlo Pascual', '', '09612615789', '2025-04-20 10:17:39', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`) USING BTREE;

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `added_by_admin_id` (`added_by_admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alerts`
--
ALTER TABLE `alerts`
  ADD CONSTRAINT `alerts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`added_by_admin_id`) REFERENCES `admins` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
