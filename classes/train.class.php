<?php
class Train
{
	var $id;
	
	private $name;
	private $loco_id;
	
	private $cars = array();
	
	// Array of station ids on route
	private $route = array();
	// Fudge, Fudge
	private $towns = array();
	private $stations  = array();
	
	// index of route array on way to
	private $segment = 0;

	// Are we travelling backwards along the route?
	private $direction = 1;
	
	// percentage of current segment completed
	private $progress = 0;
	
	private $speed = 0;
	
	/**
	 * Static
	 */
	private static $_singleton = array();
	
	/**
	 * Methods
	 */
	private function __construct($id){ $this->id = $id; }
	
	public function getName()
	{
		return strlen($this->name) ? $this->name : "Train ".$this->id;
	}
	public function getLocoID()
	{
		return $this->loco_id;
	}
	public function getCars($index=-1)
	{
		return ($index >= 0 && $index < count($this->cars)) ? $this->cars[$index] : $this->cars;
	}
	public function getProgress()
	{
		return $this->progress;
	}
	public function getSegment()
	{
		return $this->segment;
	}
	public function getSpeed()
	{
		return $this->speed;
	}
	public function getDirection()
	{
		return $this->direction;
	}
	
	function isAtStation()
	{
		return ($this->progress >= 100 || $this->progress <= 0);
	}
	
	function getNextStation()
	{
		// Ideal
		//return Station::getStation($this->route[$this->segment]);
		if ($this->direction < 0) {
			return $this->stations[$this->segment-1];
		}
		return $this->stations[$this->segment];
		// progress == 0, segment != 0
		//$train['route'][$train['segment']-1]
	}
	
	function getStations()
	{
		// Ideal
		//return Station::getStation($this->route[$this->segment]);
		return $this->stations;
		// progress == 0, segment != 0
		//$train['route'][$train['segment']-1]
	}
	
	/**
	 * Fudge - replace with $train->getStation()->getTown()
	 */
	function getTown()
	{
		if ($this->direction < 0) {
			return $this->towns[$this->segment-1];
		}
		return $this->towns[$this->segment];
	}

	function getNextTown()
	{
		if ($this->direction < 0) {
			return $this->towns[$this->segment];
		}
		return $this->towns[($this->segment+1)%count($this->towns)];
	}

	function getTowns()
	{
		return $this->towns;
	}
	
	
	/** 
	 * Modifying functions
	 */
	function move($delta)
	{
		global $database;
		$this->progress = $this->progress + $this->direction * $this->speed * $delta;
		$database->updateTrain($this->id, 'progress', $this->progress);
	}
	
	function moveToNextStation()
	{
		global $database;

		/*
		 *     Station:    A          B           C
		 *     Segment:          1          2
		 * 
		 */
		
		if ($this->direction < 0) {
			if ($this->segment <= 1) {
				$this->direction = 1;
				$this->segment = 1;
				$this->progress = -$this->progress;
			}
			else {
				$this->progress += 100;
				$this->segment = ($this->segment - 1) % count($this->route);
			}
		} else {
			global $database;
			if ($this->segment + 1 >= count($this->route)) {
				$this->direction = -1;
				$this->progress = 200 - $this->progress;
				$this->segment = count($this->route) - 1;
			} else {
				$this->progress -= 100;
				$this->segment = ($this->segment + 1) % count($this->route);
			}
		}

		// var_dump($this);

		// exit;

		$database->updateTrain($this->id, 'progress', $this->progress);
		$database->updateTrain($this->id, 'segment', $this->segment); 
		$database->updateTrain($this->id, 'direction', $this->direction); 
	}
	/**
	 * Returns whether or not load was successful
	 * don't try to load anymore after a failed load
	 */
	function load($commodity)
	{
		global $database;
		
		if(count($this->cars) >= 8)
			return false;
			
		$this->cars[] = $commodity;
		$i = count($this->cars);
		$database->updateTrain($this->id, 'Car_'.$i, $commodity);
		return true;
	}
	/**
	 * return array of cars and then empty internal cars array
	 */
	function unload()
	{
		global $database;
		$cars = $this->cars;
		$this->cars = array();
		for($i = 1; $i <= 8; $i++)
			$database->updateTrain($this->id, 'Car_'.$i, "");
		return $cars;
	}
	
	/**
	 * Static
	 */
	static function getTrain($id)
	{
		global $database;
		
		if(!is_array(Train::$_singleton))
			Train::$_singleton = array();
		
		if(!isset(Train::$_singleton[$id]))
		{
			$train = new Train($id);
			$t = $database->getTrains($id);
			
			if(!is_array($t))
			{
				//echo 'Error';
				//print_r(debug_backtrace());
				return false;
			}
			
			$train->name = $t['Name'];
			$train->loco_id = $t['loco_id'];
			$train->progress = $t['progress'];
			$train->segment = $t['segment'];
			$train->direction = $t['direction'];
			$train->speed = $t['speed'];
			$train->route = $t['route_ids'];
			// Fudge
			$train->stations = $t['route'];
			$train->towns = $t['town_ids'];
			
			for($i = 1; $i <= 8; $i++)
			{
				if(strlen($t['Car_'.$i]))
					$train->cars[] = $t['Car_'.$i];
			}
			
			Train::$_singleton[$id] = $train;
		}
		
		return Train::$_singleton[$id];
	}
}