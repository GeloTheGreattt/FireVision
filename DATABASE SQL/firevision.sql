-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2025 at 04:32 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `created_at`, `password`) VALUES
(2, 'Admin', 'admin@gmail.com', '2025-02-04 15:56:26', '$2y$10$b2rNRnsraV4bDkKd1KG/GuxT6cB4SROcGQPspi/e9y6JBQMZbM7T2'),
(3, 'Admin123', 'admin123@gmail.com', '2025-04-02 03:03:49', '$2y$10$YXopzN8KyLQetU9r1OPKlezXR/6oKUv/r9n69hObRXsOV/SyQ3k6y'),
(4, 'Zachary Wynn', 'vyleduniw@mailinator.com', '2025-04-07 14:18:22', '$2y$10$CEDZG28mCfIFLbCuV0txCeFq3fNdIFp7jZMDxCBNhEUg1bWvUhzHS');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alerts`
--

INSERT INTO `alerts` (`id`, `type`, `confidence`, `created_at`, `user_id`) VALUES
(1, 'fire', 0.75, '2025-02-05 01:09:06', NULL),
(2, 'fire', 0.84, '2025-02-05 01:13:02', NULL),
(3, 'smoke', 0.75, '2025-02-05 01:16:38', NULL),
(4, 'smoke', 0.75, '2025-02-05 01:21:57', NULL),
(5, 'fire', 0.51, '2025-02-06 05:50:10', NULL),
(6, 'fire', 0.51, '2025-02-06 05:57:23', NULL),
(7, 'fire', 0.51, '2025-02-06 05:58:10', NULL),
(8, 'fire', 0.51, '2025-02-06 05:58:12', NULL),
(9, 'fire', 0.51, '2025-02-06 05:58:13', NULL),
(10, 'fire', 0.51, '2025-02-06 05:58:14', NULL),
(11, 'fire', 0.51, '2025-02-06 05:59:22', NULL),
(12, 'fire', 0.82, '2025-03-11 03:25:15', NULL),
(13, 'fire', 0.70, '2025-04-02 04:04:44', NULL),
(14, 'fire', 0.70, '2025-04-02 04:14:55', NULL),
(15, 'fire', 0.80, '2025-04-02 05:42:37', NULL),
(16, 'fire', 1.00, '2025-04-04 01:16:33', NULL),
(17, 'fire', 1.00, '2025-04-04 01:48:24', NULL),
(18, 'fire', 1.00, '2025-04-04 01:51:13', NULL),
(19, 'fire', 1.00, '2025-04-04 01:54:18', NULL),
(20, 'fire', 1.00, '2025-04-04 01:56:38', NULL),
(21, 'fire', 1.00, '2025-04-04 02:09:00', NULL),
(22, 'fire', 1.00, '2025-04-04 02:13:50', NULL),
(23, 'fire', 1.00, '2025-04-04 02:18:43', NULL),
(24, 'fire', 1.00, '2025-04-07 06:09:45', NULL),
(25, 'fire', 1.00, '2025-04-07 13:14:32', NULL),
(26, 'fire', 1.00, '2025-04-07 13:18:22', NULL),
(27, 'fire', 1.00, '2025-04-07 13:20:10', NULL),
(28, 'fire', 1.00, '2025-04-07 13:25:49', NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `created_at`, `added_by_admin_id`) VALUES
(1, 'Tanya Danillo', 'tanyadanillo@gmail.com', '09651765634', '2025-02-04 12:03:42', NULL),
(2, 'Rey Vera', 'revyasama@gmail.com', '09760346040', '2025-02-04 16:03:41', NULL),
(4, 'Nari', 'claudelex347@gmail.com', '09667468076', '2025-02-06 05:42:08', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

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
