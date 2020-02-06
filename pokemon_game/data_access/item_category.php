<?php

function getPkmnItemCategory($id) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_item_category where id = " . $id);
        $res = $stmt->fetch(PDO::FETCH_OBJ);

        if ($res) {
            $obj = $res;
        }
    }

    return $obj;
}

function getPkmnItemCategoryList($orderBy = null, $rows = null) {
    global $db;
    $objs = array();

    $q = "select * from pkmn_item_category";

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

function insertPkmnItemCategory(&$obj) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_item_category(name)
             VALUES (:name)");

    $stmt->bindParam(":name", $obj->name);

    $stmt->execute();
    $obj->id = $db->lastInsertId();
    return $obj->id;
}

function updatePkmnItemCategory($p) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_item_category
        SET name = :name WHERE id = :id");

    $stmt->bindParam(":id", $p->id, PDO::PARAM_INT);
    $stmt->bindParam(":name", $p->name);

    $stmt->execute();
}

function deletePkmnItemCategory($p){
    deletePkmnItemCategoryById($p->id);
}

function deletePkmnItemCategoryById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_item_category
        WHERE id = :id");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

?>
