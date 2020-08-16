<?php

require_once "game.php";
require_once "classes/train.class.php";
require_once "classes/station.class.php";

$g = new Game();

$trains = Train::getTrains();

foreach ($trains as $train) {
    echo $train->getName() . "<br/>";

    $route = $train->getRoute();

    for ($i = 1; $i < count($route); $i++) {
        $s1 = Station::getStation($route[$i-1]['station_id']);
        $s2 = Station::getStation($route[$i]['station_id']);

        list ($lat1, $lon1) = $s1->getLatLon();
        list ($lat2, $lon2) = $s2->getLatLon();
        $length = dist($lat1,$lon1,$lat2,$lon2) / 1e5;

        echo $s1->getName() . " to " . $s2->getName() . " Distance: " . $route[$i]['length'] . "<br/>";
        echo "($lat1,$lon1) -> ($lat2,$lon2) [".$length."]<br/>";

        $database->updateRoute($train->id, $i, "length", $length);
    }
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