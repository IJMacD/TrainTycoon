<?php
require_once("constants.php");
require_once("debug.php");

class DB
{
	private $connection;
	private $prefix = "";
	private $game_id;

	private $commodities = [];
	private $stations = null;

    function __construct($game_id=null)
	{
		global $CONST;
		$this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS) or $this->redirect("Server Error<br>Cannot connect to database server");
		mysqli_select_db($this->connection, DB_NAME) or $this->redirect("Server Error<br>Cannot select database");

		$this->game_id = $game_id;
    }

	function query($q, $params=null){
		global $debug;
		if ($params) {
			$stmt = mysqli_prepare($this->connection, $q);
			if (!$stmt) {
				die(mysqli_error($stmt));
			}
			$types = implode("", array_map(function ($p) { return is_int($p) ? "i" : "s"; }, $params));
			$stmt->bind_param($types, ...$params);
			$result = $stmt->execute();
			if (!$result)  {
				die(mysqli_error($this->connection));
			}
			$result = $stmt->get_result();
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

	function createGame ($session_id) {
		$this->query("INSERT INTO {$this->prefix}games (`session_id`) VALUES (?)", [$session_id]);

		$this->game_id = $this->connection->insert_id;

		return $this->game_id;
	}

	function getData($key){
		if (!isset($this->game_id)) die ("Game ID not set");

		global $CONST;
		if(!isset($this->data[$key])){
			$q = "SELECT value FROM `{$this->prefix}data` WHERE `key` = '$key' AND `game_id` = {$this->game_id}";
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
			$q = "SELECT * FROM {$this->prefix}locos";
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
		if (!isset($this->game_id)) die ("Game ID not set");

		if(!isset($this->trains)){
			$this->trains = array();
			$q = "SELECT * FROM {$this->prefix}trains WHERE `game_id` = {$this->game_id} ORDER BY CASE WHEN `name` IS NULL THEN 1 ELSE 0 END, `name`";
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
		if (!isset($this->game_id)) die ("Game ID not set");

		$q = "INSERT INTO {$this->prefix}trains (game_id, loco_id, name) VALUES (?, ?, ?)";
		$this->query($q, [$this->game_id, $loco_id, $name]);
		$id = mysqli_insert_id($this->connection);

		// Clear cache
		unset($this->trains);

		return $id;
	}

	function getBuildings(){
		if (!isset($this->game_id)) die ("Game ID not set");

		if(!isset($this->buildings)){
			$this->buildings = array();
			$q = "SELECT * FROM {$this->prefix}buildings WHERE `game_id` = {$this->game_id} ORDER BY `name`";
			$result = $this->query($q);
			if($result && $result->num_rows){
				while($row = $result->fetch_assoc()){$this->buildings[$row['id']] = $row;}
			}
		}
		return $this->buildings;
	}

	function insertBuilding ($type, $town_id, $name) {
		if (!isset($this->game_id)) die ("Game ID not set");

		$q = "INSERT INTO {$this->prefix}buildings (game_id, type, town_id, name) VALUES (?, ?, ?, ?)";
		$this->query($q, [$this->game_id, $type, $town_id, $name]);
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

	function getStations () {
		if (!isset($this->game_id)) die ("Game ID not set");

		if(!isset($this->stations)){
			$this->stations = array();
			$q = "SELECT
					b.`id`,
					b.`name`,
					b.`town_id`,
					COALESCE(b.`lat`, t.`lat`) AS lat,
					COALESCE(b.`lon`, t.`lon`) AS lon
				FROM {$this->prefix}buildings AS b
					LEFT JOIN {$this->prefix}towns AS t ON b.town_id = t.id
				WHERE
					b.`type` = 'station'
					AND b.`game_id` = {$this->game_id}
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
		if (!isset($this->game_id)) die ("Game ID not set");

		$q = "INSERT INTO {$this->prefix}buildings (`game_id`, `type`, `town_id`, `name`) VALUES (?, 'station', ?, ?)";
		$this->query($q, [$this->game_id, $town_id, $name]);
		$id = mysqli_insert_id($this->connection);

		unset($this->stations);

		return $id;
	}

	function getCommodities ($town_id) {
		if (!isset($this->game_id)) die ("Game ID not set");

		if(!isset($this->commodities[$town_id])){
			$q = economySQL($this->prefix, $this->game_id, "town", $town_id);
			$this->commodities[$town_id] = $this->query($q)->fetch_all(MYSQLI_ASSOC);
		}

		return $this->commodities[$town_id];
	}

	function setCommodity ($town_id, $commodity, $available) {
		if (!isset($this->game_id)) die ("Game ID not set");

		$q = "INSERT INTO `{$this->prefix}availability` (`game_id`, `town_id`,`commodity`,`available`) "
			."VALUES (?, ?, ?, ?) "
			."ON DUPLICATE KEY UPDATE `available` = '$available'";
		$this->query($q, [$this->game_id, $town_id, $commodity, $available]);

		unset($this->commodities[$town_id]); // invalidate cache
	}

	function getCommodityList ($commodity) {
		if (!isset($this->game_id)) die ("Game ID not set");

		$q = economySQL($this->prefix, $this->game_id, "commodity", $commodity);
		return $this->query($q)->fetch_all(MYSQLI_ASSOC);
	}

	function getCommodityTypes () {
		$q = "SELECT `type` FROM {$this->prefix}commodities ORDER BY `type`";
		$r =  $this->query($q);
		$out = [];
		while($commodity = $r->fetch_row()) $out[] = $commodity[0];
		return $out;
	}

	function getBuildingTypes () {
		$q = "SELECT `type` FROM {$this->prefix}production GROUP BY `type` ORDER BY `type`";
		$r =  $this->query($q);
		$out = [];
		while($commodity = $r->fetch_row()) $out[] = $commodity[0];
		return $out;
	}

	function getCommoditySupplyDemand ($town_id) {
		if (!isset($this->game_id)) die ("Game ID not set");

		return $this->query(economySQL($this->prefix, $this->game_id, "supply_demand", $town_id))->fetch_all(MYSQLI_ASSOC);
	}

	function getProduction ($building_type) {
		$q = "SELECT commodity, supplies, demands FROM {$this->prefix}production WHERE `type` = '$building_type'";
		return $this->query($q)->fetch_all(MYSQLI_ASSOC);
	}

	function setData($key, $value){
		if (!isset($this->game_id)) die ("Game ID not set");

		$q = "INSERT INTO `{$this->prefix}data` (`game_id`, `key`, `value`) VALUES ({$this->game_id}, '$key', '$value') ON DUPLICATE KEY UPDATE `value` = '$value'";
		$this->query($q);
		$this->data[$key] = $value;
	}

	function updateTrain($id, $key, $value){
		if (!isset($this->game_id)) die ("Game ID not set");

		$q = "UPDATE {$this->prefix}trains SET `$key` = ? WHERE `id` = '$id' AND `game_id` = {$this->game_id}";
		$this->query($q, [$value]);
		$this->trains[$id][$key] = $value;
	}

	function updateBuilding($id, $key, $value){
		if (!isset($this->game_id)) die ("Game ID not set");

		$q = "UPDATE {$this->prefix}buildings SET `$key` = ? WHERE `id` = '$id' AND `game_id` = {$this->game_id}";
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
		$q = "INSERT INTO {$this->prefix}locos (`id`, `active`) VALUES ".implode(", ", $values);
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
				JOIN {$this->prefix}buildings AS b ON r.station_id = b.id AND r.game_id = b.game_id
				JOIN {$this->prefix}towns AS t ON b.town_id = t.id
			WHERE train_id = $route_id AND r.game_id = {$this->game_id}
			ORDER BY `order`";

		return $this->query($q)->fetch_all(MYSQLI_ASSOC);
	}

	function addRouteStop ($route_id, $order, $station_id, $length = 1) {
		if (!isset($this->game_id)) die ("Game ID not set");

		$q = "INSERT INTO {$this->prefix}routes (`game_id`, `train_id`, `order`, `station_id`, `length`) VALUES ({$this->game_id}, $route_id, $order, $station_id, $length)";
		$this->query($q);
	}

	function updateRoute($train_id, $order, $key, $value){
		if (!isset($this->game_id)) die ("Game ID not set");

		$q = "UPDATE {$this->prefix}routes SET `$key` = ? WHERE `train_id` = ? AND `order` = ? AND `game_id` = {$this->game_id}";
		$this->query($q, [$value, $train_id, $order]);
		$this->trains[$train_id]['route'][$i] = $value;
	}

	function insertLog($message){
		if (!isset($this->game_id)) die ("Game ID not set");

		$q = "INSERT INTO {$this->prefix}log (`game_id`, `message`) VALUES (?, ?)";
		$this->query($q, [$this->game_id, $message]);
	}

	function getLog($limit = 10, $before = ""){
		if (!isset($this->game_id)) die ("Game ID not set");

		$params = [$this->game_id, $limit];

		$whereBefore = "";

		if ($before) {
			$whereBefore = "AND `date` < ?";
			$params = [$this->game_id, $before, $limit];
		}

		$q = "SELECT `message`, `date` FROM {$this->prefix}log WHERE `game_id` = ? $whereBefore ORDER BY `date` DESC LIMIT ?";

		$result = $this->query($q, $params);

		if (!$result) {
			die(mysqli_error($this->connection));
		}

		return $result->fetch_all(MYSQLI_ASSOC);
	}

	function reset () {
		if (!isset($this->game_id)) die ("Game ID not set");

		$this->queryMultiple(
			"DELETE FROM {$this->prefix}data WHERE `game_id` = {$this->game_id};
			DELETE FROM {$this->prefix}availability WHERE `game_id` = {$this->game_id};
			UPDATE {$this->prefix}buildings SET scale = 1, wealth = 0 WHERE `game_id` = {$this->game_id};
			UPDATE {$this->prefix}trains SET Car_1 = NULL, Car_2 = NULL, Car_3 = NULL, Car_4 = NULL, Car_5 = NULL, Car_6 = NULL, Car_7 = NULL, Car_8 = NULL WHERE `game_id` = {$this->game_id};"
		);
	}
}

function economySQL ($prefix, $game_id, $mode, $id) {
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
		{$prefix}commodities AS a
		JOIN {$prefix}towns AS b
		LEFT JOIN {$prefix}buildings AS c
			ON c.town_id = b.id
		LEFT JOIN {$prefix}production AS d
			ON c.`type` = d.`type` AND d.commodity = a.`type`
		LEFT JOIN `{$prefix}availability` AS e
			ON e.commodity = a.`type` AND e.town_id = b.id AND e.game_id = c.game_id
		LEFT JOIN {$prefix}production AS  f
			ON f.`commodity` = a.`type` AND f.`type` = 'population'
		LEFT JOIN `{$prefix}availability` AS g
			ON g.town_id = b.id AND g.commodity = a.type AND g.game_id = c.game_id
		WHERE c.`game_id` = {$game_id}
	";

	if ($mode === "town" || $mode === "supply_demand") {
		$sql .= "AND b.id = $id GROUP BY a.`type` ORDER BY b.id";
	} else if ($mode === "commodity") {
		$sql .= "AND a.type = '$id' GROUP BY b.id ORDER BY price DESC, b.id";
	}

	return $sql;
}