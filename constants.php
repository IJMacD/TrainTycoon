<?php
define("STATE_PAUSED", 0);
define("STATE_VSLOW", 1);
define("STATE_SLOW", 2);
define("STATE_NORM", 3);
define("STATE_FAST", 4);
define("STATE_VFAST", 5);

define("TIMEOUT", 300);
define("SPEED_SCALE", 120); // Real Seconds per Sim Hour
define("TIME_SCALE", 600); // Real Seconds per Sim Year

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "railtycoon");

define("TABLE_data", "data");
define("TABLE_locos", "locos");
define("TABLE_trains", "trains");
define("TABLE_buildings", "buildings");
define("TABLE_towns", "towns");
define("TABLE_stations", "stations");
define("TABLE_commodities", "commodities");

$COSNT = array();

$CONST['buildings'] = array(	// Rate per year
	"sheep_farm" => array("wool" => 12),
	"cotton_mill" => array("wool" => -8, "fabric" => 4),
	"textiles" => array("fabric" => -4, "clothes" => 2.5),
	"retail" => array("clothes" => -2)
	);
$CONST['locos'] = array(
	1 => array("name" => "Flying Scotsman", "number" => "4472", "configuration" => "4-6-2", "topspeed" => 100, "reliability" => 0.95, "price" => 1000, "start_year" => 1923, "image" => "images/10194-1.gif"),
	2 => array("name" => "Mallard", "number" => "4468", "configuration" => "4-6-2", "topspeed" => 100, "reliability" => 0.85, "price" => 800, "start_year" => 1938, "image" => "images/kt203.gif")
	);
$CONST['commodities'] = array(
	"alcohol" => array("price" => 5, "car_image" => "images/a_10017_1.gif"),
	"clothes" => array("price" => 3, "car_image" => "images/10194b.gif"),
	"coal" => array("price" => 3, "car_image" => "images/10183c.gif"),
	"cotton" => array("price" => 1, "car_image" => "images/4512c.gif"),
	"fabric" => array("price" => 2, "car_image" => "images/a_10017_1.gif"),
	"logs" => array("price" => 1, "car_image" => "images/a_10013_1.gif"),
	"passengers" => array("price" => 4, "car_image" => "images/10194b.gif"),
	"wool" => array("price" => 1, "car_image" => "images/4512c.gif"),
	"wood" => array("price" => 1, "car_image" => "images/a_10013_1.gif")
	);
$CONST['towns'] = array(
	1 => array("name" => "London", "lat" => 51, "lon" => 1),
	2 => array("name" => "Birmingham", "lat" => 52, "lon" => -2),
	3 => array("name" => "Manchester", "lat" => 53, "lon" => -2)
	);
$CONST['station_suffixes'] = array("Central", "Crossing", "Gate", "Junction", "Picadilly", "Priory");

$CONST['game_speeds'][STATE_PAUSED] = 0;
$CONST['game_speeds'][STATE_VSLOW] = 0.2;
$CONST['game_speeds'][STATE_SLOW] = 0.5;
$CONST['game_speeds'][STATE_NORM] = 1;
$CONST['game_speeds'][STATE_FAST] = 2;
$CONST['game_speeds'][STATE_VFAST] = 5;

$CONST['defaults'] = array("date" => "1930-01-01", "simstamp" => strtotime("1930-01-01"), "gameState" => STATE_PAUSED, "lasttime" => 0);
?>