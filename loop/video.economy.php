<?php

function updateEconomyVideo () {
    global $g;

	$show = isset($_GET['view']) ? $_GET['view'] : "towns";
	$showOptions = ["Towns","Commodities","Buildings","Demand"];

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
				echo '<div id="town_'.$town['id'].'" class="town_list"><b>'.$town['name'].'</b><br>';
                echo '<table><tr><th>Name</th><th>Quantity</th><th>Price</th></tr>';
                $wealth = 0;
				foreach($g->getCommodities($town['id']) as $commodity){
					$h = $commodity['surplus'] <= 0 ? 'class="hidden-detail"' : '';
                    echo "<tr $h><td>".$commodity['name'].'</td><td>'.sprintf('%.3f', $commodity['surplus']).'</td><td>$'.sprintf('%.2f', $commodity['price']).'</td></tr>';
                    $wealth += $commodity['surplus'] * $commodity['price'];
				}
                echo '</table>';
                echo '<dl><dt>Wealth</dt><dd>'.sprintf("$%.02f", $wealth).'</dd><dl>';
                echo '</div>';
			}
		break;
		case "commodities":
			foreach ($g->getCommodityTypes() as $commodity) {
				echo '<div id="commodity_'.$commodity.'" class="town_list"><b>'.ucfirst($commodity).'</b><br>';
				echo '<table><tr><th>Town</th><th>Available</th><th>Price</th></tr>';
				foreach($g->getCommodityList($commodity) as $c){
					$h = $c['available'] <= 0 ? 'class="hidden-detail"' : '';
					echo "<tr $h><td>".$g->getTown($c['town_id'])['name'].'</td><td>'.sprintf('%.3f', $c['available']).'</td><td>$'.sprintf('%.2f', $c['price']).'</td></tr>';
				}
				echo '</table></div>';
			}
		break;
		case "demand":
			foreach($g->getTowns() as $town){
				echo '<div id="town_'.$town['id'].'" class="town_list"><b>'.$town['name'].'</b><br>';
				echo '<table><tr><th>Name</th><th>Supply</th><th>Demand</th><th>Available</th></tr>';
				foreach($g->getCommoditySupplyDemand($town['id']) as $commodity){
					$hidden = $commodity['available'] <= 0 &&
						$commodity['supply'] <= 0 &&
						$commodity['demand'] <= 0;
					$h = $hidden ? 'class="hidden-detail"' : '';
					echo "<tr $h><td>". $commodity['type'].'</td>'
						.'<td>'.sprintf('%.3f', $commodity['supply']).'</td>'
						.'<td>'.sprintf('%.3f', $commodity['demand']).'</td>'
						.'<td>'.sprintf('%.3f', $commodity['available']).'</td>'
						.'</tr>';
				}
				echo '</table></div>';
			}
		break;
		case "buildings":
			foreach(Building::getBuildings() as $building){
                echo '<div id="building_'.$building->id.'" class="town_list"><b>'.$building->getName().'</b>';
                $town = $building->getTown();
                echo '<dl>';
                echo '<dt>Town</dt><dd>'.$town['name'].'</dd>';
                echo '<dt>Wealth</dt><dd>'.sprintf('$%.02f', $building->getWealth()).'</dd>';
                echo '<dt>Scale</dt><dd>'.sprintf('%.03f', $building->getScale()).'</dd>';
                echo '</dl>';
                echo '</div>';
			}
		break;
	}
	echo '</div>';
}