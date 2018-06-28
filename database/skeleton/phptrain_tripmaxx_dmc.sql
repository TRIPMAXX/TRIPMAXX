-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 28, 2018 at 03:56 AM
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
-- Database: `phptrain_tripmaxx_dmc`
--

-- --------------------------------------------------------

--
-- Table structure for table `tm_access_permissions`
--

CREATE TABLE `tm_access_permissions` (
  `id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL DEFAULT '0',
  `dmc_id` int(11) NOT NULL DEFAULT '0',
  `meta_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `permission` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tm_airports`
--

CREATE TABLE `tm_airports` (
  `id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL DEFAULT '0',
  `airport_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `airport_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
-- Table structure for table `tm_cms`
--

CREATE TABLE `tm_cms` (
  `id` int(11) NOT NULL,
  `page_heading` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_description` text COLLATE utf8_unicode_ci,
  `page_banner_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_meta_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_meta_keyword` text COLLATE utf8_unicode_ci,
  `page_meta_description` text COLLATE utf8_unicode_ci,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
-- Table structure for table `tm_dmc`
--

CREATE TABLE `tm_dmc` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_type` enum('S','E') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'E',
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tm_email_templates`
--

CREATE TABLE `tm_email_templates` (
  `id` int(11) NOT NULL,
  `template_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template_subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template_body` text COLLATE utf8_unicode_ci,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tm_home_slider`
--

CREATE TABLE `tm_home_slider` (
  `id` int(11) NOT NULL,
  `slider_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slider_text` text COLLATE utf8_unicode_ci,
  `serial_number` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tm_languages`
--

CREATE TABLE `tm_languages` (
  `id` int(11) NOT NULL,
  `language_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `language_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serial_number` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tm_modules`
--

CREATE TABLE `tm_modules` (
  `id` int(11) NOT NULL,
  `module_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tm_promotional_offers`
--

CREATE TABLE `tm_promotional_offers` (
  `id` int(11) NOT NULL,
  `offer_title` text COLLATE utf8_unicode_ci,
  `offer_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `offer_description` text COLLATE utf8_unicode_ci,
  `offer_start_date` date DEFAULT NULL,
  `offer_end_date` date DEFAULT NULL,
  `offer_document` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `allowed_account` text COLLATE utf8_unicode_ci,
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
  `default_credit_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `maintenance_mode` int(11) NOT NULL DEFAULT '0',
  `google_map_api` text COLLATE utf8_unicode_ci,
  `google_analytics_api` text COLLATE utf8_unicode_ci,
  `from_email_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `threshold_booking_time` float NOT NULL DEFAULT '0' COMMENT 'In hour',
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

-- --------------------------------------------------------

--
-- Table structure for table `tm_support_tickets`
--

CREATE TABLE `tm_support_tickets` (
  `id` int(11) NOT NULL,
  `ticket_id` varchar(100) DEFAULT NULL,
  `account_type` enum('A','H','S') NOT NULL DEFAULT 'A' COMMENT 'A=agent/H=hotel/S=supplier',
  `account_name` varchar(255) DEFAULT NULL,
  `account_email` varchar(255) DEFAULT NULL,
  `account_phone` varchar(20) DEFAULT NULL,
  `heading` varchar(255) DEFAULT NULL,
  `description` text,
  `priority` enum('H','M','L') NOT NULL DEFAULT 'H' COMMENT 'H=High/M=midium/L=Low',
  `attachments` text,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('P','C') NOT NULL DEFAULT 'P' COMMENT 'P=Pending/C=complete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tm_support_ticket_replies`
--

CREATE TABLE `tm_support_ticket_replies` (
  `id` int(11) NOT NULL,
  `support_ticket_id` int(11) NOT NULL,
  `reply_from` varchar(255) DEFAULT NULL,
  `reply_to` varchar(255) DEFAULT NULL,
  `massage` text,
  `attachments` text,
  `response_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tm_access_permissions`
--
ALTER TABLE `tm_access_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_airports`
--
ALTER TABLE `tm_airports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_cities`
--
ALTER TABLE `tm_cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_cms`
--
ALTER TABLE `tm_cms`
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
-- Indexes for table `tm_dmc`
--
ALTER TABLE `tm_dmc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_address` (`email_address`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `tm_email_templates`
--
ALTER TABLE `tm_email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_home_slider`
--
ALTER TABLE `tm_home_slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_languages`
--
ALTER TABLE `tm_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_modules`
--
ALTER TABLE `tm_modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_promotional_offers`
--
ALTER TABLE `tm_promotional_offers`
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
-- Indexes for table `tm_support_tickets`
--
ALTER TABLE `tm_support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_support_ticket_replies`
--
ALTER TABLE `tm_support_ticket_replies`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tm_access_permissions`
--
ALTER TABLE `tm_access_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tm_airports`
--
ALTER TABLE `tm_airports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tm_cities`
--
ALTER TABLE `tm_cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48316;

--
-- AUTO_INCREMENT for table `tm_cms`
--
ALTER TABLE `tm_cms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
-- AUTO_INCREMENT for table `tm_dmc`
--
ALTER TABLE `tm_dmc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tm_email_templates`
--
ALTER TABLE `tm_email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `tm_home_slider`
--
ALTER TABLE `tm_home_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tm_languages`
--
ALTER TABLE `tm_languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tm_modules`
--
ALTER TABLE `tm_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tm_promotional_offers`
--
ALTER TABLE `tm_promotional_offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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

--
-- AUTO_INCREMENT for table `tm_support_tickets`
--
ALTER TABLE `tm_support_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tm_support_ticket_replies`
--
ALTER TABLE `tm_support_ticket_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
