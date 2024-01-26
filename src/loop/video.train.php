<?php

function updateTrainVideo () {
    global $g, $lang, $debug, $CONST;

    // echo '<h1>'.$lang['en']['trains'].'</h1>';
	foreach(Train::getTrains() as $train){
		/*
		echo '<div class="town_list" style="float:right;">';
		for($i = 0; $i < count($train['route']); $i++){
			echo $train['town_ids'][$i]." ".$train['route'][$i]."<br>";
		}
		echo '</div>';
		*/

		// $route_progress = $train->getProgress() * $train->getRouteDirection() * $train->getTrackDirection();

		// if ($route_progress < 0) {
		// 	$route_progress += 100;
		// }

		$image_style = "overflow: hidden;white-space: nowrap;";

		// if (!$train->isAtStation() && $train->isRunning()) {

		// 	if ($train->getRouteDirection() == 1) {
		// 		$image_style .= "padding-left: ".(100-min(max($route_progress,0),100))."%;";
		// 	}
		// 	else {
		// 		$image_style .= "padding-left: ".min(max($route_progress,0),100)."%;";
		// 	}
		// }

		if ($train->getRouteDirection() == 1) {
			$image_style .= "transform: scale(-1, 1);";
		}

		$station_names = array_map(function ($s) { return $s->getName(); }, $train->getStations());
		$i = $train->getNextDestinationIndex();
		$station_names[$i] = '<b>' . $station_names[$i] . '</b>';
		echo '<div class="train_list" id="train_'.$train->id.'">'
			. '<b>' . $train->getName() . '</b> '
			// . $train->state . ' '
			. '(Next Stop: '.implode(", ", $station_names).') '
			. '<span class="direction-indicator">'.($train->getRouteDirection() == 1 ? "UP" : "DOWN").'</span>';


		echo '<div style="'.$image_style.'">'
			. '<img src="'.$CONST['locos'][$train->getLocoID()]['image'].'">';

		foreach($train->getCars() as $car){
			echo '<img src="'.$CONST['commodities'][$car]['car_image'].'" title="'.$car.'">';
		}

		echo '</div>';

		// $current_track = $train->getTrack();
		// $from_station = $g->getStation($current_track['from_station_id']);
		// $to_station = $g->getStation($current_track['to_station_id']);
		// $direction = $train->getTrackDirection();

		// echo "[track " . $from_station['name'] . ($direction > 0 ? " => " : " <= ") . $to_station['name'] . " " . $train->getProgress() . "%]<br>\n";

		// echo '<pre>';
		// var_dump($train);
		// echo '</pre>';

		if($train->isAtStation())
		{
			// else if ($train->isLoading())
			// 	echo '<br>'.$lang['en']['stopped_at'].' '.$train->getNextStation() . " (Loading Time: " . $train->getLoadingTime() . ")";
			echo $lang['en']['stopped_at'].' '.$train->getNextDestination() . " (Loading Time: " . $train->getLoadingTime() . ")";
		}
		else if ($train->state === "READY_TO_NAVIGATE") {
			// $approx_location = $g->getStation($train->getNearestStationID());
			// echo "Waiting at a red signal near " . $approx_location['name'];
			echo "Waiting at a red signal";
		}
		else if ($train->isStopped()) {
			$station_id = $train->getNextTrackNodeID();
			$station = $g->getStation($station_id);
			echo "Stopped near " . $station['name'] . " (No route to " . $train->getNextDestination() . ")";
		}
		else if ($train->isRunning())
		{
			$current_track = $train->getTrack();
			$from_station = $g->getStation($current_track['from_station_id']);
			$to_station = $g->getStation($current_track['to_station_id']);
			$direction = $train->getTrackDirection();

			if ($direction > 0) {
				// echo "Heading from " . $from_station['name'] . " towards " . $to_station['name'];
				echo "Heading towards " . $to_station['name'];
			}
			else {
				// echo "Heading from " . $to_station['name'] . " towards " . $from_station['name'];
				echo "Heading towards " . $from_station['name'];
			}

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

			$prev_destination = $g->getStation($train->getPreviousDestinationID());
			$next_destination = $g->getStation($train->getNextDestinationID());

			// Get train lon/lat
			$current_track = $train->getTrack();
			$prev_node = $g->getStation($current_track['from_station_id']);
			$next_node = $g->getStation($current_track['to_station_id']);
			$t = $train->getProgress() / 100;
			list($lon, $lat) = interpolate($prev_node['lon'], $prev_node['lat'], $next_node['lon'], $next_node['lat'], $t);

			$completed = dist($prev_destination['lon'], $prev_destination['lat'], $lon, $lat);
			$togo = dist($next_destination['lon'], $next_destination['lat'], $lon, $lat);
			$total = $completed + $togo;
			$route_progress = ($completed / $total) * 100;

			echo '<br><img src="images/progress.gif" height="1" width="'.min($route_progress,100).'%">';
		}

		echo '</div>';
	}

	if(count($g->getTrains()) < 1) $lang['en']['no_trains'];
}