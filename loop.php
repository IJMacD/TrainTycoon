<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "constants.php";
require_once "game.php";
require_once "debug.php";
require_once "lang.php";
require_once "classes/train.class.php";
require_once "classes/building.class.php";
require_once "loop/state.train.php";
require_once "loop/state.building.php";
require_once "loop/state.town.php";
require_once "loop/video.train.php";
require_once "loop/video.economy.php";

$g = new Game;
$locos = $g->getLocos();
$trains = $g->getTrains();
$towns = $g->getTowns();
$stations = $g->getStations();

loop();

function loop(){
	if(isset($_GET['out']) && $_GET['out'] == 'json')
		outputJSON();
	else
	{
		updateInput();
		updatePhysics();
		updateState();
		//updateScore();
		updateVideo();
	}
}

// Read Paramaters from query string
function updateInput(){
	global $g;
	if(isset($_GET['state'])){
		if($_GET['state'] == "play") $g->State(STATE_NORM);
		else if($_GET['state'] == "pause") $g->State(STATE_PAUSED);
	}
	
	$g->init();
}

// Check state
function updateState(){
	global $g;

	// Only update real loops
	// e.g. ignore second viewer updates
	if ($g->delta > 0) {
		if($g->State() != STATE_PAUSED){
			$simstamp = $g->getData('simstamp');
			$simstamp += $g->dsimtime * 22896000; // Seconds in year
			//echo $simstamp;
			$g->setData('simstamp', $simstamp);
			//$g->setData('date', date('Y-m-d', $simstamp));
		}
		
		updateTrainState();
		
		updateBuildingState();

		updateTownState();
	}
}

// Update positions
function updatePhysics(){
	global $g, $CONST;
	if($g->State() != STATE_PAUSED){
		$delta = $g->delta / SPEED_SCALE * $CONST['game_speeds'][$g->State()];
		foreach($g->getTrains() as $train){
			if ($train->isLoading()) $train->waitLoading($g->dsimtime);
			else if ($train->isRunning()) $train->move($delta);
		}
	}
}

// Update Transactions
function updateScore(){
	global $g;
	foreach($g->getBuildings() as $b){
		//$surplus[$b['town']][$b['commodity']] += $buildings[$b['type']];
	}
}

// Output XML
function updateVideo(){
	global $g, $CONST, $lang, $database;
	if($g->State() == STATE_PAUSED) echo '<h1 style="color: red;">'.$lang['en']['paused'].'</h1>';
	echo "<p>Date: ".date("Y-m-d", $g->getData('simstamp')) . "</p>";
	echo "<p>Wealth: ". sprintf('$%.2f', $g->getData('wealth')) . "</p>";
	
	updateTrainVideo();

	updateEconomyVideo();

	if ($g->hasBreak)
	{
		echo '<script>stopLoop();</script>';
	}
}
function outputJSON()
{
	global $g, $CONST, $lang;
	
	$result = array();
	
	$result['gameState'] = $g->getData('gameState');
	//$result['gameStatus'] = $lang['en']['paused'];
	
	$result['date'] = date("Y-m-d", $g->getData('simstamp'));
	$result['simstamp'] = $g->getData('simstamp');
	
	$result['trains'] = array();
	
	foreach($g->getTrains() as $_train)
	{
		/*
		echo '<div class="town_list" style="float:right;">';
		for($i = 0; $i < count($train['route']); $i++){
			echo $train['town_ids'][$i]." ".$train['route'][$i]."<br>";
		}
		echo '</div>';
		*/
		$train = array();
		
		$train['id'] = $_train->id;
		$train['name'] = $_train->getName();
		
		$train['loco'] = array();
		$train['loco']['id'] = $_train->getLocoID();
		$train['loco']['type'] = '';
		$train['loco']['image'] = $CONST['locos'][$_train->getLocoID()]['image'];
		
		$train['cars'] = array();

		foreach($_train->getCars() as $_car){
			$car = array();
			
			$car['type'] = $_car;
			$car['image'] = $CONST['commodities'][$_car]['car_image'];
			
			$train['cars'][] = $car;
		}
		
		$train['speed'] = $_train->getSpeed();
		$train['progress'] = $_train->getProgress();
		$train['station'] = array();
		$train['station']['id'] = 0;
		$train['station']['town'] = 0;
		$train['station']['name'] = $_train->getNextStation();
		
		$result['trains'][] = $train;
	}
	
	$result['towns'] = array();
	
	foreach($g->getTowns() as $_town)
	{
		$town = array();
		
		$town['id'] = $_town['id'];
		$town['name'] = $_town['Name'];
		
		$town['commodities'] = array();
		
		foreach($g->getCommodities($_town['id']) as $commodity)
		{
			$town['commodities'][$commodity['name']] = $commodity;
		}
		
		$result['towns'][$town['name']] = $town;
	}
	
	echo json_encode($result);
}

function m ($value) {
	return sprintf("$%.02f", $value);
}