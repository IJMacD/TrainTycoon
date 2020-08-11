-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.49 - MySQL Community Server (GPL)
-- Server OS:                    Linux
-- HeidiSQL Version:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table railtycoon.buildings
CREATE TABLE IF NOT EXISTS `buildings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `town_id` int(11) NOT NULL,
  `type` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table railtycoon.buildings: 4 rows
/*!40000 ALTER TABLE `buildings` DISABLE KEYS */;
INSERT INTO `buildings` (`id`, `town_id`, `type`) VALUES
	(2, 2, 'cotton_mill'),
	(4, 3, 'retail'),
	(3, 1, 'textiles'),
	(8, 3, 'sheep_farm');
/*!40000 ALTER TABLE `buildings` ENABLE KEYS */;

-- Dumping structure for view railtycoon.commodities
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `commodities` 
) ENGINE=MyISAM;

-- Dumping structure for table railtycoon.data
CREATE TABLE IF NOT EXISTS `data` (
  `key` varchar(256) NOT NULL,
  `value` varchar(256) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table railtycoon.data: 3 rows
/*!40000 ALTER TABLE `data` DISABLE KEYS */;
INSERT INTO `data` (`key`, `value`) VALUES
	('lasttime', '1392248565.5447'),
	('gameState', '3'),
	('simstamp', '18494538696.505');
/*!40000 ALTER TABLE `data` ENABLE KEYS */;

-- Dumping structure for table railtycoon.routes
CREATE TABLE IF NOT EXISTS `routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `train_id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Dumping data for table railtycoon.routes: 8 rows
/*!40000 ALTER TABLE `routes` DISABLE KEYS */;
INSERT INTO `routes` (`id`, `train_id`, `station_id`, `order`) VALUES
	(1, 0, 0, 0),
	(2, 1, 2, 1),
	(3, 1, 1, 2),
	(4, 3, 1, 1),
	(5, 3, 2, 2),
	(6, 1, 3, 3),
	(8, 2, 2, 1),
	(9, 2, 3, 2);
/*!40000 ALTER TABLE `routes` ENABLE KEYS */;

-- Dumping structure for table railtycoon.trains
CREATE TABLE IF NOT EXISTS `trains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(256) NOT NULL,
  `loco_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `create_date` varchar(32) NOT NULL,
  `oil` int(11) NOT NULL DEFAULT '100',
  `water` int(11) NOT NULL DEFAULT '100',
  `sand` int(11) NOT NULL DEFAULT '100',
  `segment` int(11) NOT NULL,
  `progress` float NOT NULL,
  `speed` float NOT NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table railtycoon.trains: 3 rows
/*!40000 ALTER TABLE `trains` DISABLE KEYS */;
INSERT INTO `trains` (`id`, `Name`, `loco_id`, `route_id`, `create_date`, `oil`, `water`, `sand`, `segment`, `progress`, `speed`, `priority`, `Car_1`, `Car_2`, `Car_3`, `Car_4`, `Car_5`, `Car_6`, `Car_7`, `Car_8`) VALUES
	(1, '', 1, 1, '', 100, 100, 100, 2, 29.5937, 150, 0, '', '', '', '', '', '', '', ''),
	(3, 'Train 3', 2, 2, '1391961316', 100, 100, 100, 1, 33.3236, 100, 0, '', '', '', '', '', '', '', ''),
	(2, 'Train 2', 2, 3, '1392039137', 100, 100, 100, 1, 23.9456, 100, 0, '', '', '', '', '', '', '', '');
/*!40000 ALTER TABLE `trains` ENABLE KEYS */;

-- Dumping structure for view railtycoon.commodities
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `commodities`;
;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
