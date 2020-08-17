<?php


/**
 * Format money
 */
function m ($value) {
	return sprintf("$%.02f", $value);
}
function getStationDistance ($s1_id, $s2_id) {
	$s1 = Station::getStation($s1_id);
	$s2 = Station::getStation($s2_id);

	list ($lat1, $lon1) = $s1->getLatLon();
	list ($lat2, $lon2) = $s2->getLatLon();
	return dist($lat1,$lon1,$lat2,$lon2) / 1e5;
}
function dist ($lat1, $lon1, $lat2, $lon2) {
    $R = 6371e3; // Mean Earth radius in metres
    $φ1 = toRadians($lat1);
    $φ2 = toRadians($lat2);
    $Δφ = toRadians($lat2-$lat1);
    $Δλ = toRadians($lon2-$lon1);

    $a =   sin($Δφ/2) * sin($Δφ/2) +
                cos($φ1) * cos($φ2) *
                sin($Δλ/2) * sin($Δλ/2);

    $c = 2 * atan2(sqrt($a), sqrt(1-$a));

    return $R * $c;

}
function toRadians ($deg) {
    return $deg * (pi()/180);
}