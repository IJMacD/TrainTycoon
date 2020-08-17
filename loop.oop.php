<?php
error_reporting(E_ALL);
ini_set("display_errors", 1); 
require_once("constants.php");
require_once("database.php");
require_once("game.php");
require_once("lang.php");

$g = new DB;
$locos = $g->getLocos();
$trains = $g->getTrains();
$buildings = $g->getBuildings();
$towns = $g->getTowns();
$stations = $g->getStations();

loop();

function loop(){
	updateInput();
	updateState();
	updatePhysics();
	updateScore();
	updateVideo();
}

// Read Paramaters from query string
function updateInput(){
	global $g;
	if(isset($_GET['state'])){
		if($_GET['state'] == "play") $g->State(STATE_NORM);
		else if($_GET['state'] == "pause") $g->State(STATE_PAUSED);
	}
}

// Check state
function updateState(){
	global $g, $CONST;
	//echo $g->delta;
	if($g->delta > TIMEOUT){
		$g->State(STATE_PAUSED);
	}
	
	if($g->State() != STATE_PAUSED){
		$simstamp = $g->getData('simstamp');
		$simstamp += $g->dsimtime * 22896000; // Seconds in year
		//echo $simstamp;
		$g->setData('simstamp', $simstamp);
		//$g->setData('date', date('Y-m-d', $simstamp));
	}
	
	//updateTrains()
	foreach($g->getTrains() as $train){
		// Stopped Trains
		//if($train['speed'] == 0)
		//	$g->updateTrain($train['id'], 'speed', $CONST['locos'][$train['loco_id']]['topspeed']);
			//print_r($train);
		// Train's Reached Destination
		//while($train['progress'] >= 100){
		while($train->progress >= 100){
		//while($train->isAtStation()){
			//unload
			//unloadTrain($train);
			//function unloadTrain($train){
			for($i = 1; $i <= 8; $i++){
				//if($train['Car_'.$i] != ""){
					//$g->updateCommodities($train['town_ids'][$train['segment']], $train['Car_'.$i], +1);
					$commodity = $train->unload($i);
					$train->current_station->town->addCommodity($commodity);
					//$g->updateTrain($train['id'], 'Car_'.$i, "");
					//$train->setCar($i);
			}
			//}
			//load
			$i = 1;
			//foreach($g->getCommodities($train['town_ids'][$train['segment']]) as $commodity){
			$town = $train->current_station->town;
			foreach($town->getStock() as $stock){    /// !!! Needs work !!!
				$surplus = $stock['surplus'];
				while($surplus > 0 && $i <= 8){
					//$g->updateTrain($train['id'], 'Car_'.$i, $commodity['name']);
					$train->load($stock['commodity']);
					$i++;
					$surplus--;
				}
				//$g->updateCommodities($train['town_ids'][$train['segment']], $commodity['name'], $surplus - $commodity['surplus']);
				$town->setCommodity($stock['commodity'], $surplus - $stock['surplus']);
				if($i > 8) break;
			}
			//turnaround
			$train['progress'] -= 100;
			$g->updateTrain($train['id'], 'progress', $train['progress']);
			$g->updateTrain($train['id'], 'segment', ($train['segment'] + 1)%count($train['route']));
		}
	}
	
	//updateBuildings()
	if($g->State() != STATE_PAUSED){
	foreach($g->getBuildings() as $building){
		$town = $g->getTown($building['town_id']);
		foreach($CONST['buildings'][$building['type']] as $commodity => $rate){
			$g->updateCommodities($building['town_id'], $commodity, ($g->dsimtime * $rate));
		}
	}
	}
}

// Update positions
function updatePhysics(){
	global $g, $CONST;
	if($g->State() != STATE_PAUSED){
		$delta = $g->delta / SPEED_SCALE * $CONST['game_speeds'][$g->State()];
		foreach($g->getTrains() as $train){
			if($train['speed'] > 0) $g->updateTrain($train['id'], 'progress', $train['progress'] + $train['speed'] * $delta);
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
	global $g, $CONST, $lang;
	if($g->getData('gameState') == STATE_PAUSED) echo '<h1 style="color: red;">'.$lang['en']['paused'].'</h1>';
	echo "Date: ".date("Y-m-d", $g->getData('simstamp'));
	echo '<h1>'.$lang['en']['trains'].'</h1>';
	foreach($g->getTrains() as $train){
		$name = ($train['name'] != "") ? $train['name'] : "Train ".$train['id'];
		echo '<div class="town_list" style="float:right;">';
		for($i = 0; $i < count($train['route']); $i++){
			echo $train['town_ids'][$i]." ".$train['route'][$i]."<br>";
		}
		echo '</div>';
		echo '<div class="train_list" id="train_'.$train['id'].'">'.$name.'</b><br><img src="'.$CONST['locos'][$train['loco_id']]['image'].'">';
		for($i = 1; $i <= 8; $i++){
			if(isset($train['Car_'.$i]) && $train['Car_'.$i] != "")
				echo '<img src="'.$CONST['commodities'][$train['Car_'.$i]]['car_image'].'" title="'.$train['Car_'.$i].'">';
		}
		if($train['progress'] == 0){
			if($train['segment'] == 0) echo '<br>'.$lang['en']['stopped'];
			else echo '<br>'.$lang['en']['stopped_at'].' '.$train['route'][$train['segment']-1];
		}else echo '<br>'.$lang['en']['on_way_to'].' '.$train['route'][$train['segment']];
		echo '<br><img src="images/progress.gif" height="1" width="'.$train['progress'].'%">';
		echo '</div>';
	}
	foreach($g->getTowns() as $town){
		echo '<div id="town_'.$town['id'].'" class="town_list"><b>'.$town['name'].'<b><br>';
		echo '<table><tr><th>Name</th><th>Surplus</th><th>Price</th></tr>';
		foreach($g->getCommodities($town['id']) as $commodity){
			echo '<tr><td>'.$commodity['name'].'</td><td>'.$commodity['surplus'].'</td><td>$'.sprintf('%.2f', $commodity['price']).'</td></tr>';
		}
		echo '</table></div>';
	}
	if(count($g->getTrains()) < 1) $lang['en']['no_trains'];
}
?>