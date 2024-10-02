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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `list_product`
--
ALTER TABLE `list_product`
  ADD PRIMARY KEY (`listproduct_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `list_product`
--
ALTER TABLE `list_product`
  MODIFY `listproduct_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
