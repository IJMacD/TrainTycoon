<?php
require_once("constants.php");
require_once("debug.php");

class DB
{
	private $connection;
	
    function __construct()
	{
		global $CONST;
		$this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS) or $this->redirect("Server Error<br>Cannot connect to database server");
		mysqli_select_db($this->connection, DB_NAME) or $this->redirect("Server Error<br>Cannot select database");
    }

	function query($q){
		global $debug;
		$result = mysqli_query($this->connection, $q);
		$debug->log($q, 3);
		if(mysqli_error($this->connection)){
			echo mysqli_error($this->connection)."<br>".$q;
		}
		return $result;
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
	
	function getTowns($tid = -1){
		if(!isset($this->towns)){
			$this->towns = array();
			$q = "SELECT * FROM `".TABLE_towns."`";
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
			$q = "SELECT * FROM `".TABLE_stations."`";
			$result = $this->query($q);
			if($result && $result->num_rows){
				while($this->stations[] = $result->fetch_assoc());
			}
		}
		return $this->stations;
	}
	
	function getCommodities($town_id, $commodity="")
	{
		global $debug;
		
		if(!isset($this->commodities[$town_id])){
			$this->commodities[$town_id] = array();
			$q = "SELECT `commodity` as 'name',`surplus` as 'supply',`surplus` - `demand` as 'surplus',`demand`,`price` FROM `".TABLE_commodities."` WHERE `town_id` = '$town_id' ORDER BY `price` DESC";
			$result = $this->query($q);
			if($result && $result->num_rows > 0)
			{
				while($row = $result->fetch_assoc()){
					$this->commodities[$town_id][$row['name']] = $row;
				}
			}
		}
		
		if(!strlen($commodity))
		{
			$debug->log('Commodity not specified', 2);
			return $this->commodities[$town_id];
		}
		else
		{
			if(!isset($this->commodities[$town_id][$commodity]))
			{
				$debug->log('Commodity specified don\'t have it', 2);
				$c = makeComodity($commodity);
				$price = $c['price'];
				$supply = $c['supply'];
				$demand = $c['demand'];
				$this->setCommodity($town_id, $commodity, $supply, $demand, $price);
			}
			else
				$debug->log('Commodity specified do have it', 2);

			return $this->commodities[$town_id][$commodity];
		}
	}

	function setCommodity ($town_id, $commodity, $supply, $demand, $price) {
		
		$q = "INSERT INTO `".TABLE_commodities."` (`town_id`,`commodity`,`surplus`,`demand`,`price`) "
			."VALUES ('$town_id','$commodity','$supply','$demand','$price') "
			."ON DUPLICATE KEY UPDATE `surplus` = '$supply', `demand` = '$demand', `price` = '$price'";
		$this->query($q);

		if(!isset($this->commodities)) $this->commodities = array();
		if(!isset($this->commodities[$town_id])) $this->commodities[$town_id] = array();
		if(!isset($this->commodities[$town_id][$commodity])) $this->commodities[$town_id][$commodity] = array();

		$this->commodities[$town_id][$commodity]['name']	= $commodity;
		$this->commodities[$town_id][$commodity]['surplus']	= $supply - $demand;
		$this->commodities[$town_id][$commodity]['supply']	= $supply;
		$this->commodities[$town_id][$commodity]['demand']	= $demand;
		$this->commodities[$town_id][$commodity]['price']	= $price;
	}

	function getCommodityList ($commodity) {
		$q = "SELECT `town_id`,`surplus` as 'supply',`surplus` - `demand` as 'surplus',`demand`,`price` FROM `".TABLE_commodities."` WHERE commodity = '$commodity' ORDER BY `price` DESC";
		return $this->query($q)->fetch_all(MYSQLI_ASSOC);
	}

	function getCommodityTypes () {
		$q = "SELECT `commodity` FROM `" . TABLE_commodities . "` GROUP BY `commodity` ORDER BY `commodity`";
		$r =  $this->query($q);
		$out = [];
		while($commodity = $r->fetch_row()) $out[] = $commodity[0];
		return $out;
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
}
$database = new DB;

function makeComodity ($commodity, $surplus=0) {
	global $CONST;

	$price = $CONST['commodities'][$commodity]['price'];
	$price *= binom(1, 0.25);

	return array('name' => $commodity, 'supply' => 200 + $surplus, 'demand' => 200 - $surplus, 'surplus' => $surplus, 'price' => $price);
}

function binom ($centre = 0.5, $range = 1, $iter = 3) {
	$acc = 0;
	for ($i = 0; $i < $iter; $i++) $acc += rand(0, 100000) / 100000;
	$r = $acc / $iter;
	$r += $centre - 0.5;
	$r *= $range;
	return $r;
}