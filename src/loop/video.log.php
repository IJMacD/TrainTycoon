<?php

function updateLogVideo () {
    global $g, $CONST;

    $log = $g->getLog();

    echo '<textarea readonly style="width: 100%;height: 100px">';
    foreach ($log as $line) {
        echo $line['message']."\n";
    }
    echo "</textarea>";
}