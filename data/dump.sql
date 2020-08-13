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

-- Dumping structure for table train_tycoon.availability
CREATE TABLE IF NOT EXISTS `availability` (
  `town_id` int(11) NOT NULL,
  `commodity` varchar(50) NOT NULL,
  `available` float NOT NULL DEFAULT 0,
  PRIMARY KEY (`town_id`,`commodity`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.availability: ~98 rows (approximately)
/*!40000 ALTER TABLE `availability` DISABLE KEYS */;
INSERT INTO `availability` (`town_id`, `commodity`, `available`) VALUES
	(1, 'clothes', 0),
	(1, 'clothing', 11.1664),
	(1, 'fabric', 0.212146),
	(1, 'furniture', 0),
	(1, 'mail', 0.450121),
	(1, 'wool', 7.44334),
	(2, 'alcohol', 1),
	(2, 'clothes', 0),
	(2, 'clothing', 1.51227),
	(2, 'fabric', 0.780364),
	(2, 'furniture', 0),
	(2, 'mail', 0),
	(3, 'clothes', 0),
	(3, 'clothing', 0),
	(3, 'fabric', 0),
	(3, 'furniture', 0),
	(3, 'mail', 0),
	(4, 'clothes', 0),
	(4, 'furniture', 0),
	(4, 'logs', 15.3503),
	(5, 'clothes', 0),
	(5, 'furniture', 0),
	(6, 'alcohol', 0.989094),
	(6, 'clothes', 0),
	(6, 'fabric', 5),
	(6, 'furniture', 0),
	(6, 'grain', 0.0109057),
	(6, 'livestock', 1),
	(6, 'logs', 2),
	(6, 'mail', 1),
	(6, 'wool', 2.70071),
	(7, 'alcohol', 1),
	(7, 'clothes', 0),
	(7, 'fabric', 2.03579),
	(7, 'furniture', 0),
	(7, 'mail', 0),
	(7, 'wool', 7.92842),
	(8, 'clothes', 0),
	(8, 'clothing', 0),
	(8, 'furniture', 0),
	(9, 'clothes', 0),
	(9, 'furniture', 0),
	(10, 'clothes', 0),
	(10, 'fabric', 5),
	(10, 'furniture', 0),
	(10, 'grain', 6.45012),
	(10, 'livestock', 4.72505),
	(10, 'mail', 3),
	(10, 'wool', 4.70073),
	(11, 'clothes', 0),
	(11, 'furniture', 0),
	(12, 'clothes', 0),
	(12, 'furniture', 0),
	(13, 'clothes', 0),
	(13, 'furniture', 0),
	(14, 'clothes', 0),
	(14, 'fabric', 1),
	(14, 'furniture', 0),
	(14, 'mail', 3),
	(14, 'wool', 32.7007),
	(15, 'alcohol', 2),
	(15, 'clothes', 0),
	(15, 'furniture', 0),
	(15, 'logs', 6),
	(16, 'alcohol', 0.997938),
	(16, 'clothes', 0),
	(16, 'fabric', 0),
	(16, 'furniture', 0),
	(16, 'grain', 0.00206252),
	(16, 'livestock', 2),
	(16, 'logs', 0),
	(16, 'wool', 11),
	(17, 'clothes', 0),
	(17, 'furniture', 0),
	(17, 'logs', 5),
	(18, 'clothes', 0),
	(18, 'fabric', 0),
	(18, 'furniture', 0),
	(18, 'mail', 0),
	(18, 'wool', 0),
	(19, 'clothes', 0),
	(19, 'furniture', 0),
	(19, 'grain', 9.45011),
	(19, 'livestock', 4.72505),
	(20, 'clothes', 0),
	(20, 'furniture', 0),
	(21, 'clothes', 0),
	(21, 'furniture', 0),
	(22, 'alcohol', 4),
	(22, 'clothes', 0),
	(22, 'fabric', 2),
	(22, 'furniture', 0),
	(22, 'grain', 2.45012),
	(22, 'livestock', 1.72506),
	(22, 'wool', 18),
	(23, 'clothes', 0),
	(23, 'fabric', 0),
	(23, 'furniture', 0),
	(23, 'mail', 0),
	(23, 'wool', 8);
/*!40000 ALTER TABLE `availability` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.buildings
CREATE TABLE IF NOT EXISTS `buildings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `town_id` int(11) NOT NULL,
  `type` varchar(256) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `wealth` float DEFAULT 0,
  `scale` float DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.buildings: 18 rows
/*!40000 ALTER TABLE `buildings` DISABLE KEYS */;
INSERT INTO `buildings` (`id`, `town_id`, `type`, `name`, `wealth`, `scale`) VALUES
	(2, 2, 'tailor', NULL, 0, 1),
	(3, 1, 'textiles', NULL, 0, 2),
	(8, 14, 'sheep_farm', NULL, 0, 1),
	(10, 1, 'distillery', NULL, 0, 1),
	(13, 1, 'post_office', NULL, 0, 1),
	(12, 15, 'tailor', NULL, 0, 1),
	(14, 4, 'forest', NULL, 0, 1),
	(15, 3, 'lumber_mill', NULL, 0, 1),
	(16, 2, 'carpenter', NULL, 0, 1),
	(17, 6, 'sheep_farm', NULL, 0, 1),
	(18, 7, 'textiles', NULL, 0, 1),
	(19, 10, 'sheep_farm', NULL, 0, 1),
	(21, 1, 'tailor', NULL, 0, 1),
	(22, 10, 'farm', NULL, 0, 1),
	(23, 19, 'farm', NULL, 0, 1),
	(24, 22, 'farm', NULL, 0, 1),
	(25, 6, 'distillery', NULL, 0, 1),
	(26, 16, 'distillery', NULL, 0, 1);
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

-- Dumping data for table train_tycoon.commodities: 106 rows
/*!40000 ALTER TABLE `commodities` DISABLE KEYS */;
INSERT INTO `commodities` (`town_id`, `commodity`, `surplus`, `price`, `demand`) VALUES
	(1, 'furniture', 200, 0.294714, 200),
	(1, 'clothes', 244.927, 0.609848, 244.925),
	(2, 'furniture', 200, 0.279203, 200),
	(2, 'clothes', 200, 0.80291, 200),
	(3, 'furniture', 200, 0.246438, 200),
	(3, 'clothes', 200, 0.712095, 200),
	(4, 'furniture', 200, 0.246286, 200),
	(4, 'clothes', 200, 0.864568, 200),
	(5, 'furniture', 200, 0.211894, 200),
	(5, 'clothes', 200, 0.515567, 200),
	(6, 'furniture', 200, 0.33891, 200),
	(6, 'clothes', 200, 0.67925, 200),
	(7, 'furniture', 200, 0.275403, 200),
	(7, 'clothes', 200, 0.779858, 200),
	(8, 'furniture', 200, 0.21089, 200),
	(8, 'clothes', 200, 0.748007, 200),
	(9, 'furniture', 200, 0.26601, 200),
	(9, 'clothes', 200, 0.717165, 200),
	(10, 'furniture', 200, 0.177988, 200),
	(10, 'clothes', 200, 0.75302, 200),
	(11, 'furniture', 200, 0.219713, 200),
	(11, 'clothes', 200, 0.737345, 200),
	(12, 'furniture', 200, 0.255401, 200),
	(12, 'clothes', 200, 0.917772, 200),
	(13, 'furniture', 200, 0.234628, 200),
	(13, 'clothes', 200, 0.82034, 200),
	(14, 'furniture', 200, 0.266503, 200),
	(14, 'clothes', 200, 0.722777, 200),
	(15, 'furniture', 200, 0.165703, 200),
	(15, 'clothes', 200, 0.58626, 200),
	(16, 'furniture', 200, 0.215436, 200),
	(16, 'clothes', 200, 0.92497, 200),
	(17, 'furniture', 200, 0.301533, 200),
	(17, 'clothes', 200, 0.824493, 200),
	(18, 'furniture', 200, 0.270377, 200),
	(18, 'clothes', 200, 0.929715, 200),
	(19, 'furniture', 200, 0.206029, 200),
	(19, 'clothes', 200, 0.72434, 200),
	(20, 'furniture', 200, 0.236642, 200),
	(20, 'clothes', 200, 0.727392, 200),
	(21, 'furniture', 200, 0.180415, 200),
	(21, 'clothes', 200, 0.70006, 200),
	(22, 'furniture', 200, 0.280694, 200),
	(22, 'clothes', 200, 0.802068, 200),
	(23, 'furniture', 200, 0.228859, 200),
	(23, 'clothes', 200, 0.564758, 200),
	(2, 'fabric', 200, 0.418985, 200),
	(1, 'wool', 408, 0.265582, 407.994),
	(3, 'wool', 215.949, 0.217908, 200),
	(15, 'fabric', 200, 0.436403, 200),
	(4, 'logs', 434.464, 0.177293, 288),
	(3, 'logs', 256, 0.240242, 255.999),
	(2, 'timber', 200, 0.221857, 200),
	(6, 'wool', 681.118, 0.161878, 631),
	(7, 'wool', 584, 0.215622, 478.945),
	(1, 'fabric', 303.984, 0.493123, 303.523),
	(1, 'logs', 200, 0.220453, 200),
	(10, 'wool', 673.664, 0.172554, 464),
	(3, 'fabric', 220, 0.53696, 208),
	(15, 'timber', 200, 0.217022, 200),
	(16, 'fabric', 240, 0.517836, 200),
	(16, 'timber', 200, 0.258543, 200),
	(6, 'fabric', 248, 0.442252, 240),
	(6, 'timber', 200, 0.235993, 200),
	(2, 'logs', 208, 0.242464, 200),
	(2, 'wool', 200, 0.205052, 200),
	(7, 'fabric', 340.487, 0.415344, 248),
	(7, 'timber', 200, 0.271064, 200),
	(16, 'wool', 242, 0.212438, 200),
	(7, 'logs', 200, 0.273316, 200),
	(15, 'wool', 200, 0.227997, 200),
	(22, 'fabric', 200, 0.509045, 200),
	(22, 'timber', 200, 0.304072, 200),
	(22, 'wool', 240, 0.218707, 200),
	(6, 'logs', 200, 0.280553, 200),
	(10, 'fabric', 200, 0.455738, 200),
	(10, 'timber', 200, 0.288768, 200),
	(8, 'fabric', 202, 0.490009, 202),
	(8, 'logs', 200, 0.239702, 200),
	(8, 'timber', 200, 0.25128, 200),
	(8, 'wool', 200, 0.15905, 200),
	(17, 'fabric', 200, 0.445477, 200),
	(17, 'wool', 200, 0.191241, 200),
	(17, 'timber', 200, 0.318109, 200),
	(3, 'timber', 255.999, 0.251636, 216),
	(1, 'timber', 216, 0.285924, 200),
	(4, 'fabric', 208, 0.568885, 200),
	(4, 'timber', 200, 0.234731, 200),
	(4, 'wool', 200, 0.146149, 200),
	(10, 'logs', 200, 0.173629, 200),
	(22, 'logs', 200, 0.271635, 200),
	(15, 'logs', 200, 0.237207, 200),
	(16, 'logs', 200, 0.275287, 200),
	(14, 'wool', 652.177, 0.125052, 287),
	(17, 'logs', 224, 0.231397, 200),
	(23, 'wool', 287, 0.172344, 200),
	(18, 'wool', 200, 0.174526, 200),
	(18, 'fabric', 210, 0.537777, 200),
	(18, 'timber', 200, 0.280534, 200),
	(18, 'logs', 200, 0.196046, 200),
	(23, 'fabric', 201, 0.41208, 201),
	(23, 'timber', 200, 0.29209, 200),
	(23, 'logs', 200, 0.229717, 200),
	(14, 'fabric', 200, 0.538238, 200),
	(14, 'timber', 200, 0.19154, 200),
	(14, 'logs', 200, 0.216687, 200);
/*!40000 ALTER TABLE `commodities` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.commodities2
CREATE TABLE IF NOT EXISTS `commodities2` (
  `type` varchar(50) NOT NULL,
  `supply_m` float DEFAULT -1,
  `supply_c0` float DEFAULT 50,
  `demand_m` float DEFAULT 1,
  `demand_c0` float DEFAULT 0,
  PRIMARY KEY (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.commodities2: ~24 rows (approximately)
/*!40000 ALTER TABLE `commodities2` DISABLE KEYS */;
INSERT INTO `commodities2` (`type`, `supply_m`, `supply_c0`, `demand_m`, `demand_c0`) VALUES
	('alcohol', -0.7, 60, 1, 0),
	('clothing', -0.7, 50, 1, 0),
	('coal', -0.7, 50, 1, 0),
	('corn', -0.7, 50, 1, 0),
	('cotton', -0.7, 50, 1, 0),
	('fabric', -0.7, 50, 1, 0),
	('furniture', -0.7, 50, 1, 0),
	('goods', -0.7, 50, 1, 0),
	('grain', -0.7, 50, 1, 0),
	('iron', -0.7, 50, 1, 0),
	('livestock', -0.7, 50, 1, 0),
	('logs', -0.7, 50, 1, 0),
	('lumber', -0.7, 50, 1, 0),
	('mail', -0.7, 50, 1, 0),
	('meat', -0.7, 50, 1, 0),
	('milk', -0.7, 50, 1, 0),
	('papers', -0.7, 50, 1, 0),
	('passengers', -0.7, 50, 1, 0),
	('produce', -0.7, 50, 1, 0),
	('pulpwood', -0.7, 50, 1, 0),
	('sugar', -0.7, 50, 1, 0),
	('type', -0.7, 50, 1, 0),
	('wood', -0.7, 50, 1, 0),
	('wool', -0.7, 50, 1, 0);
/*!40000 ALTER TABLE `commodities2` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.data
CREATE TABLE IF NOT EXISTS `data` (
  `key` varchar(256) NOT NULL,
  `value` varchar(256) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.data: 4 rows
/*!40000 ALTER TABLE `data` DISABLE KEYS */;
INSERT INTO `data` (`key`, `value`) VALUES
	('wealth', '2031.1679047574'),
	('lasttime', '1597336005.0325'),
	('gameState', '3'),
	('simstamp', '-1153580288.3637');
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

-- Dumping structure for table train_tycoon.production
CREATE TABLE IF NOT EXISTS `production` (
  `type` varchar(50) NOT NULL,
  `commodity` varchar(50) NOT NULL,
  `supplies` float NOT NULL DEFAULT 0,
  `demands` float NOT NULL DEFAULT 0,
  PRIMARY KEY (`type`,`commodity`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='Constants stored in database for joining.';

-- Dumping data for table train_tycoon.production: 18 rows
/*!40000 ALTER TABLE `production` DISABLE KEYS */;
INSERT INTO `production` (`type`, `commodity`, `supplies`, `demands`) VALUES
	('sheep_farm', 'wool', 8, 0),
	('textiles', 'wool', 0, 6),
	('textiles', 'fabric', 4, 0),
	('tailor', 'fabric', 0, 3),
	('tailor', 'clothing', 2, 0),
	('forest', 'logs', 3, 0),
	('population', 'clothing', 0, 4),
	('population', 'furniture', 0, 1),
	('population', 'alcohol', 0, 1),
	('farm', 'grain', 2, 0),
	('distillery', 'grain', 0, 6),
	('distillery', 'alcohol', 6, 0),
	('post_office', 'mail', 2, 0),
	('farm', 'livestock', 1, 0),
	('abattoir', 'livestock', 0, 1),
	('abattoir', 'meat', 9, 0),
	('lumber_mill', 'logs', 6, 0),
	('lumber_mill', 'lumber', 0, 6),
	('population', 'mail', 1, 0);
/*!40000 ALTER TABLE `production` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.routes
CREATE TABLE IF NOT EXISTS `routes` (
  `train_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `length` float NOT NULL DEFAULT 1,
  PRIMARY KEY (`train_id`,`order`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.routes: 29 rows
/*!40000 ALTER TABLE `routes` DISABLE KEYS */;
INSERT INTO `routes` (`train_id`, `order`, `station_id`, `length`) VALUES
	(4, 3, 11, 10),
	(1, 1, 1, 10),
	(1, 2, 3, 10),
	(3, 1, 1, 10),
	(3, 2, 3, 10),
	(1, 3, 2, 10),
	(2, 1, 2, 10),
	(2, 2, 3, 10),
	(1, 4, 9, 10),
	(2, 3, 4, 10),
	(2, 4, 6, 10),
	(3, 3, 5, 10),
	(3, 4, 2, 10),
	(2, 5, 7, 10),
	(2, 6, 8, 10),
	(4, 1, 1, 10),
	(4, 2, 10, 10),
	(4, 4, 8, 10),
	(4, 5, 12, 10),
	(5, 1, 5, 10),
	(5, 2, 2, 10),
	(5, 3, 7, 10),
	(6, 1, 9, 10),
	(6, 2, 12, 10),
	(3, 5, 3, 10),
	(7, 1, 1, 10),
	(7, 2, 15, 10),
	(7, 3, 14, 10),
	(7, 4, 13, 10);
/*!40000 ALTER TABLE `routes` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.stations
CREATE TABLE IF NOT EXISTS `stations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(256) NOT NULL,
  `town_id` int(11) NOT NULL,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.stations: 15 rows
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
	(12, 'Waverley', 6, NULL, NULL),
	(13, 'Plymouth Junction', 14, NULL, NULL),
	(14, 'Bournmouth Halt', 23, NULL, NULL),
	(15, 'Portsmouth Central', 18, NULL, NULL);
/*!40000 ALTER TABLE `stations` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.towns
CREATE TABLE IF NOT EXISTS `towns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(256) NOT NULL,
  `lat` float NOT NULL,
  `lon` float NOT NULL,
  `population` int(11) DEFAULT NULL,
  `alcohol` float NOT NULL DEFAULT 0,
  `clothing` float NOT NULL DEFAULT 0,
  `coal` float NOT NULL DEFAULT 0,
  `corn` float NOT NULL DEFAULT 0,
  `cotton` float NOT NULL DEFAULT 0,
  `goods` float NOT NULL DEFAULT 0,
  `grain` float NOT NULL DEFAULT 0,
  `fabric` float DEFAULT 0,
  `furniture` float DEFAULT 0,
  `iron` float NOT NULL DEFAULT 0,
  `livestock` float NOT NULL DEFAULT 0,
  `logs` float NOT NULL DEFAULT 0,
  `lumber` float NOT NULL DEFAULT 0,
  `mail` float NOT NULL DEFAULT 0,
  `meat` float NOT NULL DEFAULT 0,
  `milk` float NOT NULL DEFAULT 0,
  `papers` float NOT NULL DEFAULT 0,
  `passengers` float NOT NULL DEFAULT 0,
  `produce` float NOT NULL DEFAULT 0,
  `pulpwood` float NOT NULL DEFAULT 0,
  `sugar` float NOT NULL DEFAULT 0,
  `wood` float NOT NULL DEFAULT 0,
  `wool` float DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.towns: 23 rows
/*!40000 ALTER TABLE `towns` DISABLE KEYS */;
INSERT INTO `towns` (`id`, `Name`, `lat`, `lon`, `population`, `alcohol`, `clothing`, `coal`, `corn`, `cotton`, `goods`, `grain`, `fabric`, `furniture`, `iron`, `livestock`, `logs`, `lumber`, `mail`, `meat`, `milk`, `papers`, `passengers`, `produce`, `pulpwood`, `sugar`, `wood`, `wool`) VALUES
	(1, 'London', 51.5, -0.12, 13709000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(2, 'Manchester', 53.5, -2.24, 2556000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(3, 'Birmingham', 52.5, -1.9, 3683000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(4, 'Nottingham', 53, -1.15, 1534000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(5, 'Inverness', 57.4778, -4.2247, 70000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(6, 'Edinburgh', 55.9533, -3.1883, 782000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(7, 'Glasgow', 55.8642, -4.2518, 1395000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(8, 'Liverpool', 53.4084, -2.9916, 2241000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(9, 'Dublin', 53.3498, -6.2603, 1904806, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(10, 'Cambridge', 52.2053, 0.1218, 124798, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(11, 'Oxford', 51.752, -1.2577, 152457, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(12, 'Cardiff', 51.4816, -3.1791, 1097000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(13, 'Brighton', 50.8225, -0.1372, 769000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(14, 'Plymouth', 50.3755, -4.1427, 262100, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(15, 'Leeds', 53.8008, -1.5491, 2302000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(16, 'Newcastle', 54.9783, -1.6178, 1599000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(17, 'Sheffield', 53.3811, -1.4701, 1569000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(18, 'Portsmouth', 50.8198, -1.088, 1547000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(19, 'Bristol', 51.4545, -2.5879, 1041000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(20, 'Belfast', 54.5973, -5.9301, 799000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(21, 'Leicester', 52.6369, -1.1398, 745000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(22, 'Middlesbrough', 54.5742, -1.235, 656000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(23, 'Bournemouth', 50.7192, -1.8808, 531000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
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

-- Dumping structure for table train_tycoon.tracks2
CREATE TABLE IF NOT EXISTS `tracks2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_station_id` int(11) DEFAULT NULL,
  `to_station_id` int(11) DEFAULT NULL,
  `length` float NOT NULL DEFAULT 1,
  `path` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.tracks2: ~0 rows (approximately)
/*!40000 ALTER TABLE `tracks2` DISABLE KEYS */;
/*!40000 ALTER TABLE `tracks2` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.trains
CREATE TABLE IF NOT EXISTS `trains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(256) NOT NULL,
  `loco_id` int(11) NOT NULL,
  `create_date` varchar(32) NOT NULL DEFAULT unix_timestamp(),
  `route_id` int(11) NOT NULL,
  `segment` int(11) NOT NULL DEFAULT 0,
  `progress` float NOT NULL DEFAULT 0,
  `loading_timeout` float NOT NULL DEFAULT 0,
  `oil` int(11) NOT NULL DEFAULT 100,
  `water` int(11) NOT NULL DEFAULT 100,
  `sand` int(11) NOT NULL DEFAULT 100,
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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.trains: 7 rows
/*!40000 ALTER TABLE `trains` DISABLE KEYS */;
INSERT INTO `trains` (`id`, `Name`, `loco_id`, `create_date`, `route_id`, `segment`, `progress`, `loading_timeout`, `oil`, `water`, `sand`, `direction`, `speed`, `priority`, `Car_1`, `Car_2`, `Car_3`, `Car_4`, `Car_5`, `Car_6`, `Car_7`, `Car_8`) VALUES
	(1, 'Train 1', 1, '', 1, 1, 27.3828, 0, 100, 100, 100, 1, 150, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(3, 'Train 3', 2, '1391961316', 2, 2, 0, 0, 100, 100, 100, 1, 100, 0, 'mail', 'mail', NULL, NULL, NULL, NULL, NULL, NULL),
	(2, 'Train 2', 2, '1392039137', 3, 1, 100, 0, 100, 100, 100, -1, 100, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(4, 'Flying Scotsman', 1, '1597149086', 4, 1, 71.8776, 0, 100, 100, 100, 1, 170, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(5, 'Cross Pennine', 2, '1597149946', 5, 2, 85.5866, 0, 100, 100, 100, -1, 100, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(6, 'Scottish Borders', 2, '1597150161', 6, 1, 0, 0, 100, 100, 100, 1, 100, 0, 'fabric', 'fabric', NULL, NULL, NULL, NULL, NULL, NULL),
	(7, 'West Country Flier', 2, '1597239619', 7, 2, 0, 0, 100, 100, 100, 1, 100, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `trains` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
