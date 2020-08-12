<?php
error_reporting(E_ALL);
ini_set("display_errors", 1); 
require_once("constants.php");
require_once("database.mysqli.php");
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

			$database->log("{$train->getName()} arrived at " . $g->getTowns($town_id)['Name']);

			//unload
			$unloaded_cars = $train->unload();
			foreach($unloaded_cars as $commodity => $count)
			{
				$profit = $g->updateCommodities($town_id, $commodity, $count);
				$wealth = $g->getData('wealth');
				$g->setData('wealth', $wealth + $profit);
			}
			
			//turnaround
			$train->moveToNextStation();
			
			//load
			$next_town = $train->getTown();
			$database->log("{$train->getName()} @ " . $g->getTowns($town_id)['Name'] . ' loading for ' . $g->getTowns($next_town)['Name']);
			$full = false;
			while (!$full) {
				$commodities = $g->getCommodities($town_id);
				$biggest_price_difference = 0;
				$best_commodity = -1;
				$database->log("Looking for best deals");
				$MIN_PROFIT = 0.05;
				foreach($commodities as $k => $commodity)
				{
					$dest_commodity = $g->getCommodities($next_town, $commodity['name']);

					$database->log('['.$commodity['name']."] Current: " . sprintf("$%.4f", $commodity['price']) . " Dest: ". sprintf("$%.4f", $dest_commodity['price']) . " Difference: " . sprintf("$%.4f", $dest_commodity['price'] - $commodity['price']) . " Available: " . sprintf("%.02f", $commodity['surplus']));

					$price_difference = $dest_commodity['price'] - $commodity['price'];

					if($price_difference - $MIN_PROFIT > $biggest_price_difference && $commodity['surplus'] >= 1){
						$best_commodity = $k;
						$biggest_price_difference = $price_difference;
					}
				}

				// We only want to load it if price is favourable
				if($biggest_price_difference <= 0)
				{
					$database->log("Nothing available with profit more than ". m($MIN_PROFIT));
					break;
				}

				$commodity_to_load = $commodities[$best_commodity];
				$ctl = $commodity_to_load;

				$database->log('Biggest Difference: ['.$ctl['name'].'] $'.$biggest_price_difference);

				$surplus = $commodity_to_load['surplus'];
				$database->log('Surplus: '.$surplus);
				$loaded = 0;
				while($loaded + 1 <= $commodity_to_load['surplus'])
				{
					if ($train->load($commodity_to_load['name'])) $loaded++;
					else {
						$full = true;
						break;
					}
				}
				
				$database->log('Loaded '.$loaded.' '.$commodity_to_load['name']);
				$cost = $g->updateCommodities($town_id, $commodity_to_load['name'], -$loaded);

				$wealth = $g->getData('wealth');
				$g->setData('wealth', $wealth + $cost);
			}

			// $g->break();
		}
	}
    
	//updateBuildings()
	if($g->State() != STATE_PAUSED)
	{
		foreach($g->getBuildings() as $building)
		{
			$town = $g->getTowns($building['town_id']);
			$has_all_consumables = true;

			foreach($CONST['buildings'][$building['type']] as $commodity => $rate)
			{
				// check all negative rates
				//		if it is producing it is fine
				// if it is consuming check there is stock in the town to accomodate
				$c = $g->getCommodities($building['town_id'], $commodity);
				$needs = $g->dsimtime * -$rate;
				if($rate < 0 && $c['surplus'] < $needs)
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

	// update population based consumption
	foreach ($g->getTowns() as $town) {
		$pop = $town['population'];
		$lpop = log($pop);
		
		foreach ($CONST['consumers'] as $commodity => $rate) {
			$c = $g->getCommodities($town['id'], $commodity);
			$quantity = $g->dsimtime * $rate * $lpop * 0.1;
			$database->log("Trying to consume $quantity of $commodity at {$town['Name']}", 3);

			if($quantity <= $c['surplus']) {
				$g->updateCommodities($town['id'], $commodity, -$quantity);
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
	echo "<p>Date: ".date("Y-m-d", $g->getData('simstamp')) . "</p>";
	echo "<p>Wealth: ". sprintf('$%.2f', $g->getData('wealth')) . "</p>";
	echo '<h1>'.$lang['en']['trains'].'</h1>';
	foreach($g->getTrains() as $train){
		/*
		echo '<div class="town_list" style="float:right;">';
		for($i = 0; $i < count($train['route']); $i++){
			echo $train['town_ids'][$i]." ".$train['route'][$i]."<br>";
		}
		echo '</div>';
		*/
		$stations = $train->getStations();
		$i = $train->getNextIndex();
		$stations[$i] = '<b>' . $stations[$i] . '</b>';
		echo '<div class="train_list" id="train_'.$train->id.'">'
			. '<b>' . $train->getName() . '</b> '
			. '('.implode(", ", $stations).') '
			. '<span class="direction-indicator">'.($train->getDirection() == 1 ? "UP" : "DOWN").'</span>'
			. '<br>'
			. '<img src="'.$CONST['locos'][$train->getLocoID()]['image'].'">';
		foreach($train->getCars() as $car){
			echo '<img src="'.$CONST['commodities'][$car]['car_image'].'" title="'.$car.'">';
		}
		if($train->getProgress() == 0)
		{
			if($train->getSegment() == 0)
				echo '<br>'.$lang['en']['stopped'];
			else
				echo '<br>'.$lang['en']['stopped_at'].' '.$train->getNextStation();;
		}
		else
		{
			echo '<br>'.$lang['en']['on_way_to'].' '.$train->getNextStation();

			$cars = $train->getCars();
			if (count($cars)) {
				$value = 0;
				$dest = $train->getTown();
				foreach ($cars as $car) {
					$c = $g->getCommodities($dest, $car);
					$value += $c['price'];
				}
				echo " (Value: " . sprintf('$%.2f', $value) . ")";
			}
		}
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