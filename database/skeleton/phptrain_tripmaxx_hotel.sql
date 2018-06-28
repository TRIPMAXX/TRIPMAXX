-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 28, 2018 at 03:57 AM
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
-- Database: `phptrain_tripmaxx_hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `tm_attributes`
--

CREATE TABLE `tm_attributes` (
  `id` int(11) NOT NULL,
  `attribute_name` varchar(255) DEFAULT NULL,
  `serial_number` int(11) NOT NULL DEFAULT '0',
  `type` varchar(100) DEFAULT NULL,
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
-- Table structure for table `tm_hotels`
--

CREATE TABLE `tm_hotels` (
  `id` int(11) NOT NULL,
  `hotel_type` varchar(255) DEFAULT NULL COMMENT '1=Family / 2=Honeymoon / 3=Joiner',
  `hotel_name` varchar(255) DEFAULT NULL,
  `hotel_images` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `password` text,
  `hotel_address` text,
  `country` int(11) NOT NULL DEFAULT '0',
  `state` int(11) NOT NULL DEFAULT '0',
  `city` int(11) NOT NULL DEFAULT '0',
  `postal_code` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `alternate_phone_number` varchar(20) DEFAULT NULL,
  `short_description` text,
  `long_description` text,
  `checkin_time` varchar(10) DEFAULT NULL,
  `checkout_time` varchar(10) DEFAULT NULL,
  `rating` float DEFAULT NULL,
  `is_cancellation_policy_applied` int(5) NOT NULL DEFAULT '1' COMMENT '1 - Yes / 0 - No',
  `cancellation_charge` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cancellation_allowed_days` int(11) NOT NULL DEFAULT '0',
  `other_policy` text,
  `amenities` text COMMENT 'ids of tm_attributes table',
  `status` int(5) NOT NULL DEFAULT '1' COMMENT '1 - Active / 0 - Inactive',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tm_rooms`
--

CREATE TABLE `tm_rooms` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL DEFAULT '0',
  `room_attribute_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_room_attributes',
  `room_type` varchar(255) DEFAULT NULL COMMENT 'title from tm_room_attribute',
  `room_images` text,
  `room_description` text,
  `amenities` text COMMENT 'ids of tm_attributes table',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `number_of_rooms` int(11) NOT NULL DEFAULT '0',
  `status` int(5) NOT NULL DEFAULT '1' COMMENT '1 - Active / 0 - Inactive',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tm_room_agent_markup`
--

CREATE TABLE `tm_room_agent_markup` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_room table',
  `agent_id` int(11) NOT NULL DEFAULT '0',
  `markup_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tm_room_attributes`
--

CREATE TABLE `tm_room_attributes` (
  `id` int(11) NOT NULL,
  `attribute_name` varchar(255) DEFAULT NULL,
  `serial_number` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tm_room_prices`
--

CREATE TABLE `tm_room_prices` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_rooms table',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `price_per_night` decimal(10,2) NOT NULL DEFAULT '0.00',
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
-- Indexes for table `tm_hotels`
--
ALTER TABLE `tm_hotels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_rooms`
--
ALTER TABLE `tm_rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_room_agent_markup`
--
ALTER TABLE `tm_room_agent_markup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_room_attributes`
--
ALTER TABLE `tm_room_attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_room_prices`
--
ALTER TABLE `tm_room_prices`
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
-- AUTO_INCREMENT for table `tm_attributes`
--
ALTER TABLE `tm_attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
-- AUTO_INCREMENT for table `tm_hotels`
--
ALTER TABLE `tm_hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tm_rooms`
--
ALTER TABLE `tm_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tm_room_agent_markup`
--
ALTER TABLE `tm_room_agent_markup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tm_room_attributes`
--
ALTER TABLE `tm_room_attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tm_room_prices`
--
ALTER TABLE `tm_room_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
