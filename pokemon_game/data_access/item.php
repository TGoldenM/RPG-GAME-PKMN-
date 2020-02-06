<?php

defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH . 'lib/Zebra_Pagination.php';
require_once ROOTPATH . 'data_access/pokemon.php';
require_once ROOTPATH . 'data_access/item_category.php';
require_once ROOTPATH . 'data_access/stat.php';

function initPkmnItem(&$obj){
    $obj->category = getPkmnItemCategory($obj->id_category);
    unset($obj->id_category);

    $obj->stat = getStat($obj->id_stat);
    unset($obj->id_stat);
}

function getPkmnItem($id, $typeLoad = PKMN_NO_LOAD) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_pokemon_item where id = " . $id);
        $obj = $stmt->fetch(PDO::FETCH_OBJ);

        if ($obj && $typeLoad != PKMN_NO_LOAD) {
            initPkmnItem($obj);
        }
    }

    return $obj;
}

function getRandPkmnItem($typeLoad = PKMN_NO_LOAD) {
    global $db;

    $stmt = $db->query("select * from pkmn_pokemon_item order by rand() limit 1");
    $obj = $stmt->fetch(PDO::FETCH_OBJ);

    if ($obj && $typeLoad != PKMN_NO_LOAD) {
        initPkmnItem($obj);
    }

    return $obj;
}

function getPkmnItems($name = null, $typeLoad = PKMN_NO_LOAD,
        $orderBy = null, $rows = null) {
    global $db;
    $objs = array();

    $q = "select * from pkmn_pokemon_item";

    if(!is_null($name)){
        $q .= " where name like '$name'";
    }
    
    if (!is_null($orderBy)) {
        $q .= " order by $orderBy";
    }

    if (!is_null($rows)) {
        $q .= " limit $rows";
    }

    $stmt = $db->query($q);
    $objs = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($objs && $typeLoad != PKMN_NO_LOAD) {
        foreach($objs as $obj){
            initPkmnItem($obj);
        }
    }

    return $objs;
}

function getPgPkmnItems(&$res, $navPos, $records_per_page = 10,
        $typeLoad = PKMN_NO_LOAD, $orderBy = null) {
    global $db;
    $pg = new Zebra_Pagination();
    $pg->navigation_position($navPos);

    $q = "select SQL_CALC_FOUND_ROWS * from pkmn_pokemon_item";
    
    $conds = array();
    if(!empty($conds)){
        $q .= " where ";
        $q .= implode(" and ", $conds);
    }
    
    if (!is_null($orderBy)) {
        $q .= " order by $orderBy";
    }

    $q .= " limit " . (($pg->get_page() - 1) * $records_per_page)
            . ", " . $records_per_page;

    $stmt = $db->query($q);
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);
    $stmt = $db->query("select FOUND_ROWS() as rows");
    $numRows = $stmt->fetchColumn();

    $pg->records($numRows);
    $pg->records_per_page($records_per_page);
    
    if ($typeLoad != PKMN_NO_LOAD) {
        foreach ($res as $obj) {
            initPkmnItem($obj);
        }
    }
    
    return $pg;
}

function insertPkmnItem(&$obj) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_pokemon_item
        (id_category,id_stat,name,value) VALUES
        (:id_category,:id_stat,:name,:value)");

    $id_stat = isset($obj->id_stat) && $obj->id_stat != 0 ? $obj->id_stat : null;
    
    $stmt->bindParam(':id_category', $obj->id_category, PDO::PARAM_INT);
    $stmt->bindParam(':id_stat', $id_stat, PDO::PARAM_INT);
    $stmt->bindParam(':name', $obj->name);
    $stmt->bindParam(':value', $obj->value, PDO::PARAM_INT);

    $stmt->execute();
    $obj->id = $db->lastInsertId();
    return $obj->id;
}

function updatePkmnItem($obj) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_pokemon_item SET
        id_category = :id_category,
        id_stat = :id_stat,
        name = :name,
        value = :value
        WHERE id = :id
    ");

    $id_stat = isset($obj->id_stat) && $obj->id_stat != 0 ? $obj->id_stat : null;
    
    $stmt->bindParam(":id", $obj->id, PDO::PARAM_INT);
    $stmt->bindParam(':id_category', $obj->id_category, PDO::PARAM_INT);
    $stmt->bindParam(':id_stat', $id_stat, PDO::PARAM_INT);
    $stmt->bindParam(':name', $obj->name);
    $stmt->bindParam(':value', $obj->value, PDO::PARAM_INT);
    $stmt->execute();
}

function deletePkmnItem($p){
    deletePkmnItemById($p->id);
}

function deletePkmnItemById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_pokemon_item
        WHERE id = :id");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

?>
