<?php

function getGym($id) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_gym where id = " . $id);
        $res = $stmt->fetch(PDO::FETCH_OBJ);

        if ($res) {
            $obj = $res;
        }
    }

    return $obj;
}

function getGymsList($orderBy = null, $rows = null) {
    global $db;
    $objs = array();

    $q = "select * from pkmn_gym";

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
    }

    return $objs;
}

function insertGym(&$obj) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_gym(id_region, name)
             VALUES (:id_region, :name)");

    $stmt->bindParam(":id_region", $obj->id_region, PDO::PARAM_INT);
    $stmt->bindParam(":name", $obj->name);

    $stmt->execute();
    $obj->id = $db->lastInsertId();
    return $obj->id;
}

function updateGym($obj) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_gym
        SET id_region = :id_region, name = :name WHERE id = :id");

    $stmt->bindParam(":id", $obj->id, PDO::PARAM_INT);
    $stmt->bindParam(":id_region", $obj->id_region, PDO::PARAM_INT);
    $stmt->bindParam(":name", $obj->name);

    $stmt->execute();
}

function deleteGym($p){
    deleteGymById($p->id);
}

function deleteGymById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_gym
        WHERE id = :id");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

?>
