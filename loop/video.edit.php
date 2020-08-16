<?php

function updateEditVideo () {
    global $g, $database, $CONST;

    // New Station
    echo '<form class="edit-box">';
    echo '<input type="hidden" name="action" value="new-station" />';
    echo '<p><b>New Station</b></p>';
    echo '<label>Town <select name="new-station-town">';
    foreach($g->getTowns() as $town) {
        echo '<option value="'.$town['id'].'">'.$town['Name'].'</option>';
    }
    echo '</select></label>';
    echo '<label>Name <input name="new-station-name" /></label>';
    echo '<button>Create</button>';
    echo '</form>';

    // New Building
    echo '<form class="edit-box">';
    echo '<input type="hidden" name="action" value="new-building" />';
    echo '<p><b>New Building</b></p>';
    echo '<label>Town <select name="new-building-town">';
    foreach($g->getTowns() as $town) {
        echo '<option value="'.$town['id'].'">'.$town['Name'].'</option>';
    }
    echo '</select></label>';
    echo '<label>Building Type <select name="new-building-type">';
    foreach($database->getBuildingTypes() as $type) {
        $name = ucwords(str_replace("_", " ", $type));
        echo '<option value="'.$type.'">'.$name.'</option>';
    }
    echo '</select></label>';
    echo '<label>Name <input name="new-building-name" /></label>';
    echo '<button>Create</button>';
    echo '</form>';

    // New Train
    echo '<form class="edit-box">';
    echo '<input type="hidden" name="action" value="new-train" />';
    echo '<p><b>New Train</b></p>';
    echo '<label>Loco <select name="new-train-loco">';
    foreach($CONST['locos'] as $id => $loco) {
        echo '<option value="'.$id.'">'.$loco['name'].'</option>';
    }
    echo '</select></label>';
    echo '<label>Name <input name="new-train-name" /></label>';
    echo '<label>First Station <select name="new-train-station1">';
    foreach(Station::getStations() as $station) {
        echo '<option value="'.$station->id.'">'.$station->getName().'</option>';
    }
    echo '</select></label>';
    echo '<label>Second Station <select name="new-train-station2">';
    foreach(Station::getStations() as $station) {
        echo '<option value="'.$station->id.'">'.$station->getName().'</option>';
    }
    echo '</select></label>';
    echo '<button>Create</button>';
    echo '</form>';

    // Add station to Route
    echo '<form class="edit-box">';
    echo '<input type="hidden" name="action" value="route-add" />';
    echo '<p><b>Add Station to Route</b></p>';
    echo '<label>Train <select name="route-add-train">';
    foreach(Train::getTrains() as $train) {
        echo '<option value="'.$train->id.'">'.$train->getName().'</option>';
    }
    echo '</select></label>';
    echo '<label>New Station <select name="route-add-station">';
    foreach(Station::getStations() as $station) {
        echo '<option value="'.$station->id.'">'.$station->getName().'</option>';
    }
    echo '</select></label>';
    echo '<button>Add</button>';
    echo '</form>';
}