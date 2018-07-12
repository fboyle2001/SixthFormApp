-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 12, 2018 at 07:05 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.8

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

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `ID` int(11) NOT NULL,
  `Username` text NOT NULL,
  `Password` text NOT NULL,
  `Year` tinyint(4) NOT NULL,
  `IsAdmin` tinyint(4) NOT NULL,
  `Reset` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`ID`, `Username`, `Password`, `Year`, `IsAdmin`, `Reset`) VALUES
(1, 'DevAdmin', '$2y$10$Dl0uWhCpJkW8YVYBacFyrOlmigL25Eg6vZHi5R/WocNlhmtNae4ce', 0, 1, 0),
(2, 'DevUser', '$2y$10$ydMisDkUWwxG6rw67NdybOQaJe0ycq8jpXvbiO.m2wd0MRKcz6BaG', 12, 0, 0),
(7, 'TestUser', '$2y$12$Q5Q3U.ayzI4CPJk0iiw7PuHDAbS5.EZIs5Wx6HGqhn1kMfT81oZjW', 13, 0, 0),
(9, 'FinlayBoyle', '$2y$12$mLlfqaEw9oMFKuNbwes6BOj8N76EsiVApQgURsRZ1QEkVnzXZ13Vi', 12, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
CREATE TABLE `announcements` (
  `ID` int(11) NOT NULL,
  `Title` text NOT NULL,
  `Content` mediumtext NOT NULL,
  `DateAdded` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`ID`, `Title`, `Content`, `DateAdded`) VALUES
(1, 'Test Announcement 1', 'This is a test announcement 1.', 1530699324),
(2, 'Test Announcement 2', 'Another test for fInLAy', 1530699378),
(4, 'Hello World', 'Hello!', 1530970427),
(5, 'Is it okay?', 'Another', 1530970607),
(6, 'T', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vel nulla quam. Sed a vulputate dui, in convallis leo. Quisque auctor nec arcu vel venenatis. Nam quis venenatis turpis. Duis pellentesque augue scelerisque massa condimentum efficitur. Vivamus suscipit, purus non ornare luctus, mauris odio scelerisque eros, a commodo metus sem ac ex. In fringilla vitae mi a finibus. Sed dictum justo justo, consectetur efficitur nisi blandit id. Vestibulum vitae justo ut eros pulvinar vestibulum. Fusce metus augue, scelerisque eu fringilla non, lobortis sit amet elit. Suspendisse lacinia leo eros. In non tincidunt eros. Integer sed rutrum sapien. Sed at tortor eros. Praesent ullamcorper sed nisl vel facilisis. Vivamus purus libero, sagittis at commodo et, tincidunt at purus. Aliquam rutrum, eros eu consectetur feugiat, magna lacus tempor arcu, vitae ullamcorper risus lacus pulvinar quam. Sed viverra metus nisi, vitae finibus tortor aliquet at. Aliquam a eros convallis purus tempus gravida eget vitae lorem. Vivamus vel mollis magna, eget ullamcorper quam. Donec tristique nisi pharetra felis ornare, quis rutrum diam elementum. Etiam pulvinar nulla eget erat egestas laoreet. Proin efficitur ligula sit amet vestibulum cursus. Nam eu volutpat lorem, eu ornare nisl. Sed eleifend lacus eu condimentum blandit. Integer vitae lectus dictum, auctor lorem quis, fermentum sapien. Morbi arcu nulla, aliquet id hendrerit ac, tincidunt ac diam. Etiam dictum urna enim, ut gravida mauris dignissim sit amet. Nam a odio ante. Nullam vehicula condimentum pulvinar. Donec placerat commodo enim eu dapibus. Vestibulum ante erat, semper eget dignissim nec, viverra et nulla. Proin eu ipsum eu libero facilisis blandit id vitae mi. Cras a eleifend odio, ut bibendum sapien. Sed et urna sit amet sem semper luctus quis eget ligula. Ut varius dignissim metus, id elementum felis rhoncus vel. Suspendisse volutpat sed nulla nec suscipit. Ut condimentum quis mauris ac volutpat. Aenean pharetra sem ut accumsan convallis. Quisque molestie nulla neque, ac dictum arcu congue id. Suspendisse potenti. Sed volutpat ultrices justo a auctor. Integer nec mi tellus. Suspendisse vestibulum tortor enim, non sodales augue efficitur suscipit. Phasellus mollis malesuada ante eu porta. In hac habitasse platea dictumst. Etiam a consectetur metus. Proin placerat efficitur gravida. Aenean in lacus non eros euismod hendrerit. Suspendisse ut metus tellus. Cras rhoncus risus et diam rutrum euismod. Nulla facilisi. Nulla ornare libero urna. Ut vitae urna ac eros maximus accumsan. Curabitur vel felis ex. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec sed leo in risus tempus ornare eget et nibh. Maecenas ut consequat augue. Aliquam volutpat nec erat id euismod.', 1530970640),
(7, 'T', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vel nulla quam. Sed a vulputate dui, in convallis leo. Quisque auctor nec arcu vel venenatis. Nam quis venenatis turpis. Duis pellentesque augue scelerisque massa condimentum efficitur. Vivamus suscipit, purus non ornare luctus, mauris odio scelerisque eros, a commodo metus sem ac ex. In fringilla vitae mi a finibus. Sed dictum justo justo, consectetur efficitur nisi blandit id. Vestibulum vitae justo ut eros pulvinar vestibulum. Fusce metus augue, scelerisque eu fringilla non, lobortis sit amet elit.\r\n\r\nSuspendisse lacinia leo eros. In non tincidunt eros. Integer sed rutrum sapien. Sed at tortor eros. Praesent ullamcorper sed nisl vel facilisis. Vivamus purus libero, sagittis at commodo et, tincidunt at purus. Aliquam rutrum, eros eu consectetur feugiat, magna lacus tempor arcu, vitae ullamcorper risus lacus pulvinar quam. Sed viverra metus nisi, vitae finibus tortor aliquet at. Aliquam a eros convallis purus tempus gravida eget vitae lorem. Vivamus vel mollis magna, eget ullamcorper quam. Donec tristique nisi pharetra felis ornare, quis rutrum diam elementum. Etiam pulvinar nulla eget erat egestas laoreet. Proin efficitur ligula sit amet vestibulum cursus. Nam eu volutpat lorem, eu ornare nisl.\r\n\r\nSed eleifend lacus eu condimentum blandit. Integer vitae lectus dictum, auctor lorem quis, fermentum sapien. Morbi arcu nulla, aliquet id hendrerit ac, tincidunt ac diam. Etiam dictum urna enim, ut gravida mauris dignissim sit amet. Nam a odio ante. Nullam vehicula condimentum pulvinar. Donec placerat commodo enim eu dapibus. Vestibulum ante erat, semper eget dignissim nec, viverra et nulla. Proin eu ipsum eu libero facilisis blandit id vitae mi. Cras a eleifend odio, ut bibendum sapien.\r\n\r\nSed et urna sit amet sem semper luctus quis eget ligula. Ut varius dignissim metus, id elementum felis rhoncus vel. Suspendisse volutpat sed nulla nec suscipit. Ut condimentum quis mauris ac volutpat. Aenean pharetra sem ut accumsan convallis. Quisque molestie nulla neque, ac dictum arcu congue id. Suspendisse potenti. Sed volutpat ultrices justo a auctor. Integer nec mi tellus. Suspendisse vestibulum tortor enim, non sodales augue efficitur suscipit. Phasellus mollis malesuada ante eu porta. In hac habitasse platea dictumst. Etiam a consectetur metus. Proin placerat efficitur gravida.\r\n\r\nAenean in lacus non eros euismod hendrerit. Suspendisse ut metus tellus. Cras rhoncus risus et diam rutrum euismod. Nulla facilisi. Nulla ornare libero urna. Ut vitae urna ac eros maximus accumsan. Curabitur vel felis ex. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec sed leo in risus tempus ornare eget et nibh. Maecenas ut consequat augue. Aliquam volutpat nec erat id euismod.', 1530971246);

-- --------------------------------------------------------

--
-- Table structure for table `apikeys`
--

DROP TABLE IF EXISTS `apikeys`;
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
(111, 'DevUser', 'pSx84elc2zW3EnpVLCmxHLS5jMyjX8uq.0', 1530704274),
(366, 'DevAdmin', '6mGt2FTyWmBDM1y21LXDDawJ5juclOyo.1', 1531331311),
(381, 'FinlayBoyle', 'bWwhyLt5ck6COXTXDxOInnjYgknlZKsT.0', 1531333616);

-- --------------------------------------------------------

--
-- Table structure for table `calender`
--

DROP TABLE IF EXISTS `calender`;
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

DROP TABLE IF EXISTS `files`;
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
(1, 'February 2018 Newsletter', 1530697508, 1564233493, 1, '/resources/files/February-Newsletter-2018.pdf'),
(2, 'March 2018 Newsletter', 1530697508, 1566233493, 1, '/resources/files/March-Newsletter-2018.pdf'),
(3, 'May 2018 Newsletter', 1530697512, 2147483647, 1, '/resources/files/May-2018-Newsletter.pdf'),
(8, 'Test Notices', 1531045283, 2147483647, 2, '/resources/files/Finlay Boyle - Cambridge higher education exhibition 2018.pdf'),
(9, 'Latest Test', 1531045305, 1532995200, 2, '/resources/files/Logic.ly Circuits.pdf'),
(14, 'Booking Form', 1531167923, 1534550400, 2, '/resources/files/1531167923_2.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `grouplink`
--

DROP TABLE IF EXISTS `grouplink`;
CREATE TABLE `grouplink` (
  `ID` int(11) NOT NULL,
  `GroupID` int(11) NOT NULL,
  `AccountID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grouplink`
--

INSERT INTO `grouplink` (`ID`, `GroupID`, `AccountID`) VALUES
(1, 1, 1),
(2, 1, 9),
(3, 2, 1),
(4, 2, 9),
(5, 3, 9),
(6, 3, 2),
(7, 4, 1),
(8, 4, 2),
(9, 4, 9),
(10, 5, 9),
(11, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `ID` int(11) NOT NULL,
  `GroupName` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`ID`, `GroupName`) VALUES
(1, 'fin'),
(2, 'f'),
(3, 'r'),
(4, 'q'),
(5, 'ra');

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

DROP TABLE IF EXISTS `links`;
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
(2, 'Go4Schools', 1501298939, 'https://www.go4schools.com/students/Login.aspx?rurl=https%3a%2f%2fwww.go4schools.com%2fstudents%2fDefault.aspx'),
(4, 'Test', 2147483647, 'http://www.google.co.uk/'),
(5, 'Test Link', 2147483647, 'http://www.youtube.com/'),
(6, 'School Website', 1535068800, 'http://mildenhall.attrust.org.uk/');

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
-- Indexes for table `grouplink`
--
ALTER TABLE `grouplink`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `apikeys`
--
ALTER TABLE `apikeys`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=382;
--
-- AUTO_INCREMENT for table `calender`
--
ALTER TABLE `calender`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `grouplink`
--
ALTER TABLE `grouplink`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
