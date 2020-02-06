<?php

function getStat($id) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_stat where id = " . $id);
        $res = $stmt->fetch(PDO::FETCH_OBJ);

        if ($res) {
            $obj = $res;
        }
    }

    return $obj;
}

function getStatsList($orderBy = null, $rows = null) {
    global $db;
    $objs = array();

    $q = "select * from pkmn_stat";

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

function insertStat(&$obj) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_stat(name)
             VALUES (:name)");

    $stmt->bindParam(":name", $obj->name);

    $stmt->execute();
    $obj->id = $db->lastInsertId();
    return $obj->id;
}

function updateStat($obj) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_stat
        SET name = :name WHERE id = :id");

    $stmt->bindParam(":id", $obj->id, PDO::PARAM_INT);
    $stmt->bindParam(":name", $obj->name);

    $stmt->execute();
}

function deleteStat($p){
    deleteStatById($p->id);
}

function deleteStatById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_stat
        WHERE id = :id");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

?>
