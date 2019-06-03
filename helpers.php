<?php 
function air_condition_status($value) {
    if ($value < 51) {
        echo "<span class='green-text'>Good</span>";
    }
    elseif ($value < 101) {
        echo "<span class='yellow-text'>Moderate</span>";
    }
    elseif ($value < 151) {
        echo "<span class='orange-text'>Unhealthy</span>";
    }
    elseif ($value < 201) {
        echo "<span class='red-text'>Unhealthy+</span>";
    }
    elseif ($value < 301) {
        echo "<span class='purple-text'>Very unhealthy</span>";
    }
    else {
        echo "<span class='red accent-4-text'>Hazzardous</span>";
    }
}

?>