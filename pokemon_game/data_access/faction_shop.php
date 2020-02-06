<?php

defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH . 'lib/Zebra_Pagination.php';
require_once ROOTPATH . 'data_access/trainer_item.php';
require_once ROOTPATH . 'data_access/trainer_pokemon.php';

function initFactionShopElement($obj, $typeLoad = PKMN_NO_LOAD){
    if ($typeLoad != PKMN_NO_LOAD) {
        $obj->faction = getFaction($obj->id_faction);
        unset($obj->id_faction);

        switch ($obj->type) {
            case 1:
                $obj->element = getTrainerItem($obj->id_element, PKMN_SIMPLE_LOAD);
                break;

            case 2:
                $obj->element = getTrainerPokemon($obj->id_element, PKMN_DEEP_LOAD);
                break;
        }

        unset($obj->id_element);
    }
}

function createFactionShopElem($obj){
    switch ($obj->type) {
        case 1://Item
            $id_obj = $obj->id_obj;
            $trn = (object)array(
                'id_trainer'=>null,
                'id_item'=>$id_obj
            );
            
            $idTrItm = insertTrainerItem($trn);
            $elem = (object)array('id_faction'=>$obj->id_faction,
                'id_element'=>$idTrItm, 'type'=>1,
                'pts_cost'=>$obj->pts_cost);
            
            break;

        case 2://Pokemon
            $pkmn = getPokemon($obj->id_obj, false, true);
            $tpkmn = getTrainerPkmnLeveled($obj->lvl, $pkmn, null, false);
            $tpkmn->id_pokemon = $obj->id_obj;
            $idTrPkmn = insertTrainerPokemon($tpkmn);
            
            $elem = (object)array('id_faction'=>$obj->id_faction,
                'id_element'=>$idTrPkmn, 'type'=>2,
                'pts_cost'=>$obj->pts_cost);
            
            break;
    }
    
    insertFactionShopElement($elem);
}

function getFactionShopElement($id, $typeLoad = PKMN_NO_LOAD) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_faction_shop where id = " . $id);
        $obj = $stmt->fetch(PDO::FETCH_OBJ);

        if ($obj) {
            initFactionShopElement($obj, $typeLoad);
        }
    }

    return $obj;
}

function getFactionShopElemName($obj){
    if($obj != null){
        switch ($obj->type) {
            case 1:
                return $obj->element->item->name;

            case 2:
                return $obj->element->pokemon->name;
        }
    }
    
    return "";
}

function getFactionShopElements($orderBy = null, $rows = null) {
    global $db;
    $objs = array();

    $q = "select * from pkmn_faction_shop";

    if (!is_null($orderBy)) {
        $q .= " order by $orderBy";
    }

    if (!is_null($rows)) {
        $q .= " limit $rows";
    }

    $stmt = $db->query($q);
    $objs = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($objs) {
        foreach($objs as $obj){
            initFactionShopElement($obj);
        }
    }

    return $objs;
}

function getPgFactionShopElements(&$res, $navPos, $id_faction = null,
        $records_per_page = 10, $typeLoad = PKMN_NO_LOAD,
        $orderBy = null, $rows = null) {
    global $db;
    $pg = new Zebra_Pagination();
    $pg->navigation_position($navPos);

    $q = "select SQL_CALC_FOUND_ROWS * from pkmn_faction_shop";

    $conds = array();
    if(!is_null($id_faction)){
        $conds[] = "id_faction = $id_faction";
    }
    
    if(!empty($conds)){
        $q .= " where ";
        $q .= implode(" and ", $conds);
    }
    
    $q .= " limit " . (($pg->get_page() - 1) * $records_per_page)
            . ", " . $records_per_page;

    if (!is_null($orderBy)) {
        $q .= " order by $orderBy";
    }

    if (!is_null($rows)) {
        $q .= " limit $rows";
    }

    $stmt = $db->query($q);
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);

    $stmt = $db->query("select FOUND_ROWS() as rows");
    $numRows = $stmt->fetchColumn();

    foreach($res as $obj){
        initFactionShopElement($obj, $typeLoad);
    }
    
    $pg->records($numRows);
    $pg->records_per_page($records_per_page);
    return $pg;
}

function insertFactionShopElement(&$obj) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_faction_shop
        (id_faction,id_element,type,pts_cost) VALUES
        (:id_faction,:id_element,:type,:pts_cost)");

    $stmt->bindParam(':id_faction', $obj->id_faction, PDO::PARAM_INT);
    $stmt->bindParam(':id_element', $obj->id_element, PDO::PARAM_INT);
    $stmt->bindParam(':type', $obj->type, PDO::PARAM_INT);
    $stmt->bindParam(':pts_cost', $obj->pts_cost, PDO::PARAM_INT);

    $stmt->execute();
    $obj->id = $db->lastInsertId();
    return $obj->id;
}

function updateFactionShopElement($obj) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_faction_shop SET
        id_faction = :id_faction,
        id_element = :id_element,
        type = :type,
        pts_cost = :pts_cost
        WHERE id = :id");

    $stmt->bindParam(":id", $obj->id, PDO::PARAM_INT);
    $stmt->bindParam(':id_faction', $obj->id_faction, PDO::PARAM_INT);
    $stmt->bindParam(':id_element', $obj->id_element, PDO::PARAM_INT);
    $stmt->bindParam(':type', $obj->type, PDO::PARAM_INT);
    $stmt->bindParam(':pts_cost', $obj->pts_cost, PDO::PARAM_INT);

    $stmt->execute();
}

function deleteFactionShopElem($p){
    switch($p->type){
        case 1://Item
            deleteTrainerItemById($p->id_element);
            break;
        
        case 2://Trainer pokemon
            deleteTrainerPkmnById($p->id_element);
            break;
    }
    
    deleteFactionShopElemById($p->id);
}

function deleteFactionShopElemById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_faction_shop
        WHERE id = :id");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

?>
