-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 31, 2012 at 06:52 PM
-- Server version: 5.1.50
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `varulv`
--

-- --------------------------------------------------------

--
-- Table structure for table `forums`
--

DROP TABLE IF EXISTS `forums`;
CREATE TABLE IF NOT EXISTS `forums` (
  `fID` int(7) NOT NULL AUTO_INCREMENT,
  `fName` varchar(255) NOT NULL,
  `fGame` int(6) NOT NULL,
  `fCat` int(6) NOT NULL DEFAULT '1',
  `fOpen` tinyint(1) NOT NULL DEFAULT '1',
  `fVisible` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fID`),
  KEY `fGame` (`fGame`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `forums`
--


-- --------------------------------------------------------

--
-- Table structure for table `forumxuser`
--

DROP TABLE IF EXISTS `forumxuser`;
CREATE TABLE IF NOT EXISTS `forumxuser` (
  `fxuID` bigint(20) NOT NULL AUTO_INCREMENT,
  `uID` int(7) NOT NULL,
  `fID` int(7) NOT NULL,
  `fxuRight` tinyint(3) NOT NULL,
  PRIMARY KEY (`fxuID`),
  UNIQUE KEY `uID_2` (`uID`,`fID`),
  KEY `uID` (`uID`),
  KEY `fID` (`fID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `forumxuser`
--


-- --------------------------------------------------------

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
CREATE TABLE IF NOT EXISTS `games` (
  `gID` int(6) NOT NULL AUTO_INCREMENT,
  `gCreated` int(11) NOT NULL,
  `gUpdated` int(11) NOT NULL,
  `gName` varchar(100) NOT NULL,
  `gOwner` int(7) NOT NULL,
  `gStatus` tinyint(2) NOT NULL,
  `gPlayers` tinyint(3) NOT NULL,
  `gPass` varchar(50) NOT NULL,
  `gRdb` tinyint(3) NOT NULL,
  `gRww` tinyint(3) NOT NULL,
  `gRoww` tinyint(1) NOT NULL,
  `gRdg` tinyint(1) NOT NULL,
  `gRjg` tinyint(2) NOT NULL,
  `gRhx` tinyint(2) NOT NULL,
  `gRam` tinyint(1) NOT NULL,
  `gRws` tinyint(1) NOT NULL,
  `gRwst` tinyint(1) NOT NULL,
  `gRmm` tinyint(1) NOT NULL,
  `gRoles` tinyint(1) NOT NULL,
  `gMaxboards` tinyint(2) NOT NULL,
  `gEditable` tinyint(1) NOT NULL,
  `gEditstr` tinyint(1) NOT NULL,
  `gSingleGame` tinyint(1) NOT NULL DEFAULT '0',
  `gGhosts` tinyint(1) NOT NULL,
  `gDesc` text NOT NULL,
  PRIMARY KEY (`gID`),
  KEY `gOwner` (`gOwner`),
  KEY `gStatus` (`gStatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `games`
--


-- --------------------------------------------------------

--
-- Table structure for table `gamexrolexusers`
--

DROP TABLE IF EXISTS `gamexrolexusers`;
CREATE TABLE IF NOT EXISTS `gamexrolexusers` (
  `gxrxuID` int(8) NOT NULL AUTO_INCREMENT,
  `uID` int(7) NOT NULL,
  `rID` int(6) NOT NULL,
  `gID` int(6) NOT NULL,
  `dead` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gxrxuID`),
  UNIQUE KEY `uID_2` (`uID`,`rID`,`gID`),
  KEY `uID` (`uID`),
  KEY `rID` (`rID`),
  KEY `gID` (`gID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `gamexrolexusers`
--


-- --------------------------------------------------------

--
-- Table structure for table `postings`
--

DROP TABLE IF EXISTS `postings`;
CREATE TABLE IF NOT EXISTS `postings` (
  `pID` int(9) NOT NULL AUTO_INCREMENT,
  `tID` int(6) NOT NULL,
  `uID` int(6) NOT NULL,
  `pTitle` varchar(255) DEFAULT NULL,
  `pText` text NOT NULL,
  `pCreated` int(10) NOT NULL,
  `pEdited` int(10) DEFAULT NULL,
  `pArchived` int(10) DEFAULT NULL,
  PRIMARY KEY (`pID`),
  KEY `tID` (`tID`),
  KEY `uID` (`uID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `postings`
--


-- --------------------------------------------------------

--
-- Table structure for table `postingxuser`
--

DROP TABLE IF EXISTS `postingxuser`;
CREATE TABLE IF NOT EXISTS `postingxuser` (
  `pxuID` int(11) NOT NULL AUTO_INCREMENT,
  `pID` int(9) NOT NULL,
  `uID` int(7) NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `folded` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pxuID`),
  UNIQUE KEY `pID_2` (`pID`,`uID`),
  KEY `pID` (`pID`),
  KEY `uID` (`uID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `postingxuser`
--


-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `rID` int(6) NOT NULL AUTO_INCREMENT,
  `rName` varchar(50) NOT NULL,
  `rDesc` text NOT NULL,
  `rSide` char(1) NOT NULL,
  `rImg` varchar(50) NOT NULL,
  PRIMARY KEY (`rID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`rID`, `rName`, `rDesc`, `rSide`, `rImg`) VALUES
(1, 'Dorfbewohner', 'Der ganz einfahe Dorfbewohner ohne besondere Möglichkeiten.', 'G', ''),
(2, 'Werwolf', 'Der ganz einfache Werwolf ohne besondere Möglichkeiten.', 'W', ''),
(3, 'Beobachter', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `sID` tinyint(2) NOT NULL AUTO_INCREMENT,
  `sName` varchar(20) NOT NULL,
  PRIMARY KEY (`sID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`sID`, `sName`) VALUES
(1, 'offen'),
(2, 'laufend'),
(3, 'geschlossen');

-- --------------------------------------------------------

--
-- Table structure for table `threads`
--

DROP TABLE IF EXISTS `threads`;
CREATE TABLE IF NOT EXISTS `threads` (
  `tID` int(6) NOT NULL AUTO_INCREMENT,
  `tCreated` int(11) NOT NULL,
  `fID` int(6) NOT NULL,
  `tTitle` varchar(255) NOT NULL,
  `tOwner` int(7) NOT NULL,
  `tOpen` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`tID`),
  KEY `fID` (`fID`),
  KEY `tOwner` (`tOwner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `threads`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `uID` int(7) NOT NULL AUTO_INCREMENT,
  `uCreated` datetime NOT NULL,
  `uUpdated` datetime NOT NULL,
  `uName` varchar(30) NOT NULL,
  `uPass` varchar(50) NOT NULL,
  `uReal` varchar(50) DEFAULT NULL,
  `uSex` char(1) NOT NULL,
  `uEmail` varchar(50) NOT NULL,
  PRIMARY KEY (`uID`),
  UNIQUE KEY `uEmail` (`uEmail`),
  UNIQUE KEY `uName` (`uName`),
  UNIQUE KEY `uName_2` (`uName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `users`
--


--
-- Constraints for dumped tables
--

--
-- Constraints for table `forums`
--
ALTER TABLE `forums`
  ADD CONSTRAINT `forums_ibfk_1` FOREIGN KEY (`fGame`) REFERENCES `games` (`gID`);

--
-- Constraints for table `forumxuser`
--
ALTER TABLE `forumxuser`
  ADD CONSTRAINT `forumxuser_ibfk_1` FOREIGN KEY (`uID`) REFERENCES `users` (`uID`),
  ADD CONSTRAINT `forumxuser_ibfk_2` FOREIGN KEY (`fID`) REFERENCES `forums` (`fID`);

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_ibfk_1` FOREIGN KEY (`gOwner`) REFERENCES `users` (`uID`),
  ADD CONSTRAINT `games_ibfk_3` FOREIGN KEY (`gStatus`) REFERENCES `status` (`sID`);

--
-- Constraints for table `gamexrolexusers`
--
ALTER TABLE `gamexrolexusers`
  ADD CONSTRAINT `gamexrolexusers_ibfk_2` FOREIGN KEY (`uID`) REFERENCES `users` (`uID`),
  ADD CONSTRAINT `gamexrolexusers_ibfk_3` FOREIGN KEY (`rID`) REFERENCES `roles` (`rID`),
  ADD CONSTRAINT `gamexrolexusers_ibfk_4` FOREIGN KEY (`gID`) REFERENCES `games` (`gID`);

--
-- Constraints for table `postings`
--
ALTER TABLE `postings`
  ADD CONSTRAINT `postings_ibfk_1` FOREIGN KEY (`tID`) REFERENCES `threads` (`tID`),
  ADD CONSTRAINT `postings_ibfk_2` FOREIGN KEY (`uID`) REFERENCES `users` (`uID`);

--
-- Constraints for table `postingxuser`
--
ALTER TABLE `postingxuser`
  ADD CONSTRAINT `postingxuser_ibfk_1` FOREIGN KEY (`pID`) REFERENCES `postings` (`pID`),
  ADD CONSTRAINT `postingxuser_ibfk_2` FOREIGN KEY (`uID`) REFERENCES `users` (`uID`);

--
-- Constraints for table `threads`
--
ALTER TABLE `threads`
  ADD CONSTRAINT `threads_ibfk_1` FOREIGN KEY (`fID`) REFERENCES `forums` (`fID`),
  ADD CONSTRAINT `threads_ibfk_2` FOREIGN KEY (`tOwner`) REFERENCES `users` (`uID`);
