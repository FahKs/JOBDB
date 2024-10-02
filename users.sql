-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2024 at 06:43 PM
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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `tel` char(10) NOT NULL,
  `position` varchar(10) NOT NULL,
  `store_id` int(10) NOT NULL,
  `update_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `surname`, `email`, `password`, `tel`, `position`, `store_id`, `update_at`) VALUES
(1, 'Pay', 'Chamket', 'pay@gmail.com', '12345678', '0999999999', 'Admin', 0, '2024-10-02 23:36:03'),
(2, 'Kaning', 'Sasreesom', 'kaning@gmail.com', '12345678', '0987878456', 'Manager', 0, '2024-10-02 23:35:59'),
(3, 'Kaiwan', 'Chanchai', 'kaiwan@gmail.com', '12345678', '0868789945', 'Staff', 0, '2024-10-02 23:35:53'),
(4, 'KK', 'SS', 'kk@gmail.com', '12345678', '09782141', 'Manager', 0, '2024-10-02 23:35:48'),
(5, 'Louis', 'Oliv', 'louis@gmail.com', '12345678', '0774545123', 'Admin', 0, '2024-10-02 23:35:42'),
(6, 'Bruno', 'Mars', 'bruno@gmail.com', '12345678', '0845182248', 'Admin', 0, '2024-10-02 23:35:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `storeid_fk` (`store_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
