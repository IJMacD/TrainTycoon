<?php

function updateTrainVideo () {
    global $g, $lang, $debug, $CONST;
    
    echo '<h1>'.$lang['en']['trains'].'</h1>';
	foreach($g->getTrains() as $train){
		/*
		echo '<div class="town_list" style="float:right;">';
		for($i = 0; $i < count($train['route']); $i++){
			echo $train['town_ids'][$i]." ".$train['route'][$i]."<br>";
		}
		echo '</div>';
		*/
		$station_names = array_map(function ($s) { return $s->getName(); }, $train->getStations());
		$i = $train->getNextIndex();
		$station_names[$i] = '<b>' . $station_names[$i] . '</b>';
		echo '<div class="train_list" id="train_'.$train->id.'">'
			. '<b>' . $train->getName() . '</b> '
			. '('.implode(", ", $station_names).') '
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
}