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
-- Table structure for table `defective_reports`
--

CREATE TABLE `defective_reports` (
  `defective_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `detail` text NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `defective_reports`
--

INSERT INTO `defective_reports` (`defective_id`, `product_id`, `product_name`, `quantity`, `detail`, `date`) VALUES
(1, 1, 'Coffee', 5, 'The coffee beans were exposed to moisture or stored in a high-humidity environment, leading to mold growth and spoilage.', '2024-09-12'),
(2, 2, 'Kopiko', 15, 'การขนส่งเกิดความเสียหาย', '2024-09-13'),
(3, 3, 'Blue Hawaii ', 20, 'สินค้ารั่วไหล', '2024-09-12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `defective_reports`
--
ALTER TABLE `defective_reports`
  ADD PRIMARY KEY (`defective_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `defective_reports`
--
ALTER TABLE `defective_reports`
  MODIFY `defective_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
