<?php

function updateBuildingState () {
    global $g, $debug;
    
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
}