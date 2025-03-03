-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2025 at 05:08 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `login`
--

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
  `status` enum('Pending','Confirmed','Completed','Cancelled') DEFAULT 'Pending',
  `payment_status` enum('Pending','Paid','Failed') DEFAULT 'Pending',
  `contact_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `rate_id` int(11) DEFAULT NULL,
  `rate_price` decimal(10,2) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `new_total` decimal(10,2) NOT NULL,
  `valid_amount_paid` decimal(10,2) NOT NULL,
  `reservation_code` varchar(255) DEFAULT NULL,
  `extra_pax` int(11) DEFAULT NULL,
  `extra_pax_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `check_in_date`, `check_out_date`, `check_in_time`, `check_out_time`, `reference_number`, `invoice_date`, `invoice_number`, `total_price`, `payment_receipt`, `status`, `payment_status`, `contact_number`, `created_at`, `updated_at`, `rate_id`, `rate_price`, `first_name`, `last_name`, `email`, `mobile_number`, `new_total`, `valid_amount_paid`, `reservation_code`, `extra_pax`, `extra_pax_price`) VALUES
(153, 34, '2025-03-08', '2025-03-08', '00:00:00', '00:00:00', '12343245346', '2025-03-03', 'INV-597968', 16500.00, 'payment_receipt_1741017447_5653.jpg', 'Pending', 'Pending', '096 932 1355', '2025-03-03 15:50:17', '2025-03-03 15:57:27', 73, 7500.00, 'Antoine', 'Ochea', 'antoineochea0321@gmail.com', '096 932 1355', 5125.00, 5125.00, 'DT-000187', 5, 1250.00),
(154, 34, '2025-03-14', '2025-03-15', '00:00:00', '00:00:00', '101251012510125', '2025-03-03', 'INV-518846', 20250.00, 'payment_receipt_1741017048_5484.jpg', 'Pending', 'Pending', '096 932 1355', '2025-03-03 15:50:48', '2025-03-03 15:50:48', 74, 17500.00, 'Antoine', 'Ochea', 'antoineochea0321@gmail.com', '096 932 1355', 10125.00, 10125.00, 'NT-000190', 5, 1250.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference_number` (`reference_number`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD UNIQUE KEY `reference_number_2` (`reference_number`),
  ADD UNIQUE KEY `invoice_number_2` (`invoice_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_rate_id` (`rate_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
