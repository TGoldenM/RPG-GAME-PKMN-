<?php
require_once 'config.php';

defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__).'/'));
define("LOG_FILE", ROOTPATH."log.txt");

ini_set("log_errors", 1);
ini_set("error_log", LOG_FILE);

try{
    $db = new PDO("mysql:host=$server;dbname=$name_db", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    $db_msg = "Connection failed: " . $e->getMessage();
    echo $db_msg;
    exit();
}
?>