<?php
defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH . 'db_connection.php';
require_once ROOTPATH . 'data_access/system_property.php';

function createAlertBox($typeMsg, $name, $content = null) {
    $p = getPropertyByName($name);
    genAlertBox($typeMsg, !is_null($content)
            ? sprintf($p->value, $content) : $p->value);
}

function genAlertBox($typeMsg, $msg) {
    switch ($typeMsg) {
        case 0:
            echo "<div class='alert-box alert-box-error'><span>Error: </span>$msg</div>";
            break;
        case 1:
            echo "<div class='alert-box alert-box-success'>$msg</div>";
            break;
        case 2:
            echo "<div class='alert-box alert-box-notice'><span>Notice: </span>$msg</div>";
            break;
        case 3:
            echo "<div class='alert-box alert-box-warning'><span>Warning: </span>$msg</div>";
            break;
    }
}

?>