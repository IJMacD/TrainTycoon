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

-- Dumping data for table train_tycoon.availability: ~244 rows (approximately)
/*!40000 ALTER TABLE `availability` DISABLE KEYS */;
INSERT INTO `availability` (`town_id`, `commodity`, `available`) VALUES
	(1, 'alcohol', 0.000809961),
	(1, 'clothing', 0.12725),
	(1, 'fabric', 0.475539),
	(1, 'furniture', 0),
	(1, 'grain', 0.0160377),
	(1, 'livestock', 26),
	(1, 'logs', 1),
	(1, 'lumber', 31),
	(1, 'mail', 0.00967273),
	(1, 'meat', 0.00459727),
	(1, 'passengers', 56.9853),
	(1, 'wool', 0.485114),
	(2, 'alcohol', 0.000186735),
	(2, 'clothing', 0.0294039),
	(2, 'fabric', 1.18116),
	(2, 'furniture', 0.00892458),
	(2, 'livestock', 26),
	(2, 'logs', 8),
	(2, 'lumber', 45.8036),
	(2, 'mail', 0.000255271),
	(2, 'meat', 0.00197056),
	(2, 'passengers', 6.1091),
	(3, 'alcohol', 0.00167094),
	(3, 'clothing', 0),
	(3, 'fabric', 1),
	(3, 'furniture', 0),
	(3, 'livestock', 21),
	(3, 'logs', 32.5989),
	(3, 'lumber', 0.503253),
	(3, 'mail', 0.000418237),
	(3, 'meat', 0.0022102),
	(3, 'passengers', 9.98496),
	(4, 'alcohol', 0),
	(4, 'clothing', 0),
	(4, 'fabric', 1),
	(4, 'furniture', 0),
	(4, 'livestock', 24),
	(4, 'logs', 1.48395),
	(4, 'lumber', 34),
	(4, 'mail', 0),
	(4, 'meat', 0),
	(4, 'passengers', 27.9381),
	(5, 'alcohol', 0),
	(5, 'clothing', 0),
	(5, 'fabric', 28),
	(5, 'furniture', 0),
	(5, 'livestock', 22),
	(5, 'logs', 7),
	(5, 'lumber', 31),
	(5, 'mail', 11.2695),
	(5, 'meat', 6.29839),
	(5, 'passengers', 77.1881),
	(5, 'wool', 3),
	(6, 'alcohol', 0.0000948929),
	(6, 'clothing', 0),
	(6, 'fabric', 34),
	(6, 'furniture', 0),
	(6, 'livestock', 21),
	(6, 'logs', 9),
	(6, 'lumber', 34),
	(6, 'mail', 1.48165),
	(6, 'meat', 23.8259),
	(6, 'passengers', 60.3116),
	(6, 'wool', 0.712212),
	(7, 'alcohol', 0.000864422),
	(7, 'clothing', 0),
	(7, 'fabric', 2.99973),
	(7, 'furniture', 0),
	(7, 'livestock', 17),
	(7, 'logs', 4),
	(7, 'lumber', 32),
	(7, 'mail', 17.7182),
	(7, 'meat', 22.011),
	(7, 'passengers', 59.1348),
	(7, 'wool', 0.000265015),
	(8, 'alcohol', 0),
	(8, 'clothing', 0),
	(8, 'fabric', 0),
	(8, 'furniture', 0),
	(8, 'livestock', 19),
	(8, 'logs', 0),
	(8, 'lumber', 34),
	(8, 'mail', 0.000541079),
	(8, 'meat', 0.000661232),
	(8, 'passengers', 11.8212),
	(9, 'alcohol', 0),
	(9, 'clothing', 0),
	(9, 'furniture', 0),
	(9, 'mail', 0),
	(9, 'meat', 0),
	(9, 'passengers', 0),
	(10, 'alcohol', 0.0000596558),
	(10, 'clothing', 0),
	(10, 'fabric', 29),
	(10, 'furniture', 0),
	(10, 'grain', 0.516962),
	(10, 'livestock', 20.2581),
	(10, 'logs', 1),
	(10, 'lumber', 27),
	(10, 'mail', 0),
	(10, 'meat', 0.560589),
	(10, 'passengers', 1.85443),
	(10, 'wool', 0.374553),
	(11, 'alcohol', 0.000102207),
	(11, 'clothing', 0),
	(11, 'fabric', 12),
	(11, 'furniture', 0),
	(11, 'grain', 0),
	(11, 'livestock', 27),
	(11, 'logs', 3),
	(11, 'lumber', 33),
	(11, 'mail', 0),
	(11, 'meat', 0),
	(11, 'passengers', 1.39498),
	(12, 'alcohol', 0),
	(12, 'clothing', 0),
	(12, 'fabric', 15),
	(12, 'furniture', 0),
	(12, 'grain', 64),
	(12, 'livestock', 32.7092),
	(12, 'lumber', 32),
	(12, 'mail', 0),
	(12, 'meat', 287.019),
	(12, 'passengers', 64.4788),
	(13, 'alcohol', 0.0000581255),
	(13, 'clothing', 0),
	(13, 'fabric', 27),
	(13, 'furniture', 0),
	(13, 'livestock', 22),
	(13, 'logs', 6),
	(13, 'lumber', 30),
	(13, 'mail', 0),
	(13, 'meat', 0),
	(13, 'passengers', 0.193709),
	(14, 'alcohol', 0),
	(14, 'clothing', 0),
	(14, 'fabric', 8),
	(14, 'furniture', 0),
	(14, 'livestock', 24),
	(14, 'lumber', 23),
	(14, 'mail', 0),
	(14, 'meat', 0),
	(14, 'passengers', 35.7208),
	(14, 'wool', 12.9296),
	(15, 'alcohol', 0.000147834),
	(15, 'clothing', 0.00257775),
	(15, 'fabric', 0.0000000000773352),
	(15, 'furniture', 0),
	(15, 'livestock', 20),
	(15, 'logs', 13),
	(15, 'lumber', 32),
	(15, 'mail', 0),
	(15, 'meat', 0.000364003),
	(15, 'passengers', 34.5171),
	(16, 'alcohol', 120.612),
	(16, 'clothing', 0),
	(16, 'fabric', 28),
	(16, 'furniture', 0),
	(16, 'grain', 0.00209855),
	(16, 'livestock', 40.4815),
	(16, 'logs', 23),
	(16, 'lumber', 41),
	(16, 'mail', 0.0000340877),
	(16, 'meat', 95.097),
	(16, 'passengers', 73.9641),
	(16, 'wool', 14),
	(17, 'alcohol', 0),
	(17, 'clothing', 0),
	(17, 'fabric', 0),
	(17, 'furniture', 0),
	(17, 'livestock', 21),
	(17, 'logs', 17),
	(17, 'lumber', 36),
	(17, 'mail', 0),
	(17, 'meat', 0),
	(17, 'passengers', 22.7919),
	(18, 'alcohol', 0.000351742),
	(18, 'clothing', 0),
	(18, 'fabric', 6),
	(18, 'furniture', 0),
	(18, 'livestock', 29),
	(18, 'lumber', 24),
	(18, 'mail', 0),
	(18, 'meat', 0),
	(18, 'passengers', 15.7277),
	(18, 'wool', 0),
	(19, 'alcohol', 0.0000173216),
	(19, 'clothing', 0),
	(19, 'fabric', 11),
	(19, 'furniture', 0),
	(19, 'grain', 0.361775),
	(19, 'livestock', 18.0394),
	(19, 'logs', 5),
	(19, 'lumber', 28),
	(19, 'mail', 0),
	(19, 'meat', 0.00119242),
	(19, 'passengers', 15.8285),
	(20, 'alcohol', 0),
	(20, 'clothing', 0),
	(20, 'furniture', 0),
	(20, 'mail', 0),
	(20, 'meat', 0),
	(20, 'passengers', 0),
	(21, 'alcohol', 0),
	(21, 'clothing', 0),
	(21, 'furniture', 0),
	(21, 'livestock', 24),
	(21, 'logs', 36),
	(21, 'lumber', 38),
	(21, 'mail', 0),
	(21, 'meat', 0),
	(21, 'passengers', 30.3704),
	(22, 'alcohol', 5.51812),
	(22, 'clothing', 0),
	(22, 'fabric', 30),
	(22, 'furniture', 0),
	(22, 'grain', 3.10561),
	(22, 'livestock', 43.8947),
	(22, 'logs', 14),
	(22, 'lumber', 32),
	(22, 'mail', 0),
	(22, 'meat', 46.2943),
	(22, 'passengers', 78.1123),
	(22, 'wool', 44),
	(23, 'alcohol', 0),
	(23, 'clothing', 0),
	(23, 'fabric', 0),
	(23, 'furniture', 0),
	(23, 'livestock', 25),
	(23, 'lumber', 21),
	(23, 'mail', 0),
	(23, 'meat', 0),
	(23, 'passengers', 19.417),
	(23, 'wool', 6),
	(24, 'alcohol', 0.0000353854),
	(24, 'clothing', 0),
	(24, 'fabric', 6),
	(24, 'furniture', 0),
	(24, 'livestock', 20),
	(24, 'logs', 20),
	(24, 'lumber', 38),
	(24, 'mail', 0),
	(24, 'meat', 0.250332),
	(24, 'passengers', 17.8382);
/*!40000 ALTER TABLE `availability` ENABLE KEYS */;

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
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.buildings: 44 rows
/*!40000 ALTER TABLE `buildings` DISABLE KEYS */;
INSERT INTO `buildings` (`id`, `town_id`, `type`, `name`, `wealth`, `scale`, `lat`, `lon`) VALUES
	(32, 2, 'tailor', NULL, 890.477, 1.08281, NULL, NULL),
	(33, 1, 'textiles', NULL, 3489.93, 1.21849, NULL, NULL),
	(38, 14, 'sheep_farm', NULL, 1861.39, 1.07044, NULL, NULL),
	(40, 1, 'distillery', NULL, 18254.7, 2.82097, NULL, NULL),
	(43, 1, 'post_office', NULL, 22549, 1.74898, NULL, NULL),
	(42, 15, 'tailor', NULL, 378.495, 0.000130221, NULL, NULL),
	(44, 4, 'forest', NULL, 23641.7, 1.81943, NULL, NULL),
	(45, 3, 'lumber_mill', NULL, 703.936, 1.08115, NULL, NULL),
	(46, 2, 'carpenter', NULL, 1803.39, 1.08722, NULL, NULL),
	(47, 6, 'sheep_farm', NULL, 1951.96, 1.07789, NULL, NULL),
	(48, 7, 'textiles', NULL, 2183.75, 1.13338, NULL, NULL),
	(49, 10, 'sheep_farm', NULL, 1673.28, 1.07788, NULL, NULL),
	(51, 1, 'tailor', NULL, 3325.68, 1.25003, NULL, NULL),
	(52, 10, 'farm', NULL, 17644.3, 1.3332, NULL, NULL),
	(53, 19, 'farm', NULL, 19993.9, 1.33416, NULL, NULL),
	(54, 22, 'farm', NULL, 17072.5, 1.30929, NULL, NULL),
	(55, 6, 'distillery', NULL, 0, 1.33294e-23, NULL, NULL),
	(56, 16, 'distillery', NULL, 6125.31, 1.58417, NULL, NULL),
	(57, 6, 'post_office', NULL, 16205.3, 1.56619, NULL, NULL),
	(58, 12, 'abattoir', '', 5416.87, 1.00009, NULL, NULL),
	(59, 16, 'abattoir', 'Ronnie\'s Abattoir', 15233.5, 2.22339, NULL, NULL),
	(1, 1, 'station', 'London Euston', 9302.9, 1.78745, 51, 1),
	(2, 2, 'station', 'Manchester Picadilly', 9108.32, 1.74998, 53, -2),
	(3, 3, 'station', 'Birmingham New Street', 9173.21, 1.76614, 52, -2),
	(4, 4, 'station', 'Nottingham Gate', 8938.67, 1.72372, 52, -1),
	(5, 8, 'station', 'Liverpool Gap', 9110.52, 1.74532, NULL, NULL),
	(6, 17, 'station', 'Sheffield Halt', 8928.04, 1.72979, NULL, NULL),
	(7, 15, 'station', 'Leeds Central', 8956.26, 1.72483, NULL, NULL),
	(8, 16, 'station', 'Newcastle Priory', 8577.48, 1.68034, NULL, NULL),
	(9, 7, 'station', 'Glasgow East', 8635.29, 1.69209, NULL, NULL),
	(10, 10, 'station', 'Cambridge Junction', 8910.87, 1.73635, NULL, NULL),
	(11, 22, 'station', 'Middlesbrough Crossing', 8277.04, 1.66504, NULL, NULL),
	(12, 6, 'station', 'Waverley', 8571.53, 1.68604, NULL, NULL),
	(13, 14, 'station', 'Plymouth Junction', 8676.9, 1.69869, NULL, NULL),
	(14, 23, 'station', 'Bournmouth Halt', 8869.42, 1.72242, NULL, NULL),
	(15, 18, 'station', 'Portsmouth Central', 8996.95, 1.73537, NULL, NULL),
	(16, 13, 'station', 'Brighton Priory', 9041.8, 1.74354, NULL, NULL),
	(17, 19, 'station', 'Bristol Central', 8908.28, 1.73059, NULL, NULL),
	(18, 12, 'station', 'Cardiff North', 8639.1, 1.68491, NULL, NULL),
	(19, 11, 'station', 'Oxford Crossing', 8973.08, 1.73668, NULL, NULL),
	(20, 1, 'station', 'London Central', 9303.69, 1.78754, NULL, NULL),
	(21, 21, 'station', 'Leicester', 8847.71, 1.71044, NULL, NULL),
	(22, 24, 'station', 'York West', 8826.4, 1.72063, NULL, NULL),
	(23, 5, 'station', 'Inverness Gap', 8453.69, 1.66475, NULL, NULL);
/*!40000 ALTER TABLE `buildings` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.commodities
CREATE TABLE IF NOT EXISTS `commodities` (
  `type` varchar(50) NOT NULL,
  `supply_m` float DEFAULT -1,
  `supply_c0` float DEFAULT 50,
  `demand_m` float DEFAULT 1,
  `demand_c0` float DEFAULT 0,
  PRIMARY KEY (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.commodities: ~24 rows (approximately)
/*!40000 ALTER TABLE `commodities` DISABLE KEYS */;
INSERT INTO `commodities` (`type`, `supply_m`, `supply_c0`, `demand_m`, `demand_c0`) VALUES
	('alcohol', -0.7, 80, 1, 0),
	('clothing', -0.7, 65, 1, 0),
	('coal', -0.7, 50, 1, 0),
	('corn', -0.7, 50, 1, 0),
	('cotton', -0.7, 50, 1, 0),
	('fabric', -0.7, 50, 1, 0),
	('furniture', -0.7, 70, 1, 0),
	('goods', -0.7, 50, 1, 0),
	('grain', -0.7, 25, 1, 0),
	('iron', -0.7, 50, 1, 0),
	('livestock', -0.7, 25, 1, 0),
	('logs', -0.7, 45, 1, 0),
	('lumber', -0.7, 55, 1, 0),
	('mail', -0.7, 50, 1, 0),
	('meat', -0.7, 50, 1, 0),
	('milk', -0.7, 50, 1, 0),
	('papers', -0.7, 50, 1, 0),
	('passengers', -0.7, 90, 2, 0),
	('produce', -0.7, 50, 1, 0),
	('pulpwood', -0.7, 50, 1, 0),
	('sugar', -0.7, 50, 1, 0),
	('type', -0.7, 50, 1, 0),
	('wood', -0.7, 50, 1, 0),
	('wool', -0.7, 20, 1, 0);
/*!40000 ALTER TABLE `commodities` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.commodities_old
CREATE TABLE IF NOT EXISTS `commodities_old` (
  `town_id` int(11) NOT NULL,
  `commodity` varchar(256) NOT NULL,
  `surplus` float NOT NULL DEFAULT 0,
  `price` float NOT NULL,
  `demand` float DEFAULT NULL,
  PRIMARY KEY (`town_id`,`commodity`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.commodities_old: 106 rows
/*!40000 ALTER TABLE `commodities_old` DISABLE KEYS */;
INSERT INTO `commodities_old` (`town_id`, `commodity`, `surplus`, `price`, `demand`) VALUES
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
/*!40000 ALTER TABLE `commodities_old` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.data
CREATE TABLE IF NOT EXISTS `data` (
  `key` varchar(256) NOT NULL,
  `value` varchar(256) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.data: 4 rows
/*!40000 ALTER TABLE `data` DISABLE KEYS */;
INSERT INTO `data` (`key`, `value`) VALUES
	('lasttime', '1597673921.3535'),
	('gameState', '3'),
	('simstamp', '2416895610.7174'),
	('wealth', '117444.35958788');
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

-- Dumping data for table train_tycoon.production: 24 rows
/*!40000 ALTER TABLE `production` DISABLE KEYS */;
INSERT INTO `production` (`type`, `commodity`, `supplies`, `demands`) VALUES
	('sheep_farm', 'wool', 1, 0),
	('textiles', 'wool', 0, 4),
	('textiles', 'fabric', 4, 0),
	('tailor', 'fabric', 0, 1),
	('tailor', 'clothing', 1, 0),
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
	('lumber_mill', 'logs', 0, 6),
	('lumber_mill', 'lumber', 6, 0),
	('population', 'mail', 0, 1),
	('carpenter', 'lumber', 0, 1),
	('carpenter', 'furniture', 1, 0),
	('population', 'meat', 0, 2),
	('station', 'passengers', 1, 0),
	('population', 'passengers', 0, 2);
/*!40000 ALTER TABLE `production` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.routes
CREATE TABLE IF NOT EXISTS `routes` (
  `train_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `length` float NOT NULL DEFAULT 1,
  PRIMARY KEY (`train_id`,`order`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.routes: 50 rows
/*!40000 ALTER TABLE `routes` DISABLE KEYS */;
INSERT INTO `routes` (`train_id`, `order`, `station_id`, `length`) VALUES
	(4, 2, 11, 2.78338),
	(1, 0, 1, 2.35532),
	(1, 1, 3, 2.35532),
	(3, 0, 1, 2.35532),
	(3, 1, 3, 2.35532),
	(1, 2, 2, 1.11195),
	(2, 0, 2, 1.11195),
	(2, 1, 3, 1.11195),
	(1, 3, 9, 3.5016),
	(2, 2, 4, 0.684579),
	(2, 3, 6, 1.56805),
	(3, 2, 5, 1.70258),
	(3, 3, 2, 0.801478),
	(2, 4, 7, 0.469589),
	(2, 5, 8, 1.31007),
	(4, 0, 1, 1.47106),
	(4, 1, 10, 1.47106),
	(4, 3, 8, 0.51203),
	(4, 4, 12, 1.46807),
	(5, 0, 5, 0.801478),
	(5, 1, 2, 0.801478),
	(5, 2, 7, 0.939282),
	(6, 1, 9, 0.670195),
	(6, 0, 12, 0.670195),
	(3, 4, 3, 1.11195),
	(7, 0, 1, 1.47756),
	(7, 1, 15, 1.47756),
	(7, 2, 14, 0.56864),
	(7, 3, 13, 1.64319),
	(8, 0, 1, 2.54872),
	(9, 0, 20, 0.833824),
	(8, 1, 17, 2.54872),
	(8, 2, 18, 0.410625),
	(9, 1, 19, 0.833824),
	(9, 2, 17, 0.97641),
	(10, 0, 21, 0.714549),
	(10, 1, 4, 0.714549),
	(10, 2, 6, 1.56805),
	(10, 3, 7, 0.469589),
	(12, 0, 20, 0.753443),
	(11, 0, 20, 2.80891),
	(11, 1, 22, 2.80891),
	(11, 2, 8, 1.18614),
	(11, 3, 12, 1.46807),
	(11, 4, 23, 1.80925),
	(12, 1, 16, 0.753443),
	(14, 1, 10, 0.801647),
	(14, 0, 20, 1),
	(5, 3, 22, 0.353719),
	(5, 4, 8, 1.18614);
/*!40000 ALTER TABLE `routes` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.stations_old
CREATE TABLE IF NOT EXISTS `stations_old` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(256) NOT NULL,
  `town_id` int(11) NOT NULL,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.stations_old: 23 rows
/*!40000 ALTER TABLE `stations_old` DISABLE KEYS */;
INSERT INTO `stations_old` (`id`, `Name`, `town_id`, `lat`, `lon`) VALUES
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
	(15, 'Portsmouth Central', 18, NULL, NULL),
	(16, 'Brighton Priory', 13, NULL, NULL),
	(17, 'Bristol Central', 19, NULL, NULL),
	(18, 'Cardiff North', 12, NULL, NULL),
	(19, 'Oxford Crossing', 11, NULL, NULL),
	(20, 'London Central', 1, NULL, NULL),
	(21, 'Leicester', 21, NULL, NULL),
	(22, 'York West', 24, NULL, NULL),
	(23, 'Inverness Gap', 5, NULL, NULL);
/*!40000 ALTER TABLE `stations_old` ENABLE KEYS */;

-- Dumping structure for table train_tycoon.towns
CREATE TABLE IF NOT EXISTS `towns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `lat` float NOT NULL,
  `lon` float NOT NULL,
  `population` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.towns: 24 rows
/*!40000 ALTER TABLE `towns` DISABLE KEYS */;
INSERT INTO `towns` (`id`, `name`, `lat`, `lon`, `population`) VALUES
	(1, 'London', 51.5, -0.12, 13709000),
	(2, 'Manchester', 53.5, -2.24, 2556000),
	(3, 'Birmingham', 52.5, -1.9, 3683000),
	(4, 'Nottingham', 53, -1.15, 1534000),
	(5, 'Inverness', 57.4778, -4.2247, 70000),
	(6, 'Edinburgh', 55.9533, -3.1883, 782000),
	(7, 'Glasgow', 55.8642, -4.2518, 1395000),
	(8, 'Liverpool', 53.4084, -2.9916, 2241000),
	(9, 'Dublin', 53.3498, -6.2603, 1904806),
	(10, 'Cambridge', 52.2053, 0.1218, 124798),
	(11, 'Oxford', 51.752, -1.2577, 152457),
	(12, 'Cardiff', 51.4816, -3.1791, 1097000),
	(13, 'Brighton', 50.8225, -0.1372, 769000),
	(14, 'Plymouth', 50.3755, -4.1427, 262100),
	(15, 'Leeds', 53.8008, -1.5491, 2302000),
	(16, 'Newcastle', 54.9783, -1.6178, 1599000),
	(17, 'Sheffield', 53.3811, -1.4701, 1569000),
	(18, 'Portsmouth', 50.8198, -1.088, 1547000),
	(19, 'Bristol', 51.4545, -2.5879, 1041000),
	(20, 'Belfast', 54.5973, -5.9301, 799000),
	(21, 'Leicester', 52.6369, -1.1398, 745000),
	(22, 'Middlesbrough', 54.5742, -1.235, 656000),
	(23, 'Bournemouth', 50.7192, -1.8808, 531000),
	(24, 'York', 53.9583, -1.08028, 210618);
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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- Dumping data for table train_tycoon.trains: 13 rows
/*!40000 ALTER TABLE `trains` DISABLE KEYS */;
INSERT INTO `trains` (`id`, `name`, `loco_id`, `create_date`, `segment`, `progress`, `loading_timeout`, `oil`, `water`, `sand`, `direction`, `speed`, `priority`, `Car_1`, `Car_2`, `Car_3`, `Car_4`, `Car_5`, `Car_6`, `Car_7`, `Car_8`) VALUES
	(1, NULL, 1, '', 1, 75.5409, 0, 100, 100, 100, 1, 150, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(3, NULL, 2, '1391961316', 4, 81.4201, 0, 100, 100, 100, -1, 100, 0, 'lumber', 'lumber', 'lumber', 'lumber', NULL, NULL, NULL, NULL),
	(2, NULL, 2, '1392039137', 5, 29.8539, 0, 100, 100, 100, -1, 100, 0, 'meat', 'meat', 'meat', 'meat', 'meat', 'meat', 'meat', 'meat'),
	(4, 'Flying Scotsman', 1, '1597149086', 1, 88.0223, 0, 100, 100, 100, -1, 170, 0, 'grain', 'passengers', 'passengers', 'passengers', 'passengers', 'passengers', 'passengers', 'passengers'),
	(5, 'Cross Pennine', 2, '1597149946', 4, 22.7073, 0, 100, 100, 100, 1, 100, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(6, 'Scottish Borders', 2, '1597150161', 1, 0, -0.000536867, 100, 100, 100, -1, 100, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(7, 'West Country Flier', 2, '1597239619', 3, 63.5703, 0, 100, 100, 100, -1, 100, 0, 'passengers', 'passengers', 'passengers', 'passengers', 'passengers', 'passengers', 'passengers', 'passengers'),
	(8, NULL, 2, '1597497416', 1, 76.4118, 0, 100, 100, 100, 1, 100, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(11, 'Highland Sleeper', 1, '1597510215', 4, 0, 0, 100, 100, 100, 1, 100, 0, 'mail', 'meat', 'meat', 'meat', 'meat', 'meat', 'meat', 'meat'),
	(10, 'Yorkshire Stunner', 1, '1597509770', 2, 75.6457, 0, 100, 100, 100, 1, 100, 0, 'logs', 'logs', 'passengers', 'passengers', 'passengers', 'passengers', 'passengers', 'passengers'),
	(9, NULL, 1, '1597509485', 2, 43.7077, 0, 100, 100, 100, -1, 100, 0, 'grain', 'grain', 'grain', NULL, NULL, NULL, NULL, NULL),
	(12, 'Brighton Shuttle', 2, '1597511324', 1, 47.5777, 0, 100, 100, 100, 1, 100, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(14, 'Cambridge Day Tripper', 1, '1597567415', 1, 64.9904, 0, 100, 100, 100, 1, 100, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `trains` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
