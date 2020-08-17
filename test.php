<?php

require_once "game.php";
require_once "database.php";
require_once "classes/train.class.php";
require_once "classes/station.class.php";

$g = new Game();
$prefix = uniqid();

$q = "CREATE TABLE {$prefix}_buildings (`id` int not null, `type` varchar(50) not null, `name` varchar(50) null, PRIMARY KEY (id))";
$database->query($q);