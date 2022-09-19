-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 08, 2022 at 09:58 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `slides`
--
CREATE DATABASE IF NOT EXISTS `slides` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `slides`;

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

DROP TABLE IF EXISTS `slides`;
CREATE TABLE IF NOT EXISTS `slides` (
  `slideID` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(11) NOT NULL,
  `imageName` varchar(255) NOT NULL,
  PRIMARY KEY (`slideID`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `slides`
--

INSERT INTO `slides` (`slideID`, `position`, `imageName`) VALUES
(8, 1, 'img (1).jpg'),
(9, 2, 'img (2).jpg'),
(10, 3, 'img (3).jpg'),
(11, 4, 'img (4).jpg'),
(12, 5, 'img (5).jpg'),
(13, 6, 'img (6).jpg'),
(14, 7, 'img (7).jpg'),
(15, 8, 'img (8).jpg'),
(16, 9, 'img (9).jpg'),
(17, 10, 'img (10).jpg'),
(20, 11, 'img (11).jpg');

-- --------------------------------------------------------

--
-- Table structure for table `time`
--

DROP TABLE IF EXISTS `time`;
CREATE TABLE IF NOT EXISTS `time` (
  `timeID` int(11) NOT NULL AUTO_INCREMENT,
  `duration` int(11) NOT NULL,
  PRIMARY KEY (`timeID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `time`
--

INSERT INTO `time` (`timeID`, `duration`) VALUES
(1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `password`) VALUES
(1, 'chuck', '$2y$10$IWQApq.IKzHJWVHMUJigY.cxwXu94NCtz9lMbWAxg6nXwbkrCd4Oa'),
(3, 'john', '$2y$10$YnCH2pQ5SCAIT17/l.I5JOz3emWQQfcR9Umuf3zUBA.2PmxzfCsfC');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
