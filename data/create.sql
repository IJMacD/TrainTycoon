-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 29, 2010 at 03:08 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `railtycoon`
--

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

CREATE TABLE IF NOT EXISTS `buildings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `town_id` int(11) NOT NULL,
  `type` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `commodities`
--

CREATE TABLE IF NOT EXISTS `commodities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `town_id` int(11) NOT NULL,
  `commodity` varchar(256) NOT NULL,
  `surplus` int(11) NOT NULL,
  `price` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

CREATE TABLE IF NOT EXISTS `data` (
  `key` varchar(256) NOT NULL,
  `value` varchar(256) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `locos`
--

CREATE TABLE IF NOT EXISTS `locos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE IF NOT EXISTS `routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `train_id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stations`
--

CREATE TABLE IF NOT EXISTS `stations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(256) NOT NULL,
  `town_id` int(11) NOT NULL,
  `lat` float NOT NULL,
  `lon` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `towns`
--

CREATE TABLE IF NOT EXISTS `towns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(256) NOT NULL,
  `alcohol` int(11) NOT NULL,
  `clothing` int(11) NOT NULL,
  `coal` int(11) NOT NULL,
  `corn` int(11) NOT NULL,
  `cotton` int(11) NOT NULL,
  `goods` int(11) NOT NULL,
  `grain` int(11) NOT NULL,
  `iron` int(11) NOT NULL,
  `livestock` int(11) NOT NULL,
  `logs` int(11) NOT NULL,
  `lumber` int(11) NOT NULL,
  `mail` int(11) NOT NULL,
  `meat` int(11) NOT NULL,
  `milk` int(11) NOT NULL,
  `papers` int(11) NOT NULL,
  `passengers` int(11) NOT NULL,
  `produce` int(11) NOT NULL,
  `pulpwood` int(11) NOT NULL,
  `sugar` int(11) NOT NULL,
  `wood` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `tracks`
--

CREATE TABLE IF NOT EXISTS `tracks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_lat` float NOT NULL,
  `from_lon` float NOT NULL,
  `to_lat` int(11) NOT NULL,
  `to_lon` int(11) NOT NULL,
  `Distance` float NOT NULL,
  `double` tinyint(1) NOT NULL,
  `electric` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `trains`
--

CREATE TABLE IF NOT EXISTS `trains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(256) NOT NULL,
  `loco_id` int(11) NOT NULL,
  `create_date` varchar(32) NOT NULL,
  `oil` int(11) NOT NULL DEFAULT '100',
  `water` int(11) NOT NULL DEFAULT '100',
  `sand` int(11) NOT NULL DEFAULT '100',
  `Segment` int(11) NOT NULL,
  `Progress` float NOT NULL,
  `priority` int(11) NOT NULL,
  `Car_1` varchar(256) NOT NULL,
  `Car_2` varchar(256) NOT NULL,
  `Car_3` varchar(256) NOT NULL,
  `Car_4` varchar(256) NOT NULL,
  `Car_5` varchar(256) NOT NULL,
  `Car_6` varchar(256) NOT NULL,
  `Car_7` varchar(256) NOT NULL,
  `Car_8` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
