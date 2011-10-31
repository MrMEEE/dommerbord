-- phpMyAdmin SQL Dump
-- version 3.4.3.1
-- http://www.phpmyadmin.net
--
-- Host: mysql6.gigahost.dk
-- Generation Time: Oct 31, 2011 at 01:58 PM
-- Server version: 5.0.32
-- PHP Version: 5.2.6-1+lenny13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bms_dommertest`
--

-- --------------------------------------------------------

--
-- Table structure for table `calendars`
--

CREATE TABLE IF NOT EXISTS `calendars` (
  `id` int(11) NOT NULL auto_increment,
  `address` varchar(255) NOT NULL,
  `team` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=279 ;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL auto_increment,
  `klubnavn` text NOT NULL,
  `klubpath` text NOT NULL,
  `klubid` text NOT NULL,
  `klubadresse` text NOT NULL,
  `debug` tinyint(1) NOT NULL,
  `lastupdated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `position` int(8) unsigned NOT NULL default '0',
  `text` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `dt_added` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `refereeteam1id` int(8) NOT NULL,
  `referee1id` int(8) NOT NULL,
  `refereeteam2id` int(8) NOT NULL,
  `referee2id` int(8) NOT NULL,
  `tableteam1id` int(8) NOT NULL,
  `table1id` int(8) NOT NULL,
  `tableteam2id` int(8) NOT NULL,
  `table2id` int(8) NOT NULL,
  `tableteam3id` int(8) NOT NULL,
  `table3id` int(8) NOT NULL,
  `status` int(8) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `position` (`position`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=104686 ;

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE IF NOT EXISTS `people` (
  `id` int(8) NOT NULL auto_increment,
  `team` varchar(255) NOT NULL,
  `playername` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(8) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10001 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `password` text NOT NULL,
  `admin` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
