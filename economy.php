<?php
require_once "constants.php";
require_once "database.php";
require_once "debug.php";

class Economy {
	
	function getCommodities ($town_id, $commodity="")
	{
		global $debug, $database;
		
		/*
		 * getCommodites2
		 * Array<{
		 * 	type
		 * 	price
		 * 	available
		 * }>
		 */
		$commodities = $database->getCommodities($town_id);
		
		/*
		 * getCommodities()
		 * Array<{
		 * 	name
		 * 	supply
		 * 	surplus
		 * 	demand
		 * 	price
		 * }>
		 */
		$commodities = array_map(function ($c) {
			return [
				"name" => $c['type'],
				"supply" => $c['available'],
				"surplus" => $c['available'],
				"demand" => 0,
				"price" => $c['price'],
			];
		}, $commodities);

		if ($commodity) {
			foreach ($commodities as $c) {
				if ($c['name'] == $commodity) return $c;
			} 
			$debug->log("Can't find commodity $commodity at town $town_id");
			return null;
		}

		return $commodities;
	}
    
	/**
	 * Big method to calculate entire economy!
	 */
	function updateCommodities($town_id, $commodity, $dsurplus){
		global $database, $debug, $CONST;
	
		$return_value = 0;
		
		$c = $this->getCommodities($town_id, $commodity);
		
		$database->setCommodity($town_id, $commodity, $c['surplus'] + $dsurplus);

		return $dsurplus * $c['price'];
	}
}
$economy = new Economy();


function makeComodity ($commodity, $surplus=0) {
	global $CONST;

	$price = $CONST['commodities'][$commodity]['price'];
	$price *= binom(1, 0.25);

	return array('name' => $commodity, 'supply' => 200 + $surplus, 'demand' => 200 - $surplus, 'surplus' => $surplus, 'price' => $price);
}

function binom ($centre = 0.5, $range = 1, $iter = 3) {
	$acc = 0;
	for ($i = 0; $i < $iter; $i++) $acc += rand(0, 100000) / 100000;
	$r = $acc / $iter;
	$r += $centre - 0.5;
	$r *= $range;
	return $r;
}