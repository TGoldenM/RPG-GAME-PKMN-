<?php

function getFaction($id) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_faction where id = " . $id);
        $res = $stmt->fetch(PDO::FETCH_OBJ);

        if ($res) {
            $obj = $res;
        }
    }

    return $obj;
}

function getFactionsList($visible = null, $orderBy = null, $rows = null) {
    global $db;
    $objs = array();

    $q = "select * from pkmn_faction";

    if(!is_null($visible)){
        $q .= " where visible = $visible";
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
    }

    return $objs;
}

function insertFaction(&$obj) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_faction(visible, name, points)
             VALUES (:visible, :name, :points)");

    $stmt->bindParam(":visible", $obj->visible, PDO::PARAM_BOOL);
    $stmt->bindParam(":name", $obj->name);
    $stmt->bindParam(":points", $obj->points, PDO::PARAM_INT);
    
    $stmt->execute();
    $obj->id = $db->lastInsertId();
    return $obj->id;
}

function updateFaction($obj) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_faction
        SET visible = :visible, name = :name, points = :points
        WHERE id = :id");

    $stmt->bindParam(":id", $obj->id, PDO::PARAM_INT);
    $stmt->bindParam(":visible", $obj->visible, PDO::PARAM_BOOL);
    $stmt->bindParam(":name", $obj->name);
    $stmt->bindParam(":points", $obj->points, PDO::PARAM_INT);
    
    $stmt->execute();
}

function deleteFaction($p){
    deleteFactionById($p->id);
}

function deleteFactionById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_faction
        WHERE id = :id");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

?>
