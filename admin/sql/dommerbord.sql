-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Vært: localhost
-- Genereringstid: 17. 08 2012 kl. 00:11:06
-- Serverversion: 5.5.24
-- PHP-version: 5.3.10-1ubuntu3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dommerplan`
--

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `calendars`
--

CREATE TABLE IF NOT EXISTS `calendars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(255) NOT NULL,
  `team` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=375 ;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `klubnavn` text NOT NULL,
  `klubpath` text NOT NULL,
  `klubid` text NOT NULL,
  `klubadresse` text NOT NULL,
  `debug` tinyint(1) NOT NULL,
  `lastupdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updatesurl` text NOT NULL,
  `mobileaddress` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `position` int(8) unsigned NOT NULL DEFAULT '0',
  `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dt_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `refereeteam1id` int(8) NOT NULL,
  `referee1name` text COLLATE utf8_unicode_ci NOT NULL,
  `refereeteam2id` int(8) NOT NULL,
  `referee2name` text COLLATE utf8_unicode_ci NOT NULL,
  `tableteam1id` int(8) NOT NULL,
  `table1id` int(8) NOT NULL,
  `tableteam2id` int(8) NOT NULL,
  `table2id` int(8) NOT NULL,
  `tableteam3id` int(8) NOT NULL,
  `table3id` int(8) NOT NULL,
  `status` int(8) NOT NULL,
  `place` text COLLATE utf8_unicode_ci NOT NULL,
  `homegame` tinyint(1) NOT NULL,
  `team` text COLLATE utf8_unicode_ci NOT NULL,
  `result` text COLLATE utf8_unicode_ci NOT NULL,
  `ref1confirmed` text NOT NULL,
  `ref2confirmed` text NOT NULL,
  `grandprix` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `position` (`position`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1000016 ;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `people`
--

CREATE TABLE IF NOT EXISTS `people` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `team` varchar(255) NOT NULL,
  `playername` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `teamid` text NOT NULL,
  `person` tinyint(1) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10020 ;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `password` text NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `email` text NOT NULL,
  `teams` text NOT NULL,
  `refs` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
