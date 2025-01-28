-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2025 at 03:33 PM
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
-- Table structure for table `reservation_addons`
--

CREATE TABLE `reservation_addons` (
  `reservation_id` int(11) DEFAULT NULL,
  `addon_id` int(11) DEFAULT NULL,
  `reservation_addons_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation_addons`
--

INSERT INTO `reservation_addons` (`reservation_id`, `addon_id`, `reservation_addons_id`) VALUES
(1, 17, 1),
(1, 18, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reservation_addons`
--
ALTER TABLE `reservation_addons`
  ADD PRIMARY KEY (`reservation_addons_id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `addon_id` (`addon_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reservation_addons`
--
ALTER TABLE `reservation_addons`
  MODIFY `reservation_addons_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

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
