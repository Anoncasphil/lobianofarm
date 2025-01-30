-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2025 at 09:03 PM
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
-- Database: `lobianofarm`
--

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

CREATE TABLE `addons` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `picture` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addons`
--

INSERT INTO `addons` (`id`, `name`, `price`, `description`, `picture`, `created_at`, `updated_at`, `status`) VALUES
(17, 'ATV', 3000.00, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex.', 'addon_1737893342_3043.jpg', '2025-01-26 11:22:36', '2025-01-26 12:09:02', 'active'),
(18, 'Basketball Court', 1500.00, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex', 'addon_67961d63253c40.69800751.jpg', '2025-01-26 11:32:51', '2025-01-26 11:34:12', 'active'),
(19, 'Billiards', 1000.00, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex', 'addon_67961d6d8f10c5.81942875.jpg', '2025-01-26 11:33:01', '2025-01-26 11:33:01', 'active'),
(20, 'Karaoke Machine', 500.00, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex', 'addon_67961d8148efc7.96813286.jpg', '2025-01-26 11:33:21', '2025-01-26 11:33:21', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `admin_tbl`
--

CREATE TABLE `admin_tbl` (
  `admin_id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `role` enum('superadmin','admin') NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_tbl`
--

INSERT INTO `admin_tbl` (`admin_id`, `firstname`, `lastname`, `role`, `email`, `password`, `status`, `profile_picture`, `created_at`, `updated_at`) VALUES
(8, 'asdasd', 'asdasd', 'superadmin', 'antoineochea0321@gmail.com', '$2y$10$eZgSHuFLLMCMl.v73qfVAu2XgLiZpOcse8RpeM8lwaL7fSrMStHpC', 'active', 'img_677c4d93516ba0.32344697.jpg', '2025-01-06 21:39:31', '2025-01-06 21:39:31'),
(11, 'Antoine Philipp', 'Ochea', 'superadmin', 'antoineochea0912@gmail.com', '$2y$10$3S17Gihko/9cqyvZcMsa8eUi5ZJWSenC8urFZym.whHcSROVrc89y', 'active', 'img_677c4eca1c8992.47415664.jpg', '2025-01-06 21:44:42', '2025-01-07 16:49:25'),
(14, 'test', 'test', 'superadmin', 'test@gmail.com', '$2y$10$swA23ebSBiDdeNHiwGqL0.8zsVMtNxdbpFR1E0X0oOt6Cvnac/Sb.', 'active', 'img_677d54d85c5d60.23227900.jpg', '2025-01-07 16:22:48', '2025-01-07 16:22:48'),
(15, 'test1', 'test', 'admin', 'test1@gmail.com', '$2y$10$Wb89g6jdyXWZzU6GkGaVfuVwpbE8uT00Lk85Rws7ehd3tLqS.sZmi', 'active', 'img_677d5d0c62e019.37048998.jpg', '2025-01-07 16:57:48', '2025-01-07 16:57:48');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `description`, `picture`, `date`, `created_at`, `updated_at`, `status`) VALUES
(21, 'Tropical Wedding Celebration', 'Say \'I do\' in paradise with a beachfront ceremony, lush floral arrangements, and a reception under the stars, tailored to make your special day unforgettable.', 'event_677da1fa0c6fe6.02514610.jpg', '2025-01-16', '2025-01-07 21:51:54', '2025-01-07 21:51:54', 'active'),
(22, 'Elegant Birthday Bash', 'Celebrate your milestone with an exclusive party package featuring themed décor, a gourmet buffet, and entertainment options to suit all ages.', 'event_677da20c28a6d8.76075403.jpg', '2025-01-09', '2025-01-07 21:52:12', '2025-01-07 21:52:12', 'active'),
(23, 'Anniversary Dinner', 'Reignite the spark with an intimate anniversary dinner, complete with a private gazebo, candlelit ambiance, and a personalized menu.', 'event_677da223094ca6.87210810.jpg', '2025-01-24', '2025-01-07 21:52:35', '2025-01-07 21:52:35', 'active'),
(24, 'Holiday Parties', 'Celebrate the festive season with themed holiday parties, complete with seasonal menus, live entertainment, and photo-worthy decorations.', 'event_677da23766b3d4.89142317.jpg', '2025-01-23', '2025-01-07 21:52:55', '2025-01-07 21:52:55', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE `pictures` (
  `id` int(11) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `upload_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pictures`
--

INSERT INTO `pictures` (`id`, `image_name`, `image_path`, `upload_time`) VALUES
(25, 'Resort Picture 1', 'resort_1737815439.png', '2025-01-25 14:30:39'),
(26, 'Resort Picture 2', '2nd pic_1737815456.jpg', '2025-01-25 14:30:56'),
(27, 'Resort Picture 3', '3rd pic_1737815464.jpg', '2025-01-25 14:31:04'),
(28, 'Resort Picture 4', '4th pic_1737815478.jpg', '2025-01-25 14:31:18');

-- --------------------------------------------------------

--
-- Table structure for table `rates`
--

CREATE TABLE `rates` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `hoursofstay` int(11) NOT NULL,
  `checkin_time` time DEFAULT NULL,
  `checkout_time` time DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rates`
--

INSERT INTO `rates` (`id`, `name`, `price`, `description`, `hoursofstay`, `checkin_time`, `checkout_time`, `picture`, `created_at`, `updated_at`, `status`) VALUES
(58, 'Standard Stay', 15000.00, 'Standard Stay', 10, '07:00:00', '17:00:00', '1737836997_9241.jpg', '2025-01-25 20:29:57', '2025-01-25 21:15:40', 'inactive'),
(60, 'Standard Stay', 15000.00, 'Standard Stay', 10, '07:00:00', '17:00:00', '1737839779_6026.jpg', '2025-01-25 21:16:19', '2025-01-25 21:16:19', ''),
(61, 'Standard Stay', 15000.00, 'Standard StaysStandard StaysStandard StaysStandard StaysStandard StaysStandard StaysStandard Stays', 10, '08:00:00', '17:00:00', '1737840086_7902.jpg', '2025-01-25 21:17:23', '2025-01-26 00:37:27', 'active'),
(62, 'Overnight Stay', 25000.00, 'Overnight StayOvernight StayOvernight StayOvernight StayOvernight StayOvernight StayOvernight StayOvernight Stay', 12, '19:00:00', '07:00:00', '1737851953_6905.jpg', '2025-01-26 00:39:13', '2025-01-26 00:39:13', 'active'),
(63, 'Full Day Stay', 15000.00, 'Full Day StayFull Day StayFull Day StayFull Day StayFull Day StayFull Day StayFull Day StayFull Day StayFull Day Stay', 23, '08:00:00', '07:00:00', '1737852096_7362.jpg', '2025-01-26 00:41:36', '2025-01-26 00:41:36', 'active'),
(64, '3 Day Stay', 40000.00, '3 Day Stay3 Day Stay3 Day Stay3 Day Stay3 Day Stay3 Day Stay3 Day Stay', 71, '08:00:00', '07:00:00', '1737852447_7687.png', '2025-01-26 00:47:27', '2025-01-26 00:47:27', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `check_in_time` time NOT NULL,
  `check_out_time` time NOT NULL,
  `reference_number` varchar(50) NOT NULL,
  `invoice_date` date NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_receipt` varchar(255) NOT NULL,
  `status` enum('Pending','Approved','Completed') DEFAULT 'Pending',
  `payment_status` enum('Pending','Paid','Failed') DEFAULT 'Pending',
  `contact_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `rate_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservation_addons`
--

CREATE TABLE `reservation_addons` (
  `reservation_id` int(11) DEFAULT NULL,
  `addon_id` int(11) DEFAULT NULL,
  `reservation_addons_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `review_text` text NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `title`, `review_text`, `rating`, `created_at`, `updated_at`) VALUES
(1, 6, 'gsadgsda', 'gdsagdsa', 4, '2025-01-08 16:49:10', '2025-01-08 16:49:10'),
(2, 6, 'gdf', 'gdfs', 1, '2025-01-08 16:49:21', '2025-01-08 16:49:21'),
(3, 6, 'gfds', 'gdfs', 1, '2025-01-08 16:49:29', '2025-01-08 16:49:29'),
(4, 6, 'ngf', 'ndfg', 1, '2025-01-08 16:49:37', '2025-01-08 16:49:37'),
(5, 6, 'gdfsgdfg', 'gfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdg', 4, '2025-01-08 17:15:23', '2025-01-08 17:15:23'),
(6, 6, 'dsadas', 'gfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdggfdg', 1, '2025-01-08 17:29:44', '2025-01-08 17:29:44'),
(7, 23, 'asdads', 'asdasd', 5, '2025-01-23 18:04:28', '2025-01-23 18:04:28');

-- --------------------------------------------------------

--
-- Table structure for table `user_tbl`
--

CREATE TABLE `user_tbl` (
  `user_id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token_hash` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `contact_no` varchar(15) NOT NULL,
  `picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_tbl`
--

INSERT INTO `user_tbl` (`user_id`, `role`, `email`, `username`, `password`, `reset_token_hash`, `reset_token_expires_at`, `first_name`, `middle_name`, `last_name`, `contact_no`, `picture`) VALUES
(25, 'customer', 'johncabuquin02@gmail.com', '', '$2y$10$TFtncaxNvPpm1iurc/.uZeUvkncqwiRss2OJ7Xx90Nc2i5fXfOnrq', NULL, NULL, 'John Henrei', 'Asilo', 'Cabuquin', '09672634499', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addons`
--
ALTER TABLE `addons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pictures`
--
ALTER TABLE `pictures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rates`
--
ALTER TABLE `rates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference_number` (`reference_number`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_rate_id` (`rate_id`);

--
-- Indexes for table `reservation_addons`
--
ALTER TABLE `reservation_addons`
  ADD PRIMARY KEY (`reservation_addons_id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `addon_id` (`addon_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_rating` (`rating`);

--
-- Indexes for table `user_tbl`
--
ALTER TABLE `user_tbl`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `reset_token_hash` (`reset_token_hash`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addons`
--
ALTER TABLE `addons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `pictures`
--
ALTER TABLE `pictures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `rates`
--
ALTER TABLE `rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservation_addons`
--
ALTER TABLE `reservation_addons`
  MODIFY `reservation_addons_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_tbl`
--
ALTER TABLE `user_tbl`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_rate_id` FOREIGN KEY (`rate_id`) REFERENCES `rates` (`id`),
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_tbl` (`user_id`);

--
-- Constraints for table `reservation_addons`
--
ALTER TABLE `reservation_addons`
  ADD CONSTRAINT `reservation_addons_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`),
  ADD CONSTRAINT `reservation_addons_ibfk_2` FOREIGN KEY (`addon_id`) REFERENCES `addons` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
