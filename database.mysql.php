<?php
require_once("constants.php");
class DB
{
	private $connection;
	
    function DB()
	{
		global $CONST;
		$this->connection = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Server Error<br>Cannot connect to database server");
		mysql_select_db(DB_NAME, $this->connection) or die("Server Error<br>Cannot select database");
    }
	
	function log($message, $level=1)
	{
		if(isset($_GET['debug']))
		{
			if(strlen($_GET['debug']))
				$debug = $_GET['debug'];
			else
				$debug = 1;
		}
		else
			$debug = 0;
		
		if($debug >= $level)
			echo $message."<br>\n";
	}

	function query($q){
		global $debug;
		$result = mysql_query($q, $this->connection);
		$debug->log($q, 3);
		if(mysql_error()){
			echo mysql_error()."<br>".$q;
		}
		return $result;
	}
	
	function getData($key){
		global $CONST;
		if(!isset($this->data[$key])){
			$q = "SELECT * FROM `".TABLE_data."` WHERE `key` = '$key'";
			$result = $this->query($q);
			if($result && mysql_num_rows($result)){
				$this->data[$key] = mysql_result($result, 0, "value");
			}else{
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
			if($result && mysql_num_rows($result)){
				while($this->locos[] = mysql_fetch_assoc($result));
			}else{
				$this->populateLocosTable();
			}
		}
		return $this->locos;
	}
	
	function getTrain ($id)
	{
		$trains = $this->getTrains();
		
		return isset($trains[$id]) ? $trains[$id] : null;
	}
	
	function getTrains ()
	{
		if(!isset($this->trains)){
			$this->trains = array();
			$q = "SELECT * FROM `".TABLE_trains."`";
			$result = $this->query($q);
			if($result && mysql_num_rows($result)){
				while($train = mysql_fetch_assoc($result)){
					$train['route'] = array();
					$q = "SELECT `b`.`id`,`b`.`Name`,`b`.`town_id` FROM `routes` a, `stations` b WHERE `a`.`train_id` = '".$train['id']."' AND `b`.`id` = `a`.`station_id` ORDER BY `a`.`order` ASC";
					$result_2 = $this->query($q);
					if($result_2 && mysql_num_rows($result_2)){
						while($stop = mysql_fetch_assoc($result_2)){
							$train['route_ids'][] = $stop['id'];
							$train['route'][] = $stop['name'];
							$train['town_ids'][] = $stop['town_id'];
						}
					}
					$this->trains[$train['id']] = $train;
				}
			}
		}
		
		return $this->trains;
	}
	
	function getBuildings(){
		if(!isset($this->buildings)){
			$this->buildings = array();
			$q = "SELECT * FROM `".TABLE_buildings."`";
			$result = $this->query($q);
			if($result && mysql_num_rows($result)){
				while($row = mysql_fetch_assoc($result)){$this->buildings[$row['id']] = $row;}
			}
		}
		return $this->buildings;
	}
	
	function getTowns($tid = -1){
		if(!isset($this->towns)){
			$this->towns = array();
			$q = "SELECT * FROM `".TABLE_towns."`";
			$result = $this->query($q);
			if($result && mysql_num_rows($result)){
				while($row = mysql_fetch_assoc($result)){$this->towns[$row['id']] = $row;}
			}
		}
		return ($tid == -1 || $tid == "") ? $this->towns : $this->towns[$tid];
	}
	
	function getStations(){
		if(!isset($this->stations)){
			$this->stations = array();
			$q = "SELECT * FROM `".TABLE_stations."`";
			$result = $this->query($q);
			if($result && mysql_num_rows($result)){
				while($this->stations[] = mysql_fetch_assoc($result));
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
			if($result && mysql_num_rows($result) > 0)
			{
				while($row = mysql_fetch_assoc($result)){
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
				$this->commodities[$town_id][$commodity] = array('name' => $commodity, 'supply' => 0, 'demand' => 0, 'surplus' => 0, 'price' => 0);
			}
			else
				$debug->log('Commodity specified do have it', 2);
			return $this->commodities[$town_id][$commodity];
		}
	}
	
	function setData($key, $value){
		$q = "INSERT INTO `".TABLE_data."` (`key`, `value`) VALUES ('$key', '$value') ON DUPLICATE KEY UPDATE `value` = '$value'";
		$this->query($q);
		$this->data[$key] = $value;
	}
	
	function updateTrain($id, $key, $value){
		$q = "UPDATE `".TABLE_trains."` SET `$key` = '$value' WHERE `id` = '$id'";
		$this->query($q);
		$this->trains[$id][$key] = $value;
	}
	
	/**
	 * Big method to calculate entire economy!
	 */
	function updateCommodities($town_id, $commodity, $dsurplus){
		global $debug, $CONST;
		
		$c = $this->getCommodities($town_id);
		//print_r($c);
		if(isset($c[$commodity]))
		{
			$price = $c[$commodity]['price'];
			$supply = $c[$commodity]['supply'];
			$demand = $c[$commodity]['demand'];
			/*
			$q = "SELECT ";
			$q .= "(SELECT AVG(`price`) FROM `".TABLE_commodities."` WHERE `commodity` = '$commodity') as 'avg_price',";
			$q .= "(SELECT AVG(`surplus`) FROM `".TABLE_commodities."` WHERE `commodity` = '$commodity') as 'avg_surplus'";
			//$q .= " FROM `".TABLE_commodities."` WHERE `town_id` = '$town_id' AND `commodity` = '$commodity'";
			$result = $this->query($q);
			if($result && mysql_num_rows($result)){
				$avg_price = mysql_result($result, 0, "avg_price");
				$avg_surplus = mysql_result($result, 0, "avg_surplus");
				if($surplus == $avg_surplus){
					$price = $avg_price;
					$surplus += $dsurplus;
				}else{
					$a = ($price - $avg_price)/($surplus - $avg_surplus);
					$b = $price - $a * $surplus;
					$surplus += $dsurplus;
					$price = $a * $surplus + $b;
					//$price *= (rand(16, 25) / 20); //rand(0.8, 1.25)
					$price = max(0, $price);
				}
			}else{
				$price = $CONST['commodities'][$commodity]['price'];
				$price *= (rand(16, 25) / 20);
				$surplus = $dsurplus;
			}*/
			$debug->log("TRADE: [$commodity-$town_id] Initial: Price $price, Supply $supply, Demand $demand");
			if($dsurplus > 0)
			{
				$supply += $dsurplus;
			}
			else
			{
				$demand -= $dsurplus;
			}
			// P = m_D.Q + c_D
			// P = m_S.Q + c_S
			// M = m_D/m_S
			// P = (M.c_D + c_S)/(1 - M)
			// Q === surplus
			// dP = m_D.dQ
			$m_D = -1;
			$price += $m_D * $dsurplus;
			$debug->log("TRADE: [$commodity-$town_id] Final: Price $price, Supply $supply, Demand $demand");
			//$price = max(0, $price);
		}else{
			$price = $CONST['commodities'][$commodity]['price'];
			$price *= (rand(16, 25) / 20);
			$supply = $dsurplus;
		}
		
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

	function reset () {
		$this->queryMultiple("DELETE FROM data; 
			DELETE FROM availability;
			UPDATE buildings SET scale = 1, wealth = 0;
			UPDATE trains SET Car_1 = NULL, Car_2 = NULL, Car_3 = NULL, Car_4 = NULL, Car_5 = NULL, Car_6 = NULL, Car_7 = NULL, Car_8 = NULL;");
	}
}