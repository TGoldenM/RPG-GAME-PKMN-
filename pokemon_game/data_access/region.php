<?php

function getRegion($id) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_region where id = " . $id);
        $res = $stmt->fetch(PDO::FETCH_OBJ);

        if ($res) {
            $obj = $res;
        }
    }

    return $obj;
}

function getRegionsList($orderBy = null, $rows = null) {
    global $db;
    
    $q = "select * from pkmn_region";

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

function insertRegion($p) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_region(name) VALUES (:name)");
    $stmt->bindParam(":name", $p->name);
    $stmt->execute();
    return $db->lastInsertId();
}

function updateRegion($p) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_region
        SET name = :name WHERE id = :id");

    $stmt->bindParam(":id", $p->id, PDO::PARAM_INT);
    $stmt->bindParam(":name", $p->name);
    $stmt->execute();
}

function deleteRegion($p){
    deleteRegionById($p->id);
}

function deleteRegionById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_region
        WHERE id = :id");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

?>
