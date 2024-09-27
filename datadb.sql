-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2024 at 12:47 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `inventory_reports`
--

CREATE TABLE `inventory_reports` (
  `product_id` int(10) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `inventory_value` int(11) NOT NULL,
  `first_date` date NOT NULL,
  `update_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_reports`
--

INSERT INTO `inventory_reports` (`product_id`, `product_name`, `category`, `quantity`, `inventory_value`, `first_date`, `update_at`) VALUES
(1, 'Coffee', 'beans', 1000, 10, '2024-09-10', '2024-09-12 22:45:07'),
(2, 'Tea', 'Teaa', 100, 10, '2024-09-12', '2024-09-13 14:21:06'),
(3, 'Honey', 'Topping', 500, 25, '2024-09-12', '2024-09-13 14:21:10');

-- --------------------------------------------------------

--
-- Table structure for table `list_product`
--

CREATE TABLE `list_product` (
  `listproduct_id` int(10) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `price_set` decimal(10,0) NOT NULL,
  `product_info` text NOT NULL,
  `qauntity_set` int(11) NOT NULL,
  `product_pic` blob NOT NULL,
  `update_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `visible` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `list_product`
--

INSERT INTO `list_product` (`listproduct_id`, `product_name`, `category`, `price_set`, `product_info`, `qauntity_set`, `product_pic`, `update_at`, `visible`) VALUES
(1, 'Coffee', 'beans', 1600, 'เมล็ดกาแฟยี่ห้อNikky', 10, 0x62366565636132383639386134313734383830373464343434383535306162312e706e67, '2024-09-26 21:24:19', 1),
(2, 'Tea', 'Teaa', 100, 'fff', 15, 0x64313238323261663234663134616261316163656362643265303739353634372e706e67, '2024-09-21 13:03:54', 0),
(4, 'Honney', 'topping', 500, '', 25, 0x39626161353135306664383431323831656662663937383235356464396436622e706e67, '2024-09-21 12:57:50', 0),
(5, 'Blue Hawaii', 'Syrup', 450, '', 10, 0x38613336666266306134333963353232636539653735316439353966643837312e706e67, '2024-09-21 13:00:24', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orderlist_reports`
--

CREATE TABLE `orderlist_reports` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `quantity_sets` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderlist_reports`
--

INSERT INTO `orderlist_reports` (`order_detail_id`, `order_id`, `product_id`, `product_name`, `category`, `quantity_sets`, `total`, `unit_price`, `subtotal`) VALUES
(1, 1, 1, 'Coffee', 'beans', 10, 100, 15.00, 15000.00);

-- --------------------------------------------------------

--
-- Table structure for table `requisitioned_reports`
--

CREATE TABLE `requisitioned_reports` (
  `product_id` int(10) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `user_id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `tel` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requisitioned_reports`
--

INSERT INTO `requisitioned_reports` (`product_id`, `product_name`, `category`, `quantity`, `user_id`, `name`, `position`, `tel`) VALUES
(1, 'Coffee', 'beans', 20, 1, 'Pay', 'Admin', 906793322);

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `store_id` int(10) NOT NULL,
  `store_name` varchar(50) NOT NULL,
  `location_store` varchar(200) NOT NULL,
  `tel_store` char(10) NOT NULL,
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`store_id`, `store_name`, `location_store`, `tel_store`, `update_at`) VALUES
(123, 'Botoko Kan', 'Kanchanaburi', '087556423', '2024-09-20 19:07:40'),
(124, 'Botoko Kampangsang', 'Nakonpathom', '0417879445', '2024-09-27 10:22:58'),
(125, 'Botoko Rachaburi', 'Rachaburi', '078432121', '2024-09-27 10:23:07');

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
(1, 'Pay', 'Chamket', 'pay@gmail.com', '12345678', '0999999999', 'Admin', 123, '2024-09-27 17:23:18'),
(2, 'Kaning', 'Sasreesom', 'kaning@gmail.com', '12345678', '0987878456', 'Manager', 1122, '2024-09-21 01:02:51'),
(3, 'Kaiwan', 'Chanchai', 'kaiwan@gmail.com', '12345678', '0868789945', 'Staff', 1123, '2024-09-21 01:03:00'),
(4, 'KK', 'SS', 'kk@gmail.com', '12345678', '09782141', 'Manager', 1124, '2024-09-21 13:05:03'),
(5, 'Louis', 'Oliv', 'louis@gmail.com', '12345678', '0774545123', 'Admin', 1125, '2024-09-21 01:03:25'),
(6, 'Bruno', 'Mars', 'bruno@gmail.com', '12345678', '0845182248', 'Admin', 1126, '2024-09-21 01:03:34');

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
-- Indexes for table `defective_reports`
--
ALTER TABLE `defective_reports`
  ADD PRIMARY KEY (`defective_id`);

--
-- Indexes for table `inventory_reports`
--
ALTER TABLE `inventory_reports`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `list_product`
--
ALTER TABLE `list_product`
  ADD PRIMARY KEY (`listproduct_id`);

--
-- Indexes for table `orderlist_reports`
--
ALTER TABLE `orderlist_reports`
  ADD PRIMARY KEY (`order_detail_id`);

--
-- Indexes for table `requisitioned_reports`
--
ALTER TABLE `requisitioned_reports`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`store_id`);

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
-- AUTO_INCREMENT for table `alerts_setting`
--
ALTER TABLE `alerts_setting`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `defective_reports`
--
ALTER TABLE `defective_reports`
  MODIFY `defective_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory_reports`
--
ALTER TABLE `inventory_reports`
  MODIFY `product_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `list_product`
--
ALTER TABLE `list_product`
  MODIFY `listproduct_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orderlist_reports`
--
ALTER TABLE `orderlist_reports`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `requisitioned_reports`
--
ALTER TABLE `requisitioned_reports`
  MODIFY `product_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `store_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1122;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
