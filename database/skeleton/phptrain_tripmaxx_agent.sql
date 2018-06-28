-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 28, 2018 at 03:54 AM
-- Server version: 10.0.35-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phptrain_tripmaxx_agent`
--

-- --------------------------------------------------------

--
-- Table structure for table `tm_agents`
--

CREATE TABLE `tm_agents` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_dmc table of dmc database ',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `type` enum('A','G') NOT NULL DEFAULT 'A' COMMENT 'A - Agent / G - GSA',
  `company_name` varchar(255) DEFAULT NULL,
  `accounting_name` varchar(255) DEFAULT NULL,
  `first_name` varchar(150) DEFAULT NULL,
  `middle_name` varchar(150) DEFAULT NULL,
  `last_name` varchar(150) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `iata_status` int(5) NOT NULL DEFAULT '1' COMMENT '1 - Approve / 0 - Not Approve',
  `nature_of_business` varchar(150) DEFAULT NULL,
  `preferred_currency` int(11) NOT NULL DEFAULT '0',
  `country` int(11) NOT NULL DEFAULT '0',
  `state` int(11) NOT NULL DEFAULT '0',
  `city` int(11) NOT NULL DEFAULT '0',
  `zipcode` varchar(100) DEFAULT NULL,
  `address` text,
  `timezone` varchar(255) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `mobile_number` varchar(50) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` text,
  `account_department_employee_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_dmc table of dmc database ',
  `account_department_name` varchar(255) DEFAULT NULL,
  `account_department_email` varchar(255) DEFAULT NULL,
  `account_department_number` varchar(50) DEFAULT NULL,
  `reservation_department_employee_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_dmc table of dmc database ',
  `reservation_department_name` varchar(255) DEFAULT NULL,
  `reservation_department_email` varchar(255) DEFAULT NULL,
  `reservation_department_number` varchar(50) DEFAULT NULL,
  `management_department_employee_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_dmc table of dmc database ',
  `management_department_name` varchar(255) DEFAULT NULL,
  `management_department_email` varchar(255) DEFAULT NULL,
  `management_department_number` varchar(50) DEFAULT NULL,
  `hotel_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tour_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `transfer_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `package_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `credit_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_type` varchar(20) DEFAULT 'credit' COMMENT 'credit/cash',
  `pay_within_days` int(11) NOT NULL DEFAULT '0',
  `status` int(5) NOT NULL DEFAULT '1' COMMENT '1 - Active / 2 - Inactive',
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tm_agent_accounting`
--

CREATE TABLE `tm_agent_accounting` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_agents table',
  `transaction_id` varchar(255) DEFAULT NULL COMMENT 'auto generated id',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `note` text,
  `debit_or_credit` enum('Credit','Debit') NOT NULL DEFAULT 'Credit',
  `closing_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tm_cities`
--

CREATE TABLE `tm_cities` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tm_countries`
--

CREATE TABLE `tm_countries` (
  `id` int(11) NOT NULL,
  `sortname` varchar(3) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `phonecode` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tm_currencies`
--

CREATE TABLE `tm_currencies` (
  `id` int(11) NOT NULL,
  `currency_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hex_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serial_number` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tm_settings`
--

CREATE TABLE `tm_settings` (
  `id` int(11) NOT NULL,
  `website_logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `punch_line` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_person_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_email_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_phone_number` text COLLATE utf8_unicode_ci,
  `contact_address` text COLLATE utf8_unicode_ci,
  `default_currency` int(11) DEFAULT NULL,
  `maintenance_mode` int(11) NOT NULL DEFAULT '0',
  `google_map_api` text COLLATE utf8_unicode_ci,
  `google_analytics_api` text COLLATE utf8_unicode_ci,
  `from_email_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `default_page_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `default_meta_keyword` text COLLATE utf8_unicode_ci,
  `default_meta_description` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tm_states`
--

CREATE TABLE `tm_states` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `country_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tm_agents`
--
ALTER TABLE `tm_agents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_agent_accounting`
--
ALTER TABLE `tm_agent_accounting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_cities`
--
ALTER TABLE `tm_cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_countries`
--
ALTER TABLE `tm_countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_currencies`
--
ALTER TABLE `tm_currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_settings`
--
ALTER TABLE `tm_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_states`
--
ALTER TABLE `tm_states`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tm_agents`
--
ALTER TABLE `tm_agents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tm_agent_accounting`
--
ALTER TABLE `tm_agent_accounting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `tm_cities`
--
ALTER TABLE `tm_cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48316;

--
-- AUTO_INCREMENT for table `tm_countries`
--
ALTER TABLE `tm_countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT for table `tm_currencies`
--
ALTER TABLE `tm_currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tm_settings`
--
ALTER TABLE `tm_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tm_states`
--
ALTER TABLE `tm_states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4122;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
