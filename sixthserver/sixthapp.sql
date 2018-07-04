-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 04, 2018 at 12:54 PM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sixthapp`
--
CREATE DATABASE IF NOT EXISTS `sixthapp` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `sixthapp`;

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `ID` int(11) NOT NULL,
  `Username` text NOT NULL,
  `Password` text NOT NULL,
  `Year` tinyint(4) NOT NULL,
  `IsAdmin` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`ID`, `Username`, `Password`, `Year`, `IsAdmin`) VALUES
(1, 'DevAdmin', '$2y$10$tHweuXW5nQeBoP4sBdLaLu5rinctHj60YJ93AfyzC720bTVPEEFY6', 12, 1),
(2, 'DevUser', '$2y$10$ydMisDkUWwxG6rw67NdybOQaJe0ycq8jpXvbiO.m2wd0MRKcz6BaG', 12, 0),
(7, 'TestUser', '$2y$10$DBmj6nN03PAVcmLYVaJi/ODmvPZBp1WnfmOh4C2chV.VIzxZwa/Ce', 13, 0),
(8, 'Second', '$2y$10$AgIklPcGSiV7dWDQjht7dOFw71wanND9SQfmUifXjIBYveWWkqBRm', 12, 0);

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `ID` int(11) NOT NULL,
  `Title` text NOT NULL,
  `Content` text NOT NULL,
  `DateAdded` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`ID`, `Title`, `Content`, `DateAdded`) VALUES
(1, 'Test Announcement 1', 'This is a test announcement 1.', 1530699324),
(2, 'Test Announcement 2', 'Another test', 1530699378);

-- --------------------------------------------------------

--
-- Table structure for table `apikeys`
--

CREATE TABLE `apikeys` (
  `ID` int(11) NOT NULL,
  `Username` text NOT NULL,
  `Secret` text NOT NULL,
  `ExpireTime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `apikeys`
--

INSERT INTO `apikeys` (`ID`, `Username`, `Secret`, `ExpireTime`) VALUES
(44, 'TestUser', 's7PILSu1y3C3b8k3u5bIOojg0Zhqoocp.0', 1530695838),
(63, 'DevAdmin', 'ABnExHKgN5hRqZMn18DjHIEB2tcK7C6H.1', 1530696794),
(111, 'DevUser', 'pSx84elc2zW3EnpVLCmxHLS5jMyjX8uq.0', 1530704274);

-- --------------------------------------------------------

--
-- Table structure for table `calender`
--

CREATE TABLE `calender` (
  `ID` int(11) NOT NULL,
  `Name` text NOT NULL,
  `StartTime` int(11) NOT NULL,
  `EndTime` int(11) NOT NULL,
  `Notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `ID` int(11) NOT NULL,
  `Name` text NOT NULL,
  `AddedDate` int(11) NOT NULL,
  `ExpiryDate` int(11) NOT NULL,
  `Type` tinyint(4) NOT NULL,
  `Link` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`ID`, `Name`, `AddedDate`, `ExpiryDate`, `Type`, `Link`) VALUES
(1, 'February 2018 Newsletter', 1530697508, 1562233493, 1, '/resources/files/February-Newsletter-2018.pdf'),
(2, 'March 2018 Newsletter', 1530697512, 1562233493, 1, '/resources/files/March-Newsletter-2018.pdf'),
(3, 'May 2018 Newsletter', 1530697508, 1562233493, 1, '/resources/files/May-2018-Newsletter.pdf'),
(4, 'Sixth Form Notices', 1530697508, 1531302308, 2, '/resources/file/SixthNotices04072018.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `ID` int(11) NOT NULL,
  `Name` text NOT NULL,
  `ExpiryDate` int(11) NOT NULL,
  `Link` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `links`
--

INSERT INTO `links` (`ID`, `Name`, `ExpiryDate`, `Link`) VALUES
(1, 'Office 365', 1531298877, 'https://login.microsoftonline.com/'),
(2, 'Go4Schools', 1501298939, 'https://www.go4schools.com/students/Login.aspx?rurl=https%3a%2f%2fwww.go4schools.com%2fstudents%2fDefault.aspx');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `apikeys`
--
ALTER TABLE `apikeys`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `calender`
--
ALTER TABLE `calender`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `apikeys`
--
ALTER TABLE `apikeys`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;
--
-- AUTO_INCREMENT for table `calender`
--
ALTER TABLE `calender`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
