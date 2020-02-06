<?php

defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH . 'lib/Zebra_Pagination.php';
require_once ROOTPATH . 'data_access/item.php';
require_once ROOTPATH . 'data_access/trainer.php';

function initTrainerItem(&$obj){
    $obj->trainer = getTrainer($obj->id_trainer);
    unset($obj->id_trainer);

    $obj->item = getPkmnItem($obj->id_item, PKMN_SIMPLE_LOAD);
    unset($obj->id_item);
}

function getTrainerItem($id, $typeLoad = PKMN_NO_LOAD){
    global $db;
    $q = "select * from pkmn_trainer_item where id = :id";
    
    $stmt = $db->prepare($q);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    
    $obj = $stmt->fetch(PDO::FETCH_OBJ);
    if($typeLoad != PKMN_NO_LOAD){
        initTrainerItem($obj);
    }
    
    return $obj;
}

/**
 * Creates an associative array using the item categories as key names.
 * This function is intended to be used with genSelectOptionsArr found
 * in ui_support/dropdown.php.
 * 
 * @param array $trnItems An item array created with one of the get* functions
 * found in data_access/trainer_item.php using PKMN_SIMPLE_LOAD
 * or PKMN_DEEP_LOAD.
 *  
 * @return array Associative array
 */
function getGroupedTrainerItems($trnItems){
    $values = array();
    
    foreach ($trnItems as $trnItem) {
        $item = $trnItem->item;
        $stat = $item->stat;
        
        if(!is_null($stat)){
            $mv_name = sprintf("%s (%s)", $item->name, $stat->name);
        } else {
            $mv_name = sprintf("%s", $item->name);
        }
        
        if(!isset($values[$item->category->name])){
            $values[$item->category->name] = array(array($item->id, $mv_name));
        } else {
            $values[$item->category->name][] = array($item->id, $mv_name);
        }
    }
    
    return $values;
}

function getTrainerItems($id_trainer = null, $typeLoad = PKMN_NO_LOAD) {
    global $db;
    $q = "select t.* from pkmn_trainer_item t";
    
    $conds = array();
    if(!is_null($id_trainer)){
        $conds[] = "t.id_trainer = $id_trainer";
    }
    
    if(!empty($conds)){
        $q .= " where ";
        $q .= implode(" and ", $conds);
    }    
        
    $stmt = $db->query($q);
    
    $objs = $stmt->fetchAll(PDO::FETCH_OBJ);
    if ($typeLoad != PKMN_NO_LOAD) {
        foreach ($objs as $obj) {
            initTrainerItem($obj);
        }
    }
    
    return $objs;
}

function getPgTrainerItems(&$res, $navPos, $id_trainer,
        $records_per_page = 10, $typeLoad = PKMN_NO_LOAD, $orderBy = null) {
    global $db;
    $pg = new Zebra_Pagination();
    $pg->navigation_position($navPos);
    $q = "select SQL_CALC_FOUND_ROWS t.* from pkmn_trainer_item t
        where t.id_trainer = :id";

    if (!is_null($orderBy)) {
        $q .= " order by $orderBy";
    }

    $q .= " limit " . (($pg->get_page() - 1) * $records_per_page)
            . ", " . $records_per_page;

    $stmt = $db->prepare($q);
    $stmt->bindParam(':id', $id_trainer, PDO::PARAM_INT);
    $stmt->execute();

    $res = $stmt->fetchAll(PDO::FETCH_OBJ);
    $stmt = $db->query("select FOUND_ROWS() as rows");
    $numRows = $stmt->fetchColumn();

    $pg->records($numRows);
    $pg->records_per_page($records_per_page);
    
    if ($typeLoad != PKMN_NO_LOAD) {
        foreach ($res as $obj) {
            initTrainerItem($obj);
        }
    }
    
    return $pg;
}

function insertTrainerItem(&$obj){
    global $db;
    
    $q = "INSERT INTO pkmn_trainer_item (id_trainer,id_item)
         VALUES (:id_trainer,:id_item)";
    
    $stmt = $db->prepare($q);
    $stmt->bindParam(":id_trainer", $obj->id_trainer, PDO::PARAM_INT);
    $stmt->bindParam(":id_item", $obj->id_item, PDO::PARAM_INT);
    
    $stmt->execute();
    $obj->id = $db->lastInsertId();
    return $obj->id;
}

function updateTrainerItem($obj){
    global $db;
    
    $q = "UPDATE pkmn_trainer_item
        SET id_trainer = :id_trainer,
        id_item = :id_item
        WHERE id = :id
    ";
    
    $stmt = $db->prepare($q);
    $stmt->bindParam(":id", $obj->id, PDO::PARAM_INT);
    $stmt->bindParam(":id_trainer", $obj->id_trainer, PDO::PARAM_INT);
    $stmt->bindParam(":id_item", $obj->id_item, PDO::PARAM_INT);
    
    $stmt->execute();
}

function deleteTrainerItem($obj){
    deleteTrainerItemById($obj->id);
}

function deleteTrainerItemById($id){
    global $db;
    
    $q = "DELETE FROM pkmn_trainer_item where id = :id";
    $stmt = $db->prepare($q);
    
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

?>