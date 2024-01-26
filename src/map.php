<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "constants.php";
require_once "database.php";
require_once "game.php";
require_once "lang.php";
require_once "classes/train.class.php";

// define("IMG_FILE", './basemap.gif');
// define("MAP_MIN_LON", -8.52);
// define("MAP_MIN_LAT", 50.0);
// define("MAP_MAX_LON", 2.53);
// define("MAP_MAX_LAT", 59.4);
define("IMG_FILE", './basemap2.gif');
define("MAP_MIN_LON", -11.05404079);
define("MAP_MIN_LAT", 49.26373617);
define("MAP_MAX_LON", 2.521819508);
define("MAP_MAX_LAT", 59.74063649);

$debug_level = isset($_GET['debug']) ? $_GET['debug'] : 0;

$data_mode = isset($_GET['data']) ? $_GET['data'] : "";
$commodity = isset($_GET['commodity']) ? $_GET['commodity'] : "";

function LoadGif($imgname)
{
    /* Attempt to open */
    $im = @imagecreatefromgif($imgname);

    /* See if it failed */
    if(!$im)
    {
        /* Create a blank image */
        $im = imagecreatetruecolor (150, 30);
        $bgc = imagecolorallocate ($im, 255, 255, 255);
        $tc = imagecolorallocate ($im, 0, 0, 0);

        imagefilledrectangle ($im, 0, 0, 150, 30, $bgc);

        /* Output an error message */
        imagestring ($im, 1, 5, 5, 'Error loading ' . $imgname, $tc);
    }

    return $im;
}

$img = LoadGif(IMG_FILE);

$img_w = imagesx($img);
$img_h = imagesy($img);

$town_colour = imagecolorallocate($img, 255, 128, 0);
$track_colour = imagecolorallocate($img, 80, 80, 80);
$train_colour = imagecolorallocate($img, 20, 140, 20);
$supply_colour = imagecolorallocate($img, 255, 0, 0);
$demand_colour = imagecolorallocate($img, 0, 0, 255);
$available_colour = imagecolorallocate($img, 255, 148, 0);
$money_colour = imagecolorallocate($img, 40, 240, 40);
$station_colour = imagecolorallocate($img, 240, 240, 40);

$text_colour = imagecolorallocate ($img, 255, 0, 0);

$x_scale = $img_w / (MAP_MAX_LON - MAP_MIN_LON);
$y_scale = $img_h / (MAP_MAX_LAT - MAP_MIN_LAT);

if ($debug_level) {
    $line = 1;
    imagestring ($img, 4, 5, 15 * $line++, 'Image Size: ' . $img_w . 'x' . $img_h . ' Scale: ' . $x_scale . 'x' . $y_scale, $text_colour);
    // echo 'Image Size: ' . $img_w . 'x' . $img_h . ' Scale: ' . $x_scale . 'x' . $y_scale;
}

session_start();
if (isset($_SESSION['game_id'])) {
	$g = new Game($_SESSION['game_id']);
} else {
    $tc = imagecolorallocate ($img, 255, 0, 0);
    imagestring ($img, 5, 5, 5, 'No Game', $tc);
    header('Content-Type: image/gif');
    imagegif($img);
    imagedestroy($img);
    exit;
}

// $towns = array(
//     array("name" => "thurso", "lon" => -3.5221, "lat" => 58.5936),
//     array("name" => "gloucester", "lon" => -2.2382, "lat" => 51.8642),
//     array("name" => "margate", "lon" => 1.3868, "lat" => 51.3896),
// );

function toCoords ($lon, $lat) {
    global $x_scale, $y_scale;

    $offset_lat = MAP_MAX_LAT - $lat; // Inverted Y
    $offset_lon = $lon - MAP_MIN_LON;

    $x = $offset_lon * $x_scale;
    $y = $offset_lat * $y_scale;

    return [$x, $y];
}

foreach (Train::getTrains() as $train) {
    $track = $train->getTrack();

    if (!$track) {
        return;
    }

    list($ax, $ay) = toCoords($track['from_lon'], $track['from_lat']);
    list($bx, $by) = toCoords($track['to_lon'], $track['to_lat']);

    $t = $train->getProgress();

    list($x, $y) = interpolate($ax, $ay, $bx, $by, $t / 100);

    // var_dump([$x, $y]);

    imagefilledellipse($img, (int)$x, (int)$y, 12, 12, $train_colour);
}

foreach ($g->getTracks() as $track) {
    list($ax, $ay) = toCoords($track['from_lon'], $track['from_lat']);
    list($bx, $by) = toCoords($track['to_lon'], $track['to_lat']);

    imageline($img, (int)$ax, (int)$ay, (int)$bx, (int)$by, $track_colour);

    list($x, $y) = interpolate($ax, $ay, $bx, $by, 0.9);
    imagefilledellipse($img, (int)$x, (int)$y, 5, 5, $track_colour);
}

if ($data_mode && $commodity) {
    imagestring ($img, 5, 5, 15, $commodity, $text_colour);
}

foreach ($g->getTowns() as $town) {
    list($x, $y) = toCoords($town['lon'], $town['lat']);

    $town_commodity = null;
    if ($commodity) {
        $town_commodities = $g->getCommodities($town['id']);
        foreach ($town_commodities as $tc) {
            if ($tc['name'] === $commodity) {
                $town_commodity = $tc;
                break;
            }
        }
    }

    if ($commodity) {
        if ($data_mode === "demand" && $town_commodity) {

            $town_supply_demand = null;
            foreach ($g->getCommoditySupplyDemand($town['id']) as $tsd) {
                if ($tsd['type'] === $commodity) {
                    $town_supply_demand = $tsd;
                    break;
                }
            }

            $supply = $town_supply_demand['supply'] * 5;
            $demand = $town_supply_demand['demand'] * 5;

            imagefilledrectangle($img, $x - 10, $y - $supply, $x - 2, $y, $supply_colour);
            imagefilledrectangle($img, $x + 2, $y - $demand, $x + 10, $y, $demand_colour);
        }
        else if ($data_mode === "towns" && $town_commodity) {
            $available = $town_commodity['surplus'] * 10;

            imagefilledrectangle($img, $x - 10, $y - $available, $x + 10, $y, $available_colour);
        }
        else if ($data_mode === "commodities" && $town_commodity) {
            $price = pow($town_commodity['price'], exp(1)) * 0.005;

            imagefilledrectangle($img, (int)$x - 10, (int)($y - $price), (int)$x + 10, (int)$y, $track_colour);
            imagefilledrectangle($img, (int)$x - 8, (int)($y - $price + 2), (int)$x + 8, (int)$y - 2, $money_colour);
        }
    }
    else if ($town['population'] > 1e6) {
        imagefilledrectangle($img, (int)$x - 10, (int)$y - 10, (int)$x + 10, (int)$y + 10, $town_colour);
    }
    else {
        imagefilledellipse($img, (int)$x, (int)$y, 16, 16, $town_colour);
    }
}

foreach ($g->getStations() as $station) {
    list($x, $y) = toCoords($station['lon'], $station['lat']);

    imagefilledellipse($img, (int)$x, (int)$y, 10, 10, $station_colour);
    // imagestring($img, 5, (int)$x, (int)$y, $station['name'] . " (" . $station['id'] . ")", $text_colour);
}


header('Content-Type: image/gif');
imagegif($img);
imagedestroy($img);
