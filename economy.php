<?php

class Economy {

    
	/**
	 * Big method to calculate entire economy!
	 */
	function updateCommodities($town_id, $commodity, $dsurplus){
		global $database, $debug, $CONST;
	
		$return_value = 0;
		
		$c = $database->getCommodities($town_id);
        //print_r($c);
        
		if(isset($c[$commodity]))
		{
			$price = $c[$commodity]['price'];
			$supply = $c[$commodity]['supply'];
			$demand = $c[$commodity]['demand'];

			$return_value = $dsurplus * $price;
			/*
			$q = "SELECT ";
			$q .= "(SELECT AVG(`price`) FROM `".TABLE_commodities."` WHERE `commodity` = '$commodity') as 'avg_price',";
			$q .= "(SELECT AVG(`surplus`) FROM `".TABLE_commodities."` WHERE `commodity` = '$commodity') as 'avg_surplus'";
			//$q .= " FROM `".TABLE_commodities."` WHERE `town_id` = '$town_id' AND `commodity` = '$commodity'";
			$result = $this->query($q);
			if($result && mysql_num_rows($result)){
				$avg_price = mysql_result($result, 0, "avg_price");
				$avg_surplus = mysql_result($result, 0, "avg_surplus");
				if($surplus == $avg_surplus){
					$price = $avg_price;
					$surplus += $dsurplus;
				}else{
					$a = ($price - $avg_price)/($surplus - $avg_surplus);
					$b = $price - $a * $surplus;
					$surplus += $dsurplus;
					$price = $a * $surplus + $b;
					//$price *= (rand(16, 25) / 20); //rand(0.8, 1.25)
					$price = max(0, $price);
				}
			}else{
				$price = $CONST['commodities'][$commodity]['price'];
				$price *= (rand(16, 25) / 20);
				$surplus = $dsurplus;
			}*/
			$t = $database->getTowns($town_id);
			$debug->log(($dsurplus > 0 ? "SELL" : "BUY") . ": [$commodity x ".abs($dsurplus)."] @ $$price, Supply $supply, Demand $demand, From {$t['Name']}");
			if($dsurplus > 0)
			{
				$supply += $dsurplus;
				$price -= ($dsurplus / $supply) * $price;
			}
			else
			{
				$demand -= $dsurplus;
				$price -= ($dsurplus / $demand) * $price;
			}
			// P = m_D.Q + c_D
			// P = m_S.Q + c_S
			// M = m_D/m_S
			// P = (M.c_D + c_S)/(1 - M)
			// Q === surplus
			// dP = m_D.dQ
			// $m_D = -1;
			$debug->log("New Price for [$commodity] at {$t['Name']}: Price $price, Supply $supply, Demand $demand");
			//$price = max(0, $price);
		}else{
			$c = makeComodity($commodity, $dsurplus);
			$price = $c['price'];
			$supply = $c['supply'];
			$demand = $c['demand'];
			$return_value = $c['price'] * $dsurplus;
		}
		
		$database->setCommodity($town_id, $commodity, $supply, $demand, $price);

		return $return_value;
	}
}
$economy = new Economy();