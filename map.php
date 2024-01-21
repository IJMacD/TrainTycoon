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

function interpolate ($ax, $ay, $bx, $by, $t) {
    $dx = $bx - $ax;
    $dy = $by - $ay;

    $x = $ax + $dx * $t;
    $y = $ay + $dy * $t;

    return [$x, $y];
}

foreach (Train::getTrains() as $train) {
    $route_towns = $train->getTowns();
    $seg = $train->getSegment();

    for ($i = 1; $i < count($route_towns); $i++) {
        $town_a = $g->getTown($route_towns[$i - 1]);
        $town_b = $g->getTown($route_towns[$i]);

        list($ax, $ay) = toCoords($town_a['lon'], $town_a['lat']);
        list($bx, $by) = toCoords($town_b['lon'], $town_b['lat']);

        imageline($img, $ax, $ay, $bx, $by, $track_colour);

        if ($seg == $i) {
            $t = $train->getProgress();

            list($x, $y) = interpolate($ax, $ay, $bx, $by, $t / 100);

            imagefilledellipse($img, $x, $y, 12, 12, $train_colour);
        }
    }
}

if ($data_mode && $commodity) {
    imagestring ($img, 5, 5, 15, $commodity, $text_colour);
}

foreach ($g->getTowns() as $town) {
    list($x, $y) = toCoords($town['lon'], $town['lat']);

    if ($debug_level) {
        imagestring ($img, 4, 5, 15 * $line++, $town['name'] . ' ('.$town['lon'].','.$town['lat'].') -> ('.$x.','.$y.')', $text_colour);
    }

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

            imagefilledrectangle($img, $x - 10, $y - $price, $x + 10, $y, $track_colour);
            imagefilledrectangle($img, $x - 8, $y - $price + 2, $x + 8, $y - 2, $money_colour);
        }
    }
    else if ($town['population'] > 1e6) {
        imagefilledrectangle($img, $x - 10, $y - 10, $x + 10, $y + 10, $town_colour);
    }
    else {
        imagefilledellipse($img, $x, $y, 16, 16, $town_colour);
    }
}


header('Content-Type: image/gif');
imagegif($img);
imagedestroy($img);
