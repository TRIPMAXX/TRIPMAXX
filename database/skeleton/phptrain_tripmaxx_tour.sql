-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 28, 2018 at 04:00 AM
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
-- Database: `phptrain_tripmaxx_tour`
--

-- --------------------------------------------------------

--
-- Table structure for table `tm_attributes`
--

CREATE TABLE `tm_attributes` (
  `id` int(11) NOT NULL,
  `attribute_name` varchar(255) DEFAULT NULL,
  `serial_number` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1'
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
-- Table structure for table `tm_offers`
--

CREATE TABLE `tm_offers` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL DEFAULT '0',
  `offer_title` varchar(255) DEFAULT NULL,
  `offer_capacity` varchar(255) DEFAULT NULL,
  `service_type` text,
  `price_per_person` decimal(10,2) DEFAULT '0.00',
  `status` int(5) NOT NULL DEFAULT '1' COMMENT '1 - Active / 0 - Inactive',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tm_offer_addon_prices`
--

CREATE TABLE `tm_offer_addon_prices` (
  `id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_offers table',
  `country_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_countries table',
  `nationality` varchar(255) DEFAULT NULL,
  `addon_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` int(5) NOT NULL DEFAULT '1' COMMENT '1 - Active / 0 - Inctive',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tm_offer_agent_markup`
--

CREATE TABLE `tm_offer_agent_markup` (
  `id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_offers table',
  `agent_id` int(11) NOT NULL DEFAULT '0',
  `markup_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tm_offer_prices`
--

CREATE TABLE `tm_offer_prices` (
  `id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_offers table',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `price_per_person` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` int(5) NOT NULL DEFAULT '1',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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

-- --------------------------------------------------------

--
-- Table structure for table `tm_tours`
--

CREATE TABLE `tm_tours` (
  `id` int(11) NOT NULL,
  `tour_title` varchar(255) DEFAULT NULL,
  `tour_images` varchar(255) DEFAULT NULL,
  `tour_type` varchar(255) DEFAULT NULL,
  `tour_service` varchar(255) DEFAULT NULL,
  `service_note` text,
  `country` int(11) NOT NULL DEFAULT '0',
  `state` int(11) NOT NULL DEFAULT '0',
  `city` int(11) NOT NULL DEFAULT '0',
  `short_description` text,
  `long_description` text,
  `tour_start_time` varchar(20) DEFAULT NULL,
  `tour_end_time` varchar(20) DEFAULT NULL,
  `is_cancellation_policy_applied` int(5) NOT NULL DEFAULT '1' COMMENT '1 - Yes / 0 - No',
  `cancellation_charge` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cancellation_allowed_days` int(11) NOT NULL DEFAULT '0',
  `other_policy` text,
  `is_guide_included` int(5) NOT NULL DEFAULT '1' COMMENT '1 - Yes / 0 - No',
  `guide_language` varchar(255) DEFAULT NULL,
  `status` int(5) NOT NULL DEFAULT '1' COMMENT '1 - Active / 0 - Inactive',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tm_attributes`
--
ALTER TABLE `tm_attributes`
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
-- Indexes for table `tm_offers`
--
ALTER TABLE `tm_offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_offer_addon_prices`
--
ALTER TABLE `tm_offer_addon_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_offer_agent_markup`
--
ALTER TABLE `tm_offer_agent_markup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_offer_prices`
--
ALTER TABLE `tm_offer_prices`
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
-- Indexes for table `tm_tours`
--
ALTER TABLE `tm_tours`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tm_attributes`
--
ALTER TABLE `tm_attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
-- AUTO_INCREMENT for table `tm_offers`
--
ALTER TABLE `tm_offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tm_offer_addon_prices`
--
ALTER TABLE `tm_offer_addon_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tm_offer_agent_markup`
--
ALTER TABLE `tm_offer_agent_markup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tm_offer_prices`
--
ALTER TABLE `tm_offer_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- AUTO_INCREMENT for table `tm_tours`
--
ALTER TABLE `tm_tours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
