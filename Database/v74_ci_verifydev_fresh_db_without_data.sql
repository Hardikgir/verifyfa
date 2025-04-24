-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2025 at 03:32 PM
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
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_locations`
--
ALTER TABLE `company_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_projects`
--
ALTER TABLE `company_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_detail`
--
ALTER TABLE `contact_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_read`
--
ALTER TABLE `notification_read`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_reply`
--
ALTER TABLE `notification_reply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_user`
--
ALTER TABLE `notification_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_headers`
--
ALTER TABLE `project_headers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registered_user_plan`
--
ALTER TABLE `registered_user_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `register_user_plan_log`
--
ALTER TABLE `register_user_plan_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registred_users`
--
ALTER TABLE `registred_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registred_users_payment`
--
ALTER TABLE `registred_users_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_plan`
--
ALTER TABLE `subscription_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verifiedproducts`
--
ALTER TABLE `verifiedproducts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verifiedproducts_log`
--
ALTER TABLE `verifiedproducts_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
