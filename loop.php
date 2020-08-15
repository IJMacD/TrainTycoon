<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "constants.php";
require_once "game.php";
require_once "debug.php";
require_once "lang.php";
require_once "classes/train.class.php";
require_once "classes/building.class.php";

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
	global $database, $g, $debug, $CONST;
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
		if ($train->isReadyToUnload())
		{
			$town_id = $train->getTown();

			$debug->log("{$train->getName()} arrived at " . $g->getTowns($town_id)['Name']);

			//unload
			$unloaded_cars = $train->unload();
			foreach($unloaded_cars as $commodity => $count)
			{
				$profit = $g->updateCommodities($town_id, $commodity, $count);
				$wealth = $g->getData('wealth');
				$g->setData('wealth', $wealth + $profit);
			}
		}

		if ($train->isReadyToLoad()) 
		{

			$town_id = $train->getTown();
			
			//turnaround
			$train->moveToNextStation();
			
			//load
			$next_town = $train->getTown();
			$debug->log("{$train->getName()} @ " . $g->getTowns($town_id)['Name'] . ' loading for ' . $g->getTowns($next_town)['Name']);
			$full = false;
			while (!$full) {
				$commodities = $g->getCommodities($town_id);
				$biggest_price_difference = 0;
				$best_commodity = -1;
				$debug->log("Looking for best deals");
				$MIN_PROFIT = 0.5;
				foreach($commodities as $k => $commodity)
				{
					if ($commodity['surplus'] >= 1) {
						$dest_commodity = $g->getCommodities($next_town, $commodity['name']);

						$debug->log('['.$commodity['name']."] Current: " . sprintf("$%.4f", $commodity['price']) . " Dest: ". sprintf("$%.4f", $dest_commodity['price']) . " Difference: " . sprintf("$%.4f", $dest_commodity['price'] - $commodity['price']) . " Available: " . sprintf("%.02f", $commodity['surplus']));

						$price_difference = $dest_commodity['price'] - $commodity['price'];

						if($price_difference - $MIN_PROFIT > $biggest_price_difference && $commodity['surplus'] >= 1){
							$best_commodity = $k;
							$biggest_price_difference = $price_difference;
						}
					}
				}

				// We only want to load it if price is favourable
				if($biggest_price_difference <= 0)
				{
					$debug->log("Nothing available with profit more than ". m($MIN_PROFIT));
					break;
				}

				$commodity_to_load = $commodities[$best_commodity];
				$ctl = $commodity_to_load;

				$debug->log('Biggest Difference: ['.$ctl['name'].'] $'.$biggest_price_difference);

				$surplus = $commodity_to_load['surplus'];
				$debug->log('Surplus: '.$surplus);
				$loaded = 0;
				while($loaded + 1 <= $commodity_to_load['surplus'])
				{
					if ($train->load($commodity_to_load['name'])) $loaded++;
					else {
						$full = true;
						break;
					}
				}
				
				$debug->log('Loaded '.$loaded.' '.$commodity_to_load['name']);
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
		foreach(Building::getBuildings() as $building)
		{
			$town = $building->getTown();
			$has_all_consumables = true;

			$rates = $building->getProductionRates();

			$total_cost = 0;
			$total_revenue = 0;
			
			foreach($rates as $rate)
			{
				// check all negative rates
				//		if it is producing it is fine
				// if it is consuming check there is stock in the town to accomodate
				$c = $g->getCommodities($town['id'], $rate['commodity']);
				
				$d = $g->dsimtime * $building->getScale();

				$q_demand = $d * $rate['demands'];
				$q_supply = $d * $rate['supplies'];

				$total_cost += $q_demand * $c['price'];
				$total_revenue += $q_supply * $c['price'];
				
				if($rate['demands'] > 0 && $c['surplus'] < $q_demand) {
					$has_all_consumables = false;
					$debug->log("Not enough {$rate['commodity']} available for ". $building->getName() . " in " . $town['Name'], 2);
					// $debug->log(print_r(array_map(function ($r) use ($g) { return $r['demands'] > 0 ? "Needs {$r['demands']} x {$r['commodity']} Available: {$g->getCommodities($town['id'], $rate['commodity'])['surplus']}" : "Supplies {$r['commodity']}"; }, $rates),true),3);
				}
			}

			$profit = $total_revenue - $total_cost;

			if($has_all_consumables && $profit > 0)
			{
				foreach($rates as $rate)
				{
					$delta = $rate['supplies'] > 0 ? $rate['supplies'] : -$rate['demands'];
					$g->updateCommodities($town['id'], $rate['commodity'], ($g->dsimtime * $delta * $building->getScale()));
				}

				$building->addWealth($profit);

				// Adjusting scale proportional to profit
				$building->setScale($profit * 2 + 1);
			}
			else if ($profit <= 0) {
				$debug->log($building->getName() . " in " . $town['Name'] ." not operating because there's no profit in it.");
				$debug->log("Cost $total_cost Revenue $total_revenue Profit $profit", 2);

				// Adjusting scale down
				$building->setScale($building->getScale() * (1 - $g->dsimtime));
			}
		}
	}

	// update population based consumption
	foreach ($g->getTowns() as $town) {
		$pop = $town['population'];
		$kpop = $pop / 1e6;
		
		foreach ($database->getProduction('population') as $rate) {
			$c = $g->getCommodities($town['id'], $rate['commodity']);
			$delta = $rate['supplies'] > 0 ? $rate['supplies'] : -$rate['demands'];
			$quantity = $g->dsimtime * $delta * $kpop;
			$debug->log("Trying to consume/produce $quantity of {$rate['commodity']} at {$town['Name']}", 3);

			if(-$quantity <= $c['surplus']) {
				$g->updateCommodities($town['id'], $rate['commodity'], $quantity);
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

		if($train->isAtStation())
		{
			if ($train->getSegment() == 0)
				echo '<br>'.$lang['en']['stopped'];
			// else if ($train->isLoading())
			// 	echo '<br>'.$lang['en']['stopped_at'].' '.$train->getNextStation() . " (Loading Time: " . $train->getLoadingTime() . ")";
			else
				echo '<br>'.$lang['en']['stopped_at'].' '.$train->getNextStation() . " (Loading Time: " . $train->getLoadingTime() . ")";
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
		echo '<br><img src="images/progress.gif" height="1" width="'.min($train->getProgress(),100).'%">';
		echo '</div>';
	}
	
	if(count($g->getTrains()) < 1) $lang['en']['no_trains'];

	$show = isset($_GET['view']) ? $_GET['view'] : "towns";
	$showOptions = ["Towns","Commodities","Demand"]; 

	echo '<p>';
	foreach ($showOptions as $o) {
		$t = strtolower($o);

		if ($show == $t) echo $o . ' ';
		else {
			echo '<a href="#view='.$t.'">'.$o.'</a> ';
		}
	}
	echo '</p>';

	echo '<div class="economy-list">';
	switch ($show) {
		case "towns":
			foreach($g->getTowns() as $town){
				echo '<div id="town_'.$town['id'].'" class="town_list"><b>'.$town['Name'].'</b><br>';
				echo '<table><tr><th>Name</th><th>Quantity</th><th>Price</th></tr>';
				foreach($g->getCommodities($town['id']) as $commodity){
					$h = $commodity['surplus'] <= 0 ? 'class="hidden-detail"' : '';
					echo "<tr $h><td>".$commodity['name'].'</td><td>'.sprintf('%.3f', $commodity['surplus']).'</td><td>$'.sprintf('%.2f', $commodity['price']).'</td></tr>';
				}
				echo '</table></div>';
			}
		break;
		case "commodities":
			foreach ($database->getCommodityTypes() as $commodity) {
				echo '<div id="commodity_'.$commodity.'" class="town_list"><b>'.ucfirst($commodity).'</b><br>';
				echo '<table><tr><th>Town</th><th>Surplus</th><th>Price</th></tr>';
				foreach($database->getCommodityList($commodity) as $c){
					$h = $c['available'] <= 0 ? 'class="hidden-detail"' : '';
					echo "<tr $h><td>".$g->getTowns($c['town_id'])['Name'].'</td><td>'.sprintf('%.3f', $c['available']).'</td><td>$'.sprintf('%.2f', $c['price']).'</td></tr>';
				}
				echo '</table></div>';
			}
		break;
		case "demand":
			foreach($g->getTowns() as $town){
				echo '<div id="town_'.$town['id'].'" class="town_list"><b>'.$town['Name'].'</b><br>';
				echo '<table><tr><th>Name</th><th>Supply</th><th>Demand</th><th>Available</th></tr>';
				foreach($database->getCommoditySupplyDemand($town['id']) as $commodity){
					$h = $commodity['available'] <= 0 ? 'class="hidden-detail"' : '';
					echo "<tr $h><td>".$commodity['type'].'</td><td>'.sprintf('%.3f', $commodity['supply']).'</td><td>'.sprintf('%.3f', $commodity['demand']).'</td><td>'.sprintf('%.3f', $commodity['available']).'</td></tr>';
				}
				echo '</table></div>';
			}
		break;
	}
	echo '</div>';

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