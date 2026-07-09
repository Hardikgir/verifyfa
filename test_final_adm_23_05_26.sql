-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2026 at 07:13 AM
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
-- Table structure for table `test_final_adm_23_05_26`
--

CREATE TABLE `test_final_adm_23_05_26` (
  `id` int(11) UNSIGNED NOT NULL,
  `item_category` varchar(255) NOT NULL,
  `item_sub_category` varchar(255) DEFAULT NULL,
  `item_unique_code` varchar(255) NOT NULL,
  `item_sub_code` varchar(255) DEFAULT NULL,
  `dept_internal_item_code` varchar(255) DEFAULT NULL,
  `item_description` text NOT NULL,
  `item_classification` varchar(255) DEFAULT NULL,
  `component_details` varchar(255) DEFAULT NULL,
  `serial_product_number` varchar(255) DEFAULT NULL,
  `make` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `item_user` varchar(255) DEFAULT NULL,
  `user_department` varchar(255) DEFAULT NULL,
  `cost_centre` varchar(255) DEFAULT NULL,
  `item_owner_dept` varchar(255) DEFAULT NULL,
  `location_of_the_item_last_verified` varchar(255) NOT NULL,
  `quantity_as_per_invoice` int(11) NOT NULL,
  `uom` varchar(255) DEFAULT NULL,
  `verifiable_status_y_n_na` varchar(255) NOT NULL,
  `tag_status_y_n_na` varchar(10) NOT NULL,
  `accounting_voucher_no` varchar(255) DEFAULT NULL,
  `accounting_voucher_date` varchar(20) DEFAULT NULL,
  `supplier_code` varchar(255) DEFAULT NULL,
  `supplier_s_detail` varchar(255) DEFAULT NULL,
  `po_wo_ref_no` varchar(255) DEFAULT NULL,
  `po_wo_date` varchar(20) DEFAULT NULL,
  `invoice_reference` varchar(255) DEFAULT NULL,
  `date_of_purchase_invoice_date` varchar(20) DEFAULT NULL,
  `date_of_item_capitalization` varchar(20) DEFAULT NULL,
  `date_of_last_physical_verification` varchar(255) DEFAULT NULL,
  `total_item_amount_capitalized` decimal(15,2) NOT NULL,
  `wdv_at_the_end_of_reporting_period` decimal(15,2) DEFAULT NULL,
  `verification_status` varchar(50) NOT NULL DEFAULT 'Not-Verified',
  `quantity_verified` int(11) NOT NULL DEFAULT 0,
  `new_location_verified` varchar(255) DEFAULT NULL,
  `verified_by` varchar(255) DEFAULT NULL,
  `verified_by_username` varchar(255) DEFAULT NULL,
  `verified_datetime` datetime DEFAULT NULL,
  `verification_remarks` text DEFAULT NULL,
  `item_note` text DEFAULT NULL,
  `qty_ok` int(11) NOT NULL DEFAULT 0,
  `qty_damaged` int(11) NOT NULL DEFAULT 0,
  `qty_scrapped` int(11) NOT NULL DEFAULT 0,
  `qty_not_in_use` int(11) NOT NULL DEFAULT 0,
  `qty_missing` int(11) NOT NULL DEFAULT 0,
  `qty_shifted` int(11) NOT NULL DEFAULT 0,
  `is_alotted` tinyint(4) NOT NULL DEFAULT 0,
  `is_edit` int(11) NOT NULL DEFAULT 0,
  `instance_count` int(11) NOT NULL DEFAULT 0,
  `mode_of_verification` varchar(200) NOT NULL DEFAULT 'Not Verified',
  `createdat` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedat` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `test_final_adm_23_05_26`
--
ALTER TABLE `test_final_adm_23_05_26`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `test_final_adm_23_05_26`
--
ALTER TABLE `test_final_adm_23_05_26`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
