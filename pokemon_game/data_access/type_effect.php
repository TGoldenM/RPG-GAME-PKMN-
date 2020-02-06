<?php
defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH . 'data_access/pokemon_type.php';

function getTypeEffect($id_type_one, $id_type_two) {
    global $db;
    $stmt = $db->prepare("select * from pkmn_type_effect
            where id_type_one = :id_type_one
            and id_type_two = :id_type_two");

    $stmt->bindParam(":id_type_one", $id_type_one, PDO::PARAM_INT);
    $stmt->bindParam(":id_type_two", $id_type_two, PDO::PARAM_INT);
    $stmt->execute();
    
    $obj = $stmt->fetch(PDO::FETCH_OBJ);
    return $obj;
}

function getTypeEffectsList($id_type_one = null, $loadTypeTwo = false
        , $orderBy = null, $rows = null) {
    global $db;
    $objs = array();

    $q = "select * from pkmn_type_effect";

    if (!is_null($id_type_one)){
        $q .= " where id_type_one = $id_type_one";
    }
    
    if (!is_null($orderBy)) {
        $q .= " order by $orderBy";
    }

    if (!is_null($rows)) {
        $q .= " limit $rows";
    }

    $stmt = $db->query($q);
    $objs = $stmt->fetchAll(PDO::FETCH_OBJ);

    if($loadTypeTwo){
        foreach ($objs as $obj) {
            $obj->type_two = getPokemonType($obj->id_type_two);
            unset($obj->id_type_two);
        }
    }

    return $objs;
}

function insertTypeEffect($p) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_type_effect
            (id_type_one, id_type_two, multiplier) VALUES
            (:id_type_one, :id_type_two, :multiplier)");

    $stmt->bindParam(":id_type_one", $p->id_type_one, PDO::PARAM_INT);
    $stmt->bindParam(":id_type_two", $p->id_type_two, PDO::PARAM_INT);
    $stmt->bindParam(":multiplier", $p->multiplier);

    $stmt->execute();
    return $p->id_type_one;
}

function updateTypeEffect($p) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_type_effect
        SET multiplier = :multiplier WHERE id_type_one = :id_type_one
        AND id_type_two = :id_type_two");

    $stmt->bindParam(":id_type_one", $p->id_type_one, PDO::PARAM_INT);
    $stmt->bindParam(":id_type_two", $p->id_type_two, PDO::PARAM_INT);
    $stmt->bindParam(":multiplier", $p->multiplier);

    $stmt->execute();
}

function deleteAllTypeEffects($type) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_type_effect
        WHERE id_type_one = :id_type_one");

    $stmt->bindParam(":id_type_one", $type->id, PDO::PARAM_INT);
    $stmt->execute();
}
?>
