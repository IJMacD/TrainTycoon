<?php
require_once("constants.php");
require_once("debug.php");

class DB
{
	private $connection;

	private $commodities = [];
	
    function __construct()
	{
		global $CONST;
		$this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS) or $this->redirect("Server Error<br>Cannot connect to database server");
		mysqli_select_db($this->connection, DB_NAME) or $this->redirect("Server Error<br>Cannot select database");
    }

	function query($q, $params=null){
		global $debug;
		if ($params) {
			$stmt = mysqli_prepare($this->connection, $q);
			$types = implode("", array_map(function ($p) { return is_int($p) ? "i" : "s"; }, $params));
			array_unshift($params, $types);
			$ref = new ReflectionClass("mysqli_stmt");
			$method = $ref->getMethod("bind_param");
			$method->invokeArgs($stmt, $params);
			$stmt->execute();
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
			$q = "SELECT value FROM `".TABLE_data."` WHERE `key` = '$key'";
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
			$q = "SELECT * FROM `".TABLE_locos."`";
			$result = $this->query($q);
			if($result && $result->num_rows){
				while($this->locos[] = $result->fetch_assoc());
			}else{
				$this->populateLocosTable();
			}
		}
		return $this->locos;
	}
	
	function getTrains($tid=-1)
	{
		if(!isset($this->trains)){
			$this->trains = array();
			$q = "SELECT * FROM `".TABLE_trains."`";
			$result = $this->query($q);
			if($result && $result->num_rows){
				while($train = $result->fetch_assoc()){
					$train['route'] = $this->getRoute($train['id']);
					$this->trains[$train['id']] = $train;
				}
			}
		}
		if($tid >= 0)
		{
			return isset($this->trains[$tid]) ? $this->trains[$tid] : false;
		}
		else return $this->trains;
	}

	function insertTrain ($loco_id, $name) {
		$q = "INSERT INTO trains (loco_id, Name) VALUES (?, ?)";
		$this->query($q, [$loco_id, $name]);
		$id = mysqli_insert_id($this->connection);

		$this->trains[] = ["id"=>$id,"Name"=>$name,"loco_id"=>$loco_id];

		return $id;
	}
	
	function getBuildings(){
		if(!isset($this->buildings)){
			$this->buildings = array();
			$q = "SELECT * FROM `".TABLE_buildings."`";
			$result = $this->query($q);
			if($result && $result->num_rows){
				while($row = $result->fetch_assoc()){$this->buildings[$row['id']] = $row;}
			}
		}
		return $this->buildings;
	}

	function insertBuilding ($type, $town_id, $name) {
		$q = "INSERT INTO buildings (type, town_id, name) VALUES (?, ?, ?)";
		$this->query($q, [$type, $town_id, $name]);
		$id = mysqli_insert_id($this->connection);

		$this->buildings[] = ["id"=>$id,"type"=>$type,"town_id"=>$town_id,"name"=>$name,"wealth"=>0,"scale"=>1];

		return $id;
	}
	
	function getTowns($tid = -1){
		if(!isset($this->towns)){
			$this->towns = array();
			$q = "SELECT * FROM `".TABLE_towns."` ORDER BY `Name`";
			$result = $this->query($q);
			if($result && $result->num_rows){
				while($row = $result->fetch_assoc()){$this->towns[$row['id']] = $row;}
			}
		}
		return ($tid == -1 || $tid == "") ? $this->towns : $this->towns[$tid];
	}
	
	function getStations(){
		if(!isset($this->stations)){
			$this->stations = array();
			$q = "SELECT * FROM `".TABLE_stations."` ORDER BY `Name`";
			$result = $this->query($q);
			if($result && $result->num_rows){
				while($row = $result->fetch_assoc()) {$this->stations[] = $row;}
			}
		}
		return $this->stations;
	}

	function insertStation ($town_id, $name) {
		$q = "INSERT INTO stations (town_id, Name) VALUES (?, ?)";
		$this->query($q, [$town_id, $name]);
		$id = mysqli_insert_id($this->connection);

		$this->stations[] = ["id"=>$id,"town_id"=>$town_id,"Name"=>$name];

		return $id;
	}

	function getCommodities ($town_id) {
		if(!isset($this->commodities[$town_id])){
			$q = economySQL("town", $town_id);
			$this->commodities[$town_id] = $this->query($q)->fetch_all(MYSQLI_ASSOC);
		}

		return $this->commodities[$town_id];
	}

	function setCommodity ($town_id, $commodity, $available) {
		$q = "INSERT INTO `availability` (`town_id`,`commodity`,`available`) "
			."VALUES ('$town_id','$commodity','$available') "
			."ON DUPLICATE KEY UPDATE `available` = '$available'";
		$this->query($q);

		unset($this->commodities[$town_id]); // invalidate cache
	}

	function getCommodityList ($commodity) {
		$q = economySQL("commodity", $commodity);
		return $this->query($q)->fetch_all(MYSQLI_ASSOC);
	}

	function getCommodityTypes () {
		$q = "SELECT `type` FROM commodities2 ORDER BY `type`";
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
		return $this->query(economySQL("supply_demand", $town_id))->fetch_all(MYSQLI_ASSOC);
	}

	function getProduction ($building_type) {
		$q = "SELECT commodity, supplies, demands FROM production WHERE `type` = '$building_type'";
		return $this->query($q)->fetch_all(MYSQLI_ASSOC);
	}
	
	function setData($key, $value){
		$q = "INSERT INTO `".TABLE_data."` (`key`, `value`) VALUES ('$key', '$value') ON DUPLICATE KEY UPDATE `value` = '$value'";
		$this->query($q);
		$this->data[$key] = $value;
	}
	
	function updateTrain($id, $key, $value){
		$v = $value === NULL ? "NULL" : "'$value'";
		$q = "UPDATE `".TABLE_trains."` SET `$key` = $v WHERE `id` = '$id'";
		$this->query($q);
		$this->trains[$id][$key] = $value;
	}
	
	function updateBuilding($id, $key, $value){
		$v = $value === NULL ? "NULL" : "'$value'";
		$q = "UPDATE `".TABLE_buildings."` SET `$key` = $v WHERE `id` = '$id'";
		$this->query($q);
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
		$q = "INSERT INTO `".TABLE_locos."` (`id`, `active`) VALUES ".implode(", ", $values);
		$this->query($q);
	}

	function getRoute ($route_id) {
		$q = "SELECT
			`station_id`,
				`length`,
				`stations`.`Name` AS station_name,
				`town_id`,
				`towns`.`Name` AS town_name,
				`towns`.`lat` AS lat,
				`towns`.`lon` AS lon,
				population
			FROM routes
				JOIN stations ON routes.station_id = stations.id
				JOIN towns ON stations.town_id = towns.id
			WHERE train_id = $route_id
			ORDER BY `order`";
		
		return $this->query($q)->fetch_all(MYSQLI_ASSOC);
	}

	function addRouteStop ($route_id, $order, $station_id) {
		$q = "INSERT INTO routes (`train_id`, `order`, `station_id`) VALUES ($route_id, $order, $station_id)";
		$this->query($q);
	}
}
$database = new DB;

function economySQL ($mode, $id) {
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
		commodities2 AS a
		JOIN towns AS b
		LEFT JOIN buildings AS c
			ON c.town_id = b.id 
		LEFT JOIN production AS d
			ON c.`type` = d.`type` AND d.commodity = a.`type`
		LEFT JOIN availability AS e
			ON e.commodity = a.`type` AND e.town_id = b.id
		LEFT JOIN production AS  f
			ON f.`commodity` = a.`type` AND f.`type` = 'population'
		LEFT JOIN availability AS g
			ON g.town_id = b.id AND g.commodity = a.type
	";

	if ($mode === "town" || $mode === "supply_demand") {
		$sql .= "WHERE b.id = $id GROUP BY a.`type`";
	} else if ($mode === "commodity") {
		$sql .= "WHERE a.type = '$id' GROUP BY b.id ORDER BY price DESC";
	}

	return $sql;
}