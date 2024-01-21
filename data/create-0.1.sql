-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 29, 2010 at 12:27 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `railtycoon`
--

-- --------------------------------------------------------

--
-- Table structure for table `towns`
--

CREATE TABLE IF NOT EXISTS `towns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(256) NOT NULL,
  `lat` float NOT NULL,
  `lon` float NOT NULL,
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

--
-- Dumping data for table `towns`
--

INSERT INTO `towns` (`id`, `Name`, `lat`, `lon`, `alcohol`, `clothing`, `coal`, `corn`, `cotton`, `goods`, `grain`, `iron`, `livestock`, `logs`, `lumber`, `mail`, `meat`, `milk`, `papers`, `passengers`, `produce`, `pulpwood`, `sugar`, `wood`) VALUES
(1, 'London', 51, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 'Manchester', 53, -2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 'Birmingham', 52, -2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
