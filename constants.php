<?php
define("STATE_NOGAME", -1);
define("STATE_PAUSED", 0);
define("STATE_VSLOW", 1);
define("STATE_SLOW", 2);
define("STATE_NORM", 3);
define("STATE_FAST", 4);
define("STATE_VFAST", 5);

define("MIN_DELTA", 0.5);
define("MAX_DELTA", 10);
define("TIMEOUT", 300);
define("SPEED_SCALE", 120); // Real Seconds per Sim Hour
define("TIME_SCALE", 600); // Real Seconds per Sim Year

define("DB_HOST", "localhost");
define("DB_USER", "railtycoon");
define("DB_PASS", "6iJ27aJA4AQ5");
define("DB_NAME", "train_tycoon");

$CONST = array();

$CONST['locos'] = array(
	1 => array("name" => "Flying Scotsman", "number" => "4472", "configuration" => "4-6-2", "topspeed" => 100, "reliability" => 0.95, "price" => 1000, "start_year" => 1923, "image" => "images/10194-1.gif"),
	2 => array("name" => "Mallard", "number" => "4468", "configuration" => "4-6-2", "topspeed" => 100, "reliability" => 0.85, "price" => 800, "start_year" => 1938, "image" => "images/kt203.gif")
	);
$CONST['commodities'] = array(
	"alcohol" => array("price" => 5, "car_image" => "images/4537.gif"),
	"clothing" => array("price" => 3, "car_image" => "images/4564c.gif"),
	"coal" => array("price" => 3, "car_image" => "images/10183c.gif"),
	"cotton" => array("price" => 1, "car_image" => "images/4512c.gif"),
	"fabric" => array("price" => 2, "car_image" => "images/a_10017_1.gif"),
	"logs" => array("price" => 1, "car_image" => "images/a_10013_1.gif"),
	"passengers" => array("price" => 4, "car_image" => "images/10194b.gif"),
	"wool" => array("price" => 1, "car_image" => "images/4512c.gif"),
	"timber" => array("price" => 1, "car_image" => "images/4543a.gif"),
	"lumber" => array("price" => 1, "car_image" => "images/4543a.gif"),
	"furniture" => array("price" => 1, "car_image" => "images/4563d.gif"),
	"grain" => array("price" => 1, "car_image" => "images/3225c.gif"),
	"mail" => array("price" => 1, "car_image" => "images/4758b.gif"),
	"livestock" => array("price" => 1, "car_image" => "images/60052c.gif"),
	"meat" => array("price" => 1, "car_image" => "images/4563d.gif"),
	);
$CONST['station_suffixes'] = array("Central", "Crossing", "Gate", "Junction", "Picadilly", "Priory", "East", "West", "North", "South", "Gap", "New Street", "High Street", "Halt");

$CONST['game_speeds'][STATE_PAUSED] = 0;
$CONST['game_speeds'][STATE_VSLOW] = 0.2;
$CONST['game_speeds'][STATE_SLOW] = 0.5;
$CONST['game_speeds'][STATE_NORM] = 1;
$CONST['game_speeds'][STATE_FAST] = 2;
$CONST['game_speeds'][STATE_VFAST] = 5;

$CONST['defaults'] = array("date" => "1930-01-01", "simstamp" => strtotime("1930-01-01"), "gameState" => STATE_PAUSED, "lasttime" => 0, "wealth" => 1000);

$CONST['TRAIN_LOADING_TIME'] = 10 / TIME_SCALE; // Abt 10 seconds