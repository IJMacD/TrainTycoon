<?php

function updateTrainVideo () {
    global $g, $lang, $debug, $CONST;

    echo '<h1>'.$lang['en']['trains'].'</h1>';
	foreach(Train::getTrains() as $train){
		/*
		echo '<div class="town_list" style="float:right;">';
		for($i = 0; $i < count($train['route']); $i++){
			echo $train['town_ids'][$i]." ".$train['route'][$i]."<br>";
		}
		echo '</div>';
		*/

		$route_progress = $train->getProgress() * $train->getDirection() * $train->getTrackDirection();

		if ($route_progress < 0) {
			$route_progress += 100;
		}

		$image_style = "overflow: hidden;white-space: nowrap;";

		if (!$train->isAtStation() && $train->isRunning()) {

			if ($train->getDirection() == 1) {
				$image_style .= "padding-left: ".(100-min($route_progress,100))."%;";
			}
			else {
				$image_style .= "padding-left: ".min($route_progress,100)."%;";
			}
		}

		if ($train->getDirection() == 1) {
			$image_style .= "transform: scale(-1, 1);";
		}

		$station_names = array_map(function ($s) { return $s->getName(); }, $train->getStations());
		$i = $train->getNextDestinationIndex();
		$station_names[$i] = '<b>' . $station_names[$i] . '</b>';
		echo '<div class="train_list" id="train_'.$train->id.'">'
			. '<b>' . $train->getName() . '</b> ' . $train->state . ' '
			. '('.implode(", ", $station_names).') '
			. '<span class="direction-indicator">'.($train->getDirection() == 1 ? "UP" : "DOWN").'</span>'
			. '<div style="'.$image_style.'">'
			. '<img src="'.$CONST['locos'][$train->getLocoID()]['image'].'">';

		foreach($train->getCars() as $car){
			echo '<img src="'.$CONST['commodities'][$car]['car_image'].'" title="'.$car.'">';
		}

		echo '</div>';

		// echo '<pre>';
		// var_dump($train);
		// echo '</pre>';

		if($train->isAtStation())
		{
			// else if ($train->isLoading())
			// 	echo '<br>'.$lang['en']['stopped_at'].' '.$train->getNextStation() . " (Loading Time: " . $train->getLoadingTime() . ")";
			echo $lang['en']['stopped_at'].' '.$train->getNextDestination() . " (Loading Time: " . $train->getLoadingTime() . ")";
		}
		else if ($train->isStopped()) {
			$station_id = $train->getNextTrackStationID();
			$station = $g->getStation($station_id);
			echo "Stopped near " . $station['name'] . " (No route to " . $train->getNextDestination() . ")";
		}
		else
		{
			$towards_station = $g->getStation($train->getNextTrackStationID());

			echo "Heading towards " . $towards_station['name'];

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

		echo '<br><img src="images/progress.gif" height="1" width="'.min($route_progress,100).'%">';
		echo '</div>';
	}

	if(count($g->getTrains()) < 1) $lang['en']['no_trains'];
}