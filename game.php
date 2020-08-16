<?php
require_once("constants.php");
require_once("database.php");
require_once("economy.php");

class Game
{
	private $data = array();

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
	
	function init()
	{
		global $database, $debug, $MAX_DELTA;
		
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
		global $database;
		return $database->getData($key);
	}
	
	function setData($key, $value){
		global $database;
		$database->setData($key, $value);
	}
	
	function State($state=-1)
	{
		global $database;
		if($state!=-1)
			$database->setData('gameState', $state);
		return $database->getData('gameState');
	}
	
	function Speed()
	{
		global $database, $CONST;
		return $CONST['game_speeds'][$database->getData('gameState')];
	}
	
	function getLocos()
	{
		global $database;
		return $database->getLocos();
	}
	
	function getTrains()
	{
		global $database;
		$trains = array();
		foreach($database->getTrains() as $train)
		{
			$trains[] = Train::getTrain($train['id']);
		}
		return $trains;
	}
	
	function getBuildings()
	{
		global $database;
		return $database->getBuildings();
	}
	
	function getTowns($tid = -1)
	{
		global $database;
		return $database->getTowns($tid);
	}
	
	function getCommodities($town_id, $commodity="")
	{
		global $economy;
		return $economy->getCommodities($town_id, $commodity);
	}
	
	function updateTrain($id, $key, $value)
	{
		global $database;
		return $database->updateTrain($id, $key, $value);
	}
	
	function updateCommodities($town_id, $commodity, $dsurplus)
	{
		global $economy;
		return $economy->updateCommodities($town_id, $commodity, $dsurplus);
	}
}