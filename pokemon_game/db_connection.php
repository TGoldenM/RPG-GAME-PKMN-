<?php
require_once 'config.php';

defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__).'/'));
define("LOG_FILE", ROOTPATH."log.txt");

require_once ROOTPATH."util/login_functions.php";
require_once ROOTPATH."util/sec_functions.php";

ini_set("log_errors", 1);
ini_set("error_log", LOG_FILE);

try{
    $db = new PDO("mysql:host=$server;dbname=$name_db", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //Sync time zone between PHP and MySQL
    date_default_timezone_set($time_zone);
    $n = new DateTime();
    $h = $n->getOffset()/3600;
    $i = 60*($h-floor($h));
    $offset = sprintf('%+d:%02d', $h, $i);
    $db->query("SET time_zone='$offset'");
    
} catch(PDOException $e) {
    $db_msg = "Connection failed: " . $e->getMessage();
    error_log($db_msg);
    echo $db_msg;
    exit();
}

if ((function_exists('session_status') 
  && session_status() !== PHP_SESSION_ACTIVE) || !session_id()) {
    sec_session_start();
}
?>