-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2024 at 06:42 PM
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
-- Database: `datadb`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts_setting`
--

CREATE TABLE `alerts_setting` (
  `setting_id` int(11) NOT NULL,
  `listproduct_id` int(11) NOT NULL,
  `set_expday` int(50) NOT NULL,
  `set_lowstock` int(50) NOT NULL,
  `setting_info` text NOT NULL,
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alerts_setting`
--

INSERT INTO `alerts_setting` (`setting_id`, `listproduct_id`, `set_expday`, `set_lowstock`, `setting_info`, `update_at`) VALUES
(1, 112, 7, 50, 'Exp', '2024-09-20 19:44:11'),
(2, 113, 10, 20, '', '2024-09-20 19:17:53'),
(3, 114, 14, 15, 'lw', '2024-09-20 19:18:09'),
(5, 115, 6, 50, 'Exp', '2024-09-20 19:18:23'),
(6, 112, 14, 10, '', '2024-09-20 19:38:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alerts_setting`
--
ALTER TABLE `alerts_setting`
  ADD PRIMARY KEY (`setting_id`),
  ADD KEY `listproduct_fk` (`listproduct_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alerts_setting`
--
ALTER TABLE `alerts_setting`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
