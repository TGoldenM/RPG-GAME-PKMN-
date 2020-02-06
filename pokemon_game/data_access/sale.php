<?php
defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH . 'lib/Zebra_Pagination.php';
require_once ROOTPATH . 'data_access/user.php';
require_once ROOTPATH . 'data_access/trainer.php';

function getSale($id) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_sale where id = " . $id);
        $res = $stmt->fetch(PDO::FETCH_OBJ);

        if ($res) {
            $obj = $res;
        }
    }

    return $obj;
}

function getSalesList($orderBy = null, $rows = null) {
    global $db;
    $q = "select * from pkmn_sale";

    if (!is_null($orderBy)) {
        $q .= " order by $orderBy";
    }

    if (!is_null($rows)) {
        $q .= " limit $rows";
    }

    $stmt = $db->query($q);
    $objs = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $objs;
}

function getPgSalesList(&$res, $id_trainer, $navPos, $records_per_page = 10,
        $loadPkmn = false, $loadTrainer = false) {
    global $db;
    $pg = new Zebra_Pagination();
    $pg->navigation_position($navPos);

    $q = "select SQL_CALC_FOUND_ROWS * from pkmn_sale";
    $q .= " where id_trainer = $id_trainer";
    $q .= " limit " . (($pg->get_page() - 1) * $records_per_page)
                . ", " . $records_per_page;
    
    $stmt = $db->query($q);
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    $stmt = $db->query("select FOUND_ROWS() as rows");
    $numRows = $stmt->fetchColumn();
    $pg->records($numRows);
    $pg->records_per_page($records_per_page);
    
    foreach($res as $obj){
        if($loadPkmn){
            $obj->pokemon = getPokemon($obj->id_pokemon);
            unset($obj->id_pokemon);
        }
        
        if($loadTrainer){
            $obj->id_trainer = getTrainer($obj->id_trainer);
            unset($obj->id_trainer);
        }
    }
    
    return $pg;
}

function insertSale(&$obj) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_sale
        (id_pokemon,id_trainer,date_sold,exp,gold,silver)
        VALUES (:id_pokemon,:id_trainer,:date_sold,:exp,:gold,:silver)");
    
    $stmt->bindParam(':id_pokemon', $obj->id_pokemon, PDO::PARAM_INT);
    $stmt->bindParam(':id_trainer', $obj->id_trainer, PDO::PARAM_INT);
    $stmt->bindParam(':date_sold', $obj->date_sold);
    $stmt->bindParam(':exp', $obj->exp, PDO::PARAM_INT);
    $stmt->bindParam(':gold', $obj->gold, PDO::PARAM_INT);
    $stmt->bindParam(':silver', $obj->silver, PDO::PARAM_INT);
    
    $stmt->execute();
    $obj->id = $db->lastInsertId();
    return $obj->id;
}

function updateSale($obj) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_sale SET
        id_pokemon = :id_pokemon,
        id_trainer = :id_trainer,
        date_sold = :date_sold,
        exp = :exp,
        gold = :gold,
        silver = :silver
        WHERE id = :id
    ");

    $stmt->bindParam(":id", $obj->id, PDO::PARAM_INT);
    $stmt->bindParam(':id_pokemon', $obj->id_pokemon, PDO::PARAM_INT);
    $stmt->bindParam(':id_trainer', $obj->id_trainer, PDO::PARAM_INT);
    $stmt->bindParam(':date_sold', $obj->date_sold);
    $stmt->bindParam(':exp', $obj->exp, PDO::PARAM_INT);
    $stmt->bindParam(':gold', $obj->gold, PDO::PARAM_INT);
    $stmt->bindParam(':silver', $obj->silver, PDO::PARAM_INT);
    
    $stmt->execute();
}

function deleteSale($p){
    deleteSaleById($p->id);
}

function deleteSaleById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_sale
        WHERE id = :id");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

?>
