<?php

function updateTownState () {
    global $g, $debug;

	// update population based consumption
	foreach ($g->getTowns() as $town) {
		$pop = $town['population'];
		$kpop = $pop / 1e6;

		foreach ($g->getProduction('population') as $rate) {
			$c = $g->getCommodities($town['id'], $rate['commodity']);

			if ($c === null) {
				continue;
			}

			$delta = $rate['supplies'] > 0 ? $rate['supplies'] : -$rate['demands'];
			$quantity = $g->dsimtime * $delta * $kpop;
			$debug->log("Trying to consume/produce $quantity of {$rate['commodity']} at {$town['name']}", 3);

			if(-$quantity <= $c['surplus']) {
				$g->updateCommodities($town['id'], $rate['commodity'], $quantity);
			}
		}
	}
}