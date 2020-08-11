<?php
error_reporting(E_ALL);
ini_set("display_errors", 1); 
require_once("constants.php");
require_once("database.mysqli.php");
require_once("game.php");
require_once("lang.php");
require_once("classes/train.class.php");

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

$debug = isset($_GET['debug']) ? $_GET['debug'] : 0;

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

$x_scale = $img_w / (MAP_MAX_LON - MAP_MIN_LON);
$y_scale = $img_h / (MAP_MAX_LAT - MAP_MIN_LAT);

if ($debug) {
    $line = 1;
    $text_colour = imagecolorallocate ($img, 255, 0, 0);
    imagestring ($img, 4, 5, 15 * $line++, 'Image Size: ' . $img_w . 'x' . $img_h . ' Scale: ' . $x_scale . 'x' . $y_scale, $text_colour);
    // echo 'Image Size: ' . $img_w . 'x' . $img_h . ' Scale: ' . $x_scale . 'x' . $y_scale;
}

$g = new Game;
$trains = $g->getTrains();
$towns = $g->getTowns();

// $towns = array(
//     array("Name" => "thurso", "lon" => -3.5221, "lat" => 58.5936),
//     array("Name" => "gloucester", "lon" => -2.2382, "lat" => 51.8642),
//     array("Name" => "margate", "lon" => 1.3868, "lat" => 51.3896),
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

foreach ($trains as $train) {
    $route_towns = $train->getTowns();
    $seg = $train->getSegment();

    for ($i = 1; $i < count($route_towns); $i++) {
        $town_a = $g->getTowns($route_towns[$i - 1]);
        $town_b = $g->getTowns($route_towns[$i]);

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

foreach ($towns as $town) {
    list($x, $y) = toCoords($town['lon'], $town['lat']);

    if ($debug) {
        imagestring ($img, 4, 5, 15 * $line++, $town['Name'] . ' ('.$town['lon'].','.$town['lat'].') -> ('.$x.','.$y.')', $text_colour);
    }

    if ($town['population'] > 1e6) {
        imagefilledrectangle($img, $x - 10, $y - 10, $x + 10, $y + 10, $town_colour);
    } else {
        imagefilledellipse($img, $x, $y, 16, 16, $town_colour);
    }
}


header('Content-Type: image/gif');
imagegif($img);
imagedestroy($img);
