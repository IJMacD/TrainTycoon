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
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.buildings: 14 rows
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
	(16, 2, 'carpenter'),
	(17, 6, 'sheep_farm'),
	(18, 7, 'textiles'),
	(19, 6, 'retail');
/*!40000 ALTER TABLE `buildings` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.commodities
CREATE TABLE IF NOT EXISTS `commodities` (
  `town_id` int(11) NOT NULL,
  `commodity` varchar(256) NOT NULL,
  `surplus` float NOT NULL DEFAULT 0,
  `price` float NOT NULL,
  `demand` float DEFAULT NULL,
  PRIMARY KEY (`town_id`,`commodity`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.commodities: 72 rows
/*!40000 ALTER TABLE `commodities` DISABLE KEYS */;
INSERT INTO `commodities` (`town_id`, `commodity`, `surplus`, `price`, `demand`) VALUES
	(2, 'wool', 2206, 3.14019, 2202.81),
	(2, 'fabric', 1534.1, 1.6313, 1534),
	(3, 'clothes', 1313, 5.58057, 1313),
	(1, 'fabric', 1426, 2.0723, 1426),
	(1, 'clothes', 1248.93, 1.36683, 1248),
	(3, 'wool', 2240.45, 1.97427, 2195),
	(3, 'fabric', 1388, 1.75576, 1388),
	(2, 'clothes', 104, 5.49038, 117.202),
	(1, 'wool', 1008, 0.999937, 1008),
	(4, 'timber', 1027, 0.999985, 1027),
	(4, 'logs', 1425.16, 0.920877, 1335),
	(3, 'timber', 1373.28, 1.88078, 1310),
	(3, 'logs', 1323.98, 1.19521, 1322.28),
	(2, 'timber', 1234, 3.02396, 1234),
	(2, 'furniture', 1206.24, 1.98243, 1206),
	(1, 'furniture', 1206, 8.29189, 1000),
	(3, 'furniture', 1209, 1.99898, 1209),
	(2, 'logs', 1005, 0.849979, 1005),
	(1, 'timber', 1000, 1, 1000),
	(1, 'logs', 1000, 0.9, 1000),
	(8, 'clothes', 1000, 3.75, 1000),
	(8, 'wool', 1000, 0.9, 1000),
	(8, 'furniture', 1000, 0.9, 1000),
	(8, 'timber', 1000, 1.25, 1000),
	(8, 'fabric', 1019, 2.29319, 1016),
	(8, 'logs', 1000, 0.8, 1000),
	(4, 'clothes', 1000, 2.55, 1000),
	(4, 'wool', 1000, 1.1, 1000),
	(4, 'furniture', 1000, 1.05, 1000),
	(4, 'fabric', 1018, 1.79992, 1018),
	(17, 'logs', 1005, 0.79998, 1005),
	(17, 'clothes', 1007, 3.27706, 1000),
	(17, 'fabric', 1018, 1.89991, 1018),
	(17, 'wool', 1000, 0.9, 1000),
	(17, 'furniture', 1000, 0.95, 1000),
	(17, 'timber', 1000, 1.15, 1000),
	(7, 'clothes', 1003.1, 3.72122, 1003),
	(7, 'wool', 1107, 1.09125, 1099),
	(7, 'timber', 1000, 1.1, 1000),
	(7, 'furniture', 1000, 0.95, 1000),
	(7, 'fabric', 1021, 2.49042, 1021.01),
	(7, 'logs', 1000, 0.8, 1000),
	(15, 'clothes', 1007, 3.14998, 1007),
	(15, 'fabric', 1063, 2.35184, 1000),
	(15, 'timber', 1000, 1.25, 1000),
	(15, 'furniture', 1000, 1.2, 1000),
	(15, 'wool', 1000, 0.85, 1000),
	(15, 'logs', 1000, 1.05, 1000),
	(16, 'clothes', 1016, 3.14684, 1015),
	(16, 'fabric', 1000, 1.6, 1000),
	(16, 'timber', 1000, 1.15, 1000),
	(16, 'furniture', 1000, 1.25, 1000),
	(16, 'logs', 1000, 1.05, 1000),
	(16, 'wool', 1008, 0.899943, 1008),
	(10, 'furniture', 1000, 0.8, 1000),
	(10, 'fabric', 1000, 2.5, 1000),
	(10, 'clothes', 1009, 2.69998, 1009),
	(10, 'timber', 1000, 1.2, 1000),
	(10, 'wool', 1000, 0.8, 1000),
	(10, 'logs', 1000, 1.2, 1000),
	(22, 'clothes', 1009, 3.14998, 1009),
	(22, 'fabric', 1000, 2.4, 1000),
	(22, 'logs', 1000, 1.05, 1000),
	(22, 'timber', 1000, 1.25, 1000),
	(22, 'furniture', 1000, 0.95, 1000),
	(22, 'wool', 1008, 0.94246, 1000),
	(6, 'clothes', 1000, 2.40003, 1000.01),
	(6, 'fabric', 1000, 1.6, 1000),
	(6, 'furniture', 1000, 0.8, 1000),
	(6, 'timber', 1000, 0.9, 1000),
	(6, 'logs', 1000, 1.05, 1000),
	(6, 'wool', 1790.54, 0.987532, 1605);
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
	('lasttime', '1597166565.5418'),
	('gameState', '3'),
	('simstamp', '1037931314.6129'),
	('wealth', '4055.7989122529');
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
  `train_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  PRIMARY KEY (`train_id`,`order`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.routes: 24 rows
/*!40000 ALTER TABLE `routes` DISABLE KEYS */;
INSERT INTO `routes` (`train_id`, `order`, `station_id`) VALUES
	(4, 3, 11),
	(1, 1, 1),
	(1, 2, 3),
	(3, 1, 1),
	(3, 2, 3),
	(1, 3, 2),
	(2, 1, 2),
	(2, 2, 3),
	(1, 4, 9),
	(2, 3, 4),
	(2, 4, 6),
	(3, 3, 5),
	(3, 4, 3),
	(2, 5, 7),
	(2, 6, 8),
	(4, 1, 1),
	(4, 2, 10),
	(4, 4, 8),
	(4, 5, 12),
	(5, 1, 5),
	(5, 2, 2),
	(5, 3, 7),
	(6, 1, 9),
	(6, 2, 12);
/*!40000 ALTER TABLE `routes` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.stations
CREATE TABLE IF NOT EXISTS `stations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(256) NOT NULL,
  `town_id` int(11) NOT NULL,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.stations: 12 rows
/*!40000 ALTER TABLE `stations` DISABLE KEYS */;
INSERT INTO `stations` (`id`, `Name`, `town_id`, `lat`, `lon`) VALUES
	(1, 'London Euston', 1, 51, 1),
	(2, 'Manchester Picadilly', 2, 53, -2),
	(3, 'Birmingham New Street', 3, 52, -2),
	(4, 'Nottingham Gate', 4, 52, -1),
	(5, 'Liverpool Gap', 8, NULL, NULL),
	(6, 'Sheffield Halt', 17, NULL, NULL),
	(7, 'Leeds Central', 15, NULL, NULL),
	(8, 'Newcastle Priory', 16, NULL, NULL),
	(9, 'Glasgow East', 7, NULL, NULL),
	(10, 'Cambridge Junction', 10, NULL, NULL),
	(11, 'Middlesbrough Crossing', 22, NULL, NULL),
	(12, 'Waverley', 6, NULL, NULL);
/*!40000 ALTER TABLE `stations` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.towns
CREATE TABLE IF NOT EXISTS `towns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(256) NOT NULL,
  `lat` float NOT NULL,
  `lon` float NOT NULL,
  `population` int(11) DEFAULT NULL,
  `alcohol` int(11) NOT NULL DEFAULT 0,
  `clothing` int(11) NOT NULL DEFAULT 0,
  `coal` int(11) NOT NULL DEFAULT 0,
  `corn` int(11) NOT NULL DEFAULT 0,
  `cotton` int(11) NOT NULL DEFAULT 0,
  `goods` int(11) NOT NULL DEFAULT 0,
  `grain` int(11) NOT NULL DEFAULT 0,
  `iron` int(11) NOT NULL DEFAULT 0,
  `livestock` int(11) NOT NULL DEFAULT 0,
  `logs` int(11) NOT NULL DEFAULT 0,
  `lumber` int(11) NOT NULL DEFAULT 0,
  `mail` int(11) NOT NULL DEFAULT 0,
  `meat` int(11) NOT NULL DEFAULT 0,
  `milk` int(11) NOT NULL DEFAULT 0,
  `papers` int(11) NOT NULL DEFAULT 0,
  `passengers` int(11) NOT NULL DEFAULT 0,
  `produce` int(11) NOT NULL DEFAULT 0,
  `pulpwood` int(11) NOT NULL DEFAULT 0,
  `sugar` int(11) NOT NULL DEFAULT 0,
  `wood` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.towns: 23 rows
/*!40000 ALTER TABLE `towns` DISABLE KEYS */;
INSERT INTO `towns` (`id`, `Name`, `lat`, `lon`, `population`, `alcohol`, `clothing`, `coal`, `corn`, `cotton`, `goods`, `grain`, `iron`, `livestock`, `logs`, `lumber`, `mail`, `meat`, `milk`, `papers`, `passengers`, `produce`, `pulpwood`, `sugar`, `wood`) VALUES
	(1, 'London', 51.5, -0.12, 13709000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(2, 'Manchester', 53.5, -2.24, 2556000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(3, 'Birmingham', 52.5, -1.9, 3683000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(4, 'Nottingham', 53, -1.15, 1534000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(5, 'Inverness', 57.4778, -4.2247, 70000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(6, 'Edinburgh', 55.9533, -3.1883, 782000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(7, 'Glasgow', 55.8642, -4.2518, 1395000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(8, 'Liverpool', 53.4084, -2.9916, 2241000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(9, 'Dublin', 53.3498, -6.2603, 1904806, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(10, 'Cambridge', 52.2053, 0.1218, 124798, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(11, 'Oxford', 51.752, -1.2577, 152457, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(12, 'Cardiff', 51.4816, -3.1791, 1097000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(13, 'Brighton', 50.8225, -0.1372, 769000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(14, 'Plymouth', 50.3755, -4.1427, 262100, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(15, 'Leeds', 53.8008, -1.5491, 2302000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(16, 'Newcastle', 54.9783, -1.6178, 1599000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(17, 'Sheffield', 53.3811, -1.4701, 1569000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(18, 'Portsmouth', 50.8198, -1.088, 1547000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(19, 'Bristol', 51.4545, -2.5879, 1041000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(20, 'Belfast', 54.5973, -5.9301, 799000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(21, 'Leicester', 52.6369, -1.1398, 745000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(22, 'Middlesbrough', 54.5742, -1.235, 656000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(23, 'Bournemouth', 50.7192, -1.8808, 531000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
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
  `route_id` int(11) NOT NULL,
  `create_date` varchar(32) NOT NULL DEFAULT unix_timestamp(),
  `oil` int(11) NOT NULL DEFAULT 100,
  `water` int(11) NOT NULL DEFAULT 100,
  `sand` int(11) NOT NULL DEFAULT 100,
  `segment` int(11) NOT NULL DEFAULT 0,
  `progress` float NOT NULL DEFAULT 0,
  `direction` int(11) NOT NULL DEFAULT 1,
  `speed` float NOT NULL DEFAULT 100,
  `priority` int(11) NOT NULL DEFAULT 0,
  `Car_1` varchar(256) DEFAULT NULL,
  `Car_2` varchar(256) DEFAULT NULL,
  `Car_3` varchar(256) DEFAULT NULL,
  `Car_4` varchar(256) DEFAULT NULL,
  `Car_5` varchar(256) DEFAULT NULL,
  `Car_6` varchar(256) DEFAULT NULL,
  `Car_7` varchar(256) DEFAULT NULL,
  `Car_8` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.trains: 6 rows
/*!40000 ALTER TABLE `trains` DISABLE KEYS */;
INSERT INTO `trains` (`id`, `Name`, `loco_id`, `route_id`, `create_date`, `oil`, `water`, `sand`, `segment`, `progress`, `direction`, `speed`, `priority`, `Car_1`, `Car_2`, `Car_3`, `Car_4`, `Car_5`, `Car_6`, `Car_7`, `Car_8`) VALUES
	(1, 'Train 1', 1, 1, '', 100, 100, 100, 2, 61.1106, -1, 150, 0, 'fabric', '', '', '', '', '', '', ''),
	(3, 'Train 3', 2, 2, '1391961316', 100, 100, 100, 2, 43.286, 1, 100, 0, '', '', '', '', '', '', '', ''),
	(2, 'Train 2', 2, 3, '1392039137', 100, 100, 100, 5, 49.8522, 1, 100, 0, '', '', '', '', '', '', '', ''),
	(4, 'Flying Scotsman', 1, 4, '1597149086', 100, 100, 100, 2, 48.6473, -1, 170, 0, '', '', '', '', '', '', '', ''),
	(5, 'Cross Pennine', 2, 5, '1597149946', 100, 100, 100, 1, 45.9827, -1, 100, 0, 'fabric', '', '', '', '', '', '', ''),
	(6, 'Scottish Borders', 2, 6, '1597150161', 100, 100, 100, 1, 94.6767, 1, 100, 0, '', '', '', '', '', '', '', '');
/*!40000 ALTER TABLE `trains` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
