<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "constants.php";
require_once "game.php";
require_once "debug.php";
require_once "lang.php";
require_once "classes/train.class.php";
require_once "classes/building.class.php";
require_once "classes/station.class.php";
require_once "loop/state.train.php";
require_once "loop/state.building.php";
require_once "loop/state.town.php";
require_once "loop/video.train.php";
require_once "loop/video.economy.php";
require_once "loop/video.edit.php";

$g = new Game;
$locos = $g->getLocos();
$trains = $g->getTrains();
$towns = $g->getTowns();

$video = isset($_GET['video']) ? $_GET['video'] : "default";

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
	global $g, $database, $CONST, $video;

	if(isset($_GET['state'])){
		if($_GET['state'] == "play") $g->State(STATE_NORM);
		else if($_GET['state'] == "pause") $g->State(STATE_PAUSED);
	}
	
	if(isset($_GET['action'])) {
		if ($_GET['action'] == "reset") {
			$database->queryMultiple("DELETE FROM data; 
				DELETE FROM availability;
				UPDATE buildings SET scale = 1, wealth = 0;
				UPDATE trains SET Car_1 = NULL, Car_2 = NULL, Car_3 = NULL, Car_4 = NULL, Car_5 = NULL, Car_6 = NULL, Car_7 = NULL, Car_8 = NULL;");
		} else if ($_GET['action'] === "new-station") {
			$video = "edit";

			$town_id = $_POST['new-station-town'];
			$name = $_POST['new-station-name'];

			$town = $database->getTowns($town_id);
			
			if (!$name) {
				$r = rand(0, count($CONST['station_suffixes']));
				$suffix = $r === 0 ? "" : " " . $CONST['station_suffixes'][$r - 1];
				$name = $town['Name'] . $suffix; 
			}

			$database->insertStation($town_id, $name);

			echo "<p>New station created in {$town['Name']} called $name</p>";
		} else if ($_GET['action'] === "new-building") {
			$video = "edit";

			$type = $_POST['new-building-type'];
			$town_id = $_POST['new-building-town'];
			$name = strlen($_POST['new-building-name']) ? $_POST['new-building-name'] : null;

			$database->insertBuilding($type, $town_id, $name);

			echo "<p>New $type created in {$g->getTowns($town_id)['Name']}</p>";
		} else if ($_GET['action'] === "new-train") {
			$video = "edit";

			$loco_id = $_POST['new-train-loco'];
			$name = strlen($_POST['new-train-name']) ? $_POST['new-train-name'] : null;
			$station1_id = $_POST['new-train-station1'];
			$station2_id = $_POST['new-train-station2'];

			$id = $database->insertTrain($loco_id, $name);

			if ($id) {
				$length = getStationDistance($station1_id, $station2_id);

				$database->addRouteStop($id, 0, $station1_id);
				$database->addRouteStop($id, 1, $station2_id, $length);

				echo "<p>New train created pulled by {$CONST['locos'][$loco_id]['name']}</p>";
			}
		} else if ($_GET['action'] === "route-add") {
			$video = "edit";

			$train_id = $_POST['route-add-train'];
			$station_id = $_POST['route-add-station'];
			
			$train = Train::getTrain($train_id);
			$route = $train->getRoute();
			$i = count($route);

			$length = getStationDistance($route[$i-1]['station_id'], $station_id);

			$database->addRouteStop($train->id, $i, $station_id, $length);

			echo "<p>Added new stop at ".Station::getStation($station_id)->getName()." to train " . $train->getName()."</p>";
		}
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
	global $video, $g, $CONST, $lang, $database;

	if ($video === "edit") {
		updateEditVideo();

		return;
	}

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
function getStationDistance ($s1_id, $s2_id) {
	$s1 = Station::getStation($s1_id);
	$s2 = Station::getStation($s2_id);

	list ($lat1, $lon1) = $s1->getLatLon();
	list ($lat2, $lon2) = $s2->getLatLon();
	return dist($lat1,$lon1,$lat2,$lon2) / 1e5;
}
function dist ($lat1, $lon1, $lat2, $lon2) {
    $R = 6371e3; // Mean Earth radius in metres
    $φ1 = toRadians($lat1);
    $φ2 = toRadians($lat2);
    $Δφ = toRadians($lat2-$lat1);
    $Δλ = toRadians($lon2-$lon1);

    $a =   sin($Δφ/2) * sin($Δφ/2) +
                cos($φ1) * cos($φ2) *
                sin($Δλ/2) * sin($Δλ/2);

    $c = 2 * atan2(sqrt($a), sqrt(1-$a));

    return $R * $c;

}
function toRadians ($deg) {
    return $deg * (pi()/180);
}