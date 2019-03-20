<?php
error_reporting(E_ALL);
ini_set("display_errors", 1); 
require_once("constants.php");
require_once("database.php");
require_once("game.php");
require_once("lang.php");
require_once("classes/train.class.php");

$g = new Game;
$locos = $g->getLocos();
$trains = $g->getTrains();
$buildings = $g->getBuildings();
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
	global $database, $g, $CONST;
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
		// (Train may have reached multiple stations in the delta)
		while($train->isAtStation())
		{
			$town_id = $train->getTown();
			
			//unload
			$unloaded_cars = $train->unload();
			foreach($unloaded_cars as $car)
			{
				$g->updateCommodities($town_id, $car, +1);
			}
			
			//load
			$next_town = $train->getNextTown();
			$commodities = $g->getCommodities($town_id);
			$success = true;
			$tries = 4;
			while($tries>0){
				$biggest_price_difference = 0;
				$best_commodity = -1;
				foreach($commodities as $k => $commodity)
				{
					$dest_commodity = $g->getCommodities($next_town, $commodity['name']);

					$database->log('['.$commodity['name'].'] Dest Price: '. $dest_commodity['price'].' Price: '.$commodity['price']);

					$price_difference = $dest_commodity['price'] - $commodity['price'];

					if($price_difference > $biggest_price_difference &&
							$commodity['surplus'] > 1){
						$best_commodity = $k;
						$biggest_price_difference = $price_difference;
					}
				}

				// We only want to load it if price is favourable
				if($biggest_price_difference > 0 && $best_commodity >= 0)
				{
					$commodity_to_load = $commodities[$best_commodity];
					$ctl = $commodity_to_load;

					$database->log('Biggest Difference: ['.$ctl['name'].'] $'.$biggest_price_difference);

					$surplus = $commodity_to_load['surplus'];
					$database->log('Surplus: '.$surplus);
					while($surplus > 1 && $success)
					{
						$success = $train->load($commodity_to_load['name']);
						$surplus--;
					}
					unset($commodities[$k]);
					$loaded = $commodity_to_load['surplus'] - $surplus;
					$database->log('Loaded '.$loaded.' '.$commodity_to_load['name']);
					$g->updateCommodities($town_id, $commodity_to_load['name'], -$loaded);
				}
				else {
					$success = false;
				}
				if(!$success)
				{
					$tries = 0;
				}
				// Supposed to loop through commodities finding highest profit margin each time
				// until all cars are full.
				// TODO: does not work - the line below is only to break the loop while the code is broken
				$tries--;
			}
			
			//turnaround
			$train->moveToNextStation();
		}
	}
    
	//updateBuildings()
	if($g->State() != STATE_PAUSED)
	{
		foreach($g->getBuildings() as $building)
		{
			$town = $g->getTowns($building['town_id']);
			$town_commodities = $g->getCommodities($building['town_id']);
			$has_all_consumables = true;
			foreach($CONST['buildings'][$building['type']] as $commodity => $rate)
			{
				// Town is allowed to go one unit of 'rate' into debt
				// check all negative rates
				//		if it is producing it is fine
				// if it is consuming check there is stock in the town to accomodate
				$c = $g->getCommodities($building['town_id'], $commodity);
				if($rate < 0 && $c['surplus'] < 0)
				{
					$has_all_consumables = false;
					break;
				}
			}
			if($has_all_consumables)
			{
				foreach($CONST['buildings'][$building['type']] as $commodity => $rate)
				{
					$g->updateCommodities($building['town_id'], $commodity, ($g->dsimtime * $rate));
				}
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
			$train->move($delta);
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
	if($g->State() == STATE_PAUSED) echo '<h1 style="color: red;">'.$lang['en']['paused'].'</h1>';
	echo "Date: ".date("Y-m-d", $g->getData('simstamp'));
	echo '<h1>'.$lang['en']['trains'].'</h1>';
	foreach($g->getTrains() as $train){
		/*
		echo '<div class="town_list" style="float:right;">';
		for($i = 0; $i < count($train['route']); $i++){
			echo $train['town_ids'][$i]." ".$train['route'][$i]."<br>";
		}
		echo '</div>';
		*/
		echo '<div class="train_list" id="train_'.$train->id.'">'.$train->getName().'</b><br><img src="'.$CONST['locos'][$train->getLocoID()]['image'].'">';
		foreach($train->getCars() as $car){
			echo '<img src="'.$CONST['commodities'][$car]['car_image'].'" title="'.$car.'">';
		}
		if($train->getProgress() == 0)
		{
			if($train->getSegment() == 0)
				echo '<br>'.$lang['en']['stopped'];
			else
				echo '<br>'.$lang['en']['stopped_at'].' '.$train->getStation();;
		}
		else
			echo '<br>'.$lang['en']['on_way_to'].' '.$train->getStation();
		echo '<br><img src="images/progress.gif" height="1" width="'.$train->getProgress().'%">';
		echo '</div>';
	}
	foreach($g->getTowns() as $town){
		echo '<div id="town_'.$town['id'].'" class="town_list"><b>'.$town['Name'].'</b><br>';
		echo '<table><tr><th>Name</th><th>Quantity</th><th>Price</th></tr>';
		foreach($g->getCommodities($town['id']) as $commodity){
			echo '<tr><td>'.$commodity['name'].'</td><td>'.sprintf('%.3f', $commodity['surplus']).'</td><td>$'.sprintf('%.2f', $commodity['price']).'</td></tr>';
		}
		echo '</table></div>';
	}
	if(count($g->getTrains()) < 1) $lang['en']['no_trains'];
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
		$train['station']['name'] = $_train->getStation();
		
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
?>