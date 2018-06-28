-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 28, 2018 at 03:55 AM
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
-- Database: `phptrain_tripmaxx_bookings`
--

-- --------------------------------------------------------

--
-- Table structure for table `tm_booking_assigned_supplier`
--

CREATE TABLE `tm_booking_assigned_supplier` (
  `id` int(11) NOT NULL,
  `booking_master_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_booking_masters table',
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id from supplier database',
  `status` int(5) NOT NULL DEFAULT '0' COMMENT '0 - Pending / 1 - Approved / 2 - Rejected',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tm_booking_destination`
--

CREATE TABLE `tm_booking_destination` (
  `id` int(11) NOT NULL,
  `booking_master_id` int(11) NOT NULL DEFAULT '0',
  `country_id` int(11) NOT NULL DEFAULT '0',
  `city_id` int(11) NOT NULL DEFAULT '0',
  `no_of_night` int(11) NOT NULL DEFAULT '0',
  `hotel_rating` varchar(50) DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tm_booking_hotel_details`
--

CREATE TABLE `tm_booking_hotel_details` (
  `id` int(11) NOT NULL,
  `booking_destination_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_booking_destination table',
  `hotel_id` int(11) NOT NULL DEFAULT '0',
  `room_id` int(11) NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `booking_start_date` date DEFAULT NULL,
  `booking_end_date` date DEFAULT NULL,
  `agent_markup_percentage` float NOT NULL DEFAULT '0',
  `currency_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_currencies table',
  `avalibility_status` enum('A','N') NOT NULL DEFAULT 'A' COMMENT 'A - Available / N - Not Available',
  `status` int(5) NOT NULL DEFAULT '0' COMMENT '0 - Pending / 1 - Accepted / 2 - Rejected',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tm_booking_masters`
--

CREATE TABLE `tm_booking_masters` (
  `id` int(11) NOT NULL,
  `booking_number` varchar(255) DEFAULT NULL,
  `quotation_name` varchar(255) DEFAULT NULL,
  `checkin_date` date DEFAULT NULL,
  `checkout_date` date DEFAULT NULL,
  `booking_type` varchar(100) DEFAULT NULL,
  `dmc_id` int(11) NOT NULL DEFAULT '0',
  `agent_id` int(11) NOT NULL DEFAULT '0',
  `nationality` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_countries table',
  `residance_country` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_countries table',
  `invoice_currency` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_currencies table',
  `number_of_rooms` int(11) NOT NULL DEFAULT '0',
  `adult` text COMMENT 'adult array',
  `child` text COMMENT 'child details array',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_type` varchar(20) NOT NULL DEFAULT 'credit' COMMENT 'credit/cash',
  `payment_status` enum('P','U') NOT NULL DEFAULT 'P' COMMENT 'P - Paid / U - Unpaid',
  `payment_date` datetime DEFAULT NULL,
  `pay_within_days` int(11) NOT NULL DEFAULT '0',
  `is_emailed` int(5) NOT NULL DEFAULT '0',
  `status` int(5) NOT NULL DEFAULT '0' COMMENT '0 - Pending / 1 - Accepted / 2 - Rejected',
  `is_deleted` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Y - Deleted / N - Not Deleted',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tm_booking_tour_details`
--

CREATE TABLE `tm_booking_tour_details` (
  `id` int(11) NOT NULL,
  `booking_destination_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_booking_destination table',
  `tour_id` int(11) NOT NULL DEFAULT '0',
  `offer_id` int(11) NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `number_of_person` int(11) NOT NULL DEFAULT '0',
  `booking_start_date` date DEFAULT NULL,
  `booking_end_date` date DEFAULT NULL,
  `pickup_time` varchar(10) DEFAULT NULL,
  `dropoff_time` varchar(10) DEFAULT NULL,
  `agent_markup_percentage` float NOT NULL DEFAULT '0',
  `nationality_addon_percentage` float NOT NULL DEFAULT '0',
  `avalibility_status` enum('A','N') NOT NULL DEFAULT 'A' COMMENT 'A - Available / N - Not Available',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tm_booking_transfer_details`
--

CREATE TABLE `tm_booking_transfer_details` (
  `id` int(11) NOT NULL,
  `booking_destination_id` int(11) NOT NULL DEFAULT '0' COMMENT 'id from tm_booking_destination table',
  `transfer_id` int(11) NOT NULL DEFAULT '0',
  `offer_id` int(11) NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `number_of_person` int(11) NOT NULL DEFAULT '0',
  `booking_start_date` date DEFAULT NULL,
  `booking_end_date` date DEFAULT NULL,
  `pickup_time` varchar(10) DEFAULT NULL,
  `dropoff_time` varchar(10) DEFAULT NULL,
  `airport` varchar(255) DEFAULT NULL,
  `flight_number_name` varchar(255) DEFAULT NULL,
  `agent_markup_percentage` float NOT NULL DEFAULT '0',
  `nationality_addon_percentage` float NOT NULL DEFAULT '0',
  `avalibility_status` enum('A','N') NOT NULL DEFAULT 'A' COMMENT 'A - Available / N - Not Available',
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
-- Indexes for table `tm_booking_assigned_supplier`
--
ALTER TABLE `tm_booking_assigned_supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_booking_destination`
--
ALTER TABLE `tm_booking_destination`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_booking_hotel_details`
--
ALTER TABLE `tm_booking_hotel_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_booking_masters`
--
ALTER TABLE `tm_booking_masters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_booking_tour_details`
--
ALTER TABLE `tm_booking_tour_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_booking_transfer_details`
--
ALTER TABLE `tm_booking_transfer_details`
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
-- Indexes for table `tm_states`
--
ALTER TABLE `tm_states`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tm_booking_assigned_supplier`
--
ALTER TABLE `tm_booking_assigned_supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tm_booking_destination`
--
ALTER TABLE `tm_booking_destination`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tm_booking_hotel_details`
--
ALTER TABLE `tm_booking_hotel_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tm_booking_masters`
--
ALTER TABLE `tm_booking_masters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tm_booking_tour_details`
--
ALTER TABLE `tm_booking_tour_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tm_booking_transfer_details`
--
ALTER TABLE `tm_booking_transfer_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
-- AUTO_INCREMENT for table `tm_states`
--
ALTER TABLE `tm_states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4122;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
