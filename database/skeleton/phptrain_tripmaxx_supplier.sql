-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 28, 2018 at 03:59 AM
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
-- Database: `phptrain_tripmaxx_supplier`
--

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
  `sortname` varchar(3) NOT NULL,
  `name` varchar(150) NOT NULL,
  `phonecode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- Table structure for table `tm_supplier`
--

CREATE TABLE `tm_supplier` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `type_of_business` varchar(150) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `password` text NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text,
  `country` int(11) NOT NULL DEFAULT '0',
  `supplier_code` varchar(100) DEFAULT NULL,
  `supplier_priority` int(11) NOT NULL DEFAULT '0',
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(5) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

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
-- Indexes for table `tm_states`
--
ALTER TABLE `tm_states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tm_supplier`
--
ALTER TABLE `tm_supplier`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

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
-- AUTO_INCREMENT for table `tm_states`
--
ALTER TABLE `tm_states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4122;

--
-- AUTO_INCREMENT for table `tm_supplier`
--
ALTER TABLE `tm_supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
