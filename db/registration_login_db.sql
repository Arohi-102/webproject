-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2025 at 04:26 PM
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
-- Database: `registration_login_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) GENERATED ALWAYS AS (`quantity` * `price`) STORED,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(255) DEFAULT NULL,
  `bank_slip` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `id`, `quantity`, `price`, `added_at`, `payment_method`, `bank_slip`) VALUES
(1, 2, 11, 1, 30000.00, '2025-03-11 12:43:18', NULL, NULL),
(2, 2, 15, 1, 1200.00, '2025-03-11 12:43:18', NULL, NULL),
(3, 8, 13, 1, 30000.00, '2025-03-19 11:31:23', NULL, NULL),
(4, 8, 16, 1, 2500.00, '2025-03-19 11:31:23', NULL, NULL),
(5, 8, 16, 1, 2500.00, '2025-03-19 12:12:54', NULL, NULL),
(6, 8, 12, 1, 30000.00, '2025-03-19 12:12:54', NULL, NULL),
(8, 8, 12, 1, 30000.00, '2025-03-19 12:20:56', NULL, NULL),
(9, 8, 16, 1, 2500.00, '2025-03-19 12:22:17', NULL, NULL),
(10, 8, 12, 1, 30000.00, '2025-03-19 12:22:17', NULL, NULL),
(11, 8, 16, 1, 2500.00, '2025-03-19 12:27:23', NULL, NULL),
(12, 8, 12, 1, 30000.00, '2025-03-19 12:27:23', NULL, NULL),
(13, 8, 16, 1, 2500.00, '2025-03-19 12:30:49', NULL, NULL),
(14, 8, 12, 1, 30000.00, '2025-03-19 12:30:49', NULL, NULL),
(15, 8, 16, 1, 2500.00, '2025-03-19 12:42:59', NULL, NULL),
(16, 8, 12, 1, 30000.00, '2025-03-19 12:42:59', NULL, NULL),
(17, 8, 13, 1, 30000.00, '2025-03-19 12:57:54', NULL, NULL),
(18, 8, 22, 1, 1320.00, '2025-03-19 12:57:54', NULL, NULL),
(19, 8, 15, 1, 1200.00, '2025-03-19 13:21:32', NULL, NULL),
(20, 8, 16, 1, 2500.00, '2025-03-19 13:21:32', NULL, NULL),
(22, 8, 11, 1, 30000.00, '2025-03-19 13:22:45', NULL, NULL),
(23, 8, 18, 1, 1000.00, '2025-03-19 13:32:17', NULL, NULL),
(24, 8, 18, 1, 1000.00, '2025-03-19 13:35:05', NULL, NULL),
(25, 8, 12, 1, 30000.00, '2025-03-19 13:35:05', NULL, NULL),
(26, 2, 15, 1, 1200.00, '2025-03-19 14:13:16', NULL, NULL),
(27, 4, 15, 1, 1200.00, '2025-03-19 14:15:05', NULL, NULL),
(28, 2, 15, 1, 1200.00, '2025-03-20 06:57:10', NULL, NULL),
(29, 2, 15, 1, 1200.00, '2025-03-20 06:59:11', NULL, NULL),
(31, 2, 15, 1, 1200.00, '2025-03-20 07:09:09', NULL, NULL),
(32, 11, 15, 1, 1200.00, '2025-03-20 07:11:04', NULL, NULL),
(33, 2, 26, 1, 14500.00, '2025-03-22 01:23:02', NULL, NULL),
(34, 2, 26, 1, 14500.00, '2025-03-22 01:23:08', NULL, NULL),
(35, 2, 26, 1, 14500.00, '2025-03-22 01:23:11', NULL, NULL),
(36, 2, 26, 1, 14500.00, '2025-03-22 01:23:44', NULL, NULL),
(37, 2, 26, 1, 14500.00, '2025-03-22 01:24:00', NULL, NULL),
(38, 2, 11, 1, 1500.00, '2025-03-22 01:25:31', NULL, NULL),
(39, 2, 11, 1, 1500.00, '2025-03-22 01:28:35', NULL, NULL),
(40, 2, 13, 1, 30000.00, '2025-03-22 01:32:07', NULL, NULL),
(41, 2, 13, 1, 30000.00, '2025-03-22 01:32:26', NULL, NULL),
(42, 2, 13, 1, 30000.00, '2025-03-22 01:32:27', NULL, NULL),
(43, 2, 13, 1, 30000.00, '2025-03-22 01:32:29', NULL, NULL),
(44, 2, 13, 1, 30000.00, '2025-03-22 01:34:37', NULL, NULL),
(45, 2, 13, 1, 30000.00, '2025-03-22 01:34:39', NULL, NULL),
(46, 2, 13, 1, 30000.00, '2025-03-22 01:40:35', NULL, NULL),
(47, 2, 15, 1, 1200.00, '2025-03-22 03:47:49', NULL, NULL),
(48, 2, 15, 1, 1200.00, '2025-03-22 03:49:49', NULL, NULL),
(49, 2, 13, 1, 30000.00, '2025-03-22 03:50:42', NULL, NULL),
(50, 2, 13, 1, 30000.00, '2025-03-22 03:56:06', NULL, NULL),
(51, 2, 18, 1, 1000.00, '2025-03-22 03:59:37', NULL, NULL),
(52, 2, 13, 1, 30000.00, '2025-03-22 04:13:48', NULL, NULL),
(53, 2, 11, 1, 1500.00, '2025-03-22 04:17:19', NULL, NULL),
(54, 2, 11, 1, 1500.00, '2025-03-22 04:17:20', NULL, NULL),
(55, 2, 11, 1, 1500.00, '2025-03-22 04:17:29', NULL, NULL),
(56, 2, 11, 1, 1500.00, '2025-03-22 04:25:29', NULL, NULL),
(57, 2, 11, 1, 1500.00, '2025-03-22 04:26:54', NULL, NULL),
(58, 2, 13, 1, 30000.00, '2025-03-22 05:05:26', NULL, NULL),
(59, 2, 11, 1, 1500.00, '2025-03-22 05:08:13', NULL, NULL),
(60, 2, 13, 1, 30000.00, '2025-03-22 05:15:52', NULL, NULL),
(61, 2, 13, 1, 30000.00, '2025-03-22 06:14:32', NULL, NULL),
(62, 2, 13, 1, 30000.00, '2025-03-22 06:25:52', NULL, NULL),
(63, 2, 13, 1, 30000.00, '2025-03-22 06:27:25', NULL, NULL),
(64, 2, 15, 1, 1200.00, '2025-03-22 06:36:33', NULL, NULL),
(65, 2, 13, 1, 30000.00, '2025-03-22 06:37:33', 'cash-on-delivery', ''),
(66, 2, 13, 1, 30000.00, '2025-03-22 06:37:35', 'cash-on-delivery', ''),
(67, 2, 13, 1, 30000.00, '2025-03-22 06:37:35', 'cash-on-delivery', ''),
(68, 2, 13, 1, 30000.00, '2025-03-22 06:40:13', 'cash-on-delivery', ''),
(69, 2, 13, 1, 30000.00, '2025-03-22 06:40:14', 'cash-on-delivery', ''),
(70, 2, 13, 1, 30000.00, '2025-03-22 06:40:17', 'cash-on-delivery', ''),
(71, 2, 13, 1, 30000.00, '2025-03-22 06:40:17', 'cash-on-delivery', ''),
(72, 2, 13, 1, 30000.00, '2025-03-22 06:40:18', 'cash-on-delivery', ''),
(73, 2, 13, 1, 30000.00, '2025-03-22 06:40:19', 'cash-on-delivery', ''),
(74, 2, 13, 1, 30000.00, '2025-03-22 06:40:19', 'cash-on-delivery', ''),
(75, 2, 13, 1, 30000.00, '2025-03-22 06:40:20', 'cash-on-delivery', ''),
(76, 2, 13, 1, 30000.00, '2025-03-22 06:40:21', 'cash-on-delivery', ''),
(81, 2, 13, 1, 30000.00, '2025-03-22 07:01:01', 'bank-transfer', 'iVBORw0KGgoAAAANSUhEUgAAApsAAAF2CAYAAAAhoFOlAAAAAXNSR0IArs4c6QAAIABJREFUeF7svQmQXdlZ5/k/y733vZd7KjOVSq21qxappCrXXnaVt7YHmwCajmgiCJrFYDBmMe5h66Fp6GA6Yuieng2mmegZYAII6BhMszTdGDC28VK2a6+SatGeUkq5vFzeevdzzvCdc29mSqXaS3KW676woixlvnfv/d7LfL/3/77//2OoblUFqgpUFag'),
(82, 11, 11, 1, 1500.00, '2025-03-22 08:00:00', 'cash-on-delivery', ''),
(83, 4, 15, 1, 1200.00, '2025-03-22 10:17:52', 'bank-transfer', 'iVBORw0KGgoAAAANSUhEUgAAApsAAAF2CAYAAAAhoFOlAAAAAXNSR0IArs4c6QAAIABJREFUeF7svQmQXdlZ5/k/y733vZd7KjOVSq21qxappCrXXnaVt7YHmwCajmgiCJrFYDBmMe5h66Fp6GA6Yuieng2mmegZYAII6BhMszTdGDC28VK2a6+SatGeUkq5vFzeevdzzvCdc29mSqXaS3KW676woixlvnfv/d7LfL/3/77//2OoblUFqgpUFag'),
(84, 4, 15, 1, 1200.00, '2025-03-22 10:17:55', 'bank-transfer', 'iVBORw0KGgoAAAANSUhEUgAAApsAAAF2CAYAAAAhoFOlAAAAAXNSR0IArs4c6QAAIABJREFUeF7svQmQXdlZ5/k/y733vZd7KjOVSq21qxappCrXXnaVt7YHmwCajmgiCJrFYDBmMe5h66Fp6GA6Yuieng2mmegZYAII6BhMszTdGDC28VK2a6+SatGeUkq5vFzeevdzzvCdc29mSqXaS3KW676woixlvnfv/d7LfL/3/77//2OoblUFqgpUFag');

-- --------------------------------------------------------

--
-- Table structure for table `deliver_addreses`
--

CREATE TABLE `deliver_addreses` (
  `address_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `Address_no_1` varchar(255) NOT NULL,
  `Province` varchar(255) NOT NULL,
  `Phone_Number` int(15) NOT NULL,
  `Postal_code` int(20) NOT NULL,
  `order_status` varchar(50) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deliver_addreses`
--

INSERT INTO `deliver_addreses` (`address_id`, `cart_id`, `name`, `email`, `Address_no_1`, `Province`, `Phone_Number`, `Postal_code`, `order_status`) VALUES
(1, 60, 'M.Mala', 'kala@gmail.com', '11/4 keppatpola polonnaruwa', 'western', 1123554547, 10200, 'pending'),
(2, 61, '', '', '', '', 0, 0, 'pending'),
(3, 62, '', '', '', '', 0, 0, 'pending'),
(4, 63, 'M.Mala', 'mala@gmail.com', '11/4 keppatpola polonnaruwa', 'western', 1123554547, 10200, 'pending'),
(5, 64, '', '', '', '', 0, 0, 'pending'),
(6, 65, 'M.Mala', 'mala@gmail.com', '11/4 keppatpola polonnaruwa', 'western', 1123554547, 10200, 'pending'),
(7, 66, 'M.Mala', 'mala@gmail.com', '11/4 keppatpola polonnaruwa', 'western', 1123554547, 10200, 'pending'),
(8, 67, 'M.Mala', 'mala@gmail.com', '11/4 keppatpola polonnaruwa', 'western', 1123554547, 10200, 'pending'),
(9, 68, 'M.Mala', 'mala@gmail.com', '11/4 keppatpola polonnaruwa', 'western', 1123554547, 10200, 'pending'),
(10, 69, 'M.Mala', 'mala@gmail.com', '11/4 keppatpola polonnaruwa', 'western', 1123554547, 10200, 'pending'),
(11, 70, 'M.Mala', 'mala@gmail.com', '11/4 keppatpola polonnaruwa', 'western', 1123554547, 10200, 'pending'),
(12, 71, 'M.Mala', 'mala@gmail.com', '11/4 keppatpola polonnaruwa', 'western', 1123554547, 10200, 'pending'),
(13, 72, 'M.Mala', 'mala@gmail.com', '11/4 keppatpola polonnaruwa', 'western', 1123554547, 10200, 'pending'),
(14, 73, 'M.Mala', 'mala@gmail.com', '11/4 keppatpola polonnaruwa', 'western', 1123554547, 10200, 'pending'),
(15, 74, 'M.Mala', 'mala@gmail.com', '11/4 keppatpola polonnaruwa', 'western', 1123554547, 10200, 'pending'),
(16, 75, 'M.Mala', 'mala@gmail.com', '11/4 keppatpola polonnaruwa', 'western', 1123554547, 10200, 'pending'),
(17, 76, 'M.Mala', 'mala@gmail.com', '11/4 keppatpola polonnaruwa', 'western', 1123554547, 10200, 'pending'),
(18, 77, 'M.Mala', 'mala@gmail.com', '11/4 keppatpola polonnaruwa', 'western', 1123554547, 10200, 'pending'),
(19, 78, '', '', '11/4 keppatpola polonnaruwa', '', 1123554547, 0, 'pending'),
(20, 79, '', '', '11/4 keppatpola polonnaruwa', '', 1123554547, 0, 'pending'),
(21, 80, '', '', '', '', 0, 0, 'pending'),
(22, 81, 'User Name', 'kangkan@email.com', '11/4 keppatpola polonnaruwa', 'western', 112530454, 10200, 'pending'),
(23, 82, 'mala ', 'newuser@gmail.com', '11/2 keppatpola polonnaruwa', 'western', 112784578, 10200, 'pending'),
(24, 83, 'M.D.S.Arosha', 'lorem.ipsum@gmail.com', '11/4 keppatpola polonnaruwa', 'western', 1234552, 10210, 'pending'),
(25, 84, 'M.D.S.Arosha', 'lorem.ipsum@gmail.com', '11/4 keppatpola polonnaruwa', 'western', 1234552, 10210, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `question`, `answer`) VALUES
(3, 'How do I contact support?', 'Through live chat, email, or a support ticket.'),
(4, 'What devices do you repair?', ' Laptops, desktops, printers, and IT equipment.'),
(5, 'How to request a repair?', 'Submit online, call us, or visit our center.'),
(6, 'How long does a repair take?', 'Usually 3–5 business days.'),
(7, 'Is there a warranty on repairs?', ' Yes, warranty varies by service.'),
(8, 'What parts do you sell?', 'computer / laptop parts '),
(9, 'aadsds', 'dasds');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category`, `image`, `created_at`) VALUES
(11, 'GPU', NULL, 1500.00, 'GPU', 'uploads/CPU.png', '2025-02-23 18:29:42'),
(12, 'GPU', NULL, 30000.00, 'GPU', 'uploads/67bb6919e47460.74644424.png', '2025-02-23 18:29:45'),
(13, 'GPU', NULL, 30000.00, 'GPU', 'uploads/67bb6975e4e3d7.85793704.png', '2025-02-23 18:31:17'),
(15, 'cable', NULL, 1200.00, 'Storage', 'uploads/67c5eb19bed354.54481704.png', '2025-03-03 17:47:06'),
(16, 'RAM 12', NULL, 2500.00, 'RAM', 'uploads/67c6defa4470c5.70240389.png', '2025-03-04 11:07:38'),
(17, 'GPU', NULL, 3400.00, 'GPU', 'uploads/67c73eb1cf6446.13109179.png', '2025-03-04 17:56:01'),
(18, 'GPU', NULL, 1000.00, 'GPU', 'uploads/67caa1da92b893.56641302.png', '2025-03-07 07:35:54'),
(20, 'ssd', NULL, 1300.00, 'Storage', 'uploads/ram.png', '2025-03-07 10:55:55'),
(21, 'SSD', NULL, 1300.00, 'Storage', 'uploads/SSD.png', '2025-03-07 10:57:48'),
(22, 'ssd', NULL, 1320.00, 'Storage', 'uploads/SSD.png', '2025-03-09 17:28:45'),
(26, 'CPU', NULL, 14500.00, 'CPU', 'uploads/CPU.png', '2025-03-21 08:13:32'),
(27, 'SSD', NULL, 1200.00, 'Storage', 'uploads/SSD.png', '2025-03-22 03:46:43'),
(29, 'GPU', NULL, 1200.00, 'GPU', 'uploads/SSD.png', '2025-03-22 04:11:39');

-- --------------------------------------------------------

--
-- Table structure for table `repair_requests`
--

CREATE TABLE `repair_requests` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `category` varchar(50) NOT NULL,
  `issue` text NOT NULL,
  `service_type` varchar(50) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `scheduled_time` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `repair_requests`
--

INSERT INTO `repair_requests` (`id`, `name`, `email`, `phone`, `category`, `issue`, `service_type`, `address`, `submitted_at`, `scheduled_time`, `user_id`) VALUES
(1, 'bandula siri ', 'lorem.ipsum@gmail.com', '0112542542', 'hardware', 'haerdware issue', 'home', '11/2 keppatpola polonnaruwa.', '2025-03-21 10:36:15', '2025-03-04 16:22:00', 4),
(2, 'M.D.S.A.Dharmapriya', 'lorem.ipsum@gmail.com', '0112542547', 'software', 'error message', 'remote', '', '2025-03-21 11:06:59', '2025-03-14 19:38:00', 4),
(3, 'M.D.S.A.Dharmapriya', 'lorem.ipsum@gmail.com', '14252536', 'network', 'Trubleshooting ', 'remote', '', '2025-03-22 11:34:32', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `priority` varchar(20) NOT NULL,
  `issue` text NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('New','Open','Closed') DEFAULT 'New',
  `solution` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `user_id`, `name`, `email`, `category`, `priority`, `issue`, `file`, `submitted_at`, `status`, `solution`) VALUES
(1, 2, 'M.D.S.A.Dharmapriya', 'aroshadharmapriya99@gmail.com', 'software', 'medium', 'any problems', 'uploads/42.png', '2025-03-20 18:06:39', 'Closed', 'check it'),
(2, 4, 'bandula siri ', 'lorem.ipsum@gmail.com', 'hardware', 'medium', 'Hardware problem', 'uploads/download.jpg', '2025-03-21 10:43:05', 'Open', 'check it'),
(3, 2, 'arohi dhamz', 'arohidhamz99@gmail.com', 'hardware', 'medium', 'Hard is dynamic', '', '2025-03-21 11:47:39', 'Open', ''),
(4, 11, 'bandula siri ', 'johndoe@helpdesk.com', 'other', 'low', 'i don\'t know what error to computer ', NULL, '2025-03-21 11:59:49', 'Open', ''),
(5, 2, 'M.D.S.A.Dharmapriya', 'lorem.ipsum@gmail.com', 'hardware', 'low', 'asdddsd', NULL, '2025-03-21 12:02:14', 'New', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `user_type` enum('user','admin','representative') NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `address` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `email`, `contact_number`, `user_type`, `password`, `created_at`, `address`, `reset_token`, `reset_token_expires`) VALUES
(1, 'User Name 01', 'admin@webdamn.com', '0112312117', 'admin', '$2y$10$U.u43gAjGWTiULNMC4CzM.vVE6k7.Pgo8.bs8YpeN/S6mzdxhgGP.', '2024-12-31 04:53:02', '11/2 keppatpola polonnaruwa.', '8069840261b6800701635c74a59cf9dfd937dae049880c0dc66990254c92fe02bf985ceb1f74576545a470adb89c0077e33f', '2025-03-09 08:47:58'),
(2, 'User Name', 'kangkan@email.com', '0112530454', 'user', '$2y$10$JeM/etG.0IFnoqRAAee.eubz2uRXbQXZxPHwtvYzuFFs8v3TB1yke', '2025-02-13 10:36:38', '11/4 keppatpola polonnaruwa', NULL, NULL),
(3, 'User Name 1', 'aroshadharmapriya99@gmail.com', '5156611', 'representative', '$2y$10$v9ROLkXw1O/W8l1tPcZVPe1jmedyNHsDMCxTTLdAx71pLEbIeYho2', '2025-02-13 10:40:14', '45/1 wallawa piliyandala', '47337851ce02c5f3ee1f8dcf87dd8d1f3b7f698e8dfc6d8332d7f024fd95ada3bfe0710e863dbc1080431d367f1a1a8b7d46', '2025-03-10 07:58:01'),
(4, 'M.D.S.Arosha', 'lorem.ipsum@gmail.com', '1234552', 'user', '$2y$10$UNrFtnZTsnJNAOcPETKOe.dr8ZUcQTiRAHaYBHjyKKFk0VZrN63Ci', '2025-02-19 17:17:08', '125/2 keppatpola polonnaruwa', 'f2994c8ce974ba121385b4d908d8989f5453070baec29c756712ef833469ec5da54d53f5c8d32c298b3ac724c9c43b3c806c', '2025-03-09 07:58:39'),
(6, 'K.D.Shayama', 'shama@gmail.com', '0124455253', 'user', '$2y$10$bOlbTJ75.829FdDLriIsourXS0kAutQyHSUeLoGn0xRiIdJsvSoIq', '2025-03-07 18:27:48', '11/2 keppatpola polonnaruwa.', NULL, NULL),
(7, 'User Name 2', 'seetharanjani633@gmail.com', '0124455253', 'user', '$2y$10$J2rRClRU/Ix43ycWgNshQ.QnnFUtHuHwrDH1xKtfjMkoqD5R0w48.', '2025-03-09 04:29:33', '11/2 keppatpola polonnaruwa.', NULL, NULL),
(8, 'hiran', '123@gmail.com', '0775879456', 'user', '$2y$10$BG48B7OUBulvQq9EPdI9qO3FSCivRgj1AH9yYd3gr6N4IBm404YGG', '2025-03-19 15:55:09', 'kandy', NULL, NULL),
(9, 'un', '456@gmail.com', '077777777', 'user', '$2y$10$5s3clBcURXpRjBUBAUVtMOAtS.dbEeGK5QdWOujk7kZlhYI2PDEjK', '2025-03-19 18:12:06', 'kandy', NULL, NULL),
(10, 'Nimal lanthara', 'arohidhamz99@gmail.com', '0112312115', 'user', '$2y$10$weOMyCYPBYchxA6RL9ld0.qpkXlpyNdCKFM4w8R5ebTV5vy31cnAq', '2025-03-20 06:37:41', '11/2.papiliyana,kadawatha', NULL, NULL),
(11, 'new user', 'newuser@gmail.com', '0112784578', 'user', '$2y$10$xM9BkHYxpcbdAlY/NRch.O2Hi7dzeWe66mtg/arQwBYc4zbXbuTDu', '2025-03-20 06:57:16', '2/1 wallawa piliyandala', NULL, NULL),
(12, 'new user1', 'new@gmail.com', '0118754658', 'user', '$2y$10$0WeGCUtIDUy5ecbVahYHFOo/xRpKhhryJcfkro0OxH6mheRAmtJca', '2025-03-20 07:23:38', 'no,5 kadana', NULL, NULL),
(13, 'User Name 1', 'johndoe@helpdesk.com', '0112312115', 'user', '$2y$10$90.vg1XCv.MWso2fjWQ6mORxvVD3kyeIA2aI9YgaiM4OWfNSSQJCa', '2025-03-21 14:48:36', '11/2 keppatpola polonnaruwa.', NULL, NULL),
(14, 'Padma Nilmini', 'padma@gmail.com', '0124455252', 'user', '$2y$10$WSqBPvy/0SfmvLjVZVOKPuLugbmo9avipSxEajT6LBussBmHvdF42', '2025-03-21 17:59:56', '11/2 keppatpola polonnaruwa.', NULL, NULL),
(15, 'shriya', 'shriya@gmail.com', '0112312115', 'user', '$2y$10$tnzUyD4iJrKw4XSATXRvP.d8HOfjNNntmn7Z3.XLgmo0XZURXDh7a', '2025-03-21 18:07:09', '11/2 keppatpola polonnaruwa.', NULL, NULL),
(16, 'Charlis perera', 'charlis@gmail.com', '0124455252', 'user', '$2y$10$YD2quoyLYsFTmJjqV9m0L.J7k4GC2Snmys9e66vP3MJovetbfEQM2', '2025-03-21 18:13:08', '11/2.papiliyana,kadawatha', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`,`user_id`,`added_at`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `deliver_addreses`
--
ALTER TABLE `deliver_addreses`
  ADD PRIMARY KEY (`address_id`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `repair_requests`
--
ALTER TABLE `repair_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `deliver_addreses`
--
ALTER TABLE `deliver_addreses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `repair_requests`
--
ALTER TABLE `repair_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product` FOREIGN KEY (`id`) REFERENCES `products` (`id`);

--
-- Constraints for table `repair_requests`
--
ALTER TABLE `repair_requests`
  ADD CONSTRAINT `repair_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
