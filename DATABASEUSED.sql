-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2019 at 02:08 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `minihrm`
--

-- --------------------------------------------------------

--
-- Table structure for table `allowance`
--

CREATE TABLE `allowance` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `employee_position_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `showed_name` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `nominal` bigint(20) NOT NULL,
  `status` enum('active','inactive') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'active',
  `user_c` bigint(20) NOT NULL,
  `user_m` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_detail`
--

CREATE TABLE `attendance_detail` (
  `id` bigint(20) NOT NULL,
  `attd_period_id` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `arrived` time DEFAULT NULL,
  `returns` time DEFAULT NULL,
  `attend` int(1) NOT NULL,
  `leaves` int(1) NOT NULL,
  `late` int(1) NOT NULL,
  `daily_report` int(1) NOT NULL,
  `user_c` bigint(20) NOT NULL,
  `user_m` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_period`
--

CREATE TABLE `attendance_period` (
  `id` bigint(20) NOT NULL,
  `emp_id` bigint(20) NOT NULL,
  `client_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `employee_position_id` int(11) NOT NULL,
  `period` varchar(7) COLLATE utf32_unicode_ci NOT NULL,
  `leaves_total` int(11) NOT NULL,
  `attend_total` int(11) NOT NULL,
  `late_total` int(11) NOT NULL,
  `overtime_total` int(11) NOT NULL,
  `overtime_go_home` int(11) NOT NULL,
  `medical_total` int(11) NOT NULL,
  `sick_total` int(11) NOT NULL,
  `daily_report_total` int(11) NOT NULL,
  `status` enum('not post','posted') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'not post',
  `posted_date` timestamp NULL DEFAULT NULL,
  `user_c` bigint(20) NOT NULL,
  `user_m` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_period_history`
--

CREATE TABLE `attendance_period_history` (
  `id` bigint(20) NOT NULL,
  `id_old` bigint(20) NOT NULL,
  `emp_id` bigint(20) NOT NULL,
  `client_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `employee_position_id` int(11) NOT NULL,
  `period` varchar(7) COLLATE utf32_unicode_ci NOT NULL,
  `leaves_total` int(11) NOT NULL,
  `attend_total` int(11) NOT NULL,
  `late_total` int(11) NOT NULL,
  `overtime_total` int(11) NOT NULL,
  `overtime_go_home` int(11) NOT NULL,
  `medical_total` int(11) NOT NULL,
  `sick_total` int(11) NOT NULL,
  `daily_report_total` int(11) NOT NULL,
  `status` enum('not post','posted') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'not post',
  `posted_date` timestamp NULL DEFAULT NULL,
  `user_c` bigint(20) NOT NULL,
  `user_m` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_report`
--

CREATE TABLE `attendance_report` (
  `id` bigint(20) NOT NULL,
  `emp_id` bigint(20) NOT NULL,
  `client_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `client_name` varchar(30) COLLATE utf32_unicode_ci NOT NULL,
  `project_name` varchar(30) COLLATE utf32_unicode_ci NOT NULL,
  `employee_position_id` int(11) NOT NULL,
  `employee_position_name` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `period` varchar(7) COLLATE utf32_unicode_ci NOT NULL,
  `leaves_remaining` int(11) NOT NULL,
  `leaves_total` int(11) NOT NULL,
  `sick_total` int(11) NOT NULL,
  `attend_total` int(11) NOT NULL,
  `late_total` int(11) NOT NULL,
  `overtime_total` int(11) NOT NULL,
  `overtime_go_home` bigint(20) NOT NULL,
  `medical_total` int(11) NOT NULL,
  `daily_report_total` int(11) NOT NULL,
  `laptop_internet_total` bigint(20) NOT NULL,
  `transport_total` bigint(20) NOT NULL,
  `meal_allowance_total` bigint(20) NOT NULL,
  `overtime_meal_allowance_total` bigint(20) NOT NULL,
  `total` bigint(20) NOT NULL,
  `bank_account_number` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `bank_name` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `posted_date` timestamp NULL DEFAULT NULL,
  `user_c` bigint(20) NOT NULL,
  `user_m` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_timing`
--

CREATE TABLE `attendance_timing` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `showed_name` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `time` time NOT NULL,
  `status` enum('active','inactive') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'active',
  `user_c` bigint(20) NOT NULL,
  `user_m` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `autoreport_email`
--

CREATE TABLE `autoreport_email` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `client` varchar(50) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `user_c` bigint(20) NOT NULL,
  `user_m` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bank_list`
--

CREATE TABLE `bank_list` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'active',
  `user_c` int(11) NOT NULL,
  `user_m` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions_data`
--

CREATE TABLE `ci_sessions_data` (
  `id` varchar(40) COLLATE utf32_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf32_unicode_ci NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `user_c` int(11) NOT NULL,
  `user_m` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `config_api`
--

CREATE TABLE `config_api` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL DEFAULT '1',
  `url` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cuti`
--

CREATE TABLE `cuti` (
  `id` int(11) NOT NULL,
  `emp_id` bigint(11) NOT NULL,
  `limit` int(11) NOT NULL,
  `status` enum('active','inactive','changed') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'active',
  `user_c` int(11) NOT NULL,
  `user_m` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cuti_last_periodic`
--

CREATE TABLE `cuti_last_periodic` (
  `id` int(11) NOT NULL,
  `emp_id` bigint(11) NOT NULL,
  `period` varchar(7) COLLATE utf32_unicode_ci NOT NULL,
  `leaves_remain` int(11) NOT NULL,
  `status` enum('active','inactive','changed') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'active',
  `user_c` int(11) NOT NULL,
  `user_m` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `division`
--

CREATE TABLE `division` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'active',
  `user_c` int(11) NOT NULL,
  `user_m` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `nick_name` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `job_id` int(11) NOT NULL,
  `div_id` int(11) NOT NULL,
  `birth_of_date` date DEFAULT NULL,
  `employee_status` int(11) NOT NULL,
  `employee_position` int(11) NOT NULL,
  `hp` varchar(20) COLLATE utf32_unicode_ci NOT NULL,
  `hp2` varchar(20) COLLATE utf32_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `bank_account` varchar(10) COLLATE utf32_unicode_ci DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `aia_account` varchar(20) COLLATE utf32_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `email2` varchar(255) COLLATE utf32_unicode_ci DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `contract_start` date DEFAULT NULL,
  `contract_end` date DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'active',
  `non_active_date` timestamp NULL DEFAULT NULL,
  `note` varchar(255) COLLATE utf32_unicode_ci DEFAULT NULL,
  `emergency_number` varchar(20) COLLATE utf32_unicode_ci DEFAULT NULL,
  `emergency_name` varchar(255) COLLATE utf32_unicode_ci DEFAULT NULL,
  `emergency_relation` varchar(255) COLLATE utf32_unicode_ci DEFAULT NULL,
  `npwp` varchar(15) COLLATE utf32_unicode_ci NOT NULL,
  `client_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_c` bigint(20) NOT NULL,
  `user_m` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `finger_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_position`
--

CREATE TABLE `employee_position` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'active',
  `user_c` int(11) NOT NULL,
  `user_m` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_status`
--

CREATE TABLE `employee_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'active',
  `user_c` int(11) NOT NULL,
  `user_m` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `holiday`
--

CREATE TABLE `holiday` (
  `id` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `user_c` bigint(20) NOT NULL,
  `user_m` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE `job` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'active',
  `user_c` int(11) NOT NULL,
  `user_m` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keys_api`
--

CREATE TABLE `keys_api` (
  `id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `id` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `emp_id` bigint(20) NOT NULL,
  `reason` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'active',
  `user_c` bigint(20) NOT NULL,
  `user_m` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical`
--

CREATE TABLE `medical` (
  `id` bigint(11) NOT NULL,
  `emp_id` bigint(11) NOT NULL,
  `nominal` int(11) NOT NULL,
  `status` enum('active','inactive','changed') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'active',
  `user_c` int(11) NOT NULL,
  `user_m` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_reimbursement`
--

CREATE TABLE `medical_reimbursement` (
  `id` bigint(20) NOT NULL,
  `emp_id` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `real_nominal` bigint(20) NOT NULL,
  `nominal` int(11) NOT NULL,
  `status` enum('not post','posted') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'not post',
  `user_c` bigint(20) NOT NULL,
  `user_m` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `overtime`
--

CREATE TABLE `overtime` (
  `id` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `emp_id` bigint(20) NOT NULL,
  `reason` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `time_go_home` time DEFAULT NULL,
  `start_in` timestamp NULL DEFAULT NULL,
  `end_out` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` enum('active','inactive') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'active',
  `user_c` bigint(20) NOT NULL,
  `user_m` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf32_unicode_ci NOT NULL,
  `leaves_sub` tinyint(1) NOT NULL,
  `status` enum('Active','Inactive') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'Active',
  `user_c` int(11) NOT NULL,
  `user_m` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `raw_attendance`
--

CREATE TABLE `raw_attendance` (
  `id` bigint(11) NOT NULL,
  `finger_id` bigint(11) NOT NULL,
  `name` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `tap_time` time NOT NULL,
  `user_c` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `raw_overtime`
--

CREATE TABLE `raw_overtime` (
  `id` bigint(20) NOT NULL,
  `no` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `emp_id` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `reason` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `start_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_out` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `upload_status` enum('queue','RBS','RBA','rejected','accepted','duplicate') COLLATE utf32_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'active',
  `desc_status` varchar(255) COLLATE utf32_unicode_ci DEFAULT NULL,
  `user_c` bigint(20) NOT NULL,
  `user_m` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sick`
--

CREATE TABLE `sick` (
  `id` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `emp_id` bigint(20) NOT NULL,
  `reason` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'active',
  `user_c` bigint(20) NOT NULL,
  `user_m` bigint(20) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `emp_id` bigint(11) NOT NULL,
  `password` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'active',
  `user_c` int(11) NOT NULL,
  `user_m` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allowance`
--
ALTER TABLE `allowance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance_detail`
--
ALTER TABLE `attendance_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance_period`
--
ALTER TABLE `attendance_period`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance_period_history`
--
ALTER TABLE `attendance_period_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance_report`
--
ALTER TABLE `attendance_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance_timing`
--
ALTER TABLE `attendance_timing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `autoreport_email`
--
ALTER TABLE `autoreport_email`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_list`
--
ALTER TABLE `bank_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ci_sessions_data`
--
ALTER TABLE `ci_sessions_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config_api`
--
ALTER TABLE `config_api`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cuti`
--
ALTER TABLE `cuti`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cuti_last_periodic`
--
ALTER TABLE `cuti_last_periodic`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `division`
--
ALTER TABLE `division`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_position`
--
ALTER TABLE `employee_position`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_status`
--
ALTER TABLE `employee_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `holiday`
--
ALTER TABLE `holiday`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keys_api`
--
ALTER TABLE `keys_api`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medical`
--
ALTER TABLE `medical`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medical_reimbursement`
--
ALTER TABLE `medical_reimbursement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `overtime`
--
ALTER TABLE `overtime`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `raw_attendance`
--
ALTER TABLE `raw_attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `raw_overtime`
--
ALTER TABLE `raw_overtime`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sick`
--
ALTER TABLE `sick`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allowance`
--
ALTER TABLE `allowance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance_detail`
--
ALTER TABLE `attendance_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance_period`
--
ALTER TABLE `attendance_period`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance_period_history`
--
ALTER TABLE `attendance_period_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance_report`
--
ALTER TABLE `attendance_report`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance_timing`
--
ALTER TABLE `attendance_timing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `autoreport_email`
--
ALTER TABLE `autoreport_email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_list`
--
ALTER TABLE `bank_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config_api`
--
ALTER TABLE `config_api`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cuti`
--
ALTER TABLE `cuti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cuti_last_periodic`
--
ALTER TABLE `cuti_last_periodic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `division`
--
ALTER TABLE `division`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_position`
--
ALTER TABLE `employee_position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_status`
--
ALTER TABLE `employee_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holiday`
--
ALTER TABLE `holiday`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job`
--
ALTER TABLE `job`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keys_api`
--
ALTER TABLE `keys_api`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical`
--
ALTER TABLE `medical`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical_reimbursement`
--
ALTER TABLE `medical_reimbursement`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `overtime`
--
ALTER TABLE `overtime`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `raw_attendance`
--
ALTER TABLE `raw_attendance`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `raw_overtime`
--
ALTER TABLE `raw_overtime`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sick`
--
ALTER TABLE `sick`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;


INSERT INTO `bank_list` (`id`, `name`, `status`, `user_c`, `user_m`, `created_date`, `modified_date`) VALUES
(1, 'BCA', 'active', 1, NULL, '2016-10-06 06:58:39', NULL),
(2, 'Mandiri', 'active', 1, NULL, '2016-10-06 06:58:49', NULL);


INSERT INTO `division` (`id`, `name`, `status`, `user_c`, `user_m`, `created_date`, `modified_date`) VALUES
(1, 'IT', 'active', 1, 1, '2016-09-29 06:04:39', '2016-11-07 06:53:40'),
(2, 'Admin', 'active', 1, 1, '2016-09-29 06:04:39', '2016-11-08 03:41:37'),
(3, 'Management', 'active', 2, NULL, '2017-01-17 06:46:37', NULL),
(4, 'Sales & Marketing', 'active', 2, NULL, '2017-01-17 06:46:53', NULL),
(5, 'Finance ', 'active', 1, 1, '2017-01-23 06:24:49', '2017-01-23 06:26:05');


INSERT INTO `employee` (`id`, `name`, `nick_name`, `job_id`, `div_id`, `birth_of_date`, `employee_status`, `hp`, `hp2`, `address`, `bank_account`, `bank_id`, `aia_account`, `email`, `email2`, `join_date`, `contract_start`, `contract_end`, `status`, `non_active_date`, `note`, `emergency_number`, `emergency_name`, `emergency_relation`, `npwp`, `client_id`, `project_id`, `user_c`, `user_m`, `created_date`, `modified_date`, `finger_id`) VALUES
(1, 'Admin HRM Adidata', 'Admin HRM', 1, 1, '2014-08-01', 1, '62215366 0016', NULL, 'Gedung Graha Kencana 7th floor,\r\nJl. Raya Pejuangan No. 88,\r\nJakarta 11530\r\nIndonesia', NULL, NULL, NULL, 'admin@adidata.co.id', NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, '', 0, 0, 0, NULL, '2016-10-12 18:40:52', '2016-10-12 18:41:07', NULL),
(2, 'Garpepi Hanief Aotearoa', 'Garpepi', 2, 2, '1991-08-15', 1, '000000000000', '', '0', '0', 1, '0', 'garpepi@adidata.co.id', 'others.garpepi@gmail.com', '2015-10-13', NULL, NULL, 'active', NULL, '', '0', '', '', '', 0,0, 1, NULL, '2016-10-16 16:18:43', '2016-10-19 00:26:31', 1);


INSERT INTO `employee_status` (`id`, `name`, `status`, `user_c`, `user_m`, `created_date`, `modified_date`) VALUES
(1, 'Permanent', 'active', 2, NULL, '2016-10-10 07:49:30', NULL),
(2, 'Contract', 'active', 2, NULL, '2016-10-10 07:49:30', NULL),
(3, 'Probation', 'active', 2, NULL, '2016-10-10 07:49:30', '2016-10-10 07:49:36'),
(4, 'Permanent QA', 'active', 1, NULL, '2017-04-10 05:54:42', NULL);



INSERT INTO `job` (`id`, `name`, `status`, `user_c`, `user_m`, `created_date`, `modified_date`) VALUES
(1, 'Tester', 'active', 1, 1, '2016-09-29 06:04:01', '2016-11-07 06:51:54'),
(2, 'IT Support', 'active', 1, 1, '2016-09-29 06:04:01', '2016-11-07 06:39:57'),
(3, 'Owner', 'active', 2, NULL, '2017-01-17 06:44:30', NULL),
(4, 'Marketing', 'active', 2, 1, '2017-01-17 06:45:05', '2017-03-13 07:00:24'),
(5, 'admin', 'active', 1, 1, '2017-01-23 06:22:48', '2017-01-23 06:24:12');


INSERT INTO `medical` (`id`, `emp_id`, `nominal`, `status`, `user_c`, `user_m`, `created_date`, `modified_date`) VALUES
(1, 2, 3500000, 'active', 1, NULL, '2016-10-16 16:18:43', '2016-10-25 01:01:35');

INSERT INTO `users` (`id`, `emp_id`, `password`, `status`, `user_c`, `user_m`, `created_date`, `modified_date`) VALUES
(1, 1, '502acfce93df38057cfd3519d5519545798fd7e5452ca6441719eb9d2a10f9ec', 'active', 1, NULL, '2016-10-05 18:23:06', '2016-10-12 18:43:26');
