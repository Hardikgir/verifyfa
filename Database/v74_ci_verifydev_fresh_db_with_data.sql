-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2025 at 03:29 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `v74_ci_verifydev_fresh_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `additional_data`
--

CREATE TABLE `additional_data` (
  `id` int(15) NOT NULL,
  `project_id` int(11) NOT NULL,
  `asset_category` varchar(100) DEFAULT NULL,
  `asset_classification` varchar(100) DEFAULT NULL,
  `description_of_asset` varchar(100) DEFAULT NULL,
  `qty_verified` varchar(100) DEFAULT NULL,
  `current_location` varchar(100) DEFAULT NULL,
  `condition_of_assets` varchar(100) DEFAULT NULL,
  `make` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `serial_no` varchar(100) DEFAULT NULL,
  `temp_verifiction_id_ref` varchar(100) DEFAULT NULL,
  `expected_unit_cost` varchar(100) DEFAULT NULL,
  `any_other_details_unit_cost` varchar(100) DEFAULT NULL,
  `verified_name` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `additional_data`
--

INSERT INTO `additional_data` (`id`, `project_id`, `asset_category`, `asset_classification`, `description_of_asset`, `qty_verified`, `current_location`, `condition_of_assets`, `make`, `model`, `serial_no`, `temp_verifiction_id_ref`, `expected_unit_cost`, `any_other_details_unit_cost`, `verified_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'medical furniture ', 'bed', 'hospital bed automatic fold', '1', 'ward 2A', 'Good Condition', 'Godrej Inferio', '', 'zzzzzz', '', '', '', 'AMIT KUMAR', '2023-09-09 11:04:57', '2023-09-29 13:24:39'),
(2, 1, 'medical equipment ', 'Holter machine', 'ecg Holter machine', '1', 'ecg lab', 'Good Condition', 'Philips ', '', '', '', '', '', 'NEENA GUPTA', '2023-09-10 05:59:43', '2023-09-10 05:59:43'),
(3, 1, 'furniture ', 'chair', 'Black chair', '39', 'conference room gf-10', 'Good Condition', 'godrej', '', '', '', '', '', 'AMIT KUMAR', '2023-09-29 13:36:55', '2023-09-29 13:36:55'),
(5, 5, 'weighing machine ', 'tools and equipment ', '0-5kg weight machine', '1', 'gf-23', 'Good Condition', 'make', 'test model ', '', '', '', '', 'Kartikey Sharma', '2023-10-05 09:00:57', '2024-01-31 17:03:37'),
(6, 5, 'tool and eqp', 'weight machine ', 'max-6kg weight machine ', '1', 'ug 100', 'Good Condition', 'samurai', 'stit', '', '', '', '', 'Kartikey Sharma', '2023-10-05 11:44:47', '2023-10-05 11:44:47'),
(7, 5, 'tools and equip ', 'weight machine', 'Max 6 kg', '1', 'mf1-78', 'Good Condition', 'samurai', 'stit', '', '', '', '', 'Kartikey Sharma', '2023-10-05 11:51:27', '2023-10-05 11:51:27'),
(8, 5, 'Plant & Machinery ///', 'MTC LT 100L', 'MTC LT-DK100s', '1', 'GF-48', 'Good Condition', 'Shibaura Machine ', 'LT -DK 100s', '', '', '', 'OCT 2022', 'Pradeep Kumar', '2023-10-06 06:11:54', '2023-10-06 06:11:54'),
(9, 5, 'Plant & Machinery///', 'MTC LT 100s', 'MTC LT 100S/Autonics/9KW', '1', 'GF-48', 'Good Condition', 'Shibaura Machine', 'MTC', 'LTDK100S0013', '', '', '', 'Pradeep Kumar', '2023-10-06 06:19:27', '2023-10-06 06:19:27'),
(10, 5, 'maintenance ', 'screwdriver gun', 'electronic gun', '1', 'gf-25', 'Good Condition', '', '', '', '', '', '', 'Kartikey Sharma', '2023-10-09 10:59:50', '2023-10-09 10:59:50'),
(11, 5, 'maintenance ', 'screw gun', 'electronic ', '1', 'gf-25', 'Good Condition', '', '', '', '', '', '', 'Kartikey Sharma', '2023-10-09 11:05:53', '2023-10-09 11:05:53'),
(12, 5, 'maintenance ', 'spring balancing ', 'spring balancer round', '1', 'gf-25', 'Good Condition', '', '', '', '', '', '', 'Kartikey Sharma', '2023-10-09 11:13:34', '2023-10-09 11:13:34'),
(13, 5, 'maintenance ', 'spring balancer ', 'model no psb-3', '1', 'gf-25', 'Good Condition', '', '', '', '', '', '', 'Kartikey Sharma', '2023-10-09 11:23:25', '2023-10-09 11:23:25'),
(14, 5, 'maintenance ', 'spring balancer ', 'techno', '1', 'gf-25 ', 'Good Condition', '', '', '', '', '', '', 'Kartikey Sharma', '2023-10-09 11:29:05', '2023-10-09 11:29:05'),
(16, 5, 'maintenance ', 'spring balancer ', '1kg-3kg', '1', 'gf-25 ', 'Good Condition', '', '', '', '', '', '', 'Kartikey Sharma', '2023-10-09 11:30:46', '2023-10-09 11:30:46'),
(17, 5, 'maintenance ', 'electronic screwdriver ', 'professional pneumatic tools', '3', 'gf-25', 'Good Condition', '', '', '', '', '', '', 'Kartikey Sharma', '2023-10-09 12:05:30', '2023-10-09 12:05:30'),
(18, 5, 'plant and machinery ', 'ups', 'ups controller for toyo', '2', 'mf1-79', 'Good Condition', 'consul neowatt ', '', '', '', '', '', 'Kartikey Sharma', '2023-10-11 10:35:32', '2023-10-11 10:35:32'),
(19, 25, 'asset_category UPDATE', 'asset_classification UPDATE', 'description_of_asset UPDATE', '1', 'ward 2A  UPDATE', 'Good Condition', 'godrej  UPDATE', '2023  UPDATE', '88821212  UPDATE', 'test temp_verifiction_id_ref  UPDATE', '250', '150', 'Hardikgiri Meghnathi  UPDATE', '2024-05-23 10:04:48', '2024-05-23 13:35:10');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `short_code` varchar(255) NOT NULL,
  `registered_user_id` int(11) NOT NULL,
  `entity_code` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `last_edited_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `user_id`, `company_name`, `short_code`, `registered_user_id`, `entity_code`, `created_by`, `created_at`, `last_edited_by`, `updated_at`) VALUES
(1, 0, 'COMPANY 1', 'Comp1', 1, 'LOADDEMO', 1, '2023-08-28 12:39:45', 0, '0000-00-00 00:00:00'),
(2, 0, 'COMPANY 2', 'Comp2', 1, 'LOADDEMO', 1, '2023-08-28 12:42:01', 0, '0000-00-00 00:00:00'),
(3, 0, 'Dipty Lal Judge Mal (P) Ltd', 'DLJM', 3, 'DLJM', 5, '2023-10-03 05:12:13', 0, '0000-00-00 00:00:00'),
(4, 0, 'HMCMM Auto Ltd', 'HMCMM', 4, 'HMCMM', 13, '2024-03-26 07:43:11', 0, '0000-00-00 00:00:00'),
(5, 0, 'Kisan Molding Limited', 'KMLD', 4, 'HMCMM', 13, '2024-03-26 07:50:27', 0, '0000-00-00 00:00:00'),
(6, 0, 'IT Seer Demo Co.', 'SEER', 6, 'DEMOCO', 24, '2024-04-11 10:30:54', 0, '0000-00-00 00:00:00'),
(7, 0, 'JP Engineering Works', 'JPEW', 7, 'SERITSOL', 26, '2024-05-21 07:50:45', 0, '0000-00-00 00:00:00'),
(8, 0, 'Ajay Engineering Works', 'AEW', 7, 'SERITSOL', 26, '2024-05-21 07:51:12', 0, '0000-00-00 00:00:00'),
(9, 0, 'Jay Engineering', 'JENG', 7, 'SERITSOL', 26, '2024-05-21 07:51:44', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `company_locations`
--

CREATE TABLE `company_locations` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `location_name` varchar(255) DEFAULT NULL,
  `location_shortcode` varchar(255) NOT NULL,
  `registered_user_id` int(11) NOT NULL,
  `entity_code` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `edited_by` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company_locations`
--

INSERT INTO `company_locations` (`id`, `company_id`, `location_name`, `location_shortcode`, `registered_user_id`, `entity_code`, `created_by`, `edited_by`, `status`, `updated_at`, `created_at`) VALUES
(1, 1, 'Nehru Place Delhi', 'NP-DEL', 1, 'LOADDEMO', 1, 0, 1, NULL, '2023-08-28 12:49:07'),
(2, 1, 'Zara Chennai', 'ZARA-CH', 1, 'LOADDEMO', 1, 0, 1, NULL, '2023-08-28 12:50:07'),
(3, 2, 'Plato Mumbai', 'PLAT-MUM', 1, 'LOADDEMO', 1, 0, 1, NULL, '2023-08-28 12:50:22'),
(4, 1, 'Plato Mumbai', 'PLAT-MUM', 1, 'LOADDEMO', 1, 0, 1, NULL, '2023-08-28 13:13:04'),
(5, 3, 'DLJM D-55 Phase 2 Noida', 'DLJM Ph2 Noida', 3, 'DLJM', 5, 0, 1, NULL, '2023-10-03 05:13:19'),
(6, 4, 'Manesar', 'MNSR', 4, 'HMCMM', 13, 0, 1, NULL, '2024-03-26 07:43:32'),
(7, 5, 'Mahagaon MH', 'MAHA', 4, 'HMCMM', 13, 0, 1, NULL, '2024-03-26 07:51:17'),
(8, 5, 'Silvassa DN', 'SILVS', 4, 'HMCMM', 13, 0, 1, NULL, '2024-03-26 07:51:44'),
(9, 5, 'STK Dewas (Plant) MP', 'DEWAS', 4, 'HMCMM', 13, 13, 1, '2024-04-03 13:02:36', '2024-03-26 07:52:09'),
(10, 5, 'STK Jaipur RJ', 'JPR', 4, 'HMCMM', 13, 13, 1, '2024-04-03 13:04:49', '2024-03-26 07:52:29'),
(11, 5, 'STK - Mahagaon MH', 'MAHSTK', 4, 'HMCMM', 13, 0, 1, NULL, '2024-03-29 04:54:59'),
(12, 5, 'FA MAHAGAON MH', 'MAHFA', 4, 'HMCMM', 13, 0, 1, NULL, '2024-03-29 04:55:43'),
(13, 5, 'FA SILVASSA (DAMAN)', 'SILV-FA', 4, 'HMCMM', 13, 0, 1, NULL, '2024-04-01 06:33:33'),
(14, 5, 'STK Dewas (Godown) MP', 'DEWAS-G', 4, 'HMCMM', 13, 0, 1, NULL, '2024-04-03 13:03:24'),
(15, 5, 'FA Dewas (Plant) MP', 'DWSFA-P', 4, 'HMCMM', 13, 0, 1, NULL, '2024-04-03 13:03:58'),
(16, 5, 'FA Dewas (Godown) MP', 'DWSFA-G', 4, 'HMCMM', 13, 0, 1, NULL, '2024-04-03 13:04:19'),
(17, 5, 'FA Jaipur (Godown) RJ', 'JPRFA', 4, 'HMCMM', 13, 0, 1, NULL, '2024-04-03 13:11:52'),
(18, 5, 'STK Jaipur (Godown) RJ', 'JPRGDN', 4, 'HMCMM', 13, 0, 1, NULL, '2024-04-04 04:02:57'),
(19, 6, 'Head Office (Delhi)', 'HO DEL', 6, 'DEMOCO', 24, 0, 1, NULL, '2024-04-11 10:31:36'),
(20, 6, 'Branch Office (GJ)', 'BO', 6, 'DEMOCO', 24, 0, 1, NULL, '2024-04-11 10:31:58'),
(21, 7, 'Makarpura Vadodara', 'MKRPR', 7, 'SERITSOL', 26, 0, 1, NULL, '2024-05-21 07:52:06'),
(22, 9, 'Tarsali', 'TRSL', 7, 'SERITSOL', 26, 0, 1, NULL, '2024-05-21 07:52:21');

-- --------------------------------------------------------

--
-- Table structure for table `company_projects`
--

CREATE TABLE `company_projects` (
  `id` int(11) NOT NULL,
  `project_id` varchar(255) NOT NULL,
  `company_id` int(11) NOT NULL,
  `project_name` varchar(150) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0=active,1=cmpleted,2=cancelled,3=finishedverification',
  `due_date` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `begin_datetime` timestamp NULL DEFAULT NULL,
  `period_of_verification` varchar(10) DEFAULT NULL,
  `item_category` varchar(255) DEFAULT NULL,
  `instruction_to_user` text DEFAULT NULL,
  `project_type` varchar(100) DEFAULT NULL,
  `project_verifier` varchar(255) DEFAULT NULL,
  `project_location` int(11) DEFAULT NULL,
  `process_owner` int(11) DEFAULT NULL,
  `item_owner` int(11) DEFAULT NULL,
  `manager` int(11) DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `original_table_name` varchar(255) DEFAULT NULL,
  `project_table_name` varchar(255) DEFAULT NULL,
  `project_header_id` int(11) DEFAULT NULL,
  `cancelled_date` date DEFAULT NULL,
  `finish_datetime` datetime DEFAULT NULL,
  `verification_closed_by` int(11) DEFAULT NULL,
  `project_finished_by` int(11) DEFAULT NULL,
  `cancel_reason` text DEFAULT NULL,
  `end_remark` text DEFAULT NULL,
  `original_file` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `entity_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company_projects`
--

INSERT INTO `company_projects` (`id`, `project_id`, `company_id`, `project_name`, `status`, `due_date`, `start_date`, `begin_datetime`, `period_of_verification`, `item_category`, `instruction_to_user`, `project_type`, `project_verifier`, `project_location`, `process_owner`, `item_owner`, `manager`, `assigned_by`, `original_table_name`, `project_table_name`, `project_header_id`, `cancelled_date`, `finish_datetime`, `verification_closed_by`, `project_finished_by`, `cancel_reason`, `end_remark`, `original_file`, `created_at`, `entity_code`) VALUES
(1, 'Comp1/23/001/CD', 1, 'LD - Test Project 01 (mostly Furniture related items)', 0, '2023-09-03', '2023-08-29', NULL, 'FY22', '[\"F&F\",\"MED\"]', 'testing', 'CD', '1,2,4', 1, 3, 0, 3, 3, 'project_1693229295', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, './projectfiles/1cc5c174e8659f96047068d18b281d98.xlsx', '2023-08-28 13:44:33', 'LOADDEMO'),
(3, 'DLJM/23/003/TG', 3, 'DLJM D55 FAsset Tagging ', 2, '2023-10-10', '2023-10-03', NULL, 'FY22', '[\"OFFICE EQUIPMENTS\",\"FURNITURE & FIXTURE\",\"MOTOR CAR & SCOOTER\",\"COMMERCIAL VEHICLE\",\"COMPUTERS\",\"PLANT & MACHINERY\",\"ELECTRICAL INDUSTRIAL\",\"TOOLS & EQUIPMENTS\",\"GENSET\",\"PLANT & MACHINERY-LEASE\",\"LABORATORY\",\"MOULD\"]', 'complete verification to be done', 'TG', '6,7,8,9,10', 5, 0, 0, 5, 5, 'project_1696310819', NULL, 2, '2023-10-03', '2023-10-03 08:00:01', 10, 5, 'Remapping to be done', NULL, './projectfiles/030d33caa19f4d6cb9c299438abe0522.xlsx', '2023-10-03 05:31:02', 'DLJM'),
(4, 'DLJM/23/004/TG', 3, 'DLJM D55 FA Verification & Tagging', 2, '2023-10-11', '2023-10-03', NULL, 'FY22', '[\"OFFICE EQUIPMENTS\",\"FURNITURE & FIXTURE\",\"MOTOR CAR & SCOOTER\",\"COMMERCIAL VEHICLE\",\"COMPUTERS\",\"PLANT & MACHINERY\",\"ELECTRICAL INDUSTRIAL\",\"TOOLS & EQUIPMENTS\",\"GENSET\",\"PLANT & MACHINERY-LEASE\",\"LABORATORY\",\"MOULD\"]', NULL, 'TG', '6,7,8,9,10', 5, 0, 0, 5, 5, 'project_1696320172', NULL, 4, '2023-10-03', '2023-10-03 08:23:22', 10, 5, 'Remapping required', NULL, './projectfiles/53ec528c2210aed056270ba65bf31459.xlsx', '2023-10-03 08:06:45', 'DLJM'),
(5, 'DLJM/23/005/TG', 3, 'DLJM D-55 FA Verification & Tagging', 0, '2023-10-11', '2023-10-03', NULL, 'FY22', '[\"OFFICE EQUIPMENTS\",\"FURNITURE & FIXTURE\",\"MOTOR CAR & SCOOTER\",\"COMMERCIAL VEHICLE\",\"COMPUTERS\",\"PLANT & MACHINERY\",\"ELECTRICAL INDUSTRIAL\",\"TOOLS & EQUIPMENTS\",\"GENSET\",\"PLANT & MACHINERY-LEASE\",\"LABORATORY\",\"MOULD\"]', 'e', 'TG', '5,6,7,8,9,10', 5, 0, 0, 5, 5, 'project_1696321493', NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, './projectfiles/cf3f803758d4f65599d0e3b076941d7f.xlsx', '2023-10-03 08:27:24', 'DLJM'),
(6, 'HMCMM/24/006/CD', 4, 'HMCMM/old', 0, '2024-03-29', '2024-03-28', NULL, 'FY 23-24', '[\"COM-Old\",\"EI-Old\",\"FF-Old\",\"OE-Old\",\"PM-Old\",\"TJ-Old\",\"VEH-Old\"]', NULL, 'CD', '13,14,15,16,17', 6, 0, 0, 13, 13, 'project_1711599904', NULL, 6, NULL, '2024-04-24 05:14:10', 17, 17, NULL, 'All good', './projectfiles/b264ef1aaa2e00ecb98b06b9a653f98b.xlsx', '2024-03-28 04:29:21', 'HMCMM'),
(7, 'HMCMM/24/007/CD', 4, 'HMCMM/ NEW', 0, '2024-03-29', '2024-03-28', NULL, 'FY 23 -24', '[\"PM-New\",\"COM-New\",\"FF-New\",\"TJ-New\",\"VEH-New\",\"OE-New\"]', NULL, 'CD', '13,14,15,16,17', 6, 0, 0, 13, 13, 'project_1711599904', NULL, 6, NULL, '2024-04-03 04:40:44', 17, NULL, NULL, NULL, './projectfiles/b264ef1aaa2e00ecb98b06b9a653f98b.xlsx', '2024-03-28 04:30:50', 'HMCMM'),
(8, 'KMLD/24/008/CD', 5, 'KML - MAHAG - STOCK/ RM/ FG', 0, '2024-03-29', '2024-03-28', NULL, 'FY23-24', '[\"FINISHED GOODS\",\"RAW MATERIAL\"]', 'NA', 'CD', '17,18,19,20,21,22,23', 7, 0, 0, 17, 17, 'project_1711604526', NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, './projectfiles/a182e90e8106e1a1ed6352914cf5b09a.xlsx', '2024-03-28 05:45:11', 'HMCMM'),
(9, 'KMLD/24/009/CD', 5, 'KML - MAHAG - STOCK/ OTHERS (ACC)', 0, '2024-04-02', '2024-03-29', NULL, 'FY23-24', '[\"ACC\"]', '100%', 'CD', '17,20,21', 11, 0, 0, 17, 17, 'project_1711690286', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, './projectfiles/c028ea34a3cb4b121522a7a321bbd0fd.xlsx', '2024-03-29 05:33:13', 'HMCMM'),
(10, 'KMLD/24/10/CD', 5, 'KML - MAHAG - STOCK/ OTHERS (TRD)', 0, '2024-04-02', '2024-03-29', NULL, 'FY23-24', '[\"Trading\"]', '100%', 'CD', '17,20,21', 11, 0, 0, 17, 17, 'project_1711690286', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, './projectfiles/c028ea34a3cb4b121522a7a321bbd0fd.xlsx', '2024-03-29 05:34:00', 'HMCMM'),
(11, 'KMLD/24/11/CD', 5, 'KML - MAHAG - STOCK/ OTHERS (STORS/ PACK)', 0, '2024-04-02', '2024-03-29', NULL, 'FY23-24', '[\"Store & Spare\",\"Packing Material\"]', '100%', 'CD', '17,20,21', 11, 0, 0, 17, 17, 'project_1711690286', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, './projectfiles/c028ea34a3cb4b121522a7a321bbd0fd.xlsx', '2024-03-29 05:35:10', 'HMCMM'),
(12, 'KMLD/24/12/CD', 5, 'KML - SILV - ALL STOCK', 0, '2024-04-02', '2024-04-01', NULL, 'FY23-24', '[\"RAW MATERIAL\",\"SCRAP\",\"FINISH GOODS\",\"SEMI FINISH\",\"TRADING\",\"WIP\",\"STORE & SPARE\",\"SAMPLE\"]', NULL, 'CD', '17,18,19,22,23', 8, 0, 0, 17, 17, 'project_1711949358', NULL, 12, NULL, NULL, NULL, NULL, NULL, NULL, './projectfiles/9c0b55a1ce7f58bdfe1081eb1fb964bf.xlsx', '2024-04-01 05:30:34', 'HMCMM'),
(13, 'KMLD/24/13/CD', 5, 'KML - SILV - ALL FIXED ASSETS', 0, '2024-04-02', '2024-04-01', NULL, 'FY23-24', '[\"Plant & Machinery\",\"Lab Equipments\",\"Computers\",\"Office Equipment\",\"Furniture & Fixture\",\"Vehicles\",\"Material Handling Material\"]', NULL, 'CD', '18,19,23,22,17', 13, 0, 0, 17, 17, 'project_1711953321', NULL, 13, NULL, NULL, NULL, NULL, NULL, NULL, './projectfiles/b84d917301ae8b76753b9fb2d9291d54.xlsx', '2024-04-01 06:36:45', 'HMCMM'),
(14, 'KMLD/24/14/CD', 5, 'KML - MAHAG - FIXED ASSETS', 0, '2024-04-08', '2024-04-03', NULL, 'FY23-24', '[\"Plant & Machinery\",\"Factory Installations\",\"Lab Equipments\",\"Computers\",\"Office Equipment\",\"Furniture & Fixture\",\"Vehicles\",\"Material Handling Material\"]', '100%', 'CD', '17,21,20', 12, 0, 0, 17, 17, 'project_1712144417', NULL, 14, NULL, NULL, NULL, NULL, NULL, NULL, './projectfiles/f8dd7fa340cae1a8927a12a460e9e42e.xlsx', '2024-04-03 11:41:55', 'HMCMM'),
(15, 'KMLD/24/15/CD', 5, 'KML - JPR (Godown) - ALL FIXED ASSETS', 0, '2024-04-05', '2024-04-04', NULL, 'FY23-24', '[\"Plant & Machinery\",\"Office Equipments\",\"Furniture & Fixture\",\"Eqp Installations\",\"Computer\",\"Vehicles\"]', NULL, 'CD', '17,18,19', 17, 0, 0, 17, 17, 'project_1712150495', NULL, 15, NULL, NULL, NULL, NULL, NULL, NULL, './projectfiles/b65eb54e4247bc52fe6dfe6b13a1789b.xlsx', '2024-04-03 13:23:00', 'HMCMM'),
(16, 'KMLD/24/16/CD', 5, 'KML - JPR - ALL STOCK', 3, '2024-04-05', '2024-04-04', NULL, 'FY23-24', '[\"AGRI PIPES\",\"AGRIFITTINGS\",\"ASTM FITTINGS \",\"ASTM PIPES\",\"ASTM SOLVENT\",\"CPVC FITTINGS\",\"CPVC PIPES\",\"CPVC SOLVENT\",\"FABRICATE FITTINGS\",\"LUBRICANT\",\"PACKING MATERIAL(CPVC)\",\"PACKING MATERIAL(PVC)\",\"PVC SOLVENT\",\"SALES PROMOTION\",\"SAMPLES\",\"SWR FITTINGS', NULL, 'CD', '17,18,19', 10, 0, 0, 17, 17, 'project_1712202495', NULL, 16, NULL, '2024-04-05 10:06:37', 17, NULL, NULL, NULL, './projectfiles/7068ba5bead6e6220b721dc34b27de6d.xlsx', '2024-04-04 03:49:17', 'HMCMM'),
(17, 'KMLD/24/17/CD', 5, 'KML - JAIPUR (G) - ALL STOCK', 0, '2024-04-05', '2024-04-04', NULL, 'FY23-24', '[\"Godown Stock\"]', NULL, 'CD', '17,18,19', 18, 0, 0, 17, 17, 'project_1712203455', NULL, 17, NULL, NULL, NULL, NULL, NULL, NULL, './projectfiles/304104fd8f42e47dcdba9b0c4df3e01c.xlsx', '2024-04-04 04:05:23', 'HMCMM'),
(18, 'KMLD/24/18/CD', 5, 'KML - DEWAS (P) - ALL FIXED ASSETS', 0, '2024-04-05', '2024-04-04', NULL, 'FY23-24', '[\"Plant & Machinery\",\"Computers\",\"Office Equipment\",\"Furniture & Fixture\",\"Vehicles\",\"Lab Equipments\"]', NULL, 'CD', '17,20,21', 15, 0, 0, 17, 17, 'project_1712206397', NULL, 18, NULL, NULL, NULL, NULL, NULL, NULL, './projectfiles/899a8e8eae84c2a1048001a0a0cb7898.xlsx', '2024-04-04 04:54:45', 'HMCMM'),
(19, 'KMLD/24/19/CD', 5, 'KML - DEWAS (Godown) - ALL STOCK', 3, '2024-04-05', '2024-04-04', NULL, 'FY23-24', '[\"CPVC PIPES & FITT.\",\"MICRO IRRIGATION\",\"MOULDED FURNITURE\",\"PIPES & FITTINGS\",\"PVC PIPES\",\"RAW MATERIAL\",\"ROTO MOULDING\",\"SOLVENT\"]', NULL, 'CD', '17,20,21', 14, 0, 0, 17, 17, 'project_1712209745', NULL, 19, NULL, '2024-04-08 08:17:50', 21, NULL, NULL, NULL, './projectfiles/0d01227483527bbb1048a49f132bed42.xlsx', '2024-04-04 05:50:13', 'HMCMM'),
(20, 'KMLD/24/20/CD', 5, 'KML - DEWAS (Plant) - ALL STOCK', 3, '2024-04-05', '2024-04-04', NULL, 'FY23-24', '[\"FINISH GOODS\",\"RAW MATERIAL\",\"SCRAP GOODS\",\"STORE GOODS\"]', NULL, 'CD', '17,20,21', 9, 0, 0, 17, 17, 'project_1712211555', NULL, 20, NULL, '2024-04-08 06:02:10', 21, NULL, NULL, NULL, './projectfiles/bdc60fbcef8d138799bf56e0ed307745.xlsx', '2024-04-04 06:20:03', 'HMCMM'),
(21, 'KMLD/24/21/CD', 5, 'KML - DEWAS(Godown) - ALL FIXED ASSETS', 3, '2024-04-05', '2024-04-04', NULL, 'FY23-24', '[\"Plant & Machinery\",\"Computers\",\"Office Equipment\",\"Furniture & Fixture\",\"Vehicles\",\"Material Handling Material\"]', NULL, 'CD', '17,20,21', 16, 0, 0, 17, 17, 'project_1712231862', NULL, 21, NULL, '2024-04-07 09:57:18', 20, NULL, NULL, NULL, './projectfiles/b5f4d99405d3dc9a8caf325a5527f307.xlsx', '2024-04-04 11:58:51', 'HMCMM'),
(22, 'SEER/24/22/CD', 6, 'Test Demo - 01', 0, '2024-05-12', '2024-04-11', NULL, 'Test', '[\"OFFEQP\",\"P&M\",\"COMP\",\"VEH\"]', NULL, 'CD', '24,25', 20, 0, 0, 24, 25, 'project_1712831886', NULL, 22, NULL, NULL, NULL, NULL, NULL, NULL, './projectfiles/d65495aceb9a4ff0e153a2cfee3ebd5b.xlsx', '2024-04-11 10:39:55', 'DEMOCO'),
(23, 'SEER/24/23/CD', 6, 'Test Demo', 0, '2024-04-26', '2024-04-24', NULL, 'Test', '[\"Item Category*\"]', NULL, 'CD', '24', 19, 0, 0, 24, 24, 'project_1713936295', NULL, 23, NULL, NULL, NULL, NULL, NULL, NULL, './projectfiles/a24c299b520f0f878f550f7015bfe31c.xlsx', '2024-04-24 05:25:20', 'DEMOCO'),
(24, 'SEER/24/24/CD', 6, 'test demo 2', 0, '2024-04-26', '2024-04-24', NULL, 'FY23-24', '[\"OFFEQP\",\"P&M\",\"COMP\"]', NULL, 'CD', '25', 19, 0, 0, 24, 24, 'project_1713936295', NULL, 23, NULL, NULL, NULL, NULL, NULL, NULL, './projectfiles/a24c299b520f0f878f550f7015bfe31c.xlsx', '2024-04-24 05:26:00', 'DEMOCO');

-- --------------------------------------------------------

--
-- Table structure for table `contact_detail`
--

CREATE TABLE `contact_detail` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact_detail`
--

INSERT INTO `contact_detail` (`id`, `project_id`, `name`, `email`, `phone`, `designation`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Tika Ram', 'Tika@test.com', '87', 'Service Engg', 3, 0, '2023-08-28 13:53:51', '0000-00-00 00:00:00'),
(2, 1, 'Arjun Kumar', 'arjun@test.com', '0009', 'Admin Assistant', 3, 0, '2023-08-28 13:54:38', '0000-00-00 00:00:00'),
(3, 1, 'Aryan Chaudhary', 'aryan@test.don', '443', 'Security Admin', 3, 0, '2023-08-28 13:58:40', '0000-00-00 00:00:00'),
(5, 1, 'meenu', 'meenu@test.com', '2580', 'testing user', 4, 0, '2023-09-29 13:31:13', '0000-00-00 00:00:00'),
(6, 3, 'Jasmeet Singh', '', '9818946669', 'ProBuds Management', 5, 0, '2023-10-03 05:57:22', '0000-00-00 00:00:00'),
(7, 3, 'Priyvart', '', '8709269577', 'PB - Verifier', 5, 0, '2023-10-03 05:58:09', '0000-00-00 00:00:00'),
(8, 3, 'Sangeet Sagar', '', '8929140644', 'PB - Verifier', 5, 0, '2023-10-03 05:58:36', '0000-00-00 00:00:00'),
(9, 3, 'Khushi', '', '8743808692', 'PB - Verifier', 5, 0, '2023-10-03 05:59:04', '0000-00-00 00:00:00'),
(10, 3, 'Kartikey', '', '7290028886', 'PB - Verifier', 5, 0, '2023-10-03 06:00:33', '0000-00-00 00:00:00'),
(11, 3, 'Pradeep Kumar', '', '9310849700', 'PB - Verifier', 5, 0, '2023-10-03 06:01:04', '0000-00-00 00:00:00'),
(12, 3, 'Narendra Kumar', 'audit@dljm.in', '7835016162', 'DLJM Coordinator (FIN)', 5, 0, '2023-10-03 06:46:36', '0000-00-00 00:00:00'),
(14, 3, 'MANISH', '', '8800848054', 'MAINTENANCE Coordinator', 5, 0, '2023-10-03 07:05:07', '0000-00-00 00:00:00'),
(15, 4, 'Jasmeet Singh', '', '9818946669', 'PB - Coordinator', 5, 0, '2023-10-03 08:09:06', '0000-00-00 00:00:00'),
(16, 4, 'Priyvart', '', '8709269577', 'PB - Verifier', 5, 0, '2023-10-03 08:11:29', '0000-00-00 00:00:00'),
(17, 4, 'Sangeet Sagar', '-', '8929140644', 'PB - Verifier', 10, 0, '2023-10-03 08:14:09', '0000-00-00 00:00:00'),
(18, 4, 'khushi', 'na', '8743808692', 'PB - Verifier', 10, 0, '2023-10-03 08:14:40', '0000-00-00 00:00:00'),
(19, 4, 'Kartikey', 'na', '7290028886', 'PB - Verifier', 10, 0, '2023-10-03 08:15:07', '0000-00-00 00:00:00'),
(20, 4, 'Pradeep Kumar', 'na', '9310849700', 'PB - Verifier', 10, 0, '2023-10-03 08:15:37', '0000-00-00 00:00:00'),
(21, 4, 'Narendra Kumar', 'audit@dljm.in', '7835016162', 'DLJM - Coordinator ', 10, 0, '2023-10-03 08:16:40', '0000-00-00 00:00:00'),
(22, 4, 'MANISH', 'na', '880848054', 'Maintenance Coordinator ', 10, 0, '2023-10-03 08:17:15', '0000-00-00 00:00:00'),
(23, 5, 'Jasmeet Singh', 'na', '9818946669', 'PB - Coordinator ', 10, 0, '2023-10-03 08:38:33', '0000-00-00 00:00:00'),
(24, 5, 'Priyvart', 'na', '8709269577', 'PB - Verifier ', 10, 0, '2023-10-03 08:43:18', '0000-00-00 00:00:00'),
(25, 5, 'Sangeet Sagar', 'na', '8929140644', 'PB - Verifier ', 10, 0, '2023-10-03 08:43:27', '0000-00-00 00:00:00'),
(26, 5, 'Khushi', 'na', '8743808692', 'PB - Verifier ', 10, 0, '2023-10-03 08:43:34', '0000-00-00 00:00:00'),
(27, 5, 'Kartikey', 'na', '7290028886', 'PB - Verifier ', 10, 0, '2023-10-03 08:43:42', '0000-00-00 00:00:00'),
(28, 5, 'Pradeep Kumar', 'na', '9310849700', 'PB - Verifier ', 10, 0, '2023-10-03 08:43:50', '0000-00-00 00:00:00'),
(29, 5, 'Narendra Kumar', 'audit@dljm.in', '7835016162', 'DLJM - Coordinator (FIN)', 10, 0, '2023-10-03 08:42:46', '0000-00-00 00:00:00'),
(30, 5, 'Manish', 'na', '8800848054', 'Maintenance Coordinator ', 10, 0, '2023-10-03 08:45:00', '0000-00-00 00:00:00'),
(31, 5, 'Ankit ', 'na', '9999980384', 'HR Executive ', 10, 0, '2023-10-03 09:51:44', '0000-00-00 00:00:00'),
(32, 5, 'Uttsav', 'na', '8368755849', 'IT Coordinator ', 10, 0, '2023-10-03 10:25:10', '0000-00-00 00:00:00'),
(33, 5, 'Yogesh Sharma', 'na', '9999980406', 'Maintenance Coordinator ', 5, 0, '2023-10-03 12:31:19', '0000-00-00 00:00:00'),
(34, 5, 'Pawan Kumar', 'na', '9999980371', 'Maintenance Coordinator ', 5, 0, '2023-10-03 12:35:18', '0000-00-00 00:00:00'),
(35, 5, 'virpal', 'NA', '9891965866', 'maintenance/production ', 9, 0, '2023-10-05 04:45:22', '0000-00-00 00:00:00'),
(36, 5, 'pradeep ji', 'NA', '9695720205', 'maintenance (lab)', 9, 0, '2023-10-05 04:50:20', '0000-00-00 00:00:00'),
(37, 5, 'bhupesh ji', 'NA', '9999980419', 'maintenance (assembly top cover)', 9, 0, '2023-10-05 06:29:48', '0000-00-00 00:00:00'),
(38, 5, 'Dinanath Ji', 'NA', '9999980308', 'IT Coordinator ', 9, 0, '2023-10-12 10:16:29', '0000-00-00 00:00:00'),
(39, 25, 'HardikgiriContactName', 'HardikgiriContactEmail@gmail.com', '9639639630', 'HardikgiriContactDesignation', 26, 0, '2024-05-23 10:03:04', '0000-00-00 00:00:00'),
(40, 25, 'harDIK', 'HARDIK@GMAIL.COM', '787899999', 'ASDSA', 26, 0, '2024-05-23 10:06:15', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `department_shortcode` varchar(255) NOT NULL,
  `registered_user_id` int(11) NOT NULL,
  `entity_code` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `create_by` int(11) NOT NULL,
  `edited_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `department_name`, `department_shortcode`, `registered_user_id`, `entity_code`, `status`, `create_by`, `edited_by`, `created_at`, `updated_at`) VALUES
(1, 'INFORMATION TECHNOLOGY', 'IT', 1, 'LOADDEMO', 1, 1, 0, '2023-08-28 13:06:26', '0000-00-00 00:00:00'),
(2, 'FINANCE', 'FIN', 1, 'LOADDEMO', 1, 1, 0, '2023-08-28 13:06:36', '0000-00-00 00:00:00'),
(3, 'HR & ADMIN', 'HR', 1, 'LOADDEMO', 1, 1, 0, '2023-08-28 13:07:00', '0000-00-00 00:00:00'),
(4, 'MANAGEMENT', 'MGT', 1, 'LOADDEMO', 1, 1, 0, '2023-08-28 13:07:08', '0000-00-00 00:00:00'),
(5, 'MAINTENANCE', 'MAINT', 1, 'LOADDEMO', 1, 1, 0, '2023-08-28 13:07:15', '0000-00-00 00:00:00'),
(6, 'INFORMATION TECHNOLOGY', 'IT', 3, 'DLJM', 1, 5, 0, '2023-10-03 05:13:33', '0000-00-00 00:00:00'),
(7, 'MAINTENANCE', 'MAINT', 3, 'DLJM', 1, 5, 0, '2023-10-03 05:13:42', '0000-00-00 00:00:00'),
(8, 'HR & ADMIN', 'HR', 3, 'DLJM', 1, 5, 0, '2023-10-03 05:13:57', '0000-00-00 00:00:00'),
(9, 'PRODUCTION', 'PROD', 3, 'DLJM', 1, 5, 0, '2023-10-03 05:14:12', '0000-00-00 00:00:00'),
(10, 'TOOL ROOM', 'TOOL', 3, 'DLJM', 1, 5, 0, '2023-10-03 05:14:33', '0000-00-00 00:00:00'),
(11, 'FINANCE', 'FIN', 3, 'DLJM', 1, 5, 0, '2023-10-03 05:14:40', '0000-00-00 00:00:00'),
(12, 'MANAGEMENT', 'MGT', 3, 'DLJM', 1, 5, 0, '2023-10-03 05:14:48', '0000-00-00 00:00:00'),
(13, 'Consultant', 'CONS', 4, 'HMCMM', 1, 13, 0, '2024-03-26 07:45:05', '0000-00-00 00:00:00'),
(14, 'INFORMATION TECHNOLOGY', 'IT', 6, 'DEMOCO', 1, 24, 0, '2024-04-11 10:32:09', '0000-00-00 00:00:00'),
(15, 'Admin', 'ADMN', 7, 'SERITSOL', 1, 26, 0, '2024-05-21 07:52:35', '0000-00-00 00:00:00'),
(16, 'Manager', 'MNGR', 7, 'SERITSOL', 1, 26, 0, '2024-05-21 07:53:02', '0000-00-00 00:00:00'),
(17, 'Verifier', 'VRFR', 7, 'SERITSOL', 1, 26, 0, '2024-05-21 07:53:13', '0000-00-00 00:00:00'),
(18, 'Process Owner', 'PRCSOWNR', 7, 'SERITSOL', 1, 26, 0, '2024-05-21 07:53:28', '0000-00-00 00:00:00'),
(19, 'Entity Owner', 'ENTOWNR', 7, 'SERITSOL', 1, 26, 0, '2024-05-21 07:53:49', '0000-00-00 00:00:00'),
(20, 'Sub Admin', 'SBADMN', 7, 'SERITSOL', 1, 26, 0, '2024-05-21 07:54:07', '0000-00-00 00:00:00'),
(21, 'Group Admin', 'GRPADMN', 7, 'SERITSOL', 1, 26, 0, '2024-05-21 07:54:17', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `entity_code` varchar(255) NOT NULL,
  `registered_user_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `staus` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `entity_code`, `registered_user_id`, `type`, `title`, `description`, `staus`, `created_by`, `created_at`) VALUES
(1, 'SERITSOL', 7, 'Notification', 'Notification is only for Manager & verify user', '<p>Notification is only for Hardik User and Only Role for Manager &amp; verify user</p>', 0, 26, '2024-05-23 13:29:20'),
(2, 'SERITSOL', 7, 'Notification', 'Notification for All Users', '<p>Testing</p>', 0, 26, '2024-05-23 13:52:19'),
(3, 'SERITSOL', 7, 'Notification', 'Only for Verify & Manager', '<p>Only for Verify &amp; Manager&nbsp;</p>', 0, 26, '2024-05-27 15:07:09');

-- --------------------------------------------------------

--
-- Table structure for table `notification_read`
--

CREATE TABLE `notification_read` (
  `id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL,
  `reply_message_id` int(11) NOT NULL DEFAULT 0,
  `is_read` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notification_read`
--

INSERT INTO `notification_read` (`id`, `notification_id`, `reply_message_id`, `is_read`, `user_id`) VALUES
(1, 1, 0, 1, 28),
(2, 3, 0, 1, 27),
(3, 2, 0, 1, 27);

-- --------------------------------------------------------

--
-- Table structure for table `notification_reply`
--

CREATE TABLE `notification_reply` (
  `id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL,
  `reply_message` text NOT NULL,
  `reply_from` int(11) NOT NULL,
  `reply_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notification_user`
--

CREATE TABLE `notification_user` (
  `id` int(11) NOT NULL,
  `notification_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_role` int(11) DEFAULT NULL,
  `is_read` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notification_user`
--

INSERT INTO `notification_user` (`id`, `notification_id`, `user_id`, `user_role`, `is_read`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 27, 0, 0, 1, '2024-05-23 11:29:20', '2024-05-23 11:29:20'),
(2, 2, 31, 4, 0, 1, '2024-05-23 11:52:19', '2024-05-23 11:52:19'),
(3, 2, 30, 3, 0, 1, '2024-05-23 11:52:19', '2024-05-23 11:52:19'),
(4, 2, 29, 2, 0, 1, '2024-05-23 11:52:19', '2024-05-23 11:52:19'),
(5, 2, 26, 0, 0, 1, '2024-05-23 11:52:19', '2024-05-23 11:52:19'),
(6, 2, 27, 0, 0, 1, '2024-05-23 11:52:19', '2024-05-23 11:52:19'),
(7, 2, 26, 1, 0, 1, '2024-05-23 11:52:19', '2024-05-23 11:52:19'),
(8, 2, 28, 1, 0, 1, '2024-05-23 11:52:19', '2024-05-23 11:52:19'),
(9, 3, 29, 2, 0, 1, '2024-05-27 13:07:09', '2024-05-27 13:07:09'),
(10, 3, 27, 0, 0, 1, '2024-05-27 13:07:09', '2024-05-27 13:07:09'),
(11, 3, 28, 1, 0, 1, '2024-05-27 13:07:09', '2024-05-27 13:07:09');

-- --------------------------------------------------------

--
-- Table structure for table `project_headers`
--

CREATE TABLE `project_headers` (
  `id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `keyname` varchar(255) NOT NULL,
  `keylabel` varchar(255) NOT NULL,
  `is_editable` tinyint(4) NOT NULL COMMENT '0-no,1-yes',
  `createdat` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `project_headers`
--

INSERT INTO `project_headers` (`id`, `project_id`, `user_id`, `company_id`, `table_name`, `keyname`, `keylabel`, `is_editable`, `createdat`) VALUES
(942, 1, 3, 1, 'project_1693229295', 'item_category', 'item category', 0, '2023-08-28 13:42:21'),
(943, 1, 3, 1, 'project_1693229295', 'item_unique_code', 'item unique code', 0, '2023-08-28 13:42:21'),
(944, 1, 3, 1, 'project_1693229295', 'item_description', 'item description', 1, '2023-08-28 13:42:21'),
(945, 1, 3, 1, 'project_1693229295', 'location_of_the_item_last_verified', 'location of the item last verified', 0, '2023-08-28 13:42:21'),
(946, 1, 3, 1, 'project_1693229295', 'quantity_as_per_invoice', 'quantity as per invoice', 0, '2023-08-28 13:42:21'),
(947, 1, 3, 1, 'project_1693229295', 'verifiable_status_y_n_na', 'verifiable status y n na', 0, '2023-08-28 13:42:21'),
(948, 1, 3, 1, 'project_1693229295', 'tag_status_y_n_na', 'tag status y n na', 0, '2023-08-28 13:42:21'),
(949, 1, 3, 1, 'project_1693229295', 'total_item_amount_capitalized', 'total item amount capitalized', 0, '2023-08-28 13:42:21'),
(950, 1, 3, 1, 'project_1693229295', 'is_alotted', 'is alotted', 0, '2023-08-28 13:42:21'),
(951, 1, 3, 1, 'project_1693229295', 'mode_of_verification', 'mode of verification', 0, '2023-08-28 13:42:21'),
(952, 1, 3, 1, 'project_1693229295', 'item_sub_category', 'item sub category', 1, '2023-08-28 13:42:21'),
(953, 1, 3, 1, 'project_1693229295', 'item_sub_code', 'item sub code', 0, '2023-08-28 13:42:21'),
(954, 1, 3, 1, 'project_1693229295', 'dept_internal_item_code', 'dept internal item code', 0, '2023-08-28 13:42:21'),
(955, 1, 3, 1, 'project_1693229295', 'serial_product_number', 'serial product number', 1, '2023-08-28 13:42:21'),
(956, 1, 3, 1, 'project_1693229295', 'make', 'make', 1, '2023-08-28 13:42:21'),
(957, 1, 3, 1, 'project_1693229295', 'model', 'model', 1, '2023-08-28 13:42:21'),
(958, 1, 3, 1, 'project_1693229295', 'user_department', 'user department', 0, '2023-08-28 13:42:21'),
(959, 2, 5, 3, 'project_1696310819', 'item_category', 'item category', 0, '2023-10-03 05:28:10'),
(960, 2, 5, 3, 'project_1696310819', 'item_unique_code', 'item unique code', 0, '2023-10-03 05:28:10'),
(961, 2, 5, 3, 'project_1696310819', 'item_description', 'item description', 1, '2023-10-03 05:28:10'),
(962, 2, 5, 3, 'project_1696310819', 'location_of_the_item_last_verified', 'location of the item last verified', 0, '2023-10-03 05:28:10'),
(963, 2, 5, 3, 'project_1696310819', 'quantity_as_per_invoice', 'quantity as per invoice', 0, '2023-10-03 05:28:10'),
(964, 2, 5, 3, 'project_1696310819', 'verifiable_status_y_n_na', 'verifiable status y n na', 0, '2023-10-03 05:28:10'),
(965, 2, 5, 3, 'project_1696310819', 'tag_status_y_n_na', 'tag status y n na', 0, '2023-10-03 05:28:10'),
(966, 2, 5, 3, 'project_1696310819', 'total_item_amount_capitalized', 'total item amount capitalized', 0, '2023-10-03 05:28:10'),
(967, 2, 5, 3, 'project_1696310819', 'is_alotted', 'is alotted', 0, '2023-10-03 05:28:10'),
(968, 2, 5, 3, 'project_1696310819', 'mode_of_verification', 'mode of verification', 0, '2023-10-03 05:28:10'),
(969, 2, 5, 3, 'project_1696310819', 'item_sub_category', 'item sub category', 0, '2023-10-03 05:28:10'),
(970, 2, 5, 3, 'project_1696310819', 'item_classification', 'item classification', 0, '2023-10-03 05:28:10'),
(971, 2, 5, 3, 'project_1696310819', 'serial_product_number', 'serial product number', 1, '2023-10-03 05:28:10'),
(972, 2, 5, 3, 'project_1696310819', 'make', 'make', 1, '2023-10-03 05:28:10'),
(973, 2, 5, 3, 'project_1696310819', 'model', 'model', 1, '2023-10-03 05:28:10'),
(974, 2, 5, 3, 'project_1696310819', 'item_user', 'item user', 1, '2023-10-03 05:28:10'),
(975, 2, 5, 3, 'project_1696310819', 'user_department', 'user department', 0, '2023-10-03 05:28:10'),
(976, 2, 5, 3, 'project_1696310819', 'item_owner_dept', 'item owner dept', 0, '2023-10-03 05:28:10'),
(977, 2, 5, 3, 'project_1696310819', 'supplier_s_detail', 'supplier s detail', 0, '2023-10-03 05:28:10'),
(978, 2, 5, 3, 'project_1696310819', 'date_of_item_capitalization', 'date of item capitalization', 0, '2023-10-03 05:28:10'),
(979, 2, 5, 3, 'project_1696310819', 'wdv_at_the_end_of_reporting_period', 'wdv at the end of reporting period', 0, '2023-10-03 05:28:10'),
(980, 4, 5, 3, 'project_1696320172', 'item_category', 'item category', 0, '2023-10-03 08:05:20'),
(981, 4, 5, 3, 'project_1696320172', 'item_unique_code', 'item unique code', 0, '2023-10-03 08:05:20'),
(982, 4, 5, 3, 'project_1696320172', 'item_description', 'item description', 1, '2023-10-03 08:05:20'),
(983, 4, 5, 3, 'project_1696320172', 'location_of_the_item_last_verified', 'location of the item last verified', 0, '2023-10-03 08:05:20'),
(984, 4, 5, 3, 'project_1696320172', 'quantity_as_per_invoice', 'quantity as per invoice', 0, '2023-10-03 08:05:20'),
(985, 4, 5, 3, 'project_1696320172', 'verifiable_status_y_n_na', 'verifiable status y n na', 0, '2023-10-03 08:05:20'),
(986, 4, 5, 3, 'project_1696320172', 'tag_status_y_n_na', 'tag status y n na', 0, '2023-10-03 08:05:20'),
(987, 4, 5, 3, 'project_1696320172', 'total_item_amount_capitalized', 'total item amount capitalized', 0, '2023-10-03 08:05:20'),
(988, 4, 5, 3, 'project_1696320172', 'is_alotted', 'is alotted', 0, '2023-10-03 08:05:20'),
(989, 4, 5, 3, 'project_1696320172', 'mode_of_verification', 'mode of verification', 0, '2023-10-03 08:05:20'),
(990, 4, 5, 3, 'project_1696320172', 'item_classification', 'item classification', 0, '2023-10-03 08:05:20'),
(991, 4, 5, 3, 'project_1696320172', 'serial_product_number', 'serial product number', 1, '2023-10-03 08:05:20'),
(992, 4, 5, 3, 'project_1696320172', 'make', 'make', 1, '2023-10-03 08:05:20'),
(993, 4, 5, 3, 'project_1696320172', 'model', 'model', 1, '2023-10-03 08:05:20'),
(994, 4, 5, 3, 'project_1696320172', 'user_department', 'user department', 0, '2023-10-03 08:05:20'),
(995, 4, 5, 3, 'project_1696320172', 'item_owner_dept', 'item owner dept', 0, '2023-10-03 08:05:20'),
(996, 4, 5, 3, 'project_1696320172', 'supplier_s_detail', 'supplier s detail', 0, '2023-10-03 08:05:20'),
(997, 4, 5, 3, 'project_1696320172', 'date_of_item_capitalization', 'date of item capitalization', 0, '2023-10-03 08:05:20'),
(998, 4, 5, 3, 'project_1696320172', 'wdv_at_the_end_of_reporting_period', 'wdv at the end of reporting period', 0, '2023-10-03 08:05:20'),
(999, 5, 5, 3, 'project_1696321493', 'item_category', 'item category', 0, '2023-10-03 08:26:48'),
(1000, 5, 5, 3, 'project_1696321493', 'item_unique_code', 'item unique code', 0, '2023-10-03 08:26:48'),
(1001, 5, 5, 3, 'project_1696321493', 'item_description', 'item description', 1, '2023-10-03 08:26:48'),
(1002, 5, 5, 3, 'project_1696321493', 'location_of_the_item_last_verified', 'location of the item last verified', 0, '2023-10-03 08:26:48'),
(1003, 5, 5, 3, 'project_1696321493', 'quantity_as_per_invoice', 'quantity as per invoice', 0, '2023-10-03 08:26:48'),
(1004, 5, 5, 3, 'project_1696321493', 'verifiable_status_y_n_na', 'verifiable status y n na', 0, '2023-10-03 08:26:48'),
(1005, 5, 5, 3, 'project_1696321493', 'tag_status_y_n_na', 'tag status y n na', 0, '2023-10-03 08:26:48'),
(1006, 5, 5, 3, 'project_1696321493', 'total_item_amount_capitalized', 'total item amount capitalized', 0, '2023-10-03 08:26:48'),
(1007, 5, 5, 3, 'project_1696321493', 'is_alotted', 'is alotted', 0, '2023-10-03 08:26:48'),
(1008, 5, 5, 3, 'project_1696321493', 'mode_of_verification', 'mode of verification', 0, '2023-10-03 08:26:48'),
(1009, 5, 5, 3, 'project_1696321493', 'item_classification', 'item classification', 0, '2023-10-03 08:26:48'),
(1010, 5, 5, 3, 'project_1696321493', 'serial_product_number', 'serial product number', 1, '2023-10-03 08:26:48'),
(1011, 5, 5, 3, 'project_1696321493', 'make', 'make', 1, '2023-10-03 08:26:48'),
(1012, 5, 5, 3, 'project_1696321493', 'model', 'model', 1, '2023-10-03 08:26:48'),
(1013, 5, 5, 3, 'project_1696321493', 'user_department', 'user department', 0, '2023-10-03 08:26:48'),
(1014, 5, 5, 3, 'project_1696321493', 'item_owner_dept', 'item owner dept', 0, '2023-10-03 08:26:48'),
(1015, 5, 5, 3, 'project_1696321493', 'supplier_s_detail', 'supplier s detail', 0, '2023-10-03 08:26:48'),
(1016, 5, 5, 3, 'project_1696321493', 'wdv_at_the_end_of_reporting_period', 'wdv at the end of reporting period', 0, '2023-10-03 08:26:48'),
(1017, 6, 13, 4, 'project_1711599904', 'item_category', 'item category', 0, '2024-03-28 04:27:43'),
(1018, 6, 13, 4, 'project_1711599904', 'item_unique_code', 'item unique code', 0, '2024-03-28 04:27:43'),
(1019, 6, 13, 4, 'project_1711599904', 'item_description', 'item description', 1, '2024-03-28 04:27:43'),
(1020, 6, 13, 4, 'project_1711599904', 'location_of_the_item_last_verified', 'location of the item last verified', 0, '2024-03-28 04:27:43'),
(1021, 6, 13, 4, 'project_1711599904', 'quantity_as_per_invoice', 'quantity as per invoice', 0, '2024-03-28 04:27:43'),
(1022, 6, 13, 4, 'project_1711599904', 'verifiable_status_y_n_na', 'verifiable status y n na', 0, '2024-03-28 04:27:43'),
(1023, 6, 13, 4, 'project_1711599904', 'tag_status_y_n_na', 'tag status y n na', 0, '2024-03-28 04:27:43'),
(1024, 6, 13, 4, 'project_1711599904', 'total_item_amount_capitalized', 'total item amount capitalized', 0, '2024-03-28 04:27:43'),
(1025, 6, 13, 4, 'project_1711599904', 'is_alotted', 'is alotted', 0, '2024-03-28 04:27:43'),
(1026, 6, 13, 4, 'project_1711599904', 'mode_of_verification', 'mode of verification', 0, '2024-03-28 04:27:43'),
(1027, 6, 13, 4, 'project_1711599904', 'item_sub_category', 'item sub category', 0, '2024-03-28 04:27:43'),
(1028, 6, 13, 4, 'project_1711599904', 'serial_product_number', 'serial product number', 1, '2024-03-28 04:27:43'),
(1029, 6, 13, 4, 'project_1711599904', 'make', 'make', 1, '2024-03-28 04:27:43'),
(1030, 6, 13, 4, 'project_1711599904', 'model', 'model', 1, '2024-03-28 04:27:43'),
(1031, 6, 13, 4, 'project_1711599904', 'item_user', 'item user', 1, '2024-03-28 04:27:43'),
(1032, 6, 13, 4, 'project_1711599904', 'user_department', 'user department', 1, '2024-03-28 04:27:43'),
(1033, 8, 17, 5, 'project_1711604526', 'item_category', 'item category', 0, '2024-03-28 05:43:58'),
(1034, 8, 17, 5, 'project_1711604526', 'item_unique_code', 'item unique code', 0, '2024-03-28 05:43:58'),
(1035, 8, 17, 5, 'project_1711604526', 'item_description', 'item description', 1, '2024-03-28 05:43:58'),
(1036, 8, 17, 5, 'project_1711604526', 'location_of_the_item_last_verified', 'location of the item last verified', 0, '2024-03-28 05:43:58'),
(1037, 8, 17, 5, 'project_1711604526', 'quantity_as_per_invoice', 'quantity as per invoice', 0, '2024-03-28 05:43:58'),
(1038, 8, 17, 5, 'project_1711604526', 'verifiable_status_y_n_na', 'verifiable status y n na', 0, '2024-03-28 05:43:58'),
(1039, 8, 17, 5, 'project_1711604526', 'tag_status_y_n_na', 'tag status y n na', 0, '2024-03-28 05:43:58'),
(1040, 8, 17, 5, 'project_1711604526', 'total_item_amount_capitalized', 'total item amount capitalized', 0, '2024-03-28 05:43:58'),
(1041, 8, 17, 5, 'project_1711604526', 'is_alotted', 'is alotted', 0, '2024-03-28 05:43:58'),
(1042, 8, 17, 5, 'project_1711604526', 'mode_of_verification', 'mode of verification', 0, '2024-03-28 05:43:58'),
(1043, 8, 17, 5, 'project_1711604526', 'item_sub_category', 'item sub category', 0, '2024-03-28 05:43:58'),
(1044, 8, 17, 5, 'project_1711604526', 'item_sub_code', 'item sub code', 0, '2024-03-28 05:43:58'),
(1045, 8, 17, 5, 'project_1711604526', 'component_details', 'component details', 0, '2024-03-28 05:43:58'),
(1046, 8, 17, 5, 'project_1711604526', 'uom', 'uom', 0, '2024-03-28 05:43:58'),
(1047, 9, 17, 5, 'project_1711690286', 'item_category', 'item category', 0, '2024-03-29 05:31:55'),
(1048, 9, 17, 5, 'project_1711690286', 'item_unique_code', 'item unique code', 0, '2024-03-29 05:31:55'),
(1049, 9, 17, 5, 'project_1711690286', 'item_description', 'item description', 1, '2024-03-29 05:31:55'),
(1050, 9, 17, 5, 'project_1711690286', 'location_of_the_item_last_verified', 'location of the item last verified', 0, '2024-03-29 05:31:55'),
(1051, 9, 17, 5, 'project_1711690286', 'quantity_as_per_invoice', 'quantity as per invoice', 0, '2024-03-29 05:31:55'),
(1052, 9, 17, 5, 'project_1711690286', 'verifiable_status_y_n_na', 'verifiable status y n na', 0, '2024-03-29 05:31:55'),
(1053, 9, 17, 5, 'project_1711690286', 'tag_status_y_n_na', 'tag status y n na', 0, '2024-03-29 05:31:55'),
(1054, 9, 17, 5, 'project_1711690286', 'total_item_amount_capitalized', 'total item amount capitalized', 0, '2024-03-29 05:31:55'),
(1055, 9, 17, 5, 'project_1711690286', 'is_alotted', 'is alotted', 0, '2024-03-29 05:31:55'),
(1056, 9, 17, 5, 'project_1711690286', 'mode_of_verification', 'mode of verification', 0, '2024-03-29 05:31:55'),
(1057, 9, 17, 5, 'project_1711690286', 'item_sub_category', 'item sub category', 0, '2024-03-29 05:31:55'),
(1058, 9, 17, 5, 'project_1711690286', 'item_sub_code', 'item sub code', 0, '2024-03-29 05:31:55'),
(1059, 9, 17, 5, 'project_1711690286', 'component_details', 'component details', 0, '2024-03-29 05:31:55'),
(1060, 9, 17, 5, 'project_1711690286', 'uom', 'uom', 0, '2024-03-29 05:31:55'),
(1061, 12, 17, 5, 'project_1711949358', 'item_category', 'item category', 0, '2024-04-01 05:29:49'),
(1062, 12, 17, 5, 'project_1711949358', 'item_unique_code', 'item unique code', 0, '2024-04-01 05:29:49'),
(1063, 12, 17, 5, 'project_1711949358', 'item_description', 'item description', 1, '2024-04-01 05:29:49'),
(1064, 12, 17, 5, 'project_1711949358', 'location_of_the_item_last_verified', 'location of the item last verified', 0, '2024-04-01 05:29:49'),
(1065, 12, 17, 5, 'project_1711949358', 'quantity_as_per_invoice', 'quantity as per invoice', 0, '2024-04-01 05:29:49'),
(1066, 12, 17, 5, 'project_1711949358', 'verifiable_status_y_n_na', 'verifiable status y n na', 0, '2024-04-01 05:29:49'),
(1067, 12, 17, 5, 'project_1711949358', 'tag_status_y_n_na', 'tag status y n na', 0, '2024-04-01 05:29:49'),
(1068, 12, 17, 5, 'project_1711949358', 'total_item_amount_capitalized', 'total item amount capitalized', 0, '2024-04-01 05:29:49'),
(1069, 12, 17, 5, 'project_1711949358', 'is_alotted', 'is alotted', 0, '2024-04-01 05:29:49'),
(1070, 12, 17, 5, 'project_1711949358', 'mode_of_verification', 'mode of verification', 0, '2024-04-01 05:29:49'),
(1071, 12, 17, 5, 'project_1711949358', 'item_sub_category', 'item sub category', 0, '2024-04-01 05:29:49'),
(1072, 12, 17, 5, 'project_1711949358', 'item_sub_code', 'item sub code', 0, '2024-04-01 05:29:49'),
(1073, 12, 17, 5, 'project_1711949358', 'item_classification', 'item classification', 0, '2024-04-01 05:29:49'),
(1074, 12, 17, 5, 'project_1711949358', 'uom', 'uom', 0, '2024-04-01 05:29:49'),
(1075, 13, 17, 5, 'project_1711953321', 'item_category', 'item category', 0, '2024-04-01 06:36:14'),
(1076, 13, 17, 5, 'project_1711953321', 'item_unique_code', 'item unique code', 0, '2024-04-01 06:36:14'),
(1077, 13, 17, 5, 'project_1711953321', 'item_description', 'item description', 1, '2024-04-01 06:36:14'),
(1078, 13, 17, 5, 'project_1711953321', 'location_of_the_item_last_verified', 'location of the item last verified', 0, '2024-04-01 06:36:14'),
(1079, 13, 17, 5, 'project_1711953321', 'quantity_as_per_invoice', 'quantity as per invoice', 0, '2024-04-01 06:36:14'),
(1080, 13, 17, 5, 'project_1711953321', 'verifiable_status_y_n_na', 'verifiable status y n na', 0, '2024-04-01 06:36:14'),
(1081, 13, 17, 5, 'project_1711953321', 'tag_status_y_n_na', 'tag status y n na', 0, '2024-04-01 06:36:14'),
(1082, 13, 17, 5, 'project_1711953321', 'total_item_amount_capitalized', 'total item amount capitalized', 0, '2024-04-01 06:36:14'),
(1083, 13, 17, 5, 'project_1711953321', 'is_alotted', 'is alotted', 0, '2024-04-01 06:36:14'),
(1084, 13, 17, 5, 'project_1711953321', 'mode_of_verification', 'mode of verification', 0, '2024-04-01 06:36:14'),
(1085, 13, 17, 5, 'project_1711953321', 'item_sub_category', 'item sub category', 0, '2024-04-01 06:36:14'),
(1086, 13, 17, 5, 'project_1711953321', 'item_classification', 'item classification', 1, '2024-04-01 06:36:14'),
(1087, 13, 17, 5, 'project_1711953321', 'component_details', 'component details', 0, '2024-04-01 06:36:14'),
(1088, 13, 17, 5, 'project_1711953321', 'serial_product_number', 'serial product number', 1, '2024-04-01 06:36:14'),
(1089, 13, 17, 5, 'project_1711953321', 'make', 'make', 1, '2024-04-01 06:36:14'),
(1090, 13, 17, 5, 'project_1711953321', 'model', 'model', 1, '2024-04-01 06:36:14'),
(1091, 13, 17, 5, 'project_1711953321', 'uom', 'uom', 0, '2024-04-01 06:36:14'),
(1092, 13, 17, 5, 'project_1711953321', 'date_of_item_capitalization', 'date of item capitalization', 0, '2024-04-01 06:36:14'),
(1093, 14, 17, 5, 'project_1712144417', 'item_category', 'item category', 0, '2024-04-03 11:41:16'),
(1094, 14, 17, 5, 'project_1712144417', 'item_unique_code', 'item unique code', 0, '2024-04-03 11:41:16'),
(1095, 14, 17, 5, 'project_1712144417', 'item_description', 'item description', 1, '2024-04-03 11:41:16'),
(1096, 14, 17, 5, 'project_1712144417', 'location_of_the_item_last_verified', 'location of the item last verified', 0, '2024-04-03 11:41:16'),
(1097, 14, 17, 5, 'project_1712144417', 'quantity_as_per_invoice', 'quantity as per invoice', 0, '2024-04-03 11:41:16'),
(1098, 14, 17, 5, 'project_1712144417', 'verifiable_status_y_n_na', 'verifiable status y n na', 0, '2024-04-03 11:41:16'),
(1099, 14, 17, 5, 'project_1712144417', 'tag_status_y_n_na', 'tag status y n na', 0, '2024-04-03 11:41:16'),
(1100, 14, 17, 5, 'project_1712144417', 'total_item_amount_capitalized', 'total item amount capitalized', 0, '2024-04-03 11:41:16'),
(1101, 14, 17, 5, 'project_1712144417', 'is_alotted', 'is alotted', 0, '2024-04-03 11:41:16'),
(1102, 14, 17, 5, 'project_1712144417', 'mode_of_verification', 'mode of verification', 0, '2024-04-03 11:41:16'),
(1103, 14, 17, 5, 'project_1712144417', 'item_sub_category', 'item sub category', 0, '2024-04-03 11:41:16'),
(1104, 14, 17, 5, 'project_1712144417', 'dept_internal_item_code', 'dept internal item code', 1, '2024-04-03 11:41:16'),
(1105, 14, 17, 5, 'project_1712144417', 'item_classification', 'item classification', 1, '2024-04-03 11:41:16'),
(1106, 14, 17, 5, 'project_1712144417', 'component_details', 'component details', 1, '2024-04-03 11:41:16'),
(1107, 14, 17, 5, 'project_1712144417', 'serial_product_number', 'serial product number', 1, '2024-04-03 11:41:16'),
(1108, 14, 17, 5, 'project_1712144417', 'make', 'make', 1, '2024-04-03 11:41:16'),
(1109, 14, 17, 5, 'project_1712144417', 'model', 'model', 1, '2024-04-03 11:41:16'),
(1110, 14, 17, 5, 'project_1712144417', 'item_user', 'item user', 1, '2024-04-03 11:41:16'),
(1111, 14, 17, 5, 'project_1712144417', 'uom', 'uom', 0, '2024-04-03 11:41:16'),
(1112, 14, 17, 5, 'project_1712144417', 'date_of_item_capitalization', 'date of item capitalization', 0, '2024-04-03 11:41:16'),
(1113, 15, 17, 5, 'project_1712150495', 'item_category', 'item category', 0, '2024-04-03 13:22:10'),
(1114, 15, 17, 5, 'project_1712150495', 'item_unique_code', 'item unique code', 0, '2024-04-03 13:22:10'),
(1115, 15, 17, 5, 'project_1712150495', 'item_description', 'item description', 1, '2024-04-03 13:22:10'),
(1116, 15, 17, 5, 'project_1712150495', 'location_of_the_item_last_verified', 'location of the item last verified', 0, '2024-04-03 13:22:10'),
(1117, 15, 17, 5, 'project_1712150495', 'quantity_as_per_invoice', 'quantity as per invoice', 0, '2024-04-03 13:22:10'),
(1118, 15, 17, 5, 'project_1712150495', 'verifiable_status_y_n_na', 'verifiable status y n na', 0, '2024-04-03 13:22:10'),
(1119, 15, 17, 5, 'project_1712150495', 'tag_status_y_n_na', 'tag status y n na', 0, '2024-04-03 13:22:10'),
(1120, 15, 17, 5, 'project_1712150495', 'total_item_amount_capitalized', 'total item amount capitalized', 0, '2024-04-03 13:22:10'),
(1121, 15, 17, 5, 'project_1712150495', 'is_alotted', 'is alotted', 0, '2024-04-03 13:22:10'),
(1122, 15, 17, 5, 'project_1712150495', 'mode_of_verification', 'mode of verification', 0, '2024-04-03 13:22:10'),
(1123, 15, 17, 5, 'project_1712150495', 'item_sub_category', 'item sub category', 0, '2024-04-03 13:22:10'),
(1124, 15, 17, 5, 'project_1712150495', 'item_classification', 'item classification', 1, '2024-04-03 13:22:10'),
(1125, 15, 17, 5, 'project_1712150495', 'component_details', 'component details', 1, '2024-04-03 13:22:10'),
(1126, 15, 17, 5, 'project_1712150495', 'serial_product_number', 'serial product number', 1, '2024-04-03 13:22:10'),
(1127, 15, 17, 5, 'project_1712150495', 'make', 'make', 1, '2024-04-03 13:22:10'),
(1128, 15, 17, 5, 'project_1712150495', 'model', 'model', 1, '2024-04-03 13:22:10'),
(1129, 15, 17, 5, 'project_1712150495', 'item_user', 'item user', 1, '2024-04-03 13:22:10'),
(1130, 15, 17, 5, 'project_1712150495', 'uom', 'uom', 0, '2024-04-03 13:22:10'),
(1131, 16, 17, 5, 'project_1712202495', 'item_category', 'item category', 0, '2024-04-04 03:48:47'),
(1132, 16, 17, 5, 'project_1712202495', 'item_unique_code', 'item unique code', 0, '2024-04-04 03:48:47'),
(1133, 16, 17, 5, 'project_1712202495', 'item_description', 'item description', 1, '2024-04-04 03:48:47'),
(1134, 16, 17, 5, 'project_1712202495', 'location_of_the_item_last_verified', 'location of the item last verified', 0, '2024-04-04 03:48:47'),
(1135, 16, 17, 5, 'project_1712202495', 'quantity_as_per_invoice', 'quantity as per invoice', 0, '2024-04-04 03:48:47'),
(1136, 16, 17, 5, 'project_1712202495', 'verifiable_status_y_n_na', 'verifiable status y n na', 0, '2024-04-04 03:48:47'),
(1137, 16, 17, 5, 'project_1712202495', 'tag_status_y_n_na', 'tag status y n na', 0, '2024-04-04 03:48:47'),
(1138, 16, 17, 5, 'project_1712202495', 'total_item_amount_capitalized', 'total item amount capitalized', 0, '2024-04-04 03:48:47'),
(1139, 16, 17, 5, 'project_1712202495', 'is_alotted', 'is alotted', 0, '2024-04-04 03:48:47'),
(1140, 16, 17, 5, 'project_1712202495', 'mode_of_verification', 'mode of verification', 0, '2024-04-04 03:48:47'),
(1141, 16, 17, 5, 'project_1712202495', 'item_sub_category', 'item sub category', 0, '2024-04-04 03:48:47'),
(1142, 16, 17, 5, 'project_1712202495', 'item_sub_code', 'item sub code', 0, '2024-04-04 03:48:47'),
(1143, 16, 17, 5, 'project_1712202495', 'uom', 'uom', 0, '2024-04-04 03:48:47'),
(1144, 17, 17, 5, 'project_1712203455', 'item_category', 'item category', 0, '2024-04-04 04:04:51'),
(1145, 17, 17, 5, 'project_1712203455', 'item_unique_code', 'item unique code', 0, '2024-04-04 04:04:51'),
(1146, 17, 17, 5, 'project_1712203455', 'item_description', 'item description', 1, '2024-04-04 04:04:51'),
(1147, 17, 17, 5, 'project_1712203455', 'location_of_the_item_last_verified', 'location of the item last verified', 0, '2024-04-04 04:04:51'),
(1148, 17, 17, 5, 'project_1712203455', 'quantity_as_per_invoice', 'quantity as per invoice', 0, '2024-04-04 04:04:51'),
(1149, 17, 17, 5, 'project_1712203455', 'verifiable_status_y_n_na', 'verifiable status y n na', 0, '2024-04-04 04:04:51'),
(1150, 17, 17, 5, 'project_1712203455', 'tag_status_y_n_na', 'tag status y n na', 0, '2024-04-04 04:04:51'),
(1151, 17, 17, 5, 'project_1712203455', 'total_item_amount_capitalized', 'total item amount capitalized', 0, '2024-04-04 04:04:51'),
(1152, 17, 17, 5, 'project_1712203455', 'is_alotted', 'is alotted', 0, '2024-04-04 04:04:51'),
(1153, 17, 17, 5, 'project_1712203455', 'mode_of_verification', 'mode of verification', 0, '2024-04-04 04:04:51'),
(1154, 17, 17, 5, 'project_1712203455', 'item_sub_category', 'item sub category', 1, '2024-04-04 04:04:51'),
(1155, 17, 17, 5, 'project_1712203455', 'item_sub_code', 'item sub code', 1, '2024-04-04 04:04:51'),
(1156, 17, 17, 5, 'project_1712203455', 'item_classification', 'item classification', 1, '2024-04-04 04:04:51'),
(1157, 17, 17, 5, 'project_1712203455', 'uom', 'uom', 0, '2024-04-04 04:04:51'),
(1158, 18, 17, 5, 'project_1712206397', 'item_category', 'item category', 0, '2024-04-04 04:54:04'),
(1159, 18, 17, 5, 'project_1712206397', 'item_unique_code', 'item unique code', 0, '2024-04-04 04:54:04'),
(1160, 18, 17, 5, 'project_1712206397', 'item_description', 'item description', 1, '2024-04-04 04:54:04'),
(1161, 18, 17, 5, 'project_1712206397', 'location_of_the_item_last_verified', 'location of the item last verified', 0, '2024-04-04 04:54:04'),
(1162, 18, 17, 5, 'project_1712206397', 'quantity_as_per_invoice', 'quantity as per invoice', 0, '2024-04-04 04:54:04'),
(1163, 18, 17, 5, 'project_1712206397', 'verifiable_status_y_n_na', 'verifiable status y n na', 0, '2024-04-04 04:54:04'),
(1164, 18, 17, 5, 'project_1712206397', 'tag_status_y_n_na', 'tag status y n na', 0, '2024-04-04 04:54:04'),
(1165, 18, 17, 5, 'project_1712206397', 'total_item_amount_capitalized', 'total item amount capitalized', 0, '2024-04-04 04:54:04'),
(1166, 18, 17, 5, 'project_1712206397', 'is_alotted', 'is alotted', 0, '2024-04-04 04:54:04'),
(1167, 18, 17, 5, 'project_1712206397', 'mode_of_verification', 'mode of verification', 0, '2024-04-04 04:54:04'),
(1168, 18, 17, 5, 'project_1712206397', 'item_sub_category', 'item sub category', 0, '2024-04-04 04:54:04'),
(1169, 18, 17, 5, 'project_1712206397', 'item_sub_code', 'item sub code', 1, '2024-04-04 04:54:04'),
(1170, 18, 17, 5, 'project_1712206397', 'dept_internal_item_code', 'dept internal item code', 0, '2024-04-04 04:54:04'),
(1171, 18, 17, 5, 'project_1712206397', 'item_classification', 'item classification', 1, '2024-04-04 04:54:04'),
(1172, 18, 17, 5, 'project_1712206397', 'component_details', 'component details', 1, '2024-04-04 04:54:04'),
(1173, 18, 17, 5, 'project_1712206397', 'serial_product_number', 'serial product number', 1, '2024-04-04 04:54:04'),
(1174, 18, 17, 5, 'project_1712206397', 'make', 'make', 1, '2024-04-04 04:54:04'),
(1175, 18, 17, 5, 'project_1712206397', 'model', 'model', 1, '2024-04-04 04:54:04'),
(1176, 18, 17, 5, 'project_1712206397', 'item_user', 'item user', 1, '2024-04-04 04:54:04'),
(1177, 18, 17, 5, 'project_1712206397', 'uom', 'uom', 0, '2024-04-04 04:54:04'),
(1178, 19, 17, 5, 'project_1712209745', 'item_category', 'item category', 0, '2024-04-04 05:49:38'),
(1179, 19, 17, 5, 'project_1712209745', 'item_unique_code', 'item unique code', 0, '2024-04-04 05:49:38'),
(1180, 19, 17, 5, 'project_1712209745', 'item_description', 'item description', 1, '2024-04-04 05:49:38'),
(1181, 19, 17, 5, 'project_1712209745', 'location_of_the_item_last_verified', 'location of the item last verified', 0, '2024-04-04 05:49:38'),
(1182, 19, 17, 5, 'project_1712209745', 'quantity_as_per_invoice', 'quantity as per invoice', 0, '2024-04-04 05:49:38'),
(1183, 19, 17, 5, 'project_1712209745', 'verifiable_status_y_n_na', 'verifiable status y n na', 0, '2024-04-04 05:49:38'),
(1184, 19, 17, 5, 'project_1712209745', 'tag_status_y_n_na', 'tag status y n na', 0, '2024-04-04 05:49:38'),
(1185, 19, 17, 5, 'project_1712209745', 'total_item_amount_capitalized', 'total item amount capitalized', 0, '2024-04-04 05:49:38'),
(1186, 19, 17, 5, 'project_1712209745', 'is_alotted', 'is alotted', 0, '2024-04-04 05:49:38'),
(1187, 19, 17, 5, 'project_1712209745', 'mode_of_verification', 'mode of verification', 0, '2024-04-04 05:49:38'),
(1188, 19, 17, 5, 'project_1712209745', 'item_sub_category', 'item sub category', 1, '2024-04-04 05:49:38'),
(1189, 19, 17, 5, 'project_1712209745', 'item_sub_code', 'item sub code', 0, '2024-04-04 05:49:38'),
(1190, 19, 17, 5, 'project_1712209745', 'uom', 'uom', 0, '2024-04-04 05:49:38'),
(1191, 20, 17, 5, 'project_1712211555', 'item_category', 'item category', 0, '2024-04-04 06:19:37'),
(1192, 20, 17, 5, 'project_1712211555', 'item_unique_code', 'item unique code', 0, '2024-04-04 06:19:37'),
(1193, 20, 17, 5, 'project_1712211555', 'item_description', 'item description', 1, '2024-04-04 06:19:37'),
(1194, 20, 17, 5, 'project_1712211555', 'location_of_the_item_last_verified', 'location of the item last verified', 0, '2024-04-04 06:19:37'),
(1195, 20, 17, 5, 'project_1712211555', 'quantity_as_per_invoice', 'quantity as per invoice', 0, '2024-04-04 06:19:37'),
(1196, 20, 17, 5, 'project_1712211555', 'verifiable_status_y_n_na', 'verifiable status y n na', 0, '2024-04-04 06:19:37'),
(1197, 20, 17, 5, 'project_1712211555', 'tag_status_y_n_na', 'tag status y n na', 0, '2024-04-04 06:19:37'),
(1198, 20, 17, 5, 'project_1712211555', 'total_item_amount_capitalized', 'total item amount capitalized', 0, '2024-04-04 06:19:37'),
(1199, 20, 17, 5, 'project_1712211555', 'is_alotted', 'is alotted', 0, '2024-04-04 06:19:37'),
(1200, 20, 17, 5, 'project_1712211555', 'mode_of_verification', 'mode of verification', 0, '2024-04-04 06:19:37'),
(1201, 20, 17, 5, 'project_1712211555', 'item_sub_category', 'item sub category', 0, '2024-04-04 06:19:37'),
(1202, 20, 17, 5, 'project_1712211555', 'item_sub_code', 'item sub code', 0, '2024-04-04 06:19:37'),
(1203, 20, 17, 5, 'project_1712211555', 'uom', 'uom', 0, '2024-04-04 06:19:37'),
(1204, 21, 17, 5, 'project_1712231862', 'item_category', 'item category', 0, '2024-04-04 11:58:23'),
(1205, 21, 17, 5, 'project_1712231862', 'item_unique_code', 'item unique code', 0, '2024-04-04 11:58:23'),
(1206, 21, 17, 5, 'project_1712231862', 'item_description', 'item description', 1, '2024-04-04 11:58:23'),
(1207, 21, 17, 5, 'project_1712231862', 'location_of_the_item_last_verified', 'location of the item last verified', 0, '2024-04-04 11:58:23'),
(1208, 21, 17, 5, 'project_1712231862', 'quantity_as_per_invoice', 'quantity as per invoice', 0, '2024-04-04 11:58:23'),
(1209, 21, 17, 5, 'project_1712231862', 'verifiable_status_y_n_na', 'verifiable status y n na', 0, '2024-04-04 11:58:23'),
(1210, 21, 17, 5, 'project_1712231862', 'tag_status_y_n_na', 'tag status y n na', 0, '2024-04-04 11:58:23'),
(1211, 21, 17, 5, 'project_1712231862', 'total_item_amount_capitalized', 'total item amount capitalized', 0, '2024-04-04 11:58:23'),
(1212, 21, 17, 5, 'project_1712231862', 'is_alotted', 'is alotted', 0, '2024-04-04 11:58:23'),
(1213, 21, 17, 5, 'project_1712231862', 'mode_of_verification', 'mode of verification', 0, '2024-04-04 11:58:23'),
(1214, 21, 17, 5, 'project_1712231862', 'item_sub_category', 'item sub category', 1, '2024-04-04 11:58:23'),
(1215, 21, 17, 5, 'project_1712231862', 'item_sub_code', 'item sub code', 1, '2024-04-04 11:58:23'),
(1216, 21, 17, 5, 'project_1712231862', 'item_classification', 'item classification', 1, '2024-04-04 11:58:23'),
(1217, 21, 17, 5, 'project_1712231862', 'component_details', 'component details', 1, '2024-04-04 11:58:23'),
(1218, 21, 17, 5, 'project_1712231862', 'serial_product_number', 'serial product number', 1, '2024-04-04 11:58:23'),
(1219, 21, 17, 5, 'project_1712231862', 'make', 'make', 1, '2024-04-04 11:58:23'),
(1220, 21, 17, 5, 'project_1712231862', 'model', 'model', 1, '2024-04-04 11:58:23'),
(1221, 21, 17, 5, 'project_1712231862', 'uom', 'uom', 0, '2024-04-04 11:58:23'),
(1222, 22, 25, 6, 'project_1712831886', 'item_category', 'item category', 0, '2024-04-11 10:38:47'),
(1223, 22, 25, 6, 'project_1712831886', 'item_unique_code', 'item unique code', 0, '2024-04-11 10:38:47'),
(1224, 22, 25, 6, 'project_1712831886', 'item_description', 'item description', 1, '2024-04-11 10:38:47'),
(1225, 22, 25, 6, 'project_1712831886', 'location_of_the_item_last_verified', 'location of the item last verified', 0, '2024-04-11 10:38:47'),
(1226, 22, 25, 6, 'project_1712831886', 'quantity_as_per_invoice', 'quantity as per invoice', 0, '2024-04-11 10:38:47'),
(1227, 22, 25, 6, 'project_1712831886', 'verifiable_status_y_n_na', 'verifiable status y n na', 0, '2024-04-11 10:38:47'),
(1228, 22, 25, 6, 'project_1712831886', 'tag_status_y_n_na', 'tag status y n na', 0, '2024-04-11 10:38:47'),
(1229, 22, 25, 6, 'project_1712831886', 'total_item_amount_capitalized', 'total item amount capitalized', 0, '2024-04-11 10:38:47'),
(1230, 22, 25, 6, 'project_1712831886', 'is_alotted', 'is alotted', 0, '2024-04-11 10:38:47'),
(1231, 22, 25, 6, 'project_1712831886', 'mode_of_verification', 'mode of verification', 0, '2024-04-11 10:38:47'),
(1232, 22, 25, 6, 'project_1712831886', 'item_sub_category', 'item sub category', 1, '2024-04-11 10:38:47'),
(1233, 22, 25, 6, 'project_1712831886', 'item_sub_code', 'item sub code', 1, '2024-04-11 10:38:47'),
(1234, 22, 25, 6, 'project_1712831886', 'dept_internal_item_code', 'dept internal item code', 1, '2024-04-11 10:38:47'),
(1235, 22, 25, 6, 'project_1712831886', 'item_classification', 'item classification', 1, '2024-04-11 10:38:47'),
(1236, 22, 25, 6, 'project_1712831886', 'component_details', 'component details', 1, '2024-04-11 10:38:47'),
(1237, 22, 25, 6, 'project_1712831886', 'serial_product_number', 'serial product number', 1, '2024-04-11 10:38:47'),
(1238, 22, 25, 6, 'project_1712831886', 'make', 'make', 1, '2024-04-11 10:38:47'),
(1239, 22, 25, 6, 'project_1712831886', 'model', 'model', 1, '2024-04-11 10:38:47'),
(1240, 22, 25, 6, 'project_1712831886', 'item_user', 'item user', 1, '2024-04-11 10:38:47'),
(1241, 22, 25, 6, 'project_1712831886', 'user_department', 'user department', 0, '2024-04-11 10:38:47'),
(1242, 22, 25, 6, 'project_1712831886', 'uom', 'uom', 0, '2024-04-11 10:38:47'),
(1243, 22, 25, 6, 'project_1712831886', 'date_of_item_capitalization', 'date of item capitalization', 0, '2024-04-11 10:38:47'),
(1244, 23, 24, 6, 'project_1713936295', 'item_category', 'item category', 0, '2024-04-24 05:24:58'),
(1245, 23, 24, 6, 'project_1713936295', 'item_unique_code', 'item unique code', 0, '2024-04-24 05:24:58'),
(1246, 23, 24, 6, 'project_1713936295', 'item_description', 'item description', 1, '2024-04-24 05:24:58'),
(1247, 23, 24, 6, 'project_1713936295', 'location_of_the_item_last_verified', 'location of the item last verified', 0, '2024-04-24 05:24:58'),
(1248, 23, 24, 6, 'project_1713936295', 'quantity_as_per_invoice', 'quantity as per invoice', 0, '2024-04-24 05:24:58'),
(1249, 23, 24, 6, 'project_1713936295', 'verifiable_status_y_n_na', 'verifiable status y n na', 0, '2024-04-24 05:24:58'),
(1250, 23, 24, 6, 'project_1713936295', 'tag_status_y_n_na', 'tag status y n na', 0, '2024-04-24 05:24:58'),
(1251, 23, 24, 6, 'project_1713936295', 'total_item_amount_capitalized', 'total item amount capitalized', 0, '2024-04-24 05:24:58'),
(1252, 23, 24, 6, 'project_1713936295', 'is_alotted', 'is alotted', 0, '2024-04-24 05:24:58'),
(1253, 23, 24, 6, 'project_1713936295', 'mode_of_verification', 'mode of verification', 0, '2024-04-24 05:24:58'),
(1254, 25, 26, 7, 'project_1716271513', 'id', 'id', 1, '2024-05-21 11:36:31'),
(1255, 25, 26, 7, 'project_1716271513', 'item_category', 'item category', 1, '2024-05-21 11:36:31'),
(1256, 25, 26, 7, 'project_1716271513', 'item_sub_category', 'item sub category', 1, '2024-05-21 11:36:31'),
(1257, 25, 26, 7, 'project_1716271513', 'item_unique_code', 'item unique code', 1, '2024-05-21 11:36:31'),
(1258, NULL, 27, 7, 'project_1716456206', 'item_category', 'item category', 1, '2024-05-23 14:56:40'),
(1259, NULL, 27, 7, 'project_1716456206', 'item_sub_category', 'item sub category', 1, '2024-05-23 14:56:40'),
(1260, 26, 27, 7, 'project_1716456559', 'item_category', 'item category', 0, '2024-05-23 14:59:47'),
(1261, 26, 27, 7, 'project_1716456559', 'item_sub_category', 'item sub category', 0, '2024-05-23 14:59:47'),
(1262, 26, 27, 7, 'project_1716456559', 'item_unique_code', 'item unique code', 1, '2024-05-23 14:59:47'),
(1263, 27, 26, 7, 'project_1716463346', 'item_category', 'item category', 0, '2024-05-23 16:52:34'),
(1264, 27, 26, 7, 'project_1716463346', 'item_sub_category', 'item sub category', 0, '2024-05-23 16:52:34'),
(1265, 27, 26, 7, 'project_1716463346', 'item_unique_code', 'item unique code', 1, '2024-05-23 16:52:34');

-- --------------------------------------------------------

--
-- Table structure for table `registered_user_plan`
--

CREATE TABLE `registered_user_plan` (
  `id` int(11) NOT NULL,
  `regiistered_user_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `subscription_time_value` varchar(255) NOT NULL,
  `time_subscription` varchar(255) NOT NULL,
  `plan_start_date` date NOT NULL,
  `plan_end_date` date NOT NULL,
  `category_subscription` varchar(255) NOT NULL,
  `credit_days` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `registered_user_plan`
--

INSERT INTO `registered_user_plan` (`id`, `regiistered_user_id`, `plan_id`, `subscription_time_value`, `time_subscription`, `plan_start_date`, `plan_end_date`, `category_subscription`, `credit_days`, `created_at`, `updated_at`) VALUES
(1, 1, 5, '2', 'year', '2023-08-28', '2025-08-28', 'Original', '0', '2023-12-23 06:03:44', '0000-00-00 00:00:00'),
(2, 2, 6, '1', 'year', '2023-08-28', '2024-08-28', 'Original', '0', '2023-12-20 05:25:57', '0000-00-00 00:00:00'),
(3, 3, 3, '360', 'days', '2023-10-03', '2024-09-27', 'Original', '0', '2023-10-03 05:10:48', '0000-00-00 00:00:00'),
(4, 4, 6, '1', 'year', '2024-03-26', '2025-03-26', 'Original', '0', '2024-03-26 07:38:33', '0000-00-00 00:00:00'),
(5, 5, 4, '1', 'year', '2024-03-26', '2025-03-26', 'Original', '0', '2024-03-26 07:32:22', '0000-00-00 00:00:00'),
(6, 6, 4, '1', 'year', '2024-04-11', '2025-04-11', 'Original', '0', '2024-04-11 10:29:43', '0000-00-00 00:00:00'),
(7, 7, 4, '20000', 'year', '2024-05-21', '2004-05-21', 'Original', '10', '2024-05-21 07:47:53', '0000-00-00 00:00:00'),
(8, 8, 4, '1', 'year', '2024-05-23', '2025-05-23', 'Original', '0', '2024-05-23 14:37:38', '0000-00-00 00:00:00'),
(9, 9, 4, '1', 'year', '2024-05-24', '2025-05-24', 'Original', '0', '2024-05-24 10:11:49', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `register_user_plan_log`
--

CREATE TABLE `register_user_plan_log` (
  `id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `upgrated_plan_id` int(11) NOT NULL,
  `register_user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `register_user_plan_log`
--

INSERT INTO `register_user_plan_log` (`id`, `plan_id`, `upgrated_plan_id`, `register_user_id`, `created_at`) VALUES
(1, 3, 2, 3, '2023-12-20 05:38:23'),
(2, 2, 3, 3, '2023-12-20 05:38:37'),
(3, 2, 4, 1, '2023-12-23 06:02:36'),
(4, 4, 5, 1, '2023-12-23 06:07:20'),
(5, 4, 6, 4, '2024-04-03 13:10:25'),
(6, 1, 6, 2, '2024-05-23 14:11:14'),
(8, 6, 0, 7, '2024-05-24 11:40:14'),
(9, 4, 6, 7, '2024-05-23 14:11:28'),
(10, 4, 6, 7, '2024-05-24 11:53:27'),
(11, 6, 4, 7, '2024-05-27 15:03:32');

-- --------------------------------------------------------

--
-- Table structure for table `registred_users`
--

CREATE TABLE `registred_users` (
  `id` int(11) NOT NULL,
  `urer_registration_no` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_view` varchar(255) NOT NULL,
  `phone_no` varchar(255) NOT NULL,
  `is_gst` int(11) NOT NULL,
  `organisation_name` varchar(255) NOT NULL,
  `gst_no` varchar(255) DEFAULT NULL,
  `name_org_gst` varchar(255) NOT NULL,
  `address_org_gst` varchar(255) NOT NULL,
  `entity_code` varchar(255) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1 COMMENT '1=Active,2=expire,3=regeneration,4=reactive,5=suspend,6=unsubscribe',
  `balance_due` varchar(255) NOT NULL,
  `activation_generete_link` int(11) NOT NULL DEFAULT 0,
  `activation_generete_link_date` date DEFAULT NULL,
  `is_activation_send` int(11) NOT NULL DEFAULT 0,
  `activation_send_date` date DEFAULT NULL,
  `link_expiry_date` date DEFAULT NULL,
  `regenrred_link_send_date` date DEFAULT NULL,
  `suspend_date` date DEFAULT NULL,
  `unsubscribe_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `regenerate_activation_date` date DEFAULT NULL,
  `activation_link` text DEFAULT NULL,
  `is_transfer` int(11) NOT NULL DEFAULT 0,
  `transfer_at` datetime DEFAULT NULL,
  `renew_request` int(11) NOT NULL DEFAULT 0,
  `request_renew_at` date DEFAULT NULL,
  `is_resubscribe_request` int(11) NOT NULL DEFAULT 0,
  `is_resubscribe_request_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `registred_users`
--

INSERT INTO `registred_users` (`id`, `urer_registration_no`, `first_name`, `last_name`, `email_id`, `password`, `password_view`, `phone_no`, `is_gst`, `organisation_name`, `gst_no`, `name_org_gst`, `address_org_gst`, `entity_code`, `plan_id`, `is_active`, `balance_due`, `activation_generete_link`, `activation_generete_link_date`, `is_activation_send`, `activation_send_date`, `link_expiry_date`, `regenrred_link_send_date`, `suspend_date`, `unsubscribe_date`, `created_at`, `updated_at`, `regenerate_activation_date`, `activation_link`, `is_transfer`, `transfer_at`, `renew_request`, `request_renew_at`, `is_resubscribe_request`, `is_resubscribe_request_at`) VALUES
(1, '202308VFA23203', 'TARUN', 'LAMBA', 'tarun@test.com', '827ccb0eea8a706c4c34a16891f84e7b', '12345', '876543201', 1, 'Load Testing Data Private Limited', 'LOADDEMO GST', 'Load Testing Data Pvt Ltd', 'New Delhi', 'LOADDEMO', 5, 4, '0', 1, '2023-08-28', 1, '2023-08-28', '2023-08-29', NULL, NULL, NULL, '2023-08-28 12:24:34', '2023-12-23 06:03:44', NULL, 'http://verifyfa.com/Dev/index.php/activation-registered-user/1', 0, NULL, 0, '0000-00-00', 0, NULL),
(2, '202308VFA05341', 'AMIT', 'KUMAR', 'amit@test.com', '827ccb0eea8a706c4c34a16891f84e7b', '12345', '9988778899', 0, 'ORGPREM TRADING PRIVATE LIMITED', '', '', '', 'ORGPREM', 6, 4, '400', 1, '2023-08-28', 1, '2023-08-28', '2023-08-29', NULL, NULL, NULL, '2023-08-28 12:26:30', '2023-12-20 05:25:57', NULL, 'http://verifyfa.com/Dev/index.php/activation-registered-user/2', 0, NULL, 0, '0000-00-00', 0, NULL),
(3, '202310VFA25113', 'Jasmeet', 'Singh', 'jasmeet@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', '12345', '9818946669', 0, 'DLJM Molding Pvt Ltd', '', '', '', 'DLJM', 3, 4, '1', 1, '2023-10-03', 1, '2023-10-03', '2023-10-04', NULL, NULL, NULL, '2023-10-03 05:10:48', '0000-00-00 00:00:00', NULL, 'http://verifyfa.com/Dev/index.php/activation-registered-user/3', 0, NULL, 0, NULL, 0, NULL),
(4, '202403VFA81174', 'Shubh', 'Kirti', 'shubh@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', '12345', '9818946669', 0, 'HMC MM Auto Ltd', '', '', '', 'HMCMM', 6, 4, '0', 1, '2024-03-26', 1, '2024-03-26', '2024-03-27', NULL, '2024-03-26', NULL, '2024-03-26 07:28:43', '2024-03-26 07:38:33', NULL, 'http://verifyfa.com/Dev/index.php/activation-registered-user/4', 0, NULL, 0, '0000-00-00', 4, '0000-00-00'),
(5, '202403VFA61856', 'Jasmeet', 'Singh', 'jasmeet.s@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', '12345', '9818946669', 0, 'Kisan Molding Ltd', '', '', '', 'KMLD', 4, 4, '0', 1, '2024-03-26', 1, '2024-03-26', '2024-03-27', NULL, '2024-03-26', NULL, '2024-03-26 07:32:22', '0000-00-00 00:00:00', NULL, 'http://verifyfa.com/Dev/index.php/activation-registered-user/5', 0, NULL, 0, NULL, 4, '0000-00-00'),
(6, '202404VFA42922', 'Jasmeet', 'Singh', 'jasmeet@test.com', '827ccb0eea8a706c4c34a16891f84e7b', '12345', '88888888', 0, 'IT Seer Demo Co.', '', '', '', 'DEMOCO', 4, 4, '19999', 1, '2024-04-11', 1, '2024-04-11', '2024-04-12', NULL, NULL, NULL, '2024-04-11 10:29:43', '0000-00-00 00:00:00', NULL, 'http://verifyfa.com/Dev/index.php/activation-registered-user/6', 0, NULL, 0, NULL, 0, NULL),
(7, '202405VFA99913', 'HardikGiri', 'Meghnathi', 'hardik.meghnathi12@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '12345', '9904841361', 0, 'Seer IT Solutions', '', '', '', 'SERITSOL', 4, 4, '0', 1, '2024-05-21', 1, '2024-05-21', '2024-05-22', NULL, NULL, NULL, '2024-05-21 07:47:53', '0000-00-00 00:00:00', NULL, 'http://localhost/CODEIGNITER_PHP/Verifyfa/Dev/index.php/activation-registered-user/7', 0, NULL, 0, NULL, 0, NULL),
(8, '202405VFA63585', 'RonakGiri', 'Meghnathi', 'ronakmeghnathi@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '12345', '9727803554', 0, 'Shiv Online Services', '', '', '', 'SHIV', 4, 1, '18000', 1, '2024-05-23', 0, NULL, NULL, NULL, NULL, NULL, '2024-05-23 14:37:38', '0000-00-00 00:00:00', NULL, 'http://localhost/CODEIGNITER_PHP/Verifyfa/Dev/index.php/activation-registered-user/8', 0, NULL, 0, NULL, 0, NULL),
(9, '202405VFA23350', 'DeveloperOne', 'One', 'developerone@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '12345', '9898989898', 0, 'Dev One', '', '', '', 'DEVONE', 4, 1, '0', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, '2024-05-24 10:11:49', '0000-00-00 00:00:00', NULL, NULL, 0, NULL, 0, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `registred_users_payment`
--

CREATE TABLE `registred_users_payment` (
  `id` int(11) NOT NULL,
  `regiistered_user_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `amount_subs_due` varchar(255) NOT NULL,
  `paymentother_charge` varchar(255) NOT NULL,
  `discount_credits` varchar(255) NOT NULL,
  `total_amount_payble` varchar(255) NOT NULL,
  `payment_amount` varchar(255) NOT NULL,
  `balance_refundable` varchar(255) NOT NULL,
  `payment_date` varchar(255) NOT NULL,
  `mode_of_payment` varchar(255) NOT NULL,
  `transection_ref` varchar(255) NOT NULL,
  `payment_remarks` varchar(255) NOT NULL,
  `transection_id` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `registred_users_payment`
--

INSERT INTO `registred_users_payment` (`id`, `regiistered_user_id`, `plan_id`, `amount_subs_due`, `paymentother_charge`, `discount_credits`, `total_amount_payble`, `payment_amount`, `balance_refundable`, `payment_date`, `mode_of_payment`, `transection_ref`, `payment_remarks`, `transection_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '40000', '0', '0', '40000', '25000', '15000', '2023-08-29', 'Cheque', 'CHQ', 'CHQ', '20230828122434618', '2023-08-28 12:24:34', '0000-00-00 00:00:00'),
(2, 2, 1, '10000', '0', '0', '10000', '7500', '2500', '2023-08-06', 'Bank Transfer', 'BANK TRF', 'BANK', '20230828122630044', '2023-08-28 12:26:30', '0000-00-00 00:00:00'),
(3, 1, 2, '15000', '0', '5000', '10000', '10000', '10000', '2023-08-30', 'Cheque', 'CHQ', 'CHQ', '20230828122939435', '2023-08-28 12:29:39', '0000-00-00 00:00:00'),
(4, 2, 1, '0', '0', '2000', '-2000', '0', '-2000', '2023-09-04', '', 'test', 'test', '20230904113345230', '2023-09-04 11:33:45', '0000-00-00 00:00:00'),
(5, 2, 1, '500', '0', '100', '400', '0', '400', '2023-09-03', 'Bank Transfer', 'test', 'test', '20230904114257680', '2023-09-04 11:42:57', '0000-00-00 00:00:00'),
(6, 3, 3, '360', '0', '359', '1', '1', '1', '2023-10-03', 'Bank Transfer', 'ACC SETTLED', 'ACC SETTLED', '20231003051048090', '2023-10-03 05:10:48', '0000-00-00 00:00:00'),
(7, 4, 4, '20000', '0', '0', '20000', '20000', '0', '2024-03-26', 'Cash', 'q', 'q', '20240326072843163', '2024-03-26 07:28:43', '0000-00-00 00:00:00'),
(8, 5, 4, '20000', '0', '0', '20000', '20000', '0', '2024-03-26', 'Cash', 'q', 'q', '20240326073222366', '2024-03-26 07:32:22', '0000-00-00 00:00:00'),
(9, 6, 4, '20000', '0', '0', '20000', '1', '19999', '2024-04-11', 'Cash', 'test', 'test', '20240411102943373', '2024-04-11 10:29:43', '0000-00-00 00:00:00'),
(10, 7, 4, '400000000', '0', '0', '400000000', '400000000', '0', '2024-05-21', 'Cash', 'ABCD', 'Payment Done By Cashe Temporary For Development Purpose.', '20240521074753826', '2024-05-21 07:47:53', '0000-00-00 00:00:00'),
(11, 8, 4, '20000', '0', '0', '20000', '2000', '18000', '2024-05-23', 'Cash', 'tertert', 'Remark', '20240523143738561', '2024-05-23 14:37:38', '0000-00-00 00:00:00'),
(12, 9, 4, '20000', '0', '0', '20000', '20000', '0', '2024-05-24', 'Cash', 'asdasd', 'asd', '20240524101149090', '2024-05-24 10:11:49', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plan`
--

CREATE TABLE `subscription_plan` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `allowed_entities_no` int(11) NOT NULL,
  `location_each_entity` int(11) NOT NULL,
  `user_number_register` int(11) NOT NULL,
  `line_item_avaliable` int(11) NOT NULL,
  `highlights` text NOT NULL,
  `validity_from` date NOT NULL,
  `validity_to` date NOT NULL,
  `status` int(11) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `about_payment` text NOT NULL,
  `terms_condition` text NOT NULL,
  `foot_notes` text NOT NULL,
  `time_subscription` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subscription_plan`
--

INSERT INTO `subscription_plan` (`id`, `title`, `subtitle`, `description`, `allowed_entities_no`, `location_each_entity`, `user_number_register`, `line_item_avaliable`, `highlights`, `validity_from`, `validity_to`, `status`, `amount`, `about_payment`, `terms_condition`, `foot_notes`, `time_subscription`, `created_at`, `updated_at`) VALUES
(1, 'PREMIUM - 01', 'most suited of all!', '', 3, 2, 5, 99999, '', '2023-04-22', '2023-09-30', 0, '10000', '', '', '', 'year', '2023-08-28 12:20:59', '0000-00-00 00:00:00'),
(2, 'Basic - 01', 'For who want only basic', '<p>App Brief and detailsed description Features:-</p><ul><li>App Brief and detailsed description Features:-</li><li>App Brief and detailsed description Features:-</li></ul>', 2, 3, 4, 999, '<p>App Features:-</p><ul><li>iugsil gf</li><li>&nbsp;‘ ’\'</li><li>&nbsp;dfhfh</li><li>&nbsp;hhrh</li><li>&nbsp;ttyjt</li><li>&nbsp;tyty</li><li>&nbsp;jtyj</li><li>&nbsp;</li></ul>', '2023-05-12', '2023-12-30', 0, '20000', '<p>Payment Terms:</p><ol><li>a</li><li>a</li><li>a</li><li>a</li><li>a</li><li>a</li><li>a</li></ol>', '<p>Terms :</p><ol><li>a</li><li>a</li><li>a</li><li>a</li><li>a</li><li>a</li><li>a</li></ol>', '<p>other pointersother pointers</p><p>other pointers</p><p>other pointers</p><p>other pointers</p><p>&nbsp;</p><p>&nbsp;</p>', 'year', '2023-12-16 06:52:53', '0000-00-00 00:00:00'),
(3, 'DLJM Customized', 'Customized', '', 10, 10, 25, 100000, '', '2023-10-03', '2023-12-31', 0, '1', '', '', '', 'days', '2023-10-03 05:08:41', '0000-00-00 00:00:00'),
(4, 'Premium -02', 'This is UPGRADE subscription', '', 5, 10, 25, 10000, '', '2023-12-23', '2024-05-31', 0, '20000', '', '', '', 'year', '2024-03-26 07:25:26', '0000-00-00 00:00:00'),
(5, 'Upgraded Premium -03', 'This is UPGRADED Version of Subscription', '', 10, 25, 50, 100000, '', '2023-12-23', '2023-12-31', 0, '50000', '', '', '', 'year', '2023-12-23 06:07:03', '0000-00-00 00:00:00'),
(6, 'Premium - 03', 'This is UPGRADE subscription', '', 5, 20, 25, 10000, '', '2024-04-03', '2024-06-30', 0, '25000', '', '', '', 'year', '2024-04-03 13:10:07', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `super_admin`
--

CREATE TABLE `super_admin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `super_admin`
--

INSERT INTO `super_admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'super-admin', 'super-admin@verifyfa.com', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `transfered_account`
--

CREATE TABLE `transfered_account` (
  `id` int(11) NOT NULL,
  `regestered_user_id` int(11) NOT NULL,
  `trransfer_email_from` varchar(255) NOT NULL,
  `transfer_email_to` varchar(255) NOT NULL,
  `transfer_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `userName` varchar(255) DEFAULT NULL,
  `firstName` varchar(40) NOT NULL,
  `lastName` varchar(40) NOT NULL,
  `phone_no` varchar(255) NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `userRole` tinyint(4) NOT NULL,
  `registered_user_id` int(11) DEFAULT NULL,
  `entity_code` varchar(255) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `is_login` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `edited_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `profile_update` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `userName`, `firstName`, `lastName`, `phone_no`, `userEmail`, `password`, `designation`, `company_id`, `userRole`, `registered_user_id`, `entity_code`, `department_id`, `location_id`, `is_login`, `created_by`, `edited_by`, `created_on`, `updated_on`, `profile_update`) VALUES
(1, 'TARUN', 'TARUN', 'LAMBA', '876543201', 'tarun@test.com', '827ccb0eea8a706c4c34a16891f84e7b', 'IT HEAD', 1, 5, 1, 'LOADDEMO', 1, 1, 0, 0, 1, '0000-00-00 00:00:00', '2023-08-28 13:08:26', 1),
(2, NULL, 'AMIT', 'KUMAR', '99988774', 'AMIT@TEST.COM', '827ccb0eea8a706c4c34a16891f84e7b', 'IT CONSULTANT', 1, 0, 1, 'LOADDEMO', 1, 2, 0, 1, 0, '2023-08-28 13:09:21', '2023-12-27 07:30:35', 1),
(3, NULL, 'ARUN', 'KUMAR', '221', 'ARUN@TEST.COM', '827ccb0eea8a706c4c34a16891f84e7b', 'FINANCE MANAGER', 2, 0, 1, 'LOADDEMO', 2, 3, 0, 1, 0, '2023-08-28 13:10:05', '2023-09-13 07:11:04', 1),
(4, NULL, 'NEENA', 'GUPTA', '3', 'NEENA@TEST.COM', '827ccb0eea8a706c4c34a16891f84e7b', 'HR HEAD', 1, 0, 1, 'LOADDEMO', 3, 2, 0, 1, 0, '2023-08-28 13:10:58', '0000-00-00 00:00:00', 0),
(5, 'Jasmeet', 'Jasmeet', 'Singh', '9818946669', 'jasmeet@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', 'CONSULTANT', 3, 5, 3, 'DLJM', 11, 5, 0, 0, 5, '0000-00-00 00:00:00', '2023-10-03 05:15:37', 1),
(6, NULL, 'Priyvart', 'S', '9709236397', 'priyvart@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', 'CONSULTANT', 3, 0, 3, 'DLJM', 11, 5, 0, 5, 6, '2023-10-03 05:17:05', '2023-10-03 05:19:46', 1),
(7, NULL, 'Sangeet', 'Sagar', '8929140644', 'sangeet@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', 'CONSULTANT', 3, 0, 3, 'DLJM', 11, 5, 0, 5, 7, '2023-10-03 05:17:55', '2023-10-03 05:19:10', 1),
(8, NULL, 'Khushi', 'S', '8743808692', 'khushi@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', 'CONSULTANT', 3, 0, 3, 'DLJM', 11, 5, 0, 5, 0, '2023-10-03 05:18:49', '0000-00-00 00:00:00', 0),
(9, NULL, 'Kartikey', 'Sharma', '729002886', 'kartikey@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', 'CONSULTANT', 3, 0, 3, 'DLJM', 11, 5, 0, 5, 0, '2023-10-03 05:21:11', '0000-00-00 00:00:00', 0),
(10, NULL, 'Pradeep', 'Kumar', '9310849700', 'pradeep@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', 'CONSULTANT', 3, 0, 3, 'DLJM', 11, 5, 0, 5, 0, '2023-10-03 05:22:44', '0000-00-00 00:00:00', 0),
(11, NULL, 'Shubh', 'Kirti', '9971983008', 'shubh@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', 'CONSULTANT', 3, 0, 3, 'DLJM', 11, 5, 0, 5, 0, '2023-10-03 05:23:45', '2023-10-13 07:48:22', 1),
(12, 'AMIT', 'AMIT', 'KUMAR', '9988778899', 'amit@test.com', '827ccb0eea8a706c4c34a16891f84e7b', '', 0, 5, 2, 'ORGPREM', 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(13, 'Shubh', 'Shubh', 'Kirti', '9818946669', 'shubh@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Team Leader', 4, 5, 4, 'HMCMM', 13, 6, 0, 0, 13, '0000-00-00 00:00:00', '2024-03-26 07:46:49', 1),
(14, NULL, 'Pradeep', 'Kumar', '99999999', 'pradeep@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Team', 4, 0, 4, 'HMCMM', 13, 6, 0, 13, 0, '2024-03-26 07:47:37', '0000-00-00 00:00:00', 0),
(15, NULL, 'FA User', '1', '99999999999', 'fauser1@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Team', 4, 0, 4, 'HMCMM', 13, 6, 0, 13, 0, '2024-03-26 07:49:10', '0000-00-00 00:00:00', 0),
(16, NULL, 'FA User', '2', '77777777', 'fauser2@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Team', 4, 0, 4, 'HMCMM', 13, 6, 0, 13, 0, '2024-03-26 07:49:56', '0000-00-00 00:00:00', 0),
(17, NULL, 'Jasmeet', 'Singh', '99999999999999', 'jasmeet@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Team Leader', 5, 0, 4, 'HMCMM', 13, 7, 0, 13, 0, '2024-03-26 07:53:26', '0000-00-00 00:00:00', 0),
(18, NULL, 'Priyvart', 'Raj', '777777777777', 'priyvart@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Team', 5, 0, 4, 'HMCMM', 13, 7, 0, 13, 0, '2024-03-26 07:54:10', '0000-00-00 00:00:00', 0),
(19, NULL, 'Chetan Singh', 'Rawat', '777777777777777', 'chetan@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Team', 5, 0, 4, 'HMCMM', 13, 7, 0, 13, 0, '2024-03-26 07:54:45', '0000-00-00 00:00:00', 0),
(20, NULL, 'Yashwant Kumar', 'Mishra', '7777777777', 'yashwant@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Team', 5, 0, 4, 'HMCMM', 13, 7, 0, 13, 0, '2024-03-26 07:55:33', '0000-00-00 00:00:00', 0),
(21, NULL, 'Bhaskar Kumar', 'Yadav', '777777777', 'bhaskar@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Team', 5, 0, 4, 'HMCMM', 13, 7, 0, 13, 0, '2024-03-26 07:56:12', '0000-00-00 00:00:00', 0),
(22, NULL, 'Jatin', 'Patel', '999999999999', 'jatin@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', 'APL - Finance', 5, 0, 4, 'HMCMM', 13, 7, 0, 13, 0, '2024-03-28 05:47:07', '0000-00-00 00:00:00', 0),
(23, NULL, 'Manoj', 'Paliwal', '000000000000', 'manoj@verifyfa.com', '827ccb0eea8a706c4c34a16891f84e7b', 'APL - Finance', 5, 0, 4, 'HMCMM', 13, 7, 0, 13, 0, '2024-03-28 05:47:48', '0000-00-00 00:00:00', 0),
(24, 'Jasmeet', 'Jasmeet', 'Singh', '88888888', 'jasmeet@test.com', '827ccb0eea8a706c4c34a16891f84e7b', 'IT CONSULTANT', 6, 5, 6, 'DEMOCO', 14, 19, 0, 0, 24, '0000-00-00 00:00:00', '2024-04-11 10:32:25', 1),
(25, NULL, 'Hardik', 'Meghnathi', '333333333', 'hardik@test.com', '827ccb0eea8a706c4c34a16891f84e7b', 'IT CONSULTANT', 6, 0, 6, 'DEMOCO', 14, 20, 0, 24, 0, '2024-04-11 10:33:04', '0000-00-00 00:00:00', 0),
(26, 'HardikGiri', 'HardikGiri', 'Meghnathi', '9904841361', 'hardik.meghnathi12@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Main Admin', 7, 5, 7, 'SERITSOL', 15, 21, 0, 0, 0, '0000-00-00 00:00:00', '2024-05-21 07:56:14', 1),
(27, NULL, 'Hardik Manager', 'Manager', '9999999999', 'hardik.meghnathi12manager@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Manager Role', 7, 0, 7, 'SERITSOL', 16, 21, 0, 26, 0, '2024-05-21 07:55:35', '0000-00-00 00:00:00', 0),
(28, NULL, 'Hardik Verifier', 'Verifier', '9999999998', 'hardik.meghnathi12verifier@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Verifier', 7, 0, 7, 'SERITSOL', 17, 21, 0, 26, 0, '2024-05-21 08:09:21', '0000-00-00 00:00:00', 0),
(29, NULL, 'Hardik Process Owner', 'Process Owner', '9999999997', 'hardik.meghnathi12processowner@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Process Owner', 7, 0, 7, 'SERITSOL', 18, 21, 0, 26, 0, '2024-05-21 08:10:13', '0000-00-00 00:00:00', 0),
(30, NULL, 'Hardik Entity Owner', 'Entity Owner', '9999999996', 'hardik.meghnathi12entityowner@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Entity Owner', 7, 0, 7, 'SERITSOL', 19, 21, 0, 26, 0, '2024-05-21 08:10:52', '0000-00-00 00:00:00', 0),
(31, NULL, 'Hardik Sub Admin', 'Sub Admin', '9999999995', 'hardik.meghnathi12subadmin@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Sub Admin', 7, 0, 7, 'SERITSOL', 20, 21, 0, 26, 0, '2024-05-21 08:11:31', '0000-00-00 00:00:00', 0),
(32, NULL, 'Hardik Group Admin', 'Group Admin', '9999999994', 'hardik.meghnathi12groupadmin@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Group Admin', 7, 0, 7, 'SERITSOL', 21, 21, 0, 26, 0, '2024-05-21 08:12:18', '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `registered_user_id` int(11) NOT NULL,
  `entity_code` varchar(255) NOT NULL,
  `user_role` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `edited_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `user_id`, `company_id`, `location_id`, `registered_user_id`, `entity_code`, `user_role`, `created_by`, `edited_by`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 0, 1, 'LOADDEMO', '5', 0, 0, '2023-08-28 12:39:14', '0000-00-00 00:00:00'),
(2, 1, 1, 1, 1, 'LOADDEMO', '0,2,1', 1, 0, '2023-08-28 13:18:15', '0000-00-00 00:00:00'),
(3, 2, 1, 1, 1, 'LOADDEMO', '0,2,1', 1, 0, '2023-08-28 13:18:43', '0000-00-00 00:00:00'),
(4, 3, 1, 1, 1, 'LOADDEMO', '0,2,1', 1, 0, '2023-08-28 13:19:04', '0000-00-00 00:00:00'),
(5, 4, 1, 1, 1, 'LOADDEMO', '1', 1, 0, '2023-08-28 13:19:16', '0000-00-00 00:00:00'),
(6, 1, 1, 2, 1, 'LOADDEMO', '2,1', 1, 0, '2023-08-28 13:21:52', '0000-00-00 00:00:00'),
(7, 2, 1, 2, 1, 'LOADDEMO', '4,0,2,3,1', 1, 1, '2023-08-28 13:22:12', '2023-08-28 13:25:10'),
(8, 3, 1, 2, 1, 'LOADDEMO', '2,1', 1, 0, '2023-08-28 13:22:29', '0000-00-00 00:00:00'),
(9, 4, 1, 2, 1, 'LOADDEMO', '0,2,1', 1, 0, '2023-08-28 13:22:43', '0000-00-00 00:00:00'),
(10, 1, 2, 3, 1, 'LOADDEMO', '2,1', 1, 0, '2023-08-28 13:23:23', '0000-00-00 00:00:00'),
(11, 2, 2, 3, 1, 'LOADDEMO', '2,1', 1, 0, '2023-08-28 13:23:50', '0000-00-00 00:00:00'),
(12, 3, 2, 3, 1, 'LOADDEMO', '4,0,2,1', 1, 0, '2023-08-28 13:24:13', '0000-00-00 00:00:00'),
(13, 4, 2, 3, 1, 'LOADDEMO', '2,1', 1, 0, '2023-08-28 13:24:28', '0000-00-00 00:00:00'),
(14, 5, 0, 0, 3, 'DLJM', '5', 0, 0, '2023-10-03 05:11:29', '0000-00-00 00:00:00'),
(15, 5, 3, 5, 3, 'DLJM', '0,1', 5, 11, '2023-10-03 05:24:58', '2023-10-13 07:49:07'),
(16, 6, 3, 5, 3, 'DLJM', '1', 5, 0, '2023-10-03 05:25:14', '0000-00-00 00:00:00'),
(17, 7, 3, 5, 3, 'DLJM', '1', 5, 0, '2023-10-03 05:25:26', '0000-00-00 00:00:00'),
(18, 8, 3, 5, 3, 'DLJM', '1', 5, 0, '2023-10-03 05:25:37', '0000-00-00 00:00:00'),
(19, 9, 3, 5, 3, 'DLJM', '1', 5, 0, '2023-10-03 05:25:48', '0000-00-00 00:00:00'),
(20, 10, 3, 5, 3, 'DLJM', '1', 5, 0, '2023-10-03 05:25:59', '0000-00-00 00:00:00'),
(21, 11, 3, 5, 3, 'DLJM', '0', 5, 0, '2023-10-03 05:26:12', '0000-00-00 00:00:00'),
(22, 11, 0, 0, 3, 'DLJM', '5', 5, 0, '2023-10-13 07:47:35', '0000-00-00 00:00:00'),
(23, 12, 0, 0, 2, 'ORGPREM', '5', 0, 0, '2023-12-14 05:22:00', '0000-00-00 00:00:00'),
(24, 3, 1, 4, 1, 'LOADDEMO', '3,1', 1, 0, '2023-12-27 07:33:24', '0000-00-00 00:00:00'),
(25, 13, 0, 0, 4, 'HMCMM', '5', 0, 0, '2024-03-26 07:39:12', '0000-00-00 00:00:00'),
(26, 13, 4, 6, 4, 'HMCMM', '0,1', 13, 0, '2024-03-26 07:56:46', '0000-00-00 00:00:00'),
(27, 14, 4, 6, 4, 'HMCMM', '1', 13, 0, '2024-03-26 07:57:00', '0000-00-00 00:00:00'),
(28, 15, 4, 6, 4, 'HMCMM', '1', 13, 0, '2024-03-26 07:57:10', '0000-00-00 00:00:00'),
(29, 16, 4, 6, 4, 'HMCMM', '1', 13, 0, '2024-03-26 07:57:19', '0000-00-00 00:00:00'),
(30, 17, 4, 6, 4, 'HMCMM', '0,1', 13, 0, '2024-03-26 07:57:56', '0000-00-00 00:00:00'),
(31, 17, 5, 7, 4, 'HMCMM', '0,1', 13, 0, '2024-03-26 07:58:34', '0000-00-00 00:00:00'),
(32, 17, 5, 8, 4, 'HMCMM', '0,1', 13, 0, '2024-03-26 07:58:47', '0000-00-00 00:00:00'),
(33, 17, 5, 9, 4, 'HMCMM', '0,1', 13, 0, '2024-03-26 07:58:58', '0000-00-00 00:00:00'),
(34, 17, 5, 10, 4, 'HMCMM', '0,1', 13, 0, '2024-03-26 07:59:09', '0000-00-00 00:00:00'),
(35, 18, 5, 7, 4, 'HMCMM', '1', 13, 0, '2024-03-26 07:59:25', '0000-00-00 00:00:00'),
(36, 18, 5, 8, 4, 'HMCMM', '1', 13, 0, '2024-03-26 08:26:06', '0000-00-00 00:00:00'),
(37, 18, 5, 10, 4, 'HMCMM', '1', 13, 0, '2024-03-26 08:26:18', '0000-00-00 00:00:00'),
(38, 19, 5, 7, 4, 'HMCMM', '1', 13, 0, '2024-03-26 08:26:28', '0000-00-00 00:00:00'),
(39, 19, 5, 8, 4, 'HMCMM', '1', 13, 0, '2024-03-26 08:26:37', '0000-00-00 00:00:00'),
(40, 19, 5, 10, 4, 'HMCMM', '1', 13, 0, '2024-03-26 08:26:49', '0000-00-00 00:00:00'),
(41, 20, 5, 7, 4, 'HMCMM', '1', 13, 0, '2024-03-26 08:27:07', '0000-00-00 00:00:00'),
(42, 20, 5, 8, 4, 'HMCMM', '1', 13, 0, '2024-03-26 08:27:16', '0000-00-00 00:00:00'),
(43, 20, 5, 9, 4, 'HMCMM', '1', 13, 0, '2024-03-26 08:27:31', '0000-00-00 00:00:00'),
(44, 21, 5, 7, 4, 'HMCMM', '1', 13, 0, '2024-03-26 08:27:46', '0000-00-00 00:00:00'),
(45, 21, 5, 8, 4, 'HMCMM', '1', 13, 0, '2024-03-26 08:27:58', '0000-00-00 00:00:00'),
(46, 21, 5, 9, 4, 'HMCMM', '1', 13, 0, '2024-03-26 08:28:07', '0000-00-00 00:00:00'),
(47, 22, 5, 7, 4, 'HMCMM', '1', 13, 0, '2024-03-28 05:48:18', '0000-00-00 00:00:00'),
(48, 22, 5, 8, 4, 'HMCMM', '1', 13, 0, '2024-03-28 05:48:32', '0000-00-00 00:00:00'),
(49, 23, 5, 7, 4, 'HMCMM', '1', 13, 0, '2024-03-28 05:48:45', '0000-00-00 00:00:00'),
(50, 23, 5, 8, 4, 'HMCMM', '1', 13, 0, '2024-03-28 05:48:57', '0000-00-00 00:00:00'),
(51, 17, 5, 11, 4, 'HMCMM', '0,1', 13, 0, '2024-03-29 05:26:15', '0000-00-00 00:00:00'),
(52, 22, 5, 11, 4, 'HMCMM', '1', 13, 0, '2024-03-29 05:26:30', '0000-00-00 00:00:00'),
(53, 23, 5, 11, 4, 'HMCMM', '1', 13, 0, '2024-03-29 05:26:43', '0000-00-00 00:00:00'),
(54, 20, 5, 11, 4, 'HMCMM', '1', 13, 0, '2024-03-29 05:27:00', '0000-00-00 00:00:00'),
(55, 18, 5, 11, 4, 'HMCMM', '1', 13, 0, '2024-03-29 05:27:15', '0000-00-00 00:00:00'),
(56, 23, 5, 12, 4, 'HMCMM', '1', 13, 0, '2024-03-29 05:27:31', '0000-00-00 00:00:00'),
(57, 22, 5, 12, 4, 'HMCMM', '1', 13, 0, '2024-03-29 05:27:43', '0000-00-00 00:00:00'),
(58, 17, 5, 12, 4, 'HMCMM', '0,1', 13, 0, '2024-03-29 05:27:58', '0000-00-00 00:00:00'),
(59, 19, 5, 12, 4, 'HMCMM', '1', 13, 0, '2024-03-29 05:28:14', '0000-00-00 00:00:00'),
(60, 21, 5, 12, 4, 'HMCMM', '1', 13, 0, '2024-03-29 05:28:29', '0000-00-00 00:00:00'),
(61, 21, 5, 11, 4, 'HMCMM', '1', 13, 0, '2024-04-01 04:44:02', '0000-00-00 00:00:00'),
(62, 18, 5, 13, 4, 'HMCMM', '1', 13, 0, '2024-04-01 06:33:56', '0000-00-00 00:00:00'),
(63, 19, 5, 13, 4, 'HMCMM', '1', 13, 0, '2024-04-01 06:34:07', '0000-00-00 00:00:00'),
(64, 23, 5, 13, 4, 'HMCMM', '1', 13, 0, '2024-04-01 06:34:17', '0000-00-00 00:00:00'),
(65, 22, 5, 13, 4, 'HMCMM', '1', 13, 0, '2024-04-01 06:34:28', '0000-00-00 00:00:00'),
(66, 17, 5, 13, 4, 'HMCMM', '0,1', 13, 0, '2024-04-01 06:34:44', '0000-00-00 00:00:00'),
(67, 20, 5, 12, 4, 'HMCMM', '1', 13, 0, '2024-04-03 11:43:19', '0000-00-00 00:00:00'),
(68, 17, 5, 15, 4, 'HMCMM', '0,1', 13, 0, '2024-04-03 13:13:03', '0000-00-00 00:00:00'),
(69, 20, 5, 15, 4, 'HMCMM', '1', 13, 0, '2024-04-03 13:13:21', '0000-00-00 00:00:00'),
(70, 21, 5, 15, 4, 'HMCMM', '1', 13, 0, '2024-04-03 13:13:34', '0000-00-00 00:00:00'),
(71, 17, 5, 14, 4, 'HMCMM', '0,1', 13, 0, '2024-04-03 13:14:01', '0000-00-00 00:00:00'),
(72, 20, 5, 14, 4, 'HMCMM', '1', 13, 0, '2024-04-03 13:14:15', '0000-00-00 00:00:00'),
(73, 21, 5, 14, 4, 'HMCMM', '1', 13, 0, '2024-04-03 13:14:26', '0000-00-00 00:00:00'),
(74, 17, 5, 16, 4, 'HMCMM', '0,1', 13, 0, '2024-04-03 13:15:02', '0000-00-00 00:00:00'),
(75, 20, 5, 16, 4, 'HMCMM', '1', 13, 0, '2024-04-03 13:15:17', '0000-00-00 00:00:00'),
(76, 21, 5, 16, 4, 'HMCMM', '1', 13, 0, '2024-04-03 13:15:29', '0000-00-00 00:00:00'),
(77, 17, 5, 17, 4, 'HMCMM', '0,1', 13, 0, '2024-04-03 13:16:08', '0000-00-00 00:00:00'),
(78, 18, 5, 17, 4, 'HMCMM', '1', 13, 0, '2024-04-03 13:16:25', '0000-00-00 00:00:00'),
(79, 19, 5, 17, 4, 'HMCMM', '1', 13, 0, '2024-04-03 13:16:52', '0000-00-00 00:00:00'),
(80, 17, 5, 18, 4, 'HMCMM', '0,1', 13, 0, '2024-04-04 04:03:16', '0000-00-00 00:00:00'),
(81, 18, 5, 18, 4, 'HMCMM', '1', 13, 0, '2024-04-04 04:03:28', '0000-00-00 00:00:00'),
(82, 19, 5, 18, 4, 'HMCMM', '1', 13, 0, '2024-04-04 04:03:40', '0000-00-00 00:00:00'),
(83, 24, 0, 0, 6, 'DEMOCO', '5', 0, 0, '2024-04-11 10:30:19', '0000-00-00 00:00:00'),
(84, 24, 6, 19, 6, 'DEMOCO', '0,1', 24, 0, '2024-04-11 10:33:26', '0000-00-00 00:00:00'),
(85, 24, 6, 20, 6, 'DEMOCO', '0,1', 24, 0, '2024-04-11 10:33:38', '0000-00-00 00:00:00'),
(86, 25, 6, 19, 6, 'DEMOCO', '1', 24, 0, '2024-04-11 10:33:47', '0000-00-00 00:00:00'),
(87, 25, 6, 20, 6, 'DEMOCO', '0,1', 24, 0, '2024-04-11 10:33:58', '0000-00-00 00:00:00'),
(88, 26, 0, 0, 7, 'SERITSOL', '5', 0, 0, '2024-05-21 07:50:02', '0000-00-00 00:00:00'),
(89, 26, 7, 21, 7, 'SERITSOL', '0,1', 26, 0, '2024-05-21 08:00:27', '0000-00-00 00:00:00'),
(90, 29, 7, 21, 7, 'SERITSOL', '2', 26, 0, '2024-05-21 08:12:54', '0000-00-00 00:00:00'),
(91, 30, 7, 21, 7, 'SERITSOL', '3', 26, 0, '2024-05-21 08:13:05', '0000-00-00 00:00:00'),
(92, 31, 7, 21, 7, 'SERITSOL', '4', 26, 0, '2024-05-21 08:13:28', '0000-00-00 00:00:00'),
(93, 28, 7, 21, 7, 'SERITSOL', '1', 26, 0, '2024-05-22 09:16:04', '0000-00-00 00:00:00'),
(94, 27, 7, 21, 7, 'SERITSOL', '0', 26, 0, '2024-05-22 09:16:50', '0000-00-00 00:00:00'),
(95, 32, 0, 0, 7, 'SERITSOL', '5', 26, 0, '2024-05-22 09:22:29', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `verifiedproducts`
--

CREATE TABLE `verifiedproducts` (
  `id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `entity_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `project_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_table_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_category` int(11) DEFAULT NULL,
  `item_unique_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_sub_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity_as_per_invoice` int(11) DEFAULT NULL,
  `verification_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity_verified` int(11) DEFAULT NULL,
  `new_location_verified` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verified_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verified_by_username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verified_datetime` datetime NOT NULL,
  `verification_remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty_ok` int(11) DEFAULT NULL,
  `qty_damaged` int(11) DEFAULT NULL,
  `qty_scrapped` int(11) NOT NULL,
  `qty_not_in_use` int(11) NOT NULL,
  `qty_missing` int(11) NOT NULL,
  `qty_shifted` int(11) NOT NULL,
  `mode_of_verification` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Scan,Search',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `verifiedproducts`
--

INSERT INTO `verifiedproducts` (`id`, `company_id`, `location_id`, `entity_code`, `project_id`, `project_name`, `original_table_name`, `item_id`, `item_category`, `item_unique_code`, `item_sub_code`, `item_description`, `quantity_as_per_invoice`, `verification_status`, `quantity_verified`, `new_location_verified`, `verified_by`, `verified_by_username`, `verified_datetime`, `verification_remarks`, `qty_ok`, `qty_damaged`, `qty_scrapped`, `qty_not_in_use`, `qty_missing`, `qty_shifted`, `mode_of_verification`, `created_at`) VALUES
(1, 6, 20, 'DEMOCO', 22, 'Test Demo - 01', 'project_1712831886', 3, 0, 'SWH-P&M-0036-TVN', 'FA004538', 'SAMSUNG 32\" LED TV', 34, 'Verified', 49, '0', '0', 'ABCD', '2024-05-15 14:46:29', '', 49, 0, 0, 0, 0, 0, 'Search', '2024-05-15 08:42:46'),
(2, 7, 21, 'SERITSOL', 26, '23May 2024 Project 1', 'project_1716456559', 3, 0, 'SWH-P&M-0036-TVN', 'FA004538', 'SAMSUNG 32\" LED TV', 34, 'Verified', 30, '0', '0', 'ABCD', '2024-05-23 12:41:16', '', 30, 0, 0, 0, 0, 0, 'Search', '2024-05-23 06:29:41'),
(3, 7, 21, 'SERITSOL', 26, '23May 2024 Project 1', 'project_1716456559', 3, 0, 'SWH-P&M-0036-TVN', 'FA004538', 'SAMSUNG 32\" LED TV', 34, 'Verified', 60, '0', '0', 'ABCD', '2024-05-23 11:43:19', '', 60, 0, 0, 0, 0, 0, 'Search', '2024-05-23 05:32:43'),
(4, 7, 21, 'SERITSOL', 26, '23May 2024 Project 1', 'project_1716456559', 3, 0, 'SWH-P&M-0036-TVN', 'FA004538', 'SAMSUNG 32\" LED TV', 34, 'Verified', 90, '0', '0', 'ABCD', '2024-05-23 11:44:29', 'Remark1,Remark2,Remark3,', 90, 0, 0, 0, 0, 0, 'Search', '2024-05-23 05:42:44');

-- --------------------------------------------------------

--
-- Table structure for table `verifiedproducts_log`
--

CREATE TABLE `verifiedproducts_log` (
  `id` int(11) NOT NULL,
  `row_id` int(11) DEFAULT NULL,
  `edit_opration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'qty & details update, details update',
  `edit_instance` int(11) NOT NULL DEFAULT 1,
  `previous_company_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `previous_location_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `previous_entity_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `previous_project_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `previous_project_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `previous_original_table_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_table_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `previous_item_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `previous_item_category` int(11) DEFAULT NULL,
  `item_category` int(11) DEFAULT NULL,
  `previous_item_unique_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_unique_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `previous_item_sub_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_sub_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `previous_item_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `previous_quantity_as_per_invoice` int(11) DEFAULT NULL,
  `quantity_as_per_invoice` int(11) DEFAULT NULL,
  `previous_verification_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verification_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `previous_quantity_verified` int(11) DEFAULT NULL,
  `quantity_verified` int(11) DEFAULT NULL,
  `previous_new_location_verified` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_location_verified` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `previous_verified_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verified_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `previous_verified_by_username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verified_by_username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `previous_verified_datetime` datetime NOT NULL,
  `verified_datetime` datetime NOT NULL,
  `previous_verification_remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verification_remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `previous_qty_ok` int(11) DEFAULT NULL,
  `qty_ok` int(11) DEFAULT NULL,
  `previous_qty_damaged` int(11) DEFAULT NULL,
  `qty_damaged` int(11) DEFAULT NULL,
  `previous_qty_scrapped` int(11) NOT NULL,
  `qty_scrapped` int(11) NOT NULL,
  `previous_qty_not_in_use` int(11) NOT NULL,
  `qty_not_in_use` int(11) NOT NULL,
  `previous_qty_missing` int(11) NOT NULL,
  `qty_missing` int(11) NOT NULL,
  `previous_qty_shifted` int(11) NOT NULL,
  `qty_shifted` int(11) NOT NULL,
  `previous_mode_of_verification` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Scan,Search',
  `mode_of_verification` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Scan,Search',
  `previous_created_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `verifiedproducts_log`
--

INSERT INTO `verifiedproducts_log` (`id`, `row_id`, `edit_opration`, `edit_instance`, `previous_company_id`, `company_id`, `previous_location_id`, `location_id`, `previous_entity_code`, `entity_code`, `previous_project_id`, `project_id`, `previous_project_name`, `project_name`, `previous_original_table_name`, `original_table_name`, `previous_item_id`, `item_id`, `previous_item_category`, `item_category`, `previous_item_unique_code`, `item_unique_code`, `previous_item_sub_code`, `item_sub_code`, `previous_item_description`, `item_description`, `previous_quantity_as_per_invoice`, `quantity_as_per_invoice`, `previous_verification_status`, `verification_status`, `previous_quantity_verified`, `quantity_verified`, `previous_new_location_verified`, `new_location_verified`, `previous_verified_by`, `verified_by`, `previous_verified_by_username`, `verified_by_username`, `previous_verified_datetime`, `verified_datetime`, `previous_verification_remarks`, `verification_remarks`, `previous_qty_ok`, `qty_ok`, `previous_qty_damaged`, `qty_damaged`, `previous_qty_scrapped`, `qty_scrapped`, `previous_qty_not_in_use`, `qty_not_in_use`, `previous_qty_missing`, `qty_missing`, `previous_qty_shifted`, `qty_shifted`, `previous_mode_of_verification`, `mode_of_verification`, `previous_created_at`, `created_at`) VALUES
(1, 7, 'Qty & Details', 1, 7, 7, 21, 21, 'SERITSOL', 'SERITSOL', 26, 26, '23May 2024 Project 1', '23May 2024 Project 1', 'project_1716456559', 'project_1716456559', 3, 3, 0, 0, 'SWH-P&M-0036-TVN', 'SWH-P&M-0036-TVN', 'FA004538', 'FA004538', 'SAMSUNG 32\" LED TV', 'SAMSUNG 32\" LED TV', 34, 34, 'Verified', 'Verified', 180, 180, '0', '0', '26', '26', 'HardikGiri Meghnathi', 'HardikGiri Meghnathi', '2024-05-23 13:25:04', '2024-05-23 13:25:04', 'Remark1,Remark2,Remark3, || Remark1,Remark2,Remark3, || Remark1,Remark2,Remark3, || Remark1,Remark2,Remark3, || Remark1,Remark2,Remark3, || Remark1,Remark2,Remark3,', 'Remark1,Remark2,Remark3, || Remark1,Remark2,Remark3, || Remark1,Remark2,Remark3, || Remark1,Remark2,Remark3, || Remark1,Remark2,Remark3, || Remark1,Remark2,Remark3,', 180, 180, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Search', 'Search', NULL, '2024-05-23 07:17:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additional_data`
--
ALTER TABLE `additional_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_locations`
--
ALTER TABLE `company_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_projects`
--
ALTER TABLE `company_projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_detail`
--
ALTER TABLE `contact_detail`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `notification_read`
--
ALTER TABLE `notification_read`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `notification_reply`
--
ALTER TABLE `notification_reply`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `notification_user`
--
ALTER TABLE `notification_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_headers`
--
ALTER TABLE `project_headers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registered_user_plan`
--
ALTER TABLE `registered_user_plan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `register_user_plan_log`
--
ALTER TABLE `register_user_plan_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registred_users`
--
ALTER TABLE `registred_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registred_users_payment`
--
ALTER TABLE `registred_users_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_plan`
--
ALTER TABLE `subscription_plan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `super_admin`
--
ALTER TABLE `super_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfered_account`
--
ALTER TABLE `transfered_account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `verifiedproducts`
--
ALTER TABLE `verifiedproducts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verifiedproducts_log`
--
ALTER TABLE `verifiedproducts_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `additional_data`
--
ALTER TABLE `additional_data`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `company_locations`
--
ALTER TABLE `company_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `company_projects`
--
ALTER TABLE `company_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `contact_detail`
--
ALTER TABLE `contact_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notification_read`
--
ALTER TABLE `notification_read`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notification_reply`
--
ALTER TABLE `notification_reply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_user`
--
ALTER TABLE `notification_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `project_headers`
--
ALTER TABLE `project_headers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1266;

--
-- AUTO_INCREMENT for table `registered_user_plan`
--
ALTER TABLE `registered_user_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `register_user_plan_log`
--
ALTER TABLE `register_user_plan_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `registred_users`
--
ALTER TABLE `registred_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `registred_users_payment`
--
ALTER TABLE `registred_users_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `subscription_plan`
--
ALTER TABLE `subscription_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `super_admin`
--
ALTER TABLE `super_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transfered_account`
--
ALTER TABLE `transfered_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `verifiedproducts`
--
ALTER TABLE `verifiedproducts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `verifiedproducts_log`
--
ALTER TABLE `verifiedproducts_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
