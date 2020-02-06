<?php
defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH . 'data_access/pokemon.php';
require_once ROOTPATH . 'data_access/item.php';

function initEvolution(&$obj, $typeLoad) {
    if($typeLoad != PKMN_NO_LOAD){
        $obj->required_item = getPkmnItem($obj->id_required_item);
        unset($obj->id_required_item);

        switch ($typeLoad) {
            case PKMN_DEEP_LOAD:
                $pkmn1 = getPokemon($obj->id_pkmn, true);
                $pkmn2 = getPokemon($obj->id_evolved_pkmn, true);

                $obj->pkmn = $pkmn1;
                unset($obj->id_pkmn);

                $obj->evolved_pkmn = $pkmn2;
                unset($obj->id_evolved_pkmn);
                break;
        }
    }
}

function getEvolution($id, $typeLoad = PKMN_NO_LOAD) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_evolution where id = " . $id);
        $obj = $stmt->fetch(PDO::FETCH_OBJ);

        if ($obj) {
            initEvolution($obj, $typeLoad);
        }
    }

    return $obj;
}

function getEvolutionList($id_pkmn = null, $orderBy = null, $rows = null,
        $typeLoad = PKMN_NO_LOAD) {
    global $db;
    $objs = array();

    $q = "select * from pkmn_evolution";
    if(!is_null($id_pkmn)){
        $q .= " where id_pkmn = $id_pkmn";
    }

    if (!is_null($orderBy)) {
        $q .= " order by $orderBy";
    }

    if (!is_null($rows)) {
        $q .= " limit $rows";
    }

    $stmt = $db->query($q);
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($res) {
        $objs = $res;
        if ($typeLoad != PKMN_NO_LOAD) {
            foreach ($objs as $obj) {
                initEvolution($obj, $typeLoad);
            }
        }
    }

    return $objs;
}

function insertEvolution(&$obj) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_evolution
        (id_pkmn,id_evolved_pkmn,id_required_item,required_exp) VALUES
        (:id_pkmn,:id_evolved_pkmn,:id_required_item,:required_exp)
    ");

    $stmt->bindParam(':id_pkmn', $obj->id_pkmn, PDO::PARAM_INT);
    $stmt->bindParam(':id_evolved_pkmn', $obj->id_evolved_pkmn, PDO::PARAM_INT);
    $stmt->bindParam(':id_required_item', $obj->id_required_item, PDO::PARAM_INT);
    $stmt->bindParam(':required_exp', $obj->required_exp, PDO::PARAM_INT);
    
    $stmt->execute();
    $obj->id = $db->lastInsertId();
    return $obj->id;
}

function updateEvolution($obj) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_evolution SET
        id_pkmn = :id_pkmn,
        id_evolved_pkmn = :id_evolved_pkmn,
        id_required_item = :id_required_item,
        required_exp = :required_exp
        WHERE id = :id
    ");

    $stmt->bindParam(":id", $obj->id, PDO::PARAM_INT);
    $stmt->bindParam(':id_pkmn', $obj->id_pkmn, PDO::PARAM_INT);
    $stmt->bindParam(':id_evolved_pkmn', $obj->id_evolved_pkmn, PDO::PARAM_INT);
    $stmt->bindParam(':id_required_item', $obj->id_required_item, PDO::PARAM_INT);
    $stmt->bindParam(':required_exp', $obj->required_exp, PDO::PARAM_INT);
    $stmt->execute();
}

function deleteEvolution($p){
    deleteEvolutionById($p->id);
}

function deleteEvolutionById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_evolution
        WHERE id = :id");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

?>
