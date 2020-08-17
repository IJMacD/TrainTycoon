<?php

require_once "constants.php";
require_once "database.php";
require_once "economy.php";
require_once "util.php";

class Game
{
	private $data = array();

	private $database;

	private $locos;
	private $trains;
	private $buildings;
	private $towns;
	private $stations;
	private $commodities;
	
	var $lasttime;
	var $delta;
	var $dsimtime;

	var $hasBreak = false;
	
	function __construct()
	{
		global $debug;

		$this->database = new DB(uniqid());
		
		$this->lasttime = $this->getData('lasttime');
		$time = microtime(true);
		$this->delta = $time - $this->lasttime;
	
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
	
	function getCommodities($town_id, $commodity="")
	{
		global $economy;
		return $economy->getCommodities($town_id, $commodity);
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
	
	function updateTrain($id, $key, $value)
	{
		return $this->database->updateTrain($id, $key, $value);
	}
	
	function updateCommodities($town_id, $commodity, $dsurplus)
	{
		global $economy;
		return $economy->updateCommodities($town_id, $commodity, $dsurplus);
	}

	function reset () {
		$this->database->reset();
	}

	function createStation ($town_id, $name) {
		$this->database->insertStation($town_id, $name);
	}

	function createBuilding ($type, $town_id, $name) {
		$this->database->insertBuilding($type, $town_id, $name);
	}

	function createTrain ($loco_id, $name, $station_ids) {
		$id = $this->database->insertTrain($loco_id, $name);

		if ($id) {
			$length = getStationDistance($station_ids[0], $station_id[1]);

			$this->database->addRouteStop($id, 0, $station_ids[0]);
			$this->database->addRouteStop($id, 1, $station_ids[1], $length);

			return true;
		}

		return false;
	}

	function addRouteStop ($train_id, $station_id) {
		$train = Train::getTrain($train_id);
		$route = $train->getRoute();
		$i = count($route);

		$length = getStationDistance($route[$i-1]['station_id'], $station_id);

		if ($length <=  0) return false;

		$this->database->addRouteStop($train_id, $i, $station_id, $length);
	}
}