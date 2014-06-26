-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2014 at 11:36 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_msc_online_exams`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE IF NOT EXISTS `courses` (
  `CourseID` int(11) NOT NULL AUTO_INCREMENT,
  `SubjectID` int(11) DEFAULT NULL,
  `Course` varchar(100) DEFAULT NULL,
  `CourseCode` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`CourseID`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `course_instructor`
--

CREATE TABLE IF NOT EXISTS `course_instructor` (
  `CourseInstructorID` int(11) NOT NULL AUTO_INCREMENT,
  `CourseID` int(11) DEFAULT NULL,
  `Year` int(11) DEFAULT NULL,
  `SemisterID` int(11) DEFAULT NULL,
  `InstructorID` int(11) DEFAULT NULL,
  PRIMARY KEY (`CourseInstructorID`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `course_students`
--

CREATE TABLE IF NOT EXISTS `course_students` (
  `CourseStudentID` int(11) NOT NULL AUTO_INCREMENT,
  `CourseID` int(11) DEFAULT NULL,
  `StudentID` int(11) DEFAULT NULL,
  PRIMARY KEY (`CourseStudentID`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE IF NOT EXISTS `designations` (
  `DesignationID` int(11) NOT NULL AUTO_INCREMENT,
  `Designation` varchar(75) DEFAULT NULL,
  `DelInd` enum('1','0') NOT NULL,
  PRIMARY KEY (`DesignationID`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `examinations`
--

CREATE TABLE IF NOT EXISTS `examinations` (
  `ExaminationID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(150) DEFAULT NULL,
  `ExaminationTypeID` int(11) DEFAULT NULL,
  `Year` int(11) DEFAULT NULL,
  `SeminsterID` int(11) DEFAULT NULL,
  `CourseID` int(11) DEFAULT NULL,
  `InsttructorID` int(11) DEFAULT NULL,
  `NumberOfMCQs` int(11) DEFAULT NULL,
  `NumberOfShortAnswerQuestions` int(11) DEFAULT NULL,
  `Duration` int(11) DEFAULT NULL,
  `Active` tinyint(4) DEFAULT '0' COMMENT '0 - Inactive, 1 - Active',
  PRIMARY KEY (`ExaminationID`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `examination_types`
--

CREATE TABLE IF NOT EXISTS `examination_types` (
  `ExaminationTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `ExaminationType` varchar(75) DEFAULT NULL,
  `DelInd` enum('1','0') NOT NULL,
  PRIMARY KEY (`ExaminationTypeID`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `marital_statuses`
--

CREATE TABLE IF NOT EXISTS `marital_statuses` (
  `MaritalStatusID` int(11) NOT NULL AUTO_INCREMENT,
  `MaritalStatus` varchar(75) DEFAULT NULL,
  `DelInd` enum('1','0') NOT NULL,
  PRIMARY KEY (`MaritalStatusID`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `semisters`
--

CREATE TABLE IF NOT EXISTS `semisters` (
  `SemisterID` int(11) NOT NULL AUTO_INCREMENT,
  `Semister` varchar(75) DEFAULT NULL,
  `DelInd` enum('1','0') NOT NULL,
  PRIMARY KEY (`SemisterID`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=20 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE IF NOT EXISTS `subjects` (
  `SubjectID` int(11) NOT NULL AUTO_INCREMENT,
  `Subject` varchar(75) DEFAULT NULL,
  `DelInd` enum('1','0') NOT NULL,
  PRIMARY KEY (`SubjectID`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `userlevelpermissions`
--

CREATE TABLE IF NOT EXISTS `userlevelpermissions` (
  `userlevelid` int(11) NOT NULL,
  `tablename` varchar(255) NOT NULL,
  `permission` int(11) NOT NULL,
  PRIMARY KEY (`userlevelid`,`tablename`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=1365;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(20) DEFAULT NULL,
  `Password` varchar(50) DEFAULT NULL,
  `UserLevel` int(11) DEFAULT NULL,
  `Activated` tinyint(1) DEFAULT '0' COMMENT '0 - Inactive, 1 - Active',
  `DesignationID` int(11) DEFAULT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `MiddleName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `Email` varchar(75) DEFAULT NULL,
  `RegistrationNumber` varchar(30) DEFAULT NULL,
  `NICNumber` varchar(10) DEFAULT NULL,
  `Gender` tinyint(4) DEFAULT '0' COMMENT '0 - Male, 1 - Female',
  `MaritalStatusID` int(11) DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `RegisteredOn` date DEFAULT NULL,
  `RegistrationValidTill` date DEFAULT NULL,
  `PhotoPath` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`UserID`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_levels`
--

CREATE TABLE IF NOT EXISTS `user_levels` (
  `userlevelid` int(11) NOT NULL,
  `userlevelname` varchar(255) NOT NULL,
  `DelInd` enum('1','0') NOT NULL,
  PRIMARY KEY (`userlevelid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
