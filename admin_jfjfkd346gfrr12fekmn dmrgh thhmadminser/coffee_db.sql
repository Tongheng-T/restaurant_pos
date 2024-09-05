-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 18, 2023 at 01:33 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coffee_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'A1', 'unavailable', '2023-07-16 09:18:13', '2023-07-16 09:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `catid` int(11) NOT NULL,
  `category` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`catid`, `category`) VALUES
(24, 'ភេជៈ'),
(25, 'តែ'),
(26, 'Coffee');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice`
--

CREATE TABLE `tbl_invoice` (
  `invoice_id` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `subtotal` double NOT NULL,
  `discount` double NOT NULL,
  `total` double NOT NULL,
  `payment_type` tinytext NOT NULL,
  `due` double NOT NULL,
  `paid` double NOT NULL,
  `saler_name` int(11) NOT NULL,
  `edit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_invoice`
--

INSERT INTO `tbl_invoice` (`invoice_id`, `order_date`, `subtotal`, `discount`, `total`, `payment_type`, `due`, `paid`, `saler_name`, `edit`) VALUES
(4, '2023-07-17', 5000, 50, 2500, 'Cash', 0, 2500, 2, 1),
(5, '2023-03-01', 185, 2, 190.55, 'Cash', -9.45, 200, 2, 0),
(7, '2023-03-02', 22550, 2, 23226.5, 'Check', 0, 23226.5, 2, 0),
(8, '2023-03-02', 1000, 2, 1030, 'Card', 0, 1030, 1, 0),
(9, '2023-03-02', 22300, 2, 22969, 'Check', 0, 22969, 2, 0),
(10, '2023-03-02', 680, 2, 700.4, 'Cash', -9.6, 710, 1, 0),
(11, '2023-03-02', 200, 2, 206, 'Cash', -14, 220, 2, 0),
(12, '2023-03-02', 25, 2, 25.75, 'Cash', -4.25, 30, 2, 0),
(13, '2023-03-02', 800, 2, 824, 'Cash', -76, 900, 2, 0),
(14, '2023-03-02', 50, 2, 51.5, 'Card', 0, 51.5, 2, 0),
(15, '2023-03-02', 50, 2, 51.5, 'Check', 0, 51.5, 1, 0),
(17, '2023-03-04', 25, 2, 25.75, 'Cash', -4.25, 30, 1, 0),
(18, '2023-03-04', 1200, 2, 1236, 'Card', 0, 1236, 1, 0),
(19, '2023-03-04', 750, 2, 772.5, 'Check', 0, 772.5, 1, 0),
(20, '2023-03-04', 340, 2, 350.2, 'Cash', 0, 350.2, 1, 0),
(21, '2023-03-04', 400, 2, 412, 'Cash', 0, 412, 2, 0),
(22, '2023-03-04', 21500, 2, 22145, 'Card', 0, 22145, 2, 0),
(23, '2023-03-06', 2920, 2, 3007.6, 'Cash', -92.4, 3100, 2, 0),
(24, '2023-03-06', 225, 2, 231.75, 'Check', 0, 231.75, 2, 0),
(26, '2023-03-07', 25, 2, 25.75, 'Cash', -4.25, 30, 2, 0),
(27, '2023-03-07', 200, 2, 206, 'Card', 0, 206, 2, 0),
(36, '2023-03-08', 1845, 2, 1900.35, 'Card', 0, 1900.35, 2, 0),
(37, '2023-03-08', 840, 2, 865.2, 'Check', 0, 865.2, 1, 0),
(38, '2023-03-08', 22550, 2, 23226.5, 'Cash', -1773.5, 25000, 1, 0),
(39, '2023-06-02', 13900, 0, 13900, 'Cash', -1100, 15000, 14, 1),
(64, '2023-07-21', 3000, 0, 3000, 'Cash', 0, 3000, 1, 0),
(65, '2023-07-21', 3000, 0, 3000, 'Cash', 0, 3000, 1, 0),
(66, '2023-08-03', 9000, 0, 9000, 'Card', 0, 9000, 1, 0),
(67, '2023-08-03', 2500, 0, 2500, 'Cash', 0, 2500, 1, 0),
(68, '2023-08-03', 3000, 0, 3000, 'Cash', 3000, 0, 1, 0),
(69, '2023-08-03', 3000, 0, 3000, 'Cash', 3000, 0, 1, 0),
(70, '2023-08-03', 5000, 0, 5000, 'Cash', 5000, 0, 1, 0),
(71, '2023-08-03', 5000, 0, 5000, 'Cash', 5000, 0, 1, 0),
(72, '2023-08-03', 5000, 0, 5000, 'Cash', 5000, 0, 1, 0),
(73, '2023-08-03', 5000, 0, 5000, 'Cash', 5000, 0, 1, 0),
(74, '2023-08-03', 2500, 0, 2500, 'Cash', 2500, 0, 1, 0),
(75, '2023-08-03', 3000, 0, 3000, 'Cash', 3000, 0, 1, 0),
(76, '2023-08-07', 3000, 0, 3000, 'Cash', 3000, 0, 1, 0),
(77, '2023-08-16', 3000, 0, 3000, 'Cash', 0, 3000, 1, 0),
(78, '2023-08-17', 28000, 0, 28000, 'Card', 0, 28000, 1, 2),
(79, '2023-08-17', 30000, 0, 30000, 'Card', 0, 30000, 2, 0),
(80, '2023-08-17', 3000, 0, 3000, 'Check', 3000, 0, 2, 0),
(81, '2023-08-17', 5000, 0, 5000, 'Check', 5000, 0, 2, 0),
(82, '2023-08-18', 18000, 0, 18000, 'Cash', -2000, 20000, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice_details`
--

CREATE TABLE `tbl_invoice_details` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `qty` int(11) NOT NULL,
  `rate` double NOT NULL,
  `saleprice` double NOT NULL,
  `order_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_invoice_details`
--

INSERT INTO `tbl_invoice_details` (`id`, `invoice_id`, `product_id`, `product_name`, `qty`, `rate`, `saleprice`, `order_date`) VALUES
(18, 7, 2, 'Kangaro Stapler', 1, 200, 200, '2023-03-02'),
(19, 7, 1, 'Kangaro Stapler Pins', 1, 50, 50, '2023-03-02'),
(20, 7, 4, 'lenovo charger', 1, 800, 800, '2023-03-02'),
(21, 7, 11, 'Real me XT', 1, 21500, 21500, '2023-03-02'),
(22, 8, 7, 'Dyna Soap', 10, 25, 250, '2023-03-02'),
(23, 8, 10, 'Wow Omega 3 Capsules', 1, 500, 500, '2023-03-02'),
(24, 8, 9, 'Modelling Comb', 1, 250, 250, '2023-03-02'),
(25, 9, 11, 'Real me XT', 1, 21500, 21500, '2023-03-02'),
(26, 9, 4, 'lenovo charger', 1, 800, 800, '2023-03-02'),
(27, 10, 12, 'Mix Spices 500gm', 1, 240, 240, '2023-03-02'),
(28, 10, 3, 'kissan tomato katchup', 1, 160, 160, '2023-03-02'),
(29, 10, 6, 'Suger Packet 5 KG', 1, 200, 200, '2023-03-02'),
(30, 10, 5, 'Veg Burger', 1, 80, 80, '2023-03-02'),
(31, 11, 2, 'Kangaro Stapler', 1, 200, 200, '2023-03-02'),
(32, 12, 7, 'Dyna Soap', 1, 25, 25, '2023-03-02'),
(33, 13, 4, 'lenovo charger', 1, 800, 800, '2023-03-02'),
(34, 14, 1, 'Kangaro Stapler Pins', 1, 50, 50, '2023-03-02'),
(35, 15, 1, 'Kangaro Stapler Pins', 1, 50, 50, '2023-03-02'),
(38, 17, 7, 'Dyna Soap', 1, 25, 25, '2023-03-04'),
(39, 18, 3, 'kissan tomato katchup', 1, 160, 160, '2023-03-04'),
(40, 18, 4, 'lenovo charger', 1, 800, 800, '2023-03-04'),
(41, 18, 12, 'Mix Spices 500gm', 1, 240, 240, '2023-03-04'),
(42, 19, 10, 'Wow Omega 3 Capsules', 1, 500, 500, '2023-03-04'),
(43, 19, 9, 'Modelling Comb', 1, 250, 250, '2023-03-04'),
(44, 20, 6, 'Suger Packet 5 KG', 1, 200, 200, '2023-03-04'),
(45, 20, 8, 'Pepsodent Toothpaste', 1, 140, 140, '2023-03-04'),
(46, 21, 3, 'kissan tomato katchup', 1, 160, 160, '2023-03-04'),
(47, 21, 12, 'Mix Spices 500gm', 1, 240, 240, '2023-03-04'),
(48, 22, 11, 'Real me XT', 1, 21500, 21500, '2023-03-04'),
(94, 5, 7, 'Dyna Soap', 1, 25, 25, '2023-03-01'),
(95, 5, 3, 'kissan tomato katchup', 1, 160, 160, '2023-03-01'),
(100, 23, 3, 'kissan tomato katchup', 2, 160, 320, '2023-03-06'),
(101, 23, 1, 'Kangaro Stapler Pins', 2, 50, 100, '2023-03-06'),
(102, 23, 10, 'Wow Omega 3 Capsules', 5, 500, 2500, '2023-03-06'),
(103, 24, 7, 'Dyna Soap', 1, 25, 25, '2023-03-06'),
(104, 24, 6, 'Suger Packet 5 KG', 1, 200, 200, '2023-03-06'),
(115, 26, 7, 'Dyna Soap', 1, 25, 25, '2023-03-07'),
(116, 27, 2, 'Kangaro Stapler', 1, 200, 200, '2023-03-07'),
(162, 36, 7, 'Dyna Soap', 1, 25, 25, '2023-03-08'),
(163, 36, 2, 'Kangaro Stapler', 1, 200, 200, '2023-03-08'),
(164, 36, 1, 'Kangaro Stapler Pins', 1, 50, 50, '2023-03-08'),
(165, 36, 3, 'kissan tomato katchup', 1, 160, 160, '2023-03-08'),
(166, 36, 10, 'Wow Omega 3 Capsules', 1, 500, 500, '2023-03-08'),
(167, 36, 5, 'Veg Burger', 1, 80, 80, '2023-03-08'),
(168, 36, 6, 'Suger Packet 5 KG', 1, 200, 200, '2023-03-08'),
(169, 36, 8, 'Pepsodent Toothpaste', 1, 140, 140, '2023-03-08'),
(170, 36, 9, 'Modelling Comb', 1, 250, 250, '2023-03-08'),
(171, 36, 12, 'Mix Spices 500gm', 1, 240, 240, '2023-03-08'),
(172, 37, 6, 'Suger Packet 5 KG', 1, 200, 200, '2023-03-08'),
(173, 37, 10, 'Wow Omega 3 Capsules', 1, 500, 500, '2023-03-08'),
(174, 37, 8, 'Pepsodent Toothpaste', 1, 140, 140, '2023-03-08'),
(175, 38, 4, 'lenovo charger', 1, 800, 800, '2023-03-08'),
(176, 38, 9, 'Modelling Comb', 1, 250, 250, '2023-03-08'),
(177, 38, 11, 'Real me XT', 1, 21500, 21500, '2023-03-08'),
(238, 48, 7, 'Dyna Soap', 1, 2500, 2500, '2023-06-02'),
(239, 48, 3, 'kissan tomato katchup', 1, 3000, 3000, '2023-06-02'),
(240, 47, 2, 'Kangaro Stapler', 1, 4000, 4000, '2023-06-02'),
(241, 46, 7, 'Dyna Soap', 1, 2500, 2500, '2023-06-02'),
(242, 45, 7, 'Dyna Soap', 3, 2500, 7500, '2023-06-02'),
(243, 45, 1, 'Kangaro Stapler Pins', 2, 2500, 5000, '2023-06-02'),
(253, 43, 1, 'Kangaro Stapler Pins', 3, 2500, 7500, '2023-06-02'),
(254, 43, 2, 'Kangaro Stapler', 1, 4000, 4000, '2023-06-02'),
(255, 43, 8, 'Pepsodent Toothpaste', 1, 1400, 1400, '2023-06-02'),
(256, 43, 7, 'Dyna Soap', 1, 2500, 2500, '2023-06-02'),
(257, 43, 9, 'Modelling Comb', 1, 25000, 25000, '2023-06-02'),
(259, 41, 1, 'Kangaro Stapler Pins', 5, 2500, 12500, '2023-06-02'),
(260, 41, 10, 'Wow Omega 3 Capsules', 1, 5000, 5000, '2023-06-02'),
(261, 40, 10, 'Wow Omega 3 Capsules', 1, 5000, 5000, '2023-06-02'),
(262, 40, 9, 'Modelling Comb', 1, 25000, 25000, '2023-06-02'),
(263, 44, 2, 'Kangaro Stapler', 3, 4000, 12000, '2023-06-02'),
(264, 44, 3, 'kissan tomato katchup', 1, 3000, 3000, '2023-06-02'),
(265, 44, 8, 'Pepsodent Toothpaste', 1, 1400, 1400, '2023-06-02'),
(266, 44, 10, 'Wow Omega 3 Capsules', 1, 5000, 5000, '2023-06-02'),
(267, 42, 2, 'Kangaro Stapler', 1, 4000, 4000, '2023-06-02'),
(268, 39, 3, 'kissan tomato katchup', 1, 3000, 3000, '2023-06-02'),
(269, 39, 1, 'Kangaro Stapler Pins', 1, 2500, 2500, '2023-06-02'),
(270, 39, 8, 'Pepsodent Toothpaste', 1, 1400, 1400, '2023-06-02'),
(271, 39, 10, 'Wow Omega 3 Capsules', 1, 5000, 5000, '2023-06-02'),
(272, 39, 6, 'Suger Packet 5 KG', 1, 2000, 2000, '2023-06-02'),
(273, 49, 2, 'Kangaro Stapler', 1, 4000, 4000, '2023-07-13'),
(274, 49, 3, 'kissan tomato katchup', 1, 3000, 3000, '2023-07-13'),
(275, 50, 1, 'Kangaro Stapler Pins', 1, 2500, 2500, '2023-07-13'),
(276, 50, 3, 'kissan tomato katchup', 7, 3000, 21000, '2023-07-13'),
(277, 51, 1, 'Kangaro Stapler Pins', 1, 2500, 2500, '2023-07-13'),
(278, 52, 25, 'black coffee', 1, 5000, 5000, '2023-07-13'),
(294, 54, 25, 'black coffee', 1, 5000, 5000, '2023-07-14'),
(295, 54, 3, 'kissan tomato katchup', 1, 3000, 3000, '2023-07-14'),
(298, 53, 3, 'kissan tomato katchup', 1, 3000, 3000, '2023-07-14'),
(299, 56, 5, 'Veg Burger', 1, 5000, 5000, '2023-07-14'),
(300, 56, 26, 'Sushi', 1, 20000, 20000, '2023-07-14'),
(301, 56, 25, 'black coffee', 1, 5000, 5000, '2023-07-14'),
(303, 58, 25, 'black coffee', 1, 5000, 5000, '2023-07-14'),
(304, 59, 5, 'Veg Burger', 1, 5000, 5000, '2023-07-14'),
(305, 4, 26, 'Sushi', 1, 20000, 20000, '2023-07-14'),
(309, 4, 5, 'Veg Burger', 1, 5000, 5000, '2023-07-17'),
(311, 76, 3, 'kissan tomato katchup', 1, 3000, 3000, '2023-08-07'),
(312, 77, 3, 'kissan tomato katchup', 1, 3000, 3000, '2023-08-16'),
(323, 78, 3, 'kissan tomato katchup', 1, 3000, 3000, '2023-08-17'),
(324, 78, 25, 'black coffee', 1, 5000, 5000, '2023-08-17'),
(325, 78, 26, 'Sushi', 1, 20000, 20000, '2023-08-17'),
(327, 80, 0, '', 0, 0, 0, '2023-08-17'),
(328, 81, 25, 'black coffee', 1, 5000, 5000, '2023-08-17'),
(338, 82, 25, 'black coffee', 1, 5000, 5000, '2023-08-18'),
(339, 82, 1, 'Kangaro Stapler Pins', 1, 5000, 5000, '2023-08-18'),
(340, 82, 3, 'black coffee', 1, 4000, 4000, '2023-08-18'),
(341, 82, 2, 'iced-latte', 1, 4000, 4000, '2023-08-18');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `pid` int(11) NOT NULL,
  `barcode` varchar(1000) NOT NULL,
  `product` varchar(200) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` varchar(200) NOT NULL,
  `saleprice` float NOT NULL,
  `image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`pid`, `barcode`, `product`, `category_id`, `description`, `saleprice`, `image`) VALUES
(1, '8901057510028', 'green tea', 25, 'no 10 mm     ', 5000, '64df45dc45fb0.png'),
(2, '8901057310062', 'iced-latte', 26, 'no 10    ', 4000, '64df4c24d9fde.png'),
(3, '8901030865237', 'black coffee', 26, '1  ', 4000, '64df4646438b2.png'),
(5, '5120819', 'ដូងក្រឡុក', 25, 'small p        ', 5000, '64df4c77aba61.png'),
(6, '6121422', 'Butterfly Tea', 25, ' ', 5000, '64df4c46b2fa8.png'),
(25, '25122418', 'តែមេអំបៅដំឡូងស្វាយ', 25, ' ', 5000, '64df4c8db4473.png'),
(26, '26122300', 'passion soda', 25, '   ', 5000, '64df4c5ce07b0.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_taxdis`
--

CREATE TABLE `tbl_taxdis` (
  `taxdis_id` int(11) NOT NULL,
  `discount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_taxdis`
--

INSERT INTO `tbl_taxdis` (`taxdis_id`, `discount`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `useremail` varchar(200) NOT NULL,
  `userpassword` varchar(200) NOT NULL,
  `img` text NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `username`, `useremail`, `userpassword`, `img`, `role`) VALUES
(1, 'Admin', 'admin@gmail.com', '12345', '6749939_preview.png', 'Admin'),
(2, 'user', 'user@gmail.com', '123', 'user.png', 'User'),
(14, 'test', 'test@gmail.com', '123', 'user.png', 'User');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`catid`);

--
-- Indexes for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `tbl_invoice_details`
--
ALTER TABLE `tbl_invoice_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `tbl_taxdis`
--
ALTER TABLE `tbl_taxdis`
  ADD PRIMARY KEY (`taxdis_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `catid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `tbl_invoice_details`
--
ALTER TABLE `tbl_invoice_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=342;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_taxdis`
--
ALTER TABLE `tbl_taxdis`
  MODIFY `taxdis_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
