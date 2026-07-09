-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2026 at 07:14 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `v74_ci_verifyfa_local_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `verifiedproducts`
--

CREATE TABLE `verifiedproducts` (
  `id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `entity_code` varchar(255) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `original_table_name` varchar(255) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_category` int(11) DEFAULT NULL,
  `item_unique_code` varchar(255) DEFAULT NULL,
  `item_sub_code` varchar(255) DEFAULT NULL,
  `item_description` text DEFAULT NULL,
  `quantity_as_per_invoice` int(11) DEFAULT NULL,
  `verification_status` varchar(50) DEFAULT NULL,
  `quantity_verified` varchar(255) DEFAULT NULL,
  `new_location_verified` varchar(255) DEFAULT NULL,
  `verified_by` varchar(255) NOT NULL,
  `verified_by_username` varchar(255) NOT NULL,
  `verified_datetime` datetime NOT NULL,
  `verification_remarks` text DEFAULT NULL,
  `qty_ok` int(11) DEFAULT NULL,
  `qty_damaged` int(11) DEFAULT NULL,
  `qty_scrapped` int(11) NOT NULL,
  `qty_not_in_use` int(11) NOT NULL,
  `qty_missing` int(11) NOT NULL,
  `qty_shifted` int(11) NOT NULL,
  `mode_of_verification` varchar(255) NOT NULL COMMENT 'Scan,Search',
  `type_of_operation` enum('add','edit','rollback') NOT NULL DEFAULT 'add',
  `qty_value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `verifiedproducts`
--
ALTER TABLE `verifiedproducts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `verifiedproducts`
--
ALTER TABLE `verifiedproducts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
