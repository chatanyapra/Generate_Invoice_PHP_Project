-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2025 at 05:42 PM
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
-- Database: `invoice_generate`
--

-- --------------------------------------------------------

--
-- Table structure for table `business_profile`
--

CREATE TABLE `business_profile` (
  `id` int(11) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `gstin` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(50) NOT NULL,
  `state_code` varchar(2) NOT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `vat_tin` varchar(20) DEFAULT NULL,
  `pan_number` varchar(10) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `account_number` varchar(20) DEFAULT NULL,
  `branch_ifsc` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(50) NOT NULL,
  `state_code` varchar(2) NOT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `gstin` varchar(15) DEFAULT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `invoice_date` date NOT NULL,
  `transaction_type` varchar(20) NOT NULL COMMENT 'retail, inter-city, purchase',
  `input_mode` varchar(20) NOT NULL COMMENT 'component, direct, reverse',
  `eway_bill` varchar(50) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL COMMENT 'Reference to customers table',
  `buyer_name` varchar(255) NOT NULL,
  `buyer_address` text NOT NULL,
  `buyer_gstin` varchar(15) DEFAULT NULL,
  `buyer_state_code` int(5) DEFAULT NULL,
  `tax_type` varchar(20) NOT NULL COMMENT 'CGST+SGST, IGST',
  `total_invoice_value` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `invoice_date`, `transaction_type`, `input_mode`, `eway_bill`, `buyer_id`, `buyer_name`, `buyer_address`, `buyer_gstin`, `buyer_state_code`, `tax_type`, `total_invoice_value`, `created_at`, `updated_at`) VALUES
(5, 'JVJ/021', '2025-06-25', 'retail', 'component', '878887', 1, 'cskasd', 'sdasadsmdasdmdm  mdasmdmas dmasm dma dma sdm amdmass dm', 'sa8as78sa778sa8', 9, 'CGST+SGST', 603795.10, '2025-06-28 17:44:38', '2025-06-28 17:44:38'),
(7, 'JVJ/022', '2025-06-11', 'retail', 'component', '878887', 1, 'cskasd', 'new address', '985623147', 9, 'CGST+SGST', 89600.00, '2025-06-29 09:51:48', '2025-06-29 09:51:48'),
(8, 'JVJ/025', '2025-06-04', 'retail', 'component', '747474111', 0, 'chatanya', 'chatata  a sas a s aasasas', 'sa8as78sa778sa8', 9, 'CGST+SGST', 603795.10, '2025-06-29 18:23:15', '2025-06-29 18:23:15');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_line_items`
--

CREATE TABLE `invoice_line_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `hsn_sac_code` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` decimal(10,3) NOT NULL,
  `unit` varchar(10) NOT NULL,
  `rate` decimal(12,2) NOT NULL,
  `taxable_value` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_line_items`
--

INSERT INTO `invoice_line_items` (`id`, `invoice_id`, `hsn_sac_code`, `description`, `quantity`, `unit`, `rate`, `taxable_value`, `created_at`) VALUES
(1, 5, '7113', 'SILVER ORNAMENTS', 10.468, 'KGS', 56000.08, 586208.84, '2025-06-28 17:44:38'),
(2, 7, '7113', 'SILVER ORNAMENTS', 1.000, 'KGS', 80000.00, 80000.00, '2025-06-29 09:51:48'),
(3, 8, '7113', 'SILVER ORNAMENTS', 10.468, 'KGS', 56000.08, 586208.84, '2025-06-29 18:23:15');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_settings`
--

CREATE TABLE `invoice_settings` (
  `id` int(11) NOT NULL,
  `invoice_prefix` varchar(20) NOT NULL DEFAULT 'JVJ/D/',
  `default_transaction_type` enum('retail','inter-state','purchase') NOT NULL DEFAULT 'retail',
  `number_digits` tinyint(2) NOT NULL DEFAULT 3 COMMENT 'Number of digits in sequence (3 = 001-999)',
  `default_input_mode` enum('component','direct','reverse') NOT NULL DEFAULT 'component',
  `starting_number` int(11) NOT NULL DEFAULT 1,
  `generate_original` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Original for Recipient',
  `generate_duplicate` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Duplicate for Transporter',
  `generate_triplicate` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Triplicate for Supplier',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `line_item_taxes`
--

CREATE TABLE `line_item_taxes` (
  `id` int(11) NOT NULL,
  `line_item_id` int(11) NOT NULL,
  `tax_name` varchar(20) NOT NULL COMMENT 'CGST, SGST, IGST',
  `tax_rate` decimal(5,2) NOT NULL,
  `tax_amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `line_item_taxes`
--

INSERT INTO `line_item_taxes` (`id`, `line_item_id`, `tax_name`, `tax_rate`, `tax_amount`, `created_at`) VALUES
(1, 1, 'CGST', 1.50, 8793.13, '2025-06-28 17:44:38'),
(2, 1, 'SGST', 1.50, 8793.13, '2025-06-28 17:44:38'),
(3, 2, 'CGST', 6.00, 4800.00, '2025-06-29 09:51:48'),
(4, 2, 'SGST', 6.00, 4800.00, '2025-06-29 09:51:48'),
(5, 3, 'CGST', 1.50, 8793.13, '2025-06-29 18:23:15'),
(6, 3, 'SGST', 1.50, 8793.13, '2025-06-29 18:23:15');

-- --------------------------------------------------------

--
-- Table structure for table `tax_rates`
--

CREATE TABLE `tax_rates` (
  `id` int(11) NOT NULL,
  `hsn_code` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `cgst_rate` decimal(5,2) NOT NULL,
  `sgst_rate` decimal(5,2) NOT NULL,
  `igst_rate` decimal(5,2) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `business_profile`
--
ALTER TABLE `business_profile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_gstin` (`gstin`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gstin` (`gstin`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `invoice_date` (`invoice_date`),
  ADD KEY `buyer_id` (`buyer_id`);

--
-- Indexes for table `invoice_line_items`
--
ALTER TABLE `invoice_line_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `invoice_settings`
--
ALTER TABLE `invoice_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `line_item_taxes`
--
ALTER TABLE `line_item_taxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `line_item_id` (`line_item_id`);

--
-- Indexes for table `tax_rates`
--
ALTER TABLE `tax_rates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_hsn_code` (`hsn_code`),
  ADD KEY `idx_default` (`is_default`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `business_profile`
--
ALTER TABLE `business_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `invoice_line_items`
--
ALTER TABLE `invoice_line_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `invoice_settings`
--
ALTER TABLE `invoice_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `line_item_taxes`
--
ALTER TABLE `line_item_taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tax_rates`
--
ALTER TABLE `tax_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
