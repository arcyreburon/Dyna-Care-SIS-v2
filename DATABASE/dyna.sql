-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2025 at 12:34 PM
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
-- Database: `dyna`
--

-- --------------------------------------------------------

--
-- Table structure for table `archive`
--

CREATE TABLE `archive` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `branches_id` int(20) NOT NULL,
  `archive_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archive`
--

INSERT INTO `archive` (`id`, `product_name`, `category_name`, `branches_id`, `archive_date`) VALUES
(20, 'Amoxicillin', 'Medicine', 2, '2025-02-15 23:40:28'),
(21, 'Amlodipine', 'Medicine', 2, '2025-02-15 23:41:55'),
(22, 'Cetirizine', 'Medicine', 1, '2025-02-16 00:18:55'),
(23, 'Metformin', 'Medicine', 1, '2025-02-16 00:19:16');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(20) NOT NULL,
  `branch_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_name`) VALUES
(1, 'DynaCare'),
(2, 'Valentine'),
(3, 'Jacfil\'s Tamag'),
(4, 'Jacfil\'s San Juan'),
(5, 'Jascha');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(20) NOT NULL,
  `category_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(1, 'Medicine'),
(2, 'Supplies');

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `id` int(20) NOT NULL,
  `dosage` varchar(150) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `brand` varchar(150) NOT NULL,
  `batch` varchar(222) NOT NULL,
  `supplier` varchar(150) NOT NULL,
  `price` decimal(25,0) NOT NULL,
  `received` varchar(250) NOT NULL,
  `expiration_date` varchar(100) NOT NULL,
  `delivery_man` varchar(250) NOT NULL,
  `contact_number` varchar(100) NOT NULL,
  `quantity` varchar(100) NOT NULL,
  `categories_id` int(20) NOT NULL,
  `branch_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`id`, `dosage`, `product_name`, `brand`, `batch`, `supplier`, `price`, `received`, `expiration_date`, `delivery_man`, `contact_number`, `quantity`, `categories_id`, `branch_id`) VALUES
(2, '', 'Tissue', 'Y', 'Batch 1', 'XYZ Company', 22, '2025-02-16', '2025-02-16', '0', '09876543211', '79', 2, NULL),
(3, '', 'Paracetamol', 'Bioflu', '874568357', 'Reburon', 590, '2025-04-29', '2030-04-26', '0', '09123456789', '199', 1, NULL),
(4, '', 'Ibufropen', 'Medicol', '723465827', 'QWERTY', 34, '2025-05-05', '2028-09-23', '0', '0974 283 4728', '34', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dosage_forms`
--

CREATE TABLE `dosage_forms` (
  `id` int(11) NOT NULL,
  `form_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  `avail_stock` int(11) NOT NULL DEFAULT 0,
  `damage_stock` int(11) NOT NULL DEFAULT 0,
  `release_stock` int(11) NOT NULL DEFAULT 0,
  `price` decimal(10,2) NOT NULL,
  `delivery_price` decimal(10,2) NOT NULL,
  `batch` varchar(100) NOT NULL,
  `dosage` varchar(100) NOT NULL,
  `old_price` decimal(10,2) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `received` varchar(100) NOT NULL,
  `branches_id` int(20) NOT NULL,
  `unit_measure` varchar(50) DEFAULT NULL,
  `material_type` varchar(50) DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `storage_instructions` text DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `reorder_level` int(11) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `acquisition_cost` decimal(10,2) DEFAULT NULL,
  `warranty_details` text DEFAULT NULL,
  `strength` varchar(50) DEFAULT NULL,
  `generic_name` varchar(100) DEFAULT NULL,
  `supply_type` varchar(50) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `model_number` varchar(50) DEFAULT NULL,
  `warranty` varchar(50) DEFAULT NULL,
  `dosage_form_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `products_id`, `avail_stock`, `damage_stock`, `release_stock`, `price`, `delivery_price`, `batch`, `dosage`, `old_price`, `brand`, `received`, `branches_id`, `unit_measure`, `material_type`, `supplier`, `storage_instructions`, `sku`, `reorder_level`, `purchase_date`, `acquisition_cost`, `warranty_details`, `strength`, `generic_name`, `supply_type`, `size`, `model_number`, `warranty`, `dosage_form_id`) VALUES
(36, 34, 2, 0, 0, 4000.00, 0.00, '345345', '0', 0.00, 'fdsg', '2025-04-30', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 36, 38, 3, 0, 9.00, 64.00, '345655', '0', 0.00, 'Advil', '2025-05-03', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '500mg', 'Advil', 'Disposable', 'Large', '456345', '3 Years', NULL),
(39, 37, 40, 1, 0, 127.00, 20.00, '0', '0', 5.00, 'Arcy', '2025-02-03', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '500mg', NULL, '', '', '', '', NULL),
(40, 38, 0, 0, 0, 50.00, 15.00, '', '', 0.00, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 40, 33, 0, 0, 40.00, 12.00, '', '', 0.00, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 41, 43, 0, 0, 40.00, 12.00, '', '', 0.00, '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 43, 0, 0, 0, 25.00, 8.00, '', '', 0.00, '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 45, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(48, 46, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(49, 47, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(50, 48, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(51, 49, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(52, 50, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(53, 51, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(54, 52, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(55, 53, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(56, 54, 87, 0, 0, 1.70, 0.00, '', '', 0.00, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(57, 55, 80, 0, 0, 1.70, 0.00, '', '', 0.00, '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(58, 56, 45, 0, 0, 120.00, 40.00, '', '', 0.00, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(59, 57, 52, 0, 0, 120.00, 40.00, '0', '0', 110.00, 'X', '2025-02-14', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(60, 58, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(61, 59, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(62, 60, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(63, 61, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(64, 62, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(65, 63, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(66, 64, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(67, 65, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(68, 66, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(69, 67, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(70, 68, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(71, 69, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(72, 70, 200, 0, 0, 2.00, 1.00, '', '', 0.00, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(73, 71, 120, 0, 0, 2.00, 1.00, '', '', 0.00, '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(75, 73, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(76, 74, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(77, 75, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(78, 76, 0, 0, 0, 0.00, 0.00, '', '', 0.00, '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `type` enum('medicine','supplies') NOT NULL,
  `recipient_email` varchar(255) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `product_name`, `quantity`, `type`, `recipient_email`, `order_date`) VALUES
(1, 'Paracetamol\r\n', 12, 'medicine', 'arcyreburonngmail.com', '2025-02-04 04:44:59'),
(2, 'Surgical Gloves', 12, 'supplies', 'arcyreburon@unp.edu.ph', '2025-02-04 04:44:59'),
(3, 'Wheel Chair', 33, 'medicine', 'arcyreburon0@gmail.com', '2025-02-04 05:00:49'),
(4, 'Sterile Gauze Pads', 22, 'supplies', 'arcyreburon@gmail.com', '2025-02-04 05:00:49'),
(5, 'Losartan', 33, 'medicine', 'arcyreburon@gmail.com', '2025-02-04 05:02:24'),
(6, 'Hypodermic Needles', 22, 'supplies', 'arcyreburon0@gmail.com', '2025-02-04 05:02:24'),
(7, 'Amlodipine', 33, 'medicine', 'arcyreburon@gmail.com', '2025-02-04 05:04:12'),
(8, 'Face Mask (Syrgical)', 22, 'supplies', 'arcyreburon@gmail.com', '2025-02-04 05:04:12'),
(9, 'Losartan', 33, 'medicine', 'arcyreburon0@gmail.com', '2025-02-04 05:05:33'),
(10, 'Alcohol Prep Pads', 22, 'supplies', 'arcyreburonn@gmail.com', '2025-02-04 05:05:33'),
(11, 'Salbutalmol', 33, 'medicine', 'arcyreburon@gmail.com', '2025-02-04 05:07:35'),
(12, 'Hypodermic Needles', 22, 'supplies', 'arcyreburon@gmail.com', '2025-02-04 05:07:35'),
(13, 'Ibuprofens', 33, 'medicine', 'arcyreburon@gmail.com', '2025-02-04 05:09:31'),
(14, 'Thermometer (Digital)', 22, 'supplies', 'asreburon.ccit@unp.edu.ph', '2025-02-04 05:09:31'),
(15, 'Biogesic', 100, 'medicine', 'sacquiatenjosh@gmail.com', '2025-02-15 14:51:04'),
(16, 'Face Mask (Surgical)', 22, 'supplies', 'arcyreburon@gmail.com', '2025-02-15 14:51:04'),
(17, 'Cherifer', 12, 'medicine', 'asreburon.ccit@unp.edu.ph', '2025-02-15 14:54:46'),
(18, 'Thermometer (Digital)', 22, 'supplies', 'arcyreburon@gmal.com', '2025-02-15 14:54:47'),
(19, 'Biogesic', 2, 'medicine', 'arcyreburon@gmal.com', '2025-02-15 14:56:32'),
(20, 'Sterile Gauze Pads', 0, 'supplies', 'arcyreburon@gmal.com', '2025-02-15 14:56:32'),
(21, 'Amoxicilin', 3, 'medicine', 'asreburon.ccit@unp.edu.ph', '2025-04-23 05:37:31'),
(22, 'Amoxicilin', 3, 'supplies', 'asreburon.ccit@unp.edu.ph', '2025-04-23 05:37:31'),
(23, 'Amoxicilin', 3, 'medicine', 'asreburon.ccit@unp.edu.ph', '2025-04-23 06:20:21'),
(24, 'Amoxicilin', 3, 'supplies', 'asreburon.ccit@unp.edu.ph', '2025-04-23 06:20:21'),
(25, 'Amoxicilin', 3, 'medicine', 'asreburon.ccit@unp.edu.ph', '2025-04-25 08:36:22'),
(26, 'Amoxicilin', 3, 'supplies', 'asreburon.ccit@unp.edu.ph', '2025-04-25 08:36:22'),
(27, 'Tissue', 3, 'medicine', 'asreburon.ccit@unp.edu.ph', '2025-04-25 08:36:56'),
(28, 'Tissue', 3, 'supplies', 'asreburon.ccit@unp.edu.ph', '2025-04-25 08:36:56'),
(29, 'Paracetamol', 3, 'medicine', 'arcyreburon0@gmail.com', '2025-04-27 15:31:54'),
(30, 'Tissue', 2, 'supplies', 'arcyreburon0@gmail.com', '2025-04-27 15:31:54');

-- --------------------------------------------------------

--
-- Table structure for table `others`
--

CREATE TABLE `others` (
  `id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `batch` varchar(255) DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `old_price` decimal(10,2) DEFAULT NULL,
  `dosage` varchar(255) DEFAULT NULL,
  `received` datetime DEFAULT NULL,
  `delivery_man` varchar(255) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(20) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `categories_id` int(150) NOT NULL,
  `price` decimal(24,0) NOT NULL,
  `expiration_date` date NOT NULL,
  `batch` varchar(45) NOT NULL,
  `supplier` varchar(45) NOT NULL,
  `branches_id` int(20) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `generic_name` varchar(255) DEFAULT NULL,
  `dosage` varchar(255) DEFAULT NULL,
  `form` varchar(255) DEFAULT NULL,
  `strength` varchar(255) DEFAULT NULL,
  `route_of_administration` varchar(255) DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `prescription_required` varchar(3) DEFAULT NULL,
  `side_effects` text DEFAULT NULL,
  `contraindications` text DEFAULT NULL,
  `storage_instructions` text DEFAULT NULL,
  `active_ingredients` text DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `packaging_type` varchar(255) DEFAULT NULL,
  `material_type` varchar(255) DEFAULT NULL,
  `specifications` text DEFAULT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `warranty` varchar(255) DEFAULT NULL,
  `usage_instructions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `categories_id`, `price`, `expiration_date`, `batch`, `supplier`, `branches_id`, `type`, `generic_name`, `dosage`, `form`, `strength`, `route_of_administration`, `manufacturer`, `prescription_required`, `side_effects`, `contraindications`, `storage_instructions`, `active_ingredients`, `sku`, `packaging_type`, `material_type`, `specifications`, `supplier_name`, `warranty`, `usage_instructions`) VALUES
(34, '', 0, 0, '2025-05-07', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 'Ibuprofen', 1, 0, '2026-02-03', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 'Ibuprofens', 1, 0, '2026-02-19', '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 'Amlodipine', 1, 0, '2025-03-19', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 'Omeprazole', 1, 0, '2025-12-03', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 'Omeprazole', 1, 0, '2025-12-03', '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 'Cetirizine', 1, 0, '2027-10-03', '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 'Metformin', 1, 0, '0000-00-00', '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 'Losartan', 1, 0, '0000-00-00', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 'Losartan', 1, 0, '0000-00-00', '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(48, 'Salbutamol', 1, 0, '0000-00-00', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(49, 'Salbutamol', 1, 0, '0000-00-00', '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(50, 'Loratadine', 1, 0, '0000-00-00', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(51, 'Loratadine', 1, 0, '0000-00-00', '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(52, 'Surgical Gloves', 2, 0, '0000-00-00', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(53, 'Surgical Gloves', 2, 0, '0000-00-00', '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(54, 'Sterile Gauze Pads', 2, 0, '0000-00-00', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(55, 'Sterile Gauze Pads', 2, 0, '0000-00-00', '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(56, 'Thermometer (Digital)', 2, 0, '0000-00-00', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(57, 'Thermometer (Digital)', 2, 0, '2028-11-16', '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(58, 'Hypodermic Needles', 2, 0, '0000-00-00', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(59, 'Hypodermic Needles', 2, 0, '0000-00-00', '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(60, 'Face Masks (Surgical)', 2, 0, '0000-00-00', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(61, 'Face Masks (Surgical)', 2, 0, '0000-00-00', '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(62, 'Alcohol Prep Pads', 2, 0, '0000-00-00', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(63, 'Alcohol Prep Pads', 2, 0, '0000-00-00', '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(64, 'Blood Pressure Cuff', 2, 0, '0000-00-00', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(65, 'Blood Pressure Cuff', 2, 0, '0000-00-00', '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(66, 'Cotton Balls', 2, 0, '0000-00-00', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(67, 'Cotton Balls', 2, 0, '0000-00-00', '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(68, 'Surgical Scalpels', 2, 0, '0000-00-00', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(69, 'Surgical Scalpels', 2, 0, '0000-00-00', '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(70, 'Elastic Bandage (Gauze)', 2, 0, '0000-00-00', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(71, 'Elastic Bandage (Gauze)', 2, 0, '0000-00-00', '', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(73, 'Paracetamol', 1, 0, '0000-00-00', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(74, 'Mefenamic', 1, 0, '0000-00-00', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(75, 'Cherifer', 1, 0, '0000-00-00', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(76, 'Medicol', 1, 0, '0000-00-00', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `release_stock`
--

CREATE TABLE `release_stock` (
  `id` int(20) NOT NULL,
  `delivery_id` int(20) NOT NULL,
  `branches_id` int(20) NOT NULL,
  `quantity` varchar(100) NOT NULL,
  `release_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `release_stock`
--

INSERT INTO `release_stock` (`id`, `delivery_id`, `branches_id`, `quantity`, `release_date`) VALUES
(2, 2, 2, '1', '2025-02-17 02:34:26'),
(3, 2, 3, '3', '2025-04-25 06:53:01'),
(4, 2, 2, '4', '2025-04-25 07:27:41'),
(5, 2, 5, '4', '2025-04-25 08:14:56'),
(6, 3, 2, '1', '2025-05-05 06:17:32'),
(7, 2, 2, '1', '2025-05-05 06:17:32');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `transaction_no` int(5) UNSIGNED ZEROFILL DEFAULT NULL,
  `products_id` int(11) NOT NULL,
  `total_price` decimal(30,0) NOT NULL,
  `discount` varchar(150) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `transaction_no`, `products_id`, `total_price`, `discount`, `date`) VALUES
(67, 00922, 37, 21, '0', '2025-02-12 06:12:52'),
(68, 00922, 57, 360, '0', '2025-02-03 06:12:52'),
(69, 00606, 40, 160, '10', '2025-02-13 06:27:42'),
(70, 00606, 54, 5, '10', '2025-02-03 06:27:42'),
(71, 00579, 39, 50, '0', '2025-02-03 13:49:40'),
(72, 00579, 37, 7, '0', '2025-02-03 13:49:41'),
(73, 00875, 39, 50, '0', '2025-02-03 13:49:56'),
(74, 00208, 39, 200, '0', '2025-02-03 14:50:42'),
(75, 00882, 39, 200, '20', '2025-02-03 15:04:12'),
(76, 00937, 37, 7, '0', '2025-02-16 14:11:34'),
(77, 83786, 41, 80, '0', '2025-02-17 01:47:30'),
(78, 73721, 36, 12, '30', '2025-04-25 06:56:25'),
(79, 73721, 40, 80, '30', '2025-04-25 06:56:25'),
(80, 37526, 40, 120, '10', '2025-04-25 08:19:32'),
(81, 37526, 36, 18, '10', '2025-04-25 08:19:32'),
(82, 24085, 36, 0, '13', '2025-05-02 04:12:54'),
(83, 24085, 40, 0, '13', '2025-05-02 04:12:54'),
(84, 24085, 34, 0, '13', '2025-05-02 04:12:54'),
(85, 63854, 36, 18, '20', '2025-05-05 07:08:48'),
(86, 63854, 40, 40, '20', '2025-05-05 07:08:48'),
(87, 63364, 36, 9, '0', '2025-05-05 07:11:17'),
(88, 63364, 40, 40, '0', '2025-05-05 07:11:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(20) NOT NULL,
  `username` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `cpnumber` varchar(150) NOT NULL,
  `password` varchar(250) NOT NULL,
  `name` varchar(50) NOT NULL,
  `users_role_id` int(20) NOT NULL,
  `branches_id` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `cpnumber`, `password`, `name`, `users_role_id`, `branches_id`) VALUES
(10, 'arcyinv.dyna', 'arcyreburon0@gmail.com', '0919 3686 141', '202cb962ac59075b964b07152d234b70', 'Arcy Dyna', 3, 1),
(12, 'arcyadmin.dyna', 'arcyreburon0@gmail.com', '0919 3686 141', '202cb962ac59075b964b07152d234b70', 'Arcy Dyna', 2, 1),
(22, 'arcycash.dyna', 'arcyreburon0@gmail.com', '0919 3686 141', '202cb962ac59075b964b07152d234b70', 'Arcy Dyna', 4, 1),
(24, 'arcyadmin.val', 'asreburon.ccit@unp.edu.ph', '0919 368 6141', '202cb962ac59075b964b07152d234b70', 'Arcy Valentine', 2, 2),
(25, 'arcysa', 'asreburon.ccit@unp.edu.ph', '0919 368 6141', 'e3cf7d242189de8abf75141afa694438', 'Arcy', 1, 1),
(26, 'arcyinv.val', 'arcyreburon0@gmail.com', '0919 368 6141', '202cb962ac59075b964b07152d234b70', 'Arcy Valentine', 3, 2),
(27, 'arcycash.val', 'arcyreburon0@gmail.com', '0919 368 6141', '202cb962ac59075b964b07152d234b70', 'Arcy Valentine', 4, 2),
(28, 'arcyadmin.jact', 'arcyreburon0@gmail.com', '0919 368 6141', '202cb962ac59075b964b07152d234b70', 'Arcy Jacfil\'s', 2, 3),
(29, 'arcyinv.jact', 'arcyreburon0@gmail.com', '0919 368 6141', '202cb962ac59075b964b07152d234b70', 'Arcy Jacfil\'s', 3, NULL),
(30, 'arcyinv.jact', 'arcyreburon0@gmail.com', '0919 368 6141', '202cb962ac59075b964b07152d234b70', 'Arcy Jacfil\'s', 3, 3),
(31, 'arcycash.jact', 'arcyreburon0@gmail.com', '0919 368 6141', '202cb962ac59075b964b07152d234b70', 'Arcy Jacfil\'s', 4, 3),
(32, 'arcyadmin.jacsj', 'arcyreburon0@gmail.com', '0919 368 6141', '202cb962ac59075b964b07152d234b70', 'Arcy Jacfil\'s SJ', 2, 4),
(33, 'arcyinv.jacjs', 'arcyreburon0@gmail.com', '0919 368 6141', '202cb962ac59075b964b07152d234b70', 'Arcy Jacfil\'s SJ', 3, 4),
(34, 'arcycash.jacsj', 'arcyreburon0@gmail.com', '0919 368 6141', '202cb962ac59075b964b07152d234b70', 'Arcy Jacfil\'s SJ', 4, 4),
(35, 'arcyadmin.jas', 'arcyreburon0@gmail.com', '0919 368 6141', '202cb962ac59075b964b07152d234b70', 'Arcy Jascha', 2, 5),
(36, 'arcyinv.jas', 'arcyreburon0@gmail.com', '0919 368 6141', '202cb962ac59075b964b07152d234b70', 'Arcy Jascha', 3, 5),
(37, 'arcycash.jas', 'arcyreburon0@gmail.com', '0919 368 6141', '202cb962ac59075b964b07152d234b70', 'Arcy Jascha', 4, 5),
(38, '1', 'd@gmail.com', '4091 936 8614', '289dff07669d7a23de0ef88d2f7129e7', 'Arcy', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_role`
--

CREATE TABLE `users_role` (
  `id` int(20) NOT NULL,
  `role` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_role`
--

INSERT INTO `users_role` (`id`, `role`) VALUES
(1, 'Super Admin'),
(2, 'Admin'),
(3, 'Inventory Clerk'),
(4, 'Cashier');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archive`
--
ALTER TABLE `archive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_id` (`categories_id`);

--
-- Indexes for table `dosage_forms`
--
ALTER TABLE `dosage_forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `products_id` (`products_id`) USING BTREE,
  ADD KEY `branches_id` (`branches_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `others`
--
ALTER TABLE `others`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_id` (`products_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `categories_id` (`categories_id`),
  ADD KEY `branches_id` (`branches_id`);

--
-- Indexes for table `release_stock`
--
ALTER TABLE `release_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_id` (`delivery_id`,`branches_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_role_id` (`users_role_id`),
  ADD KEY `branches_id` (`branches_id`);

--
-- Indexes for table `users_role`
--
ALTER TABLE `users_role`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `archive`
--
ALTER TABLE `archive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dosage_forms`
--
ALTER TABLE `dosage_forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `others`
--
ALTER TABLE `others`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `release_stock`
--
ALTER TABLE `release_stock`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `users_role`
--
ALTER TABLE `users_role`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `others`
--
ALTER TABLE `others`
  ADD CONSTRAINT `others_ibfk_1` FOREIGN KEY (`products_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
