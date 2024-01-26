<?php

require_once "constants.php";
require_once "database.php";
require_once "util.php";

class Game
{
	private $database;

	private $delta = 0;
	var $dsimtime;

	function __construct ($game_id)
	{
		$this->database = new DB($game_id);
	}

	function delta ()
	{
		global $debug;

		if ($this->delta === 0) {
			$last_time = $this->getData('lasttime');
			$time = microtime(true);
			$this->delta = $time - $last_time;

			if($this->delta < MIN_DELTA) {
				// Don't update too frequently e.g. multiple viewers
				$this->delta = 0;
			}
			else if($this->delta > TIMEOUT){
				$this->State(STATE_PAUSED);
			}
			else if($this->delta > MAX_DELTA){
				$this->delta = MAX_DELTA;
			}

			$debug->log('Delta: '.$this->delta);
			$this->setData('lasttime', $time);
			$this->dsimtime = $this->delta / TIME_SCALE * $this->Speed();
			$debug->log("Delta (Sim): " . $this->dsimtime, 2);
		}

		return $this->delta;
	}

	function getData($key){
		return $this->database->getData($key);
	}

	function setData($key, $value){
		$this->database->setData($key, $value);
	}

	function State($state=-1)
	{
		if($state!=-1)
			$this->database->setData('gameState', $state);

		return $this->database->getData('gameState');
	}

	function Speed()
	{
		global $CONST;
		return $CONST['game_speeds'][$this->database->getData('gameState')];
	}

	function getLocos()
	{
		return $this->database->getLocos();
	}

	function getTrain ($id)
	{
		return $this->database->getTrain($id);
	}

	function getTrains ()
	{
		return $this->database->getTrains();
	}

	function getBuildings()
	{
		return $this->database->getBuildings();
	}

	function getStation ($id)
	{
		return $this->database->getStation($id);
	}

	function getStations ()
	{
		return $this->database->getStations();
	}

	function getBuildingTypes()
	{
		return $this->database->getBuildingTypes();
	}

	function getTown ($tid)
	{
		return $this->database->getTowns($tid);
	}

	function getTowns()
	{
		return $this->database->getTowns();
	}

	function getTracks () {
		return $this->database->getTracks();
	}

	function getTrack ($id) {
		return $this->database->getTrack($id);
	}

	function getCommodities ($town_id, $commodity="")
	{
		global $debug;

		$commodities = $this->database->getCommodities($town_id);

		/*
		 * getCommodities()
		 * Array<{
		 * 	name
		 * 	supply
		 * 	surplus
		 * 	demand
		 * 	price
		 * }>
		 */
		$commodities = array_map(function ($c) {
			return [
				"name" => $c['type'],
				"supply" => $c['available'],
				"surplus" => $c['available'],
				"demand" => 0,
				"price" => $c['price'],
			];
		}, $commodities);

		if ($commodity) {
			foreach ($commodities as $c) {
				if ($c['name'] == $commodity) return $c;
			}
			$debug->log("Can't find commodity $commodity at town $town_id", 2);
			return null;
		}

		return $commodities;
	}

	function getCommodityTypes ()
	{
		return $this->database->getCommodityTypes();
	}

	function getCommodityList ($commodity)
	{
		return $this->database->getCommodityList($commodity);
	}

	function getCommoditySupplyDemand ($town_id)
	{
		return $this->database->getCommoditySupplyDemand($town_id);
	}

	function getProduction ($type)
	{
		return $this->database->getProduction($type);
	}

	function updateTrain($id, $key, $value)
	{
		return $this->database->updateTrain($id, $key, $value);
	}

	function updateBuilding($id, $key, $value){
		return $this->database->updateBuilding($id, $key, $value);
	}

	function updateCommodities($town_id, $commodity, $dsurplus)
	{
		$c = $this->getCommodities($town_id, $commodity);

		$this->database->setCommodity($town_id, $commodity, $c['surplus'] + $dsurplus);

		return $dsurplus * $c['price'];
	}

	function reset () {
		$this->database->reset();
	}

	function createStation ($town_id, $name) {
		$this->database->insertStation($town_id, $name);
	}

	function createBuilding ($type, $town_id, $name = "") {
		if (!$name) {
			$town = $this->getTown($town_id);
			$name = $town['name'] . " " . ucfirst(implode(" ", explode("_", $type)));
		}
		$this->database->insertBuilding($type, $town_id, $name);
	}

	function createTrain ($loco_id, $name, $station_ids) {
		$id = $this->database->insertTrain($loco_id, $name);

		if (!$id) {
			return false;
		}

		$length = getStationDistance($station_ids[0], $station_ids[1]);

		$this->database->addRouteStop($id, 0, $station_ids[0]);
		$this->database->addRouteStop($id, 1, $station_ids[1], $length);

		// Head towards first station to load
		$this->database->updateTrain($id, "route_segment", -1);

		// Head towards first station
		$route_result = $this->findRoute($station_ids[1], $station_ids[0]);

		// Place train onto track
		if ($route_result) {
			$this->database->updateTrain($id, "track_id", $route_result['track_id']);

			$direction = $route_result['direction'];
			$this->database->updateTrain($id, "direction", $direction);
			// So that train is ready to load at first station
			$this->database->updateTrain($id, "progress", $direction < 0 ? 0 : 100);
		}
		else {
			// Just pick any random track at this station
			$tracks = $this->getTracks();
			$station_id = $station_ids[0];
			$connected_tracks = array_filter($tracks, function ($track) use ($station_id) {
				return $track['from_station_id'] == $station_id || $track['to_station_id'] == $station_id;
			});

			if (count($connected_tracks)) {
				$track = array_values($connected_tracks)[0];
				$this->database->updateTrain($id, "track_id", $track['id']);
				$this->database->updateTrain($id, "direction", 1);
			}
			else {
				$track = $tracks[0];
				$this->database->updateTrain($id, "track_id", $track['id']);
				$this->database->updateTrain($id, "direction", 1);
			}
		}

		return true;
	}

	function createTrack ($from_station_id, $to_station_id) {
		$length = getStationDistance($from_station_id, $to_station_id);
		$this->database->insertTrack($from_station_id, $to_station_id, $length);

		return true;
	}

	function addRouteStop ($train_id, $station_id) {
		$train = Train::getTrain($train_id);
		$route = $train->getRoute();
		$i = count($route);

		$length = getStationDistance($route[$i-1]['station_id'], $station_id);

		if ($length <= 0) return false;

		$this->database->addRouteStop($train_id, $i, $station_id, $length);

		return true;
	}

	function getLog () {
		return $this->database->getLog();
	}

	function insertLog ($message) {
		return $this->database->insertLog($message);
	}

	/**
	 * Dijkstra's algorithm
	 * @param int $current_station_id
	 * @param int $target_station_id
	 * @return array|false ['track_id' => number, 'direction' => number]
	 */
	function findRoute (int $current_station_id, int $target_station_id) {
		if ($current_station_id == $target_station_id) {
			return false;
		}

		// $from_station = $this->getStation($current_station_id);
		// $to_station = $this->getStation($target_station_id);
		// $this->insertLog("Routing from: " . $from_station['name']. " ($current_station_id) to: ".$to_station['name']." ($target_station_id)<br>\n");

		$tracks = $this->getTracks();

		$unvisited_nodes = [];
		$visited_nodes = [];
		$current_node = $current_station_id;

		$prev_path = [];

		// Setup
		foreach ($this->getStations() as $station) {
			$id = $station['id'];

			$unvisited_nodes[$id] = $id == $current_station_id ? 0 : INF;
		}

		// Sanity
		if (!isset($unvisited_nodes[$target_station_id])) {
			die("Prevented infinite loop");
		}

		while (true) {
			// Find all edges for this node
			$connected_tracks = array_filter($tracks, function ($track) use ($current_node) {
				return $track['from_station_id'] == $current_node
					|| $track['to_station_id'] == $current_node;
			});

			$current_weight = $unvisited_nodes[$current_node];

			// Consider all unvisited nodes connected to current node
			foreach ($connected_tracks as $track) {
				$neighbour_id = ($track['from_station_id'] == $current_node)
					? $track['to_station_id']
					: $track['from_station_id'];

				if (isset($unvisited_nodes[$neighbour_id])) {
					$dist = $current_weight + $track['length'];

					// If the distance is shorter, assign the new distance
					if ($dist < $unvisited_nodes[$neighbour_id]) {
						$unvisited_nodes[$neighbour_id] = $dist;

						// Save path for backtracking
						$prev_path[$neighbour_id] = $current_node;
					}
				}
			}

			// Move current node to visited
			$visited_nodes[$current_node] = $current_weight;
			unset($unvisited_nodes[$current_node]);

			// If target has been visited, then we're done
			if (isset($visited_nodes[$target_station_id])) {
				break;
			}

			// Find smallest unvisited node
			$smallest_weight = INF;
			foreach ($unvisited_nodes as $id => $weight) {
				if ($weight < $smallest_weight) {
					$current_node = $id;
					$smallest_weight = $weight;
				}
			}
			if ($smallest_weight === INF) {
				// No route to destination
				break;
			}
		}

		if (!isset($visited_nodes[$target_station_id])) {
			// Couldn't find path
			return false;
		}

		// $this->insertLog("map: " . json_encode($prev_path));

		// We found the shortest path. Now trace it to find route.
		$path = [];
		$prev = $target_station_id;
		while (true) {
			$path[] = $prev;
			if (!isset($prev_path[$prev])) {
				break;
			}
			$prev = $prev_path[$prev];
		}

		$path = array_reverse($path);

		// $this->insertLog("path:" . json_encode($path));

		if ($path[0] != $current_station_id) {
			// echo "from: $current_station_id to: $target_station_id<br>\n";
			// var_dump($path);
			die("Dijkstra's algorithm broke @ 1");
		}

		$next_hop = $path[1];

		foreach ($tracks as $track) {
			if (
				$track['from_station_id'] == $current_station_id
				&& $track['to_station_id'] == $next_hop)
			{
				return [
					'track_id' => $track['id'],
					'direction' => 1,
				];
			}
			else if (
				$track['from_station_id'] == $next_hop
				&& $track['to_station_id'] == $current_station_id)
			{
				return [
					'track_id' => $track['id'],
					'direction' => -1,
				];
			}
		}

		die("Dijkstra's algorithm broke @ 2");
	}

	static function newGame () {
		$tmp_database = new DB();

		$game_id = $tmp_database->createGame(uniqid());

		$game = new self($game_id);

		// Seed economy
		$buildingTypes = $game->getBuildingTypes();
		$towns = $game->getTowns();

		for ($i = 0; $i < 100; $i++) {
			$building_rand = rand(0, count($buildingTypes)-1);
			$town_rand_id = array_rand($towns);

			$buildingType = $buildingTypes[$building_rand];

			if ($buildingType !== "station" && $buildingType !== "population") {
				$game->createBuilding($buildingType, $town_rand_id);
			}
		}

		return $game_id;
	}
}