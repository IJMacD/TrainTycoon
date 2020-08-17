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

-- Data exporting was unselected.

-- Dumping structure for table train_tycoon.buildings
CREATE TABLE IF NOT EXISTS `buildings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `town_id` int(11) NOT NULL,
  `type` varchar(256) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `wealth` float DEFAULT 0,
  `scale` float DEFAULT 1,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table train_tycoon.commodities
CREATE TABLE IF NOT EXISTS `commodities` (
  `type` varchar(50) NOT NULL,
  `supply_m` float DEFAULT -1,
  `supply_c0` float DEFAULT 50,
  `demand_m` float DEFAULT 1,
  `demand_c0` float DEFAULT 0,
  PRIMARY KEY (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table train_tycoon.data
CREATE TABLE IF NOT EXISTS `data` (
  `key` varchar(256) NOT NULL,
  `value` varchar(256) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table train_tycoon.locos
CREATE TABLE IF NOT EXISTS `locos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table train_tycoon.production
CREATE TABLE IF NOT EXISTS `production` (
  `type` varchar(50) NOT NULL,
  `commodity` varchar(50) NOT NULL,
  `supplies` float NOT NULL DEFAULT 0,
  `demands` float NOT NULL DEFAULT 0,
  PRIMARY KEY (`type`,`commodity`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='Constants stored in database for joining.';

-- Data exporting was unselected.

-- Dumping structure for table train_tycoon.routes
CREATE TABLE IF NOT EXISTS `routes` (
  `train_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `length` float NOT NULL DEFAULT 1,
  PRIMARY KEY (`train_id`,`order`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table train_tycoon.towns
CREATE TABLE IF NOT EXISTS `towns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `lat` float NOT NULL,
  `lon` float NOT NULL,
  `population` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table train_tycoon.tracks2
CREATE TABLE IF NOT EXISTS `tracks2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_station_id` int(11) DEFAULT NULL,
  `to_station_id` int(11) DEFAULT NULL,
  `length` float NOT NULL DEFAULT 1,
  `path` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table train_tycoon.trains
CREATE TABLE IF NOT EXISTS `trains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `loco_id` int(11) NOT NULL,
  `create_date` varchar(32) NOT NULL DEFAULT unix_timestamp(),
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
