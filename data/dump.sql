-- --------------------------------------------------------
-- Host:                         54.224.46.4
-- Server version:               10.2.10-MariaDB - MariaDB Server
-- Server OS:                    Linux
-- HeidiSQL Version:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table train_tycoon.buildings
CREATE TABLE IF NOT EXISTS `buildings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `town_id` int(11) NOT NULL,
  `type` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.buildings: 11 rows
/*!40000 ALTER TABLE `buildings` DISABLE KEYS */;
INSERT INTO `buildings` (`id`, `town_id`, `type`) VALUES
	(2, 2, 'cotton_mill'),
	(4, 3, 'retail'),
	(3, 1, 'textiles'),
	(8, 3, 'sheep_farm'),
	(9, 2, 'retail'),
	(10, 1, 'textiles'),
	(13, 1, 'textiles'),
	(12, 2, 'cotton_mill'),
	(14, 4, 'forest'),
	(15, 3, 'lumber_mill'),
	(16, 2, 'carpenter');
/*!40000 ALTER TABLE `buildings` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.commodities
CREATE TABLE IF NOT EXISTS `commodities` (
  `town_id` int(11) NOT NULL,
  `commodity` varchar(256) NOT NULL,
  `surplus` float NOT NULL DEFAULT 0,
  `price` float NOT NULL,
  `demand` float DEFAULT NULL,
  UNIQUE KEY `town_id_commodity` (`town_id`,`commodity`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.commodities: 17 rows
/*!40000 ALTER TABLE `commodities` DISABLE KEYS */;
INSERT INTO `commodities` (`town_id`, `commodity`, `surplus`, `price`, `demand`) VALUES
	(2, 'wool', 1482, 3.19138, 1482.01),
	(2, 'fabric', 1207.75, 1.6898, 1205),
	(3, 'clothes', 1194, 5.56999, 1193.06),
	(1, 'fabric', 1202, 2.07166, 1198.1),
	(1, 'clothes', 1120.68, 1.37465, 1120),
	(3, 'wool', 1463.92, 2.01106, 1458),
	(3, 'fabric', 1144, 1.75636, 1144),
	(2, 'clothes', 78, 7.32051, 117.202),
	(1, 'wool', 1000, 1, 1000),
	(4, 'timber', 1027, 0.999985, 1027),
	(4, 'logs', 1046.16, 0.98774, 1045),
	(3, 'timber', 1083.75, 1.9909, 1081),
	(3, 'logs', 1037.98, 1.19125, 1032.69),
	(2, 'timber', 1050, 2.96906, 1038.79),
	(2, 'furniture', 1011.05, 1.99563, 1008),
	(1, 'furniture', 1008, 9.92064, 1000),
	(3, 'furniture', 1008, 1.99997, 1008);
/*!40000 ALTER TABLE `commodities` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.data
CREATE TABLE IF NOT EXISTS `data` (
  `key` varchar(256) NOT NULL,
  `value` varchar(256) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.data: 5 rows
/*!40000 ALTER TABLE `data` DISABLE KEYS */;
INSERT INTO `data` (`key`, `value`) VALUES
	('date', '1930-01-01'),
	('lasttime', '1597115641.195'),
	('gameState', '3'),
	('simstamp', '-436687808.83922'),
	('wealth', '1053.9676654619');
/*!40000 ALTER TABLE `data` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.locos
CREATE TABLE IF NOT EXISTS `locos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.locos: 2 rows
/*!40000 ALTER TABLE `locos` DISABLE KEYS */;
INSERT INTO `locos` (`id`, `active`) VALUES
	(1, 1),
	(2, 0);
/*!40000 ALTER TABLE `locos` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.routes
CREATE TABLE IF NOT EXISTS `routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `train_id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.routes: 11 rows
/*!40000 ALTER TABLE `routes` DISABLE KEYS */;
INSERT INTO `routes` (`id`, `train_id`, `station_id`, `order`) VALUES
	(1, 0, 0, 0),
	(2, 1, 1, 1),
	(3, 1, 3, 2),
	(4, 3, 1, 1),
	(5, 3, 3, 2),
	(6, 1, 2, 3),
	(8, 2, 2, 1),
	(9, 2, 3, 2),
	(10, 1, 3, 4),
	(11, 2, 4, 3),
	(12, 2, 3, 4);
/*!40000 ALTER TABLE `routes` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.stations
CREATE TABLE IF NOT EXISTS `stations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(256) NOT NULL,
  `town_id` int(11) NOT NULL,
  `lat` float NOT NULL,
  `lon` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.stations: 4 rows
/*!40000 ALTER TABLE `stations` DISABLE KEYS */;
INSERT INTO `stations` (`id`, `Name`, `town_id`, `lat`, `lon`) VALUES
	(1, 'London Euston', 1, 51, 1),
	(2, 'Manchester Picadilly', 2, 53, -2),
	(3, 'Birmingham New Street', 3, 52, -2),
	(4, 'Nottingham Gate', 4, 52, -1);
/*!40000 ALTER TABLE `stations` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.towns
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.towns: 4 rows
/*!40000 ALTER TABLE `towns` DISABLE KEYS */;
INSERT INTO `towns` (`id`, `Name`, `lat`, `lon`, `alcohol`, `clothing`, `coal`, `corn`, `cotton`, `goods`, `grain`, `iron`, `livestock`, `logs`, `lumber`, `mail`, `meat`, `milk`, `papers`, `passengers`, `produce`, `pulpwood`, `sugar`, `wood`) VALUES
	(1, 'London', 51, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(2, 'Manchester', 53, -2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(3, 'Birmingham', 52, -2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(4, 'Nottingham', 52, -1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
/*!40000 ALTER TABLE `towns` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.tracks
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.tracks: 0 rows
/*!40000 ALTER TABLE `tracks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tracks` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.trains
CREATE TABLE IF NOT EXISTS `trains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(256) NOT NULL,
  `loco_id` int(11) NOT NULL,
  `route_id` int(11) DEFAULT NULL,
  `create_date` varchar(32) NOT NULL,
  `oil` int(11) NOT NULL DEFAULT 100,
  `water` int(11) NOT NULL DEFAULT 100,
  `sand` int(11) NOT NULL DEFAULT 100,
  `segment` int(11) NOT NULL,
  `progress` float NOT NULL,
  `speed` float NOT NULL DEFAULT 0,
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

-- Dumping data for table train_tycoon.trains: 3 rows
/*!40000 ALTER TABLE `trains` DISABLE KEYS */;
INSERT INTO `trains` (`id`, `Name`, `loco_id`, `route_id`, `create_date`, `oil`, `water`, `sand`, `segment`, `progress`, `speed`, `priority`, `Car_1`, `Car_2`, `Car_3`, `Car_4`, `Car_5`, `Car_6`, `Car_7`, `Car_8`) VALUES
	(1, '', 1, 1, '', 100, 100, 100, 1, 45.8613, 150, 0, '', '', '', '', '', '', '', ''),
	(3, 'Train 3', 2, 2, '1391961316', 100, 100, 100, 0, 30.5746, 100, 0, '', '', '', '', '', '', '', ''),
	(2, 'Train 2', 2, 3, '1392039137', 100, 100, 100, 3, 72.1246, 100, 0, 'logs', 'logs', 'logs', 'logs', '', '', '', '');
/*!40000 ALTER TABLE `trains` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
