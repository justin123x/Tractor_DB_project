-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2026 at 04:44 AM
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
-- Database: `tracktor_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(50) NOT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`log_id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 10, 'payment', 'payment', 13, '{\"booking_id\":12,\"amount\":44,\"method\":\"credit_card\",\"transaction_ref\":\"TXN17782464236129\",\"source\":\"customer_portal\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-08 13:20:23'),
(2, 10, 'payment', 'payment', 14, '{\"booking_id\":12,\"amount\":4278,\"method\":\"cash\",\"transaction_ref\":\"TXN17782464398405\",\"source\":\"customer_portal\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-08 13:20:39'),
(3, 11, 'create', 'tractor', 12, '{\"name\":\"sdad\",\"brand\":\"sd,ansdk\",\"status\":\"available\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:03:30'),
(4, 11, 'delete', 'tractor', 12, '{\"name\":\"sdad\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:10:43'),
(5, 11, 'delete', 'tractor', 11, '{\"name\":\"sadas\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:10:47'),
(6, 11, 'delete', 'tractor', 4, '{\"name\":\"WorkHorse 700\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:10:51'),
(7, 11, 'delete', 'tractor', 3, '{\"name\":\"CompactX 100\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:11:06'),
(8, 11, 'delete', 'tractor', 7, '{\"name\":\"CropKing 400\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:11:10'),
(9, 11, 'delete', 'tractor', 6, '{\"name\":\"GreenKeeper 50\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:11:12'),
(10, 11, 'delete', 'tractor', 5, '{\"name\":\"IndustriTitan 900\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:11:14'),
(11, 11, 'delete', 'tractor', 8, '{\"name\":\"MiniForce 75\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:11:17'),
(12, 11, 'delete', 'tractor', 9, '{\"name\":\"QWE\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:11:20'),
(13, 11, 'update', 'tractor', 2, '{\"name\":\"RowMaster 30032\",\"brand\":\"Case IH\",\"status\":\"booked\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:11:27'),
(14, 11, 'delete', 'tractor', 2, '{\"name\":\"RowMaster 30032\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:11:34'),
(15, 11, 'delete', 'tractor', 10, '{\"name\":\"wdad\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:11:38'),
(16, 11, 'create', 'tractor', 13, '{\"name\":\"RiceField Pro 55\",\"brand\":\"Kubota\",\"status\":\"available\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:24:53'),
(17, 11, 'create', 'tractor', 14, '{\"name\":\"AgriPower 75\",\"brand\":\"New Holland\",\"status\":\"available\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:26:20'),
(18, 11, 'create', 'tractor', 15, '{\"name\":\"7630\",\"brand\":\"Massey Ferguson\",\"status\":\"available\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:28:14'),
(19, 11, 'update', 'tractor', 14, '{\"name\":\"AgriPower 75\",\"brand\":\"New Holland\",\"status\":\"available\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:30:20'),
(20, 11, 'create', 'tractor', 16, '{\"name\":\"GreenLand 50\",\"brand\":\"John Deere\",\"status\":\"available\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:32:09'),
(21, 11, 'create', 'tractor', 17, '{\"name\":\"Harvest King 85\",\"brand\":\"Mahindra\",\"status\":\"available\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:33:30'),
(22, 11, 'create', 'tractor', 18, '{\"name\":\"SoilMaster 60\",\"brand\":\"Sonalika\",\"status\":\"available\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:34:54'),
(23, 11, 'create', 'tractor', 19, '{\"name\":\"Agro Beast 90\",\"brand\":\"Case IH\",\"status\":\"available\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:37:38'),
(24, 11, 'create', 'tractor', 20, '{\"name\":\"Field Warrior 45\",\"brand\":\"Yanmar\",\"status\":\"available\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:39:43'),
(25, 11, 'create', 'tractor', 21, '{\"name\":\"Terra Farmer 70\",\"brand\":\"Foton\",\"status\":\"available\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:41:06'),
(26, 11, 'create', 'tractor', 22, '{\"name\":\"CropMover 80\",\"brand\":\"Deutz-Fahr\",\"status\":\"available\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:42:33'),
(27, 11, 'delete', 'tractor', 21, '{\"name\":\"Terra Farmer 70\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-05-09 02:42:50');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `tractor_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `booking_date` date NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `rental_type` enum('hourly','daily') DEFAULT 'daily',
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','confirmed','active','completed','cancelled') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`, `created_at`) VALUES
(1, 'Utility Tractors', 'General-purpose tractors for everyday farming tasks', '2026-05-05 15:10:05'),
(2, 'Row Crop Tractors', 'Designed for row crop farming with high clearance', '2026-05-05 15:10:05'),
(3, 'Garden Tractors', 'Smaller tractors for garden and lawn maintenance', '2026-05-05 15:10:05'),
(4, 'Compact Tractors', 'Small versatile tractors for light-duty work', '2026-05-05 15:10:05'),
(5, 'Industrial Tractors', 'Heavy-duty tractors for construction and industrial use', '2026-05-05 15:10:05');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `first_name`, `last_name`, `email`, `phone`, `address`, `city`, `state`, `zip_code`, `created_at`, `updated_at`) VALUES
(1, 'Robert', 'Johnson', 'robert.j@email.com', '555-0101', '123 Farm Road', 'Springfield', 'IL', '62701', '2026-05-05 15:10:05', '2026-05-05 15:10:05'),
(2, 'Mary', 'Williams', 'mary.w@email.com', '555-0102', '456 Oak Avenue', 'Shelbyville', 'IL', '62565', '2026-05-05 15:10:05', '2026-05-05 15:10:05'),
(3, 'James', 'Brown', 'james.b@email.com', '555-0103', '789 Elm Street', 'Capital City', 'IL', '62702', '2026-05-05 15:10:05', '2026-05-05 15:10:05'),
(4, 'Patricia', 'Davis', 'patricia.d@email.com', '555-0104', '321 Pine Lane', 'Ogden', 'IL', '61859', '2026-05-05 15:10:05', '2026-05-05 15:10:05'),
(5, 'Michael', 'Miller', 'michael.m@email.com', '555-0105', '654 Maple Drive', 'Shelbyville', 'IL', '62566', '2026-05-05 15:10:05', '2026-05-05 15:10:05'),
(6, 'Linda', 'Wilson', 'linda.w@email.com', '555-0106', '987 Cedar Court', 'Springfield', 'IL', '62703', '2026-05-05 15:10:05', '2026-05-05 15:10:05'),
(7, 'David', 'Moore', 'david.m@email.com', '555-0107', '147 Birch Blvd', 'Capital City', 'IL', '62704', '2026-05-05 15:10:05', '2026-05-05 15:10:05'),
(8, 'Sarah', 'Taylor', 'sarah.t@email.com', '555-0108', '258 Walnut Way', 'Ogden', 'IL', '61860', '2026-05-05 15:10:05', '2026-05-05 15:10:05'),
(9, 'joshua', 'N/A', 'joshau@gmail.com', '', NULL, NULL, NULL, NULL, '2026-05-07 01:38:35', '2026-05-07 01:38:35'),
(10, 'joshua', 'N/A', 'sadboy@gmail.com', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '2026-05-08 03:22:45', '2026-05-08 03:22:45'),
(11, 'justinqwe', 'N/A', 'justinqwe@gmail.com', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '2026-05-08 11:40:52', '2026-05-08 11:40:52'),
(12, 'joshuadas', 'N/A', 'opopop@gmail.com', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '2026-05-08 13:08:40', '2026-05-08 13:08:40');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `change_amount` decimal(12,2) DEFAULT 0.00,
  `payment_method` enum('cash','credit_card','bank_transfer','check') DEFAULT 'cash',
  `payment_status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `transaction_ref` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tractors`
--

CREATE TABLE `tractors` (
  `tractor_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `tractor_name` varchar(150) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `year_manufactured` int(11) DEFAULT NULL,
  `horsepower` int(11) DEFAULT NULL,
  `hourly_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `daily_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('available','booked','maintenance','retired') DEFAULT 'available',
  `image_url` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tractors`
--

INSERT INTO `tractors` (`tractor_id`, `category_id`, `tractor_name`, `brand`, `model`, `year_manufactured`, `horsepower`, `hourly_rate`, `daily_rate`, `status`, `image_url`, `image_path`, `description`, `created_at`, `updated_at`) VALUES
(13, 4, 'RiceField Pro 55', 'Kubota', 'MU5501', 2022, 55, 950.00, 6500.00, '', '', 'uploads/tractors/tractor_1778293493_827737a7.webp', 'Reliable tractor designed for rice field cultivation and light hauling tasks.', '2026-05-09 02:24:53', '2026-05-09 02:24:53'),
(14, 2, 'AgriPower 75', 'New Holland', '7630', 2021, 75, 1300.00, 9500.00, 'available', '', 'uploads/tractors/tractor_1778293580_0c854722.jpg', 'Powerful and fuel-efficient tractor ideal for medium to large farm', '2026-05-09 02:26:20', '2026-05-09 02:30:20'),
(15, 5, '7630', 'Massey Ferguson', 'MF 260', 2020, 65, 1150.00, 8500.00, '', '', 'uploads/tractors/tractor_1778293694_4b7de201.webp', 'Durable all-purpose tractor suitable for plowing, tilling, and transport work.', '2026-05-09 02:28:14', '2026-05-09 02:28:14'),
(16, 5, 'GreenLand 50', 'John Deere', '5045D', 2023, 55, 900.00, 6800.00, '', '', 'uploads/tractors/tractor_1778293929_388aa42b.webp', 'Compact farming tractor with smooth handling and low fuel consumption.', '2026-05-09 02:32:09', '2026-05-09 02:32:09'),
(17, 2, 'Harvest King 85', 'Mahindra', 'NOVO 755 DI', 2022, 75, 1500.00, 11000.00, '', '', 'uploads/tractors/tractor_1778294010_841f7a31.jpg', 'Heavy-duty tractor built for intensive agricultural operations and harvesting.', '2026-05-09 02:33:30', '2026-05-09 02:33:30'),
(18, 3, 'SoilMaster 60', 'Sonalika', 'RX 60', 2021, 65, 1000.00, 7800.00, '', '', 'uploads/tractors/tractor_1778294094_c045a031.webp', 'Efficient tractor designed for soil preparation and farming productivity.', '2026-05-09 02:34:54', '2026-05-09 02:34:54'),
(19, 5, 'Agro Beast 90', 'Case IH', 'Farmall 90', 2020, 90, 1700.00, 12500.00, '', '', 'uploads/tractors/tractor_1778294258_7b05cf85.jpg', 'High-performance tractor capable of handling demanding field operations.', '2026-05-09 02:37:38', '2026-05-09 02:37:38'),
(20, 2, 'Field Warrior 45', 'Yanmar', 'EF453T', 2021, 45, 850.00, 6200.00, '', '', 'uploads/tractors/tractor_1778294383_2a4ecdd1.jpg', 'Lightweight and maneuverable tractor perfect for small farms and gardens.', '2026-05-09 02:39:43', '2026-05-09 02:39:43'),
(22, 1, 'CropMover 80', 'Deutz-Fahr', 'Agrolux 80', 2021, 65, 1450.00, 10800.00, '', '', 'uploads/tractors/tractor_1778294553_ddedd91c.jpeg', 'Modern tractor with advanced controls for efficient crop and land management.', '2026-05-09 02:42:33', '2026-05-09 02:42:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('admin','staff','customer') DEFAULT 'staff',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `full_name`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@tracktor.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin', 1, '2026-05-05 15:10:05', '2026-05-05 15:10:05'),
(2, 'john_staff', 'john@tracktor.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Smith', 'staff', 1, '2026-05-05 15:10:05', '2026-05-05 15:10:05'),
(3, 'jane_staff', 'jane@tracktor.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Doe', 'staff', 1, '2026-05-05 15:10:05', '2026-05-05 15:10:05'),
(4, 'justin', 'juatib@gmail.com', '$2y$10$0vCygsQkiTz0PCYrU.y62umbMdaoxnhO6xMVmoN67.cEul5GfU97S', 'justin quidit', 'staff', 1, '2026-05-05 15:22:42', '2026-05-05 15:22:42'),
(5, 'joshua', 'emial@gmail.com', '$2y$10$7KJdUDj4vevAII5NjTW68u/t4bcgIWCH.V9uJXV6iUVoBie5G6kp.', 'joshua', 'staff', 1, '2026-05-05 16:17:47', '2026-05-05 16:17:47'),
(6, 'opop', 'joshau@gmail.com', '$2y$10$wcEbFqF1wqSwgNkgOKAmLOgMaGmPvIhWMdTANCif..c1hXIdPScmO', 'joshua', 'customer', 1, '2026-05-07 01:32:30', '2026-05-07 01:32:30'),
(7, 'opopop', 'sadboy@gmail.com', '$2y$10$CzVfWCvtlSleLGpAI7tfO.HniDUoDyioLsztSfV.BvWgYHT9Wnxx2', 'joshua', 'customer', 1, '2026-05-08 03:22:45', '2026-05-08 03:22:45'),
(8, 'qwe', 'qwe@gmail.com', '$2y$10$6Fi4ipTcTc8YgzprDyqbr..4FWDibxjU0.sgy8dJ9zcHLNVuCSj1O', 'justin', 'admin', 1, '2026-05-08 07:41:32', '2026-05-08 07:41:32'),
(9, 'justinqwe', 'justinqwe@gmail.com', '$2y$10$WbRKzSIqLQNBX6pcJCE1EOME2sJUxoBsp5Rb8Q7iOmsyQb3.aQojG', 'justinqwe', 'customer', 1, '2026-05-08 11:40:52', '2026-05-08 11:40:52'),
(10, 'asdasd', 'opopop@gmail.com', '$2y$10$tDYpZxKFtocy2/Vybsd8fupuAxsNBDe3JvmgZUETrTbSSlKh9Veki', 'joshuadas', 'customer', 1, '2026-05-08 13:08:40', '2026-05-08 13:08:40'),
(11, 'joshuasdasd', 'joshua@gmail.com', '$2y$10$ifu.05rqYxLj/pwo5IqcyuUK1dX0Kmv0KUpKWFKEdMcu40JKwtPgu', 'joshuasdasdas', 'admin', 1, '2026-05-08 15:40:41', '2026-05-08 15:40:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`),
  ADD KEY `idx_action_time` (`action`,`created_at`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `idx_bookings_tractor` (`tractor_id`),
  ADD KEY `idx_bookings_customer` (`customer_id`),
  ADD KEY `idx_bookings_status` (`status`),
  ADD KEY `idx_bookings_dates` (`start_date`,`end_date`),
  ADD KEY `idx_bookings_user` (`user_id`),
  ADD KEY `idx_bookings_tractor_status` (`tractor_id`,`status`),
  ADD KEY `idx_bookings_customer_status` (`customer_id`,`status`),
  ADD KEY `idx_bookings_tractor_dates` (`tractor_id`,`start_date`,`end_date`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_customers_email` (`email`),
  ADD KEY `idx_customers_name` (`last_name`,`first_name`),
  ADD KEY `idx_customers_phone` (`phone`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `idx_payments_booking` (`booking_id`),
  ADD KEY `idx_payments_status` (`payment_status`),
  ADD KEY `idx_payments_date` (`payment_date`);

--
-- Indexes for table `tractors`
--
ALTER TABLE `tractors`
  ADD PRIMARY KEY (`tractor_id`),
  ADD KEY `idx_tractors_status` (`status`),
  ADD KEY `idx_tractors_category` (`category_id`),
  ADD KEY `idx_tractors_name` (`tractor_name`(50));

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_username` (`username`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tractors`
--
ALTER TABLE `tractors`
  MODIFY `tractor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`tractor_id`) REFERENCES `tractors` (`tractor_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `tractors`
--
ALTER TABLE `tractors`
  ADD CONSTRAINT `tractors_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
