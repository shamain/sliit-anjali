-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2014 at 09:49 AM
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
  `DelInd` enum('1','0') NOT NULL,
  PRIMARY KEY (`CourseID`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`CourseID`, `SubjectID`, `Course`, `CourseCode`, `DelInd`) VALUES
(1, 1, 'vxv', 'vzx', '0'),
(2, 1, 'dadaAxxx', 'wwwwwwwwwwwwww', '1');

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
  `DelInd` enum('1','0') NOT NULL,
  PRIMARY KEY (`CourseInstructorID`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED AUTO_INCREMENT=2 ;

--
-- Dumping data for table `course_instructor`
--

INSERT INTO `course_instructor` (`CourseInstructorID`, `CourseID`, `Year`, `SemisterID`, `InstructorID`, `DelInd`) VALUES
(1, 2, 0, 14, 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `course_students`
--

CREATE TABLE IF NOT EXISTS `course_students` (
  `CourseStudentID` int(11) NOT NULL AUTO_INCREMENT,
  `CourseID` int(11) DEFAULT NULL,
  `StudentID` int(11) DEFAULT NULL,
  `DelInd` enum('1','0') NOT NULL,
  PRIMARY KEY (`CourseStudentID`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED AUTO_INCREMENT=3 ;

--
-- Dumping data for table `course_students`
--

INSERT INTO `course_students` (`CourseStudentID`, `CourseID`, `StudentID`, `DelInd`) VALUES
(1, 2, 0, '1'),
(2, 2, 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE IF NOT EXISTS `designations` (
  `DesignationID` int(11) NOT NULL AUTO_INCREMENT,
  `Designation` varchar(75) DEFAULT NULL,
  `DelInd` enum('1','0') NOT NULL,
  PRIMARY KEY (`DesignationID`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`DesignationID`, `Designation`, `DelInd`) VALUES
(1, 'desig', '1'),
(2, 'fdfs', '1');

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
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL,
  `Active` tinyint(4) DEFAULT '0' COMMENT '0 - Inactive, 1 - Active',
  `DelInd` enum('1','0') NOT NULL,
  PRIMARY KEY (`ExaminationID`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `examinations`
--

INSERT INTO `examinations` (`ExaminationID`, `Name`, `ExaminationTypeID`, `Year`, `SeminsterID`, `CourseID`, `InsttructorID`, `NumberOfMCQs`, `NumberOfShortAnswerQuestions`, `Duration`, `StartDate`, `EndDate`, `Active`, `DelInd`) VALUES
(1, 'asdd', 1, 33, 1, 1, 1, NULL, NULL, NULL, '0000-00-00', '0000-00-00', 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `examination_types`
--

CREATE TABLE IF NOT EXISTS `examination_types` (
  `ExaminationTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `ExaminationType` varchar(75) DEFAULT NULL,
  `DelInd` enum('1','0') NOT NULL,
  PRIMARY KEY (`ExaminationTypeID`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `examination_types`
--

INSERT INTO `examination_types` (`ExaminationTypeID`, `ExaminationType`, `DelInd`) VALUES
(1, 'dsgdg', '1'),
(2, 'test', '1'),
(3, 'xvzvzx', '1');

-- --------------------------------------------------------

--
-- Table structure for table `marital_statuses`
--

CREATE TABLE IF NOT EXISTS `marital_statuses` (
  `MaritalStatusID` int(11) NOT NULL AUTO_INCREMENT,
  `MaritalStatus` varchar(75) DEFAULT NULL,
  `DelInd` enum('1','0') NOT NULL,
  PRIMARY KEY (`MaritalStatusID`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `marital_statuses`
--

INSERT INTO `marital_statuses` (`MaritalStatusID`, `MaritalStatus`, `DelInd`) VALUES
(1, 'mari', '1');

-- --------------------------------------------------------

--
-- Table structure for table `semisters`
--

CREATE TABLE IF NOT EXISTS `semisters` (
  `SemesterID` int(11) NOT NULL AUTO_INCREMENT,
  `Semester` varchar(75) DEFAULT NULL,
  `DelInd` enum('1','0') NOT NULL,
  PRIMARY KEY (`SemesterID`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=20 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `semisters`
--

INSERT INTO `semisters` (`SemesterID`, `Semester`, `DelInd`) VALUES
(4, 'xcx', '1'),
(5, 'vxcvcccc', '1'),
(6, 'vcncvn', '1'),
(7, 'ncvn', '1'),
(8, 'cvnv', '1'),
(9, 'cnvncv', '1'),
(10, 'bcxbxc', '1'),
(11, 'xcbxcb', '1'),
(12, 'xbcbxc', '1'),
(13, 'bxcbxcbxzxzxz', '1'),
(14, 'cvvcvcv', '1'),
(15, 'bcbcb', '1'),
(16, 'x', '1'),
(17, 'vxcv', '0'),
(18, 'ghh', '1');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE IF NOT EXISTS `subjects` (
  `SubjectID` int(11) NOT NULL AUTO_INCREMENT,
  `Subject` varchar(75) DEFAULT NULL,
  `DelInd` enum('1','0') NOT NULL,
  PRIMARY KEY (`SubjectID`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`SubjectID`, `Subject`, `DelInd`) VALUES
(1, 'zz', '1'),
(2, 'czxcz', '1');

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
  `DelInd` enum('1','0') NOT NULL,
  PRIMARY KEY (`UserID`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `UserLevel`, `Activated`, `DesignationID`, `FirstName`, `MiddleName`, `LastName`, `Email`, `RegistrationNumber`, `NICNumber`, `Gender`, `MaritalStatusID`, `DateOfBirth`, `RegisteredOn`, `RegistrationValidTill`, `PhotoPath`, `DelInd`) VALUES
(1, 'gayathma', 'c4961b067d274050e43e26beb9d7d19c', 1, 1, 1, 'Gayathma', 'Achini', 'Perera', 'fsf@s.dgsd', '353236236', '32535235', 0, 1, NULL, NULL, NULL, NULL, '1'),
(2, 'dsvdggg', '202cb962ac59075b964b07152d234b70', 2, 1, 1, 'testuser', '', '', 'dsdW@fds.gsd', '235235', '52352', 0, 1, '0000-00-00', '2014-06-27', '0000-00-00', 'pic1404322323-despicable-me-4fdb838e20288.jpg', '1'),
(3, 'trryruyrjy', '202cb962ac59075b964b07152d234b70', 3, 1, 2, 'gjhf', 'jhjf', '', 'g@tfh.fjhf', '4653', '533', 0, 1, '0000-00-00', '2014-07-02', '0000-00-00', 'pic1404325241-The-Twilight-Saga_breaking-Dawn-Part-2-2012-Movie-Photos-wide-Wallpapers-13-728x455.jp', '1');

-- --------------------------------------------------------

--
-- Table structure for table `user_levels`
--

CREATE TABLE IF NOT EXISTS `user_levels` (
  `userlevelid` int(11) NOT NULL AUTO_INCREMENT,
  `userlevelname` varchar(255) NOT NULL,
  `DelInd` enum('1','0') NOT NULL,
  PRIMARY KEY (`userlevelid`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user_levels`
--

INSERT INTO `user_levels` (`userlevelid`, `userlevelname`, `DelInd`) VALUES
(1, 'cvcxv', '0'),
(2, 'erest', '1'),
(3, 'vzxv', '1');

-- --------------------------------------------------------

--
-- Table structure for table `user_level_permissions`
--

CREATE TABLE IF NOT EXISTS `user_level_permissions` (
  `userlevelid` int(11) NOT NULL,
  `tablename` varchar(255) NOT NULL,
  `permission` int(11) NOT NULL,
  `DelInd` enum('1','0') NOT NULL,
  PRIMARY KEY (`userlevelid`,`tablename`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=1365;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
