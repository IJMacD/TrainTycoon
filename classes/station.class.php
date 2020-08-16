<?php

require_once "database.php";

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
		global $database;

		return $database->getTown($this->town_id);
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
		global $database;

		if(!isset(self::$_singleton[$id]))
		{
			self::$_singleton[$id] = new self($id);

			$station = $database->getStation($id);

			self::$_singleton[$id]->name = $station['Name'];
			self::$_singleton[$id]->town_id = $station['town_id'];
			self::$_singleton[$id]->lat = $station['lat'];
			self::$_singleton[$id]->lon = $station['lon'];
		}
		
		return self::$_singleton[$id];
	}
	
	static function getStations ($ids=null)
	{
		if (!$ids) {
			global $database;
			$ids = array_map(function ($s) { return $s['id']; }, $database->getStations());
		}

		return array_map(function ($id) { return self::getStation($id); }, $ids);
	}
}
