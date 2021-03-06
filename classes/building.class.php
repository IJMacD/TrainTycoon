<?php

require_once "database.php";

class Building {
    var $id;

    private $type;
    private $town_id;
    private $name;
    private $wealth;
    private $scale;

    function getProductionRates () {
        global $database;

        return $database->getProduction($this->type);
    }

    function getTown () {
        global $database;

        return $database->getTown($this->town_id);
    }

    function getName () {
        if (!$this->name) {
            $n = str_replace("_", " " , $this->type);
            $this->name = ucwords($n) . " " . $this->id;
        }
        
        return $this->name;
    }

    function getWealth () {
        return $this->wealth;
    }

    function addWealth ($value) {
        global $database;

        $this->wealth += $value;

        $database->updateBuilding($this->id, "wealth", $this->wealth);
    }

    function getScale () {
        return $this->scale;
    }

    function setScale ($scale) {
        global $database;

        $this->scale = $scale;

        $database->updateBuilding($this->id, "scale", $this->scale);
    }


    static function getBuildings () {
        global $database;

        $db_list = $database->getBuildings();
        $out_list = [];

        foreach ($db_list as $b) {
            $building = new Building();
            $building->id =         $b['id'];
            $building->type =       $b['type'];
            $building->town_id =    $b['town_id'];
            $building->name =       $b['name'];
            $building->wealth =     $b['wealth'];
            $building->scale =      $b['scale'];

            $out_list[] = $building;
        }

        return $out_list;
    }
}