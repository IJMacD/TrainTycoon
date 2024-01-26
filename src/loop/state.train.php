<?php

function updateTrainState () {
    global $g, $debug;

	//updateTrains()
	foreach(Train::getTrains() as $train){
		// Stopped Trains
		//if($train['speed'] == 0)
		//	$g->updateTrain($train['id'], 'speed', $CONST['locos'][$train['loco_id']]['topspeed']);
			//print_r($train);

		// Need to do routing
		if ($train->isReadyToNavigate()) {
			$target_station_id = $train->getNextDestinationID();
			$current_node = $train->getNextTrackNodeID();

			$routing_result = $g->findRoute($current_node, $target_station_id);

			if ($routing_result) {
				$train->moveToTrack($routing_result['track_id'], $routing_result['direction']);
				$train->start();
				// $g->insertLog("Routed train {$train->getName()} onto track " . $routing_result['track_id'] . " (Direction: " . $routing_result['direction'] . ")");
			}
			else {
				$train->stop();
				// $target_station = $g->getStation($target_station_id);
				// $g->insertLog("No route found for {$train->getName()} to " . $target_station['name']);
			}
		}

		// Train's Reached Destination
		else if ($train->isReadyToUnload())
		{
			$town_id = $train->getTown();

			$debug->log("{$train->getName()} arrived at " . $g->getTown($town_id)['name']);

			//unload
			$unloaded_cars = $train->unload();
			foreach($unloaded_cars as $commodity => $count)
			{
				$profit = $g->updateCommodities($town_id, $commodity, $count);
				$wealth = $g->getData('wealth');
				$g->setData('wealth', $wealth + $profit);

				$g->insertLog("Sold {$count} x {$commodity} for ".round($profit, 2)." at " . $g->getTown($town_id)['name']);
			}
		}

		else if ($train->isReadyToLoad())
		{

			$town_id = $train->getTown();

			// Choose next destination
			$train->setNextDestination();

			//load
			$next_town = $train->getTown();
			$debug->log("{$train->getName()} @ " . $g->getTown($town_id)['name'] . ' loading for ' . $g->getTown($next_town)['name']);
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

				$g->insertLog("Bought {$loaded} x ".$commodity_to_load['name']." for ".round(-$cost, 2)." at " . $g->getTown($town_id)['name']);
			}

			// $g->break();
		}
	}
}