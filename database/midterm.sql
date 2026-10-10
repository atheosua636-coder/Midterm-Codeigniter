-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2026 at 01:04 PM
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
-- Database: `midterm`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `full_name`, `email`, `phone`, `created_at`) VALUES
(1, 'John Romero', 'jromero@gmail.com', NULL, '2026-10-10 11:01:51');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `stock_quantity`, `image`, `created_at`) VALUES
(1, '1-Piece Fried Chicken Meal', 129.00, 40, '97ce474006fc351f67830a921e98efca.png', '2026-10-10 18:20:28'),
(2, '2-Piece Fried Chicken Meal', 219.00, 25, 'fd390ac27e511342df5f89c6b512bd06.png', '2026-10-10 18:20:28'),
(3, '2-Piece Spicy Chicken Meal', 229.00, 30, '5093f513127397a603b2e36f2874c03d.png', '2026-10-10 18:20:28'),
(5, 'Chicken Sandwich', 89.00, 25, '5b06eb9e2db202f44ad18d1251c75abe.png', '2026-10-10 18:20:28'),
(6, '4-Piece Chicken Wings', 199.00, 20, 'a6cd962a0d8cf756855356b3679827bf.png', '2026-10-10 18:20:28'),
(7, '10-Piece Chicken Wings', 319.00, 12, 'd86ee57399a1b5d2cf4b8a2fb6db280a.png', '2026-10-10 18:20:28'),
(8, '6-Piece Chicken Nuggets', 79.00, 20, 'a3b8265bed30bb50e334e6357bed5a17.png', '2026-10-10 18:20:28'),
(9, 'Chicken Sisig Rice Meal', 139.00, 18, '3a857f2a39dd77738101ffd62a3329b6.png', '2026-10-10 18:20:28'),
(10, 'Extra Rice', 25.00, 60, '285e6a3c7c1a69a0a56abb3c6c848df6.png', '2026-10-10 18:20:28'),
(11, 'French Fries', 59.00, 25, '76318873e2558ffe83caaa599e44db4e.png', '2026-10-10 18:20:28'),
(12, 'Gravy Cup', 15.00, 50, 'c628ecbdc272952c934be36c28dda59f.png', '2026-10-10 18:20:28'),
(13, 'Iced Tea', 35.00, 40, '68a76c6ad4207620fe21eef19bf08618.png', '2026-10-10 18:20:28'),
(14, 'Bottled Water', 20.00, 30, 'a4cd45f8bc2c24a5ab090a771d0bf683.png', '2026-10-10 18:20:28');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `sold_by` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `full_name`, `password`, `avatar`, `created_at`) VALUES
(1, 'admin', 'Atheo Carl C. Sua', '$2y$10$nUH7JkxhBVwv8UEX.F2u6.i/qRmzzS5oJ0by/yStf.D2YMYwnZ3ly', '8985e644d202868c7de173274dd074ee.jpg', '2026-10-10 00:57:08'),
(2, 'manager', 'Ifan Jon Maniquiz', '$2y$10$G2rwBs1hCEeX0zUSJia39.nYAW1.rm3DjxWfLQaAyB5/mVc4Mxpzi', 'fb3533634f0d0a4144eaf4b98c8410cf.jpg', '2026-10-10 10:56:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `sold_by` (`sold_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `sales_ibfk_3` FOREIGN KEY (`sold_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
