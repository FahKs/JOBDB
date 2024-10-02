-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2024 at 06:41 PM
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
(1, 'Espresso Blend ', 'Blend', 156, 'เมล็ดกาแฟคั่ว Espresso Blend สูตร 2 250 G.', 10, 0x33306261636635363638663237343136666634383335373139303164356338352e706e67, '2024-09-28 04:06:37', 1),
(2, 'Arabica Special ', 'Blend', 162, 'เมล็ดกาแฟคั่ว Arabica Special 250 G.\r\n', 10, 0x32326465396530353639356362326663653030663736653431623835653930302e706e67, '2024-09-28 13:18:32', 0),
(4, 'Gold Blend', 'Blend', 156, 'เมล็ดกาแฟคั่ว Gold Blend 250 G.', 10, 0x37613338306464363162653735326634316135336363616662336262643033652e706e67, '2024-09-28 13:18:33', 0),
(5, ' Aroma Espresso Blend ', ' Blend ', 164, 'เมล็ดกาแฟคั่ว\r\n\r\n⤷Aroma Original Blend', 10, 0x63356363636339623537306462396439356239363531386638306431326338662e706e67, '2024-09-28 13:18:35', 0),
(14, 'French Blend', 'Blend', 199, 'เมล็ดกาแฟคั่ว French Blend 250 G.', 10, 0x61623539666138356664373264386262353965363762646364666533643230652e706e67, '2024-09-28 13:18:38', 0),
(15, 'All Day Blend ', 'Blend', 141, 'เมล็ดกาแฟคั่ว All Day Blend 250 G.', 10, 0x33316238643162366335353330323532323531616630373234623036323832352e706e67, '2024-09-28 13:18:39', 0);

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
  MODIFY `listproduct_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
