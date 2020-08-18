<?php
require_once("constants.php");
require_once("debug.php");

class DB
{
	private $connection;
	private $prefix = "";

	private $commodities = [];
	private $stations = null;
	
    function __construct($game_id = null)
	{
		global $CONST;
		$this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS) or $this->redirect("Server Error<br>Cannot connect to database server");
		mysqli_select_db($this->connection, DB_NAME) or $this->redirect("Server Error<br>Cannot select database");
		
		if ($game_id) {
			$this->prefix = $game_id . "_";
		}
    }

	function query($q, $params=null){
		global $debug;
		if ($params) {
			$stmt = mysqli_prepare($this->connection, $q);
			$types = implode("", array_map(function ($p) { return is_int($p) ? "i" : "s"; }, $params));
			$stmt->bind_param($types, ...$params);
			$result = $stmt->execute();
		} else {
			$result = mysqli_query($this->connection, $q);
		}
		$debug->log($q, 3);
		if(mysqli_error($this->connection)){
			echo mysqli_error($this->connection)."<br>".$q;
		}
		return $result;
	}

	function queryMultiple ($sql) {
		$statements = explode(";", $sql);

		$this->query("SET autocommit = OFF");
		$this->query("START TRANSACTION");

		foreach($statements as $statement) {
			if (strlen($statement) > 0) $this->query($statement);
		}

		$this->query("COMMIT");
		$this->query("SET autocommit = ON");
	}
	
	function getData($key){
		global $CONST;
		if(!isset($this->data[$key])){
			$q = "SELECT value FROM `{$this->prefix}data` WHERE `key` = '$key'";
			$result = $this->query($q);
			if ($result && $result->num_rows) {
				$this->data[$key] = $result->fetch_assoc()['value'];
			} else {
				$this->setData($key, $CONST['defaults'][$key]);
			}
		}
		return $this->data[$key];
	}
	
	function getLocos(){
		if(!isset($this->locos)){
			$this->locos = array();
			$q = "SELECT * FROM locos";
			$result = $this->query($q);
			if($result && $result->num_rows){
				while($this->locos[] = $result->fetch_assoc());
			}else{
				$this->populateLocosTable();
			}
		}
		return $this->locos;
	}
	
	function getTrain ($id)
	{
		$trains = $this->getTrains();
		return isset($trains[$id]) ? $trains[$id] : false;
	}
	
	function getTrains()
	{
		if(!isset($this->trains)){
			$this->trains = array();
			$q = "SELECT * FROM {$this->prefix}trains ORDER BY CASE WHEN `name` IS NULL THEN 1 ELSE 0 END, `name`";
			$result = $this->query($q);
			if($result && $result->num_rows){
				while($train = $result->fetch_assoc()){
					$train['route'] = $this->getRoute($train['id']);
					$this->trains[$train['id']] = $train;
				}
			}
		}
		
		return $this->trains;
	}

	function insertTrain ($loco_id, $name) {
		$q = "INSERT INTO {$this->prefix}trains (loco_id, name) VALUES (?, ?)";
		$this->query($q, [$loco_id, $name]);
		$id = mysqli_insert_id($this->connection);

		// Clear cache
		unset($this->trains);

		return $id;
	}
	
	function getBuildings(){
		if(!isset($this->buildings)){
			$this->buildings = array();
			$q = "SELECT * FROM {$this->prefix}buildings ORDER BY `name`";
			$result = $this->query($q);
			if($result && $result->num_rows){
				while($row = $result->fetch_assoc()){$this->buildings[$row['id']] = $row;}
			}
		}
		return $this->buildings;
	}

	function insertBuilding ($type, $town_id, $name) {
		$q = "INSERT INTO {$this->prefix}buildings (type, town_id, name) VALUES (?, ?, ?)";
		$this->query($q, [$type, $town_id, $name]);
		$id = mysqli_insert_id($this->connection);

		$this->buildings[] = ["id"=>$id,"type"=>$type,"town_id"=>$town_id,"name"=>$name,"wealth"=>0,"scale"=>1];

		return $id;
	}
	
	function getTowns($tid = -1){
		if(!isset($this->towns)){
			$this->towns = array();
			$q = "SELECT * FROM towns ORDER BY `name`";
			$result = $this->query($q);
			if($result && $result->num_rows){
				while($row = $result->fetch_assoc()){$this->towns[$row['id']] = $row;}
			}
		}
		return ($tid == -1 || $tid == "") ? $this->towns : $this->towns[$tid];
	}
	
	function getTown ($tid){
		return $this->getTowns()[$tid];
	}
	
	function getStations (){
		if(!isset($this->stations)){
			$this->stations = array();
			$q = "SELECT 
					b.`id`,
					b.`name`,
					b.`town_id`,
					COALESCE(b.`lat`, t.`lat`) AS lat,
					COALESCE(b.`lon`, t.`lon`) AS lon
				FROM {$this->prefix}buildings AS b
					LEFT JOIN towns AS t ON b.town_id = t.id
				WHERE
					b.`type` = 'station'
				ORDER BY b.`name`";
			$result = $this->query($q);
			if($result && $result->num_rows){
				while($row = $result->fetch_assoc()) {
					$this->stations[$row['id']] = $row;
				}
			}
		}
		return $this->stations;
	}
	
	function getStation ($id)
	{
		$this->getStations();
		return $this->stations[$id];
	}

	function insertStation ($town_id, $name) {
		$q = "INSERT INTO {$this->prefix}buildings (`type`, `town_id`, `name`) VALUES ('station', ?, ?)";
		$this->query($q, [$town_id, $name]);
		$id = mysqli_insert_id($this->connection);

		unset($this->stations);

		return $id;
	}

	function getCommodities ($town_id) {
		if(!isset($this->commodities[$town_id])){
			$q = economySQL($this->prefix, "town", $town_id);
			$this->commodities[$town_id] = $this->query($q)->fetch_all(MYSQLI_ASSOC);
		}

		return $this->commodities[$town_id];
	}

	function setCommodity ($town_id, $commodity, $available) {
		$q = "INSERT INTO `{$this->prefix}availability` (`town_id`,`commodity`,`available`) "
			."VALUES ('$town_id','$commodity','$available') "
			."ON DUPLICATE KEY UPDATE `available` = '$available'";
		$this->query($q);

		unset($this->commodities[$town_id]); // invalidate cache
	}

	function getCommodityList ($commodity) {
		$q = economySQL($this->prefix, "commodity", $commodity);
		return $this->query($q)->fetch_all(MYSQLI_ASSOC);
	}

	function getCommodityTypes () {
		$q = "SELECT `type` FROM commodities ORDER BY `type`";
		$r =  $this->query($q);
		$out = [];
		while($commodity = $r->fetch_row()) $out[] = $commodity[0];
		return $out;
	}

	function getBuildingTypes () {
		$q = "SELECT `type` FROM production GROUP BY `type` ORDER BY `type`";
		$r =  $this->query($q);
		$out = [];
		while($commodity = $r->fetch_row()) $out[] = $commodity[0];
		return $out;
	}

	function getCommoditySupplyDemand ($town_id) {
		return $this->query(economySQL($this->prefix, "supply_demand", $town_id))->fetch_all(MYSQLI_ASSOC);
	}

	function getProduction ($building_type) {
		$q = "SELECT commodity, supplies, demands FROM production WHERE `type` = '$building_type'";
		return $this->query($q)->fetch_all(MYSQLI_ASSOC);
	}
	
	function setData($key, $value){
		$q = "INSERT INTO `{$this->prefix}data` (`key`, `value`) VALUES ('$key', '$value') ON DUPLICATE KEY UPDATE `value` = '$value'";
		$this->query($q);
		$this->data[$key] = $value;
	}
	
	function updateTrain($id, $key, $value){
		$q = "UPDATE {$this->prefix}trains SET `$key` = ? WHERE `id` = '$id'";
		$this->query($q, [$value]);
		$this->trains[$id][$key] = $value;
	}
	
	function updateBuilding($id, $key, $value){
		$q = "UPDATE {$this->prefix}buildings SET `$key` = ? WHERE `id` = '$id'";
		$this->query($q, [$value]);
		$this->buildings[$id][$key] = $value;
	}
	
	function populateLocosTable(){
		global $CONST;
		
		$year = substr($this->getData('date'), 0, 4);
		foreach($CONST['locos'] as $id => $loco){
			$active = ($loco['start_year'] < $year);
			$this->locos[$id] = array("active" => $active);
			$values[] = "('$id', '$active')";
		}
		$q = "INSERT INTO locos (`id`, `active`) VALUES ".implode(", ", $values);
		$this->query($q);
	}

	function getRoute ($route_id) {
		$q = "SELECT
			`station_id`,
				CASE WHEN `length` = 0 THEN 1 ELSE `length` END AS length,
				`station_id`,
				b.`name` AS station_name,
				`town_id`,
				t.`name` AS town_name,
				COALESCE(b.`lat`, t.`lat`) AS lat,
				COALESCE(b.`lon`, t.`lon`) AS lon,
				population
			FROM {$this->prefix}routes AS r
				JOIN {$this->prefix}buildings AS b ON r.station_id = b.id
				JOIN towns AS t ON b.town_id = t.id
			WHERE train_id = $route_id
			ORDER BY `order`";
		
		return $this->query($q)->fetch_all(MYSQLI_ASSOC);
	}

	function addRouteStop ($route_id, $order, $station_id, $length = 1) {
		$q = "INSERT INTO {$this->prefix}routes (`train_id`, `order`, `station_id`, `length`) VALUES ($route_id, $order, $station_id, $length)";
		$this->query($q);
	}
	
	function updateRoute($train_id, $order, $key, $value){
		$q = "UPDATE {$this->prefix}routes SET `$key` = ? WHERE `train_id` = ? AND `order` = ?";
		$this->query($q, [$value, $train_id, $order]);
		$this->trains[$train_id]['route'][$i] = $value;
	}

	function create () {
		$this->queryMultiple(
		 "CREATE TABLE `{$this->prefix}availability` (
			`town_id` int(11) NOT NULL,
			`commodity` varchar(50) NOT NULL,
			`available` float NOT NULL DEFAULT 0,
			PRIMARY KEY (`town_id`,`commodity`)
		  );
		  
		  CREATE TABLE `{$this->prefix}buildings` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`town_id` int(11) NOT NULL,
			`type` varchar(256) NOT NULL,
			`name` varchar(50) DEFAULT NULL,
			`wealth` float DEFAULT 0,
			`scale` float DEFAULT 1,
			`lat` float DEFAULT NULL,
			`lon` float DEFAULT NULL,
			PRIMARY KEY (`id`)
		  );

		  CREATE TABLE `{$this->prefix}data` (
			`key` varchar(256) NOT NULL,
			`value` varchar(256) NOT NULL,
			PRIMARY KEY (`key`)
		  );

		  CREATE TABLE `{$this->prefix}routes` (
			`train_id` int(11) NOT NULL,
			`order` int(11) NOT NULL,
			`station_id` int(11) NOT NULL,
			`length` float NOT NULL DEFAULT 1,
			PRIMARY KEY (`train_id`,`order`)
		  ) ENGINE=MyISAM DEFAULT CHARSET=latin1;

		  -- Dumping structure for table train_tycoon.trains
		  CREATE TABLE `{$this->prefix}trains` (
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
		  );");							
	}

	function reset () {
		$this->queryMultiple(
			"DELETE FROM {$this->prefix}data; 
			DELETE FROM {$this->prefix}availability;
			UPDATE {$this->prefix}buildings SET scale = 1, wealth = 0;
			UPDATE {$this->prefix}trains SET Car_1 = NULL, Car_2 = NULL, Car_3 = NULL, Car_4 = NULL, Car_5 = NULL, Car_6 = NULL, Car_7 = NULL, Car_8 = NULL;"
		);
	}
}

function economySQL ($prefix, $mode, $id) {
	$STOCKPILE_FACTOR = 0.2;

	$col = $mode === "town" ? "a.type" : "b.id AS town_id";

	$supply = "SUM(IFNULL(d.supplies * c.scale, 0)) 
	+ (b.population / 1e6 * IFNULL(f.supplies, 0))";

	$demand = "SUM(IFNULL(d.demands * c.scale, 0)) 
	+ (b.population / 1e6 * IFNULL(f.demands, 0))";

	$available = "IFNULL(g.available, 0) * $STOCKPILE_FACTOR";

	$price = "(
		(a.supply_c0 - $supply) * a.demand_m
		-
		(a.demand_c0 + $demand - $available) * a.supply_m
	) / ( a.demand_m - a.supply_m ) AS price";

	$available_col = "IFNULL(e.available,0) AS available";

	if ($mode === "town") {
		$cols = "a.type, $price, $available_col";
	} else if ($mode === "commodity") {
		$cols = "b.id AS town_id, $price, $available_col";
	} else if ($mode === "supply_demand") {
		$cols = "a.type, ($supply) AS supply, ($demand) AS demand, $available_col";
	} else {
		$cols = "*";
	}
	
	$sql = "SELECT 
		$cols
	FROM
		commodities AS a
		JOIN towns AS b
		LEFT JOIN {$prefix}buildings AS c
			ON c.town_id = b.id 
		LEFT JOIN production AS d
			ON c.`type` = d.`type` AND d.commodity = a.`type`
		LEFT JOIN `{$prefix}availability` AS e
			ON e.commodity = a.`type` AND e.town_id = b.id
		LEFT JOIN production AS  f
			ON f.`commodity` = a.`type` AND f.`type` = 'population'
		LEFT JOIN `{$prefix}availability` AS g
			ON g.town_id = b.id AND g.commodity = a.type
	";

	if ($mode === "town" || $mode === "supply_demand") {
		$sql .= "WHERE b.id = $id GROUP BY a.`type`";
	} else if ($mode === "commodity") {
		$sql .= "WHERE a.type = '$id' GROUP BY b.id ORDER BY price DESC";
	}

	return $sql;
}