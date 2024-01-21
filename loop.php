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
require_once "loop/video.log.php";

session_start();

if(isset($_GET['action'])) {
	if ($_GET['action'] == "new-game") {
		$_SESSION['game_id'] = Game::newGame();
	}
}

if (isset($_SESSION['game_id'])) {
	$g = new Game($_SESSION['game_id']);
} else {
	echo '<h1>No Game</h1>';
	exit;
}

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
	global $g, $CONST, $video;

	if(isset($_GET['action'])) {
		if ($_GET['action'] == "play-game") $g->State(STATE_NORM);
		else if ($_GET['action'] == "pause-game") $g->State(STATE_PAUSED);
		else if ($_GET['action'] == "reset") $g->reset();
		else if ($_GET['action'] === "new-station") {
			$video = "edit";

			$town_id = $_POST['new-station-town'];
			$name = $_POST['new-station-name'];

			$town = $g->getTown($town_id);

			if (!$name) {
				$r = rand(0, count($CONST['station_suffixes']));
				$suffix = $r === 0 ? "" : " " . $CONST['station_suffixes'][$r - 1];
				$name = $town['name'] . $suffix;
			}

			$g->createStation($town_id, $name);

			$g->insertLog("New station created in {$town['name']} called $name");
		} else if ($_GET['action'] === "new-building") {
			$video = "edit";

			$type = $_POST['new-building-type'];
			$town_id = $_POST['new-building-town'];
			$name = strlen($_POST['new-building-name']) ? $_POST['new-building-name'] : null;

			$g->createBuilding($type, $town_id, $name);

			$g->insertLog("New $type created in {$g->getTown($town_id)['name']}");
		} else if ($_GET['action'] === "new-train") {
			$video = "edit";

			$loco_id = $_POST['new-train-loco'];
			$name = strlen($_POST['new-train-name']) ? $_POST['new-train-name'] : null;
			$station1_id = $_POST['new-train-station1'];
			$station2_id = $_POST['new-train-station2'];

			if ($g->createTrain($loco_id, $name, [$station1_id, $station2_id])) {
				$g->insertLog("New train created pulled by {$CONST['locos'][$loco_id]['name']}");
			} else {
				echo "<p>There was a problem creating the train</p>";
			}
		} else if ($_GET['action'] === "route-add") {
			$video = "edit";

			$train_id = $_POST['route-add-train'];
			$station_id = $_POST['route-add-station'];

			if ($g->addRouteStop($train_id, $station_id)) {

				$train = Train::getTrain($train_id);
				$station = Station::getStation($station_id);

				$g->insertLog("Added new stop at " . $station->getName() . " to train " . $train->getName());
			} else {
				echo "<p>Unable to add new stop</p>";
			}
		}
	}
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
		foreach(Train::getTrains() as $train){
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
	echo "<p>Cash: ". sprintf('$%.2f', $g->getData('wealth')) . "</p>";

	updateTrainVideo();

	updateEconomyVideo();

	updateLogVideo();
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

	foreach(Train::getTrains() as $_train)
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
		$town['name'] = $_town['name'];

		$town['commodities'] = array();

		foreach($g->getCommodities($_town['id']) as $commodity)
		{
			$town['commodities'][$commodity['name']] = $commodity;
		}

		$result['towns'][$town['name']] = $town;
	}

	echo json_encode($result);
}