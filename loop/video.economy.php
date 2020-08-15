<?php

function updateEconomyVideo () {
    global $g;
    
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
}