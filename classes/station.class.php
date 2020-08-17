<?php

require_once "game.php";

class Station
{
	var $id;
	
	private $town_id;
	private $name;
	private $lat;
	private $lon;

	static private $_singleton = [];

	function __construct ($id) {
		$this->id = $id;
	}

	function getName () {
		return $this->name;
	}
	
	function getTown()
	{
		global $g;

		return $g->getTown($this->town_id);
	}

	function getLat () {
		return $this->lat;
	}

	function getLon () {
		return $this->lon;
	}

	function getLatLon () {
		return [$this->lat, $this->lon];
	}
	
	static function getStation ($id)
	{
		global $g;

		if(!isset(self::$_singleton[$id]))
		{
			self::$_singleton[$id] = new self($id);

			$station = $g->getStation($id);

			self::$_singleton[$id]->name = $station['name'];
			self::$_singleton[$id]->town_id = $station['town_id'];
			self::$_singleton[$id]->lat = $station['lat'];
			self::$_singleton[$id]->lon = $station['lon'];
		}
		
		return self::$_singleton[$id];
	}
	
	static function getStations ($ids=null)
	{
		if (!$ids) {
			global $g;
			$ids = array_map(function ($s) { return $s['id']; }, $g->getStations());
		}

		return array_map(function ($id) { return self::getStation($id); }, $ids);
	}
}
