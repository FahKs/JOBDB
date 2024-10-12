-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2024 at 04:21 AM
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
(2, 113, 10, 6, '', '2024-10-11 18:29:19'),
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
  `quantity_set` int(11) NOT NULL,
  `product_pic` blob NOT NULL,
  `update_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `visible` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `list_product`
--

INSERT INTO `list_product` (`listproduct_id`, `product_name`, `category`, `price_set`, `product_info`, `quantity_set`, `product_pic`, `update_at`, `visible`) VALUES
(1, 'Espresso Blend ', 'Blend', 156, 'เมล็ดกาแฟคั่ว Espresso Blend สูตร 2 250 G.', 10, 0x33306261636635363638663237343136666634383335373139303164356338352e706e67, '2024-10-05 14:04:27', 1),
(2, 'Arabica Special ', 'Blend', 162, 'เมล็ดกาแฟคั่ว Arabica Special 250 G.\r\n', 10, 0x32326465396530353639356362326663653030663736653431623835653930302e706e67, '2024-10-05 13:03:29', 1),
(4, 'Gold Blend', 'Blend', 156, 'เมล็ดกาแฟคั่ว Gold Blend 250 G.', 10, 0x37613338306464363162653735326634316135336363616662336262643033652e706e67, '2024-10-04 21:34:11', 1),
(5, ' Aroma Espresso Blend ', ' Blend ', 164, 'เมล็ดกาแฟคั่ว\r\n\r\n⤷Aroma Original Blend', 10, 0x63356363636339623537306462396439356239363531386638306431326338662e706e67, '2024-10-05 13:05:10', 1),
(14, 'French Blend', 'Blend', 199, 'เมล็ดกาแฟคั่ว French Blend 250 G.', 10, 0x61623539666138356664373264386262353965363762646364666533643230652e706e67, '2024-10-05 13:05:11', 1),
(15, 'All Day Blend ', 'Blend', 141, 'เมล็ดกาแฟคั่ว All Day Blend 250 G.', 10, 0x33316238643162366335353330323532323531616630373234623036323832352e706e67, '2024-10-05 13:05:11', 1),
(18, 'Honney', 'Syrup', 159, '', 20, 0x31663136613366643630396539623663363939653034663861396165366265652e706e67, '2024-10-05 13:05:12', 1);

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
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `store_id` int(11) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `total_sets` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_status` enum('จัดส่ง','รอตรวจสอบ','รายงานปัญหา','ส่งคืน','สินค้าเสียหาย','สินค้าอยู่ในคลัง') DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `delivery_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `store_id`, `order_date`, `total_sets`, `total_price`, `order_status`, `customer_name`, `delivery_date`) VALUES
(1, 1, '2024-10-12 00:46:43', 150, 250.00, 'จัดส่ง', 'Taylor Swift', '2024-10-11 19:11:20'),
(3, 1, '2024-10-12 00:13:05', 2, 324.00, 'รอตรวจสอบ', 'Taylor Swift', '2024-10-14 18:06:41'),
(4, 1, '2024-10-11 23:53:55', 5, 952.00, 'ส่งคืน', 'Taylor Swift', '2024-10-14 18:12:47');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `listproduct_id` int(11) DEFAULT NULL,
  `quantity_sets` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `detail_location` varchar(100) NOT NULL,
  `tel_store` char(10) NOT NULL,
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`store_id`, `store_name`, `location_store`, `detail_location`, `tel_store`, `update_at`) VALUES
(1, 'Botoko Kan', 'กาญจนบุรี', '', '087556423', '2024-10-03 18:23:23'),
(2, 'Botoko Kampangsang', 'นครปฐม', '', '0417879445', '2024-10-03 18:23:31'),
(3, 'Botoko BK', 'กรุงเทพมหานคร', '', '0977878456', '2024-10-03 18:23:38'),
(4, 'Botoko CMU', 'เชียงใหม่', '', '0977878448', '2024-10-03 18:23:45'),
(5, 'Botoko Kabi', 'กระบี่', '', '0066647588', '2024-10-03 18:25:39'),
(6, 'Botoko Chantaburi', 'จันทบุรี', '', '0999432513', '2024-10-03 18:25:45'),
(7, 'Botoko Chainat', 'ชัยนาท', '', '0815420037', '2024-10-05 05:42:44');

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
(1, 'Pay', 'Chamket', 'pay@gmail.com', 'A!8jsdF$2', '0995890209', 'Admin', 0, '2024-10-05 00:39:16'),
(2, 'Kaning', 'Sasreesom', 'kaning@gmail.com', '12345678', '0987878456', 'Manager', 4, '2024-10-04 22:02:34'),
(3, 'Kaiwan', 'Chanchai', 'kaiwan@gmail.com', '12345678', '0868789945', 'Staff', 1, '2024-10-04 20:30:35'),
(4, 'KK', 'SS', 'kk@gmail.com', '12345678', '09782141', 'Manager', 1, '2024-10-04 20:30:35'),
(10, 'Bruno', 'Mars', 'bruno@gmail.com', '12345678', '0878784501', 'Staff', 1, '2024-10-04 20:31:42'),
(11, 'Taylor', 'Swift', 'taylor@gmail.com', '12345678', '0818774581', 'Manager', 1, '2024-10-04 20:43:29'),
(13, 'Max', 'Keshi', 'max@gmail.com', '12345678', '0818524399', 'Staff', 1, '2024-10-04 20:47:40'),
(14, 'Peter', 'pan', 'peter@gmail.com', '12345678', '0124567865', 'Staff', 4, '2024-10-04 22:03:38'),
(15, 'pp', 'krit', 'pp@gmail.com', '1245678', '0888888884', 'Staff', 1, '2024-10-05 12:31:11'),
(16, 'Anna', 'Sangrajui', 'anna@gmail.com', '12345678', '024169458', 'Manager', 1, '2024-10-05 12:38:21');

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `listproduct_id` (`listproduct_id`);

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
  MODIFY `listproduct_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `orderlist_reports`
--
ALTER TABLE `orderlist_reports`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `requisitioned_reports`
--
ALTER TABLE `requisitioned_reports`
  MODIFY `product_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `store_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1126;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`);

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`listproduct_id`) REFERENCES `list_product` (`listproduct_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
