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

	private $loading_timeout = 0;

	private $state = NULL;

	/**
	 * Static
	 */
	private static $_singleton = array();

	/**
	 * Methods
	 */
	private function __construct ($id) { $this->id = $id; }

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

	function getRoute () {
		return $this->route;
	}

	function isAtStation()
	{
		return $this->state == "LOADING" || $this->state == "READY_TO_UNLOAD" || $this->state === "READY_TO_LOAD";
	}

	function isReadyToLoad () {
		return $this->state == "READY_TO_LOAD";
	}

	function isReadyToUnload () {
		return $this->state == "READY_TO_UNLOAD";
	}

	function isLoading () {
		return $this->state == "LOADING";
	}

	function isRunning () {
		return $this->state == "RUNNING";
	}

	function getLoadingTime () {
		$t = max($this->loading_timeout * TIME_SCALE / 60, 0);
		$i = (int)$t;
		$r = $t - $i;

		return sprintf("%02d:%02d", $i, $r * 60);
	}

	function getNextStation()
	{
		// Ideal
		//return Station::getStation($this->route[$this->segment]);
		return $this->route[$this->getNextIndex()]['station_name'];
		// progress == 0, segment != 0
		//$train['route'][$train['segment']-1]
	}

	function getNextIndex()
	{
		return $this->direction < 0 ? $this->segment - 1 : $this->segment;
	}

	function getStations()
	{
		$ids = array_map(function ($r) { return $r['station_id']; }, $this->route);
		return Station::getStations($ids);
	}

	/**
	 * Fudge - replace with $train->getStation()->getTown()
	 */
	function getTown()
	{
		return $this->route[$this->getNextIndex()]['town_id'];
	}

	function getNextTown()
	{
		if ($this->direction < 0) {
			return $this->route[$this->segment]['town_id'];
		}
		return $this->route[($this->segment+1)%count($this->route)]['town_id'];
	}

	function getTowns ()
	{
		return array_map(function ($v) { return $v['town_id']; }, $this->route);
	}


	/**
	 * Modifying functions
	 */
	function move($delta)
	{
		global $g, $debug;

		if (!$this->isAtStation()) {
			$distance = $this->route[$this->segment]['length'];
			if ($distance <= 0) {
				$debug->log($this->getName() . " tried to move along a route with distance " . $distance);
				$distance = 1;
			}
			$this->progress = $this->progress + $this->direction * $this->speed * $delta / $distance;
			$g->updateTrain($this->id, 'progress', $this->progress);
		}
	}

	function moveToNextStation()
	{
		global $g;

		/*
		 *     Station:    A          B           C
		 *     Segment:          1          2
		 *
		 */

		if ($this->direction < 0) {
			if ($this->segment <= 1) {
				$this->direction = 1;
				$this->segment = 1;
				$this->progress = 0;
			}
			else {
				$this->progress = 100;
				$this->segment = ($this->segment - 1) % count($this->route);
			}
		} else {
			global $g;
			if ($this->segment + 1 >= count($this->route)) {
				$this->direction = -1;
				$this->progress = 100;
				$this->segment = count($this->route) - 1;
			} else {
				$this->progress = 0;
				$this->segment = ($this->segment + 1) % count($this->route);
			}
		}

		// var_dump($this);

		// exit;

		$g->updateTrain($this->id, 'progress', $this->progress);
		$g->updateTrain($this->id, 'segment', $this->segment);
		$g->updateTrain($this->id, 'direction', $this->direction);

		$this->loading_timeout = 0;
		$g->updateTrain($this->id, "loading_timeout", $this->loading_timeout);

		$this->state = "RUNNING";
	}
	/**
	 * Returns whether or not load was successful
	 * don't try to load anymore after a failed load
	 */
	function load($commodity)
	{
		global $g;

		if(count($this->cars) >= 8)
			return false;

		$this->cars[] = $commodity;
		$i = count($this->cars);
		$g->updateTrain($this->id, 'Car_'.$i, $commodity);

		return true;
	}

	/**
	 * return array of cars and then empty internal cars array
	 */
	function unload()
	{
		global $g, $CONST;

		$out = array();

		foreach ($this->cars as $car) {
			if (!isset($out[$car])) $out[$car] = 1;
			else $out[$car]++;
		}

		$this->cars = array();
		for($i = 1; $i <= 8; $i++)
			$g->updateTrain($this->id, 'Car_'.$i, NULL);

		$this->loading_timeout = $CONST['TRAIN_LOADING_TIME'];
		$g->updateTrain($this->id, "loading_timeout", $this->loading_timeout);

		// Snap to end
		$this->progress = $this->direction > 0 ? 100 : 0;
		$g->updateTrain($this->id, "progress", $this->progress);

		return $out;
	}

	function waitLoading ($dtime) {
		global $g;
		$this->loading_timeout -= $dtime;
		$g->updateTrain($this->id, "loading_timeout", $this->loading_timeout);
	}

	/**
	 * Static
	 */
	static function getTrain ($id)
	{
		global $g, $debug;

		if(!isset(self::$_singleton[$id]))
		{
			$train = new self($id);
			$t = $g->getTrain($id);

			if(!is_array($t))
			{
				//echo 'Error';
				//print_r(debug_backtrace());
				return false;
			}

			$train->name = $t['name'];
			$train->loco_id = $t['loco_id'];
			$train->progress = $t['progress'];
			$train->route = $t['route'];
			$train->segment = min(max($t['segment'], 1), count($train->route) - 1);
			$train->direction = $t['direction'];
			$train->speed = $t['speed'];
			$train->loading_timeout = $t['loading_timeout'];

			if ($train->progress > 0 && $train->progress < 100) {
				$train->state = "RUNNING";
			}
			else if ($train->loading_timeout > 0) {
				$train->state = "LOADING";
			}
			else if (
				$train->loading_timeout == 0 && (
					($train->direction > 0 && $train->progress > 100) ||
					($train->direction < 0 && $train->progress < 0)
				)
			) {
				$train->state = "READY_TO_UNLOAD";
			}
			else if (
				$train->loading_timeout < 0 && (
					($train->direction > 0 && $train->progress >= 100) ||
					($train->direction < 0 && $train->progress <= 0)
				)
			) {
				$train->state = "READY_TO_LOAD";
			}
			else {
				$train->state = "RUNNING";
			}

			for($i = 1; $i <= 8; $i++)
			{
				if(strlen($t['Car_'.$i]))
					$train->cars[] = $t['Car_'.$i];
			}

			self::$_singleton[$id] = $train;
		}

		return self::$_singleton[$id];
	}

	static function getTrains () {
		global $g;
		return array_map(function ($t) { return self::getTrain($t['id']); }, $g->getTrains());
	}
}