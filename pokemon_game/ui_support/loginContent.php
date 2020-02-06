<?php

function genNumberOfUsrsText($numUsrs) {
    if ($numUsrs == 0) {
        echo 'No users';
    } else if ($numUsrs == 1) {
        echo '1 user';
    } else if ($numUsrs > 1) {
        echo "$numUsrs users";
    }
}

function genNumberOfPlyrsText($numPlyrs) {
    if ($numPlyrs == 0) {
        echo 'No players';
    } else if ($numPlyrs == 1) {
        echo '1 player';
    } else if ($numPlyrs > 1) {
        echo "$numPlyrs players";
    }
}

?>