-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2017 at 03:59 PM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skips`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `ID` mediumint(9) NOT NULL,
  `AddressLine1` varchar(45) NOT NULL,
  `AddressLine2` varchar(45) NOT NULL,
  `County` varchar(20) NOT NULL,
  `Postcode` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `ID` int(11) NOT NULL,
  `Title` varchar(5) NOT NULL,
  `FirstName` varchar(25) NOT NULL,
  `LastName` varchar(25) NOT NULL,
  `PhoneNumber` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `ID` int(11) NOT NULL,
  `CustomerID` mediumint(9) NOT NULL,
  `AddressID` mediumint(9) NOT NULL,
  `DateOrdered` date NOT NULL,
  `SkipSize` tinyint(4) NOT NULL,
  `Instructions` varchar(500) NOT NULL,
  `DeliveryDate` date NOT NULL,
  `CollectionDate` date NOT NULL,
  `WeighbridgeTicket` mediumint(9) NOT NULL,
  `PaymentMethod` tinyint(4) NOT NULL,
  `Cost` double(8,2) NOT NULL,
  `InvoiceNumber` mediumint(9) NOT NULL,
  `Paid` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

