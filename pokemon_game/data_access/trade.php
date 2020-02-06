<?php
defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH . 'lib/Zebra_Pagination.php';
require_once ROOTPATH . 'data_access/trade_state.php';
require_once ROOTPATH . 'data_access/trainer_pokemon.php';

function loadTradeData(&$obj, $typeLoad){
    $obj->state = getTradeState($obj->id_state);
    unset($obj->id_state);
    
    $obj->trainer1 = getTrainer($obj->id_trainer1, PKMN_SIMPLE_LOAD);
    unset($obj->id_trainer1);
    
    $obj->trainer2 = getTrainer($obj->id_trainer2, PKMN_SIMPLE_LOAD);
    unset($obj->id_trainer2);
    
    $obj->tpkmn1 = getTrainerPokemon($obj->id_tpkmn1, $typeLoad);
    unset($obj->id_tpkmn1);

    $obj->tpkmn2 = getTrainerPokemon($obj->id_tpkmn2, $typeLoad);
    unset($obj->id_tpkmn2);
}

function getTrade($id, $loadTrainers = PKMN_NO_LOAD) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_trade where id = " . $id);
        $obj = $stmt->fetch(PDO::FETCH_OBJ);

        if($obj && $loadTrainers != PKMN_NO_LOAD){
            loadTradeData($obj, $loadTrainers);
        }
    }

    return $obj;
}

function getTradeCountByState($id_trainer_orig, $state_name){
    global $db;
    $q = "select count(t.id) from pkmn_trade t
         inner join pkmn_trainer_pokemon tr2 on tr2.id = t.id_tpkmn2
         inner join pkmn_trade_state s on s.id = t.id_state
         where tr2.id_trainer = :id_trainer
         and s.name = :state_name";
    
    $stmt = $db->prepare($q);
    $stmt->bindParam(":id_trainer", $id_trainer_orig, PDO::PARAM_INT);
    $stmt->bindParam(":state_name", $state_name);
    $stmt->execute();
    
    return $stmt->fetchColumn();
}

function getTradeList($id_trainer_orig = null, $id_trainer_dest = null,
        $loadTrainers = PKMN_NO_LOAD, $orderBy = null, $rows = null) {
    global $db;
    
    $q = "select t.* from pkmn_trade t";

    $conds = array();
    if(!is_null($id_trainer_orig)){
        $conds[] = "t.id_trainer1 = $id_trainer_orig";
    }
    
    if(!is_null($id_trainer_dest)){
        $conds[] = "t.id_trainer2 = $id_trainer_dest";
    }
    
    if(empty($conds)){
        $q .= " where ";
        $q .= implode(" and ", $conds);
    }
    
    if (!is_null($orderBy)) {
        $q .= " order by $orderBy";
    }

    if (!is_null($rows)) {
        $q .= " limit $rows";
    }

    $stmt = $db->query($q);
    $objs = $stmt->fetchAll(PDO::FETCH_OBJ);
    if($loadTrainers != PKMN_NO_LOAD){
        foreach($objs as $trade){
            loadTradeData($trade, $loadTrainers);
        }
    }
    return $objs;
}

function getPgTradeList(&$res, $navPos, $records_per_page = 10,
        $id_trainer_orig = null, $id_trainer_dest = null,
        $loadTrainers = PKMN_NO_LOAD, $orderBy = null) {
    global $db;
    
    $pg = new Zebra_Pagination();
    $pg->navigation_position($navPos);

    $q = "select SQL_CALC_FOUND_ROWS t.* from pkmn_trade t";
    
    $conds = array();
    if(!is_null($id_trainer_orig)){
        $conds[] = "t.id_trainer1 = $id_trainer_orig";
    }
    
    if(!is_null($id_trainer_dest)){
        $conds[] = "t.id_trainer2 = $id_trainer_dest";
    }
    
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
    
    if($loadTrainers != PKMN_NO_LOAD){
        foreach($res as $trade){
            loadTradeData($trade, $loadTrainers);
        }
    }
    return $pg;
}

function insertTrade(&$obj) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_trade
        (creation_date,id_state,id_trainer1,id_trainer2,id_tpkmn1,id_tpkmn2)
        VALUES (:creation_date,:id_state,:id_trainer1,:id_trainer2,:id_tpkmn1,:id_tpkmn2)");
    
    $stmt->bindParam(":creation_date", $obj->creation_date);
    $stmt->bindParam(":id_state", $obj->id_state, PDO::PARAM_INT);
    $stmt->bindParam(":id_trainer1", $obj->id_trainer1, PDO::PARAM_INT);
    $stmt->bindParam(":id_trainer2", $obj->id_trainer2, PDO::PARAM_INT);
    $stmt->bindParam(":id_tpkmn1", $obj->id_tpkmn1, PDO::PARAM_INT);
    $stmt->bindParam(":id_tpkmn2", $obj->id_tpkmn2, PDO::PARAM_INT);
    $stmt->execute();
    $obj->id = $db->lastInsertId();
    return $obj->id;
}

function updateTrade($obj) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_trade
        SET creation_date = :creation_date,
        id_state = :id_state,
        id_trainer1 = :id_trainer1,
        id_trainer2 = :id_trainer2,
        id_tpkmn1 = :id_tpkmn1,
        id_tpkmn2 = :id_tpkmn2
        WHERE id = :id
    ");

    $stmt->bindParam(":id", $obj->id, PDO::PARAM_INT);
    $stmt->bindParam(":creation_date", $obj->creation_date);
    $stmt->bindParam(":id_state", $obj->id_state, PDO::PARAM_INT);
    $stmt->bindParam(":id_trainer1", $obj->id_trainer1, PDO::PARAM_INT);
    $stmt->bindParam(":id_trainer2", $obj->id_trainer2, PDO::PARAM_INT);
    $stmt->bindParam(":id_tpkmn1", $obj->id_tpkmn1, PDO::PARAM_INT);
    $stmt->bindParam(":id_tpkmn2", $obj->id_tpkmn2, PDO::PARAM_INT);
    $stmt->execute();
}

function cancelOtherTrades($obj) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_trade
        SET id_state = :id_state
        WHERE id != :id and id_tpkmn2 = :id_tpkmn
    ");

    $stmt->bindParam(":id", $obj->id, PDO::PARAM_INT);
    $stmt->bindParam(":id_tpkmn", $obj->id_tpkmn2, PDO::PARAM_INT);
    $stmt->bindParam(":id_state", getTradeStateByName('Cancelled')->id
            , PDO::PARAM_INT);
    $stmt->execute();
}

function deleteTrade($p){
    deleteTradeById($p->id);
}

function deleteTradeById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_trade
        WHERE id = :id");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

?>
