<?php

function getMoveCategory($id) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_move_category where id = " . $id);
        $res = $stmt->fetch(PDO::FETCH_OBJ);

        if ($res) {
            $obj = $res;
        }
    }

    return $obj;
}

function getMoveCategoryList($orderBy = null, $rows = null) {
    global $db;
    $objs = array();

    $q = "select * from pkmn_move_category";

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

function insertMoveCategory($p) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_move_category(name) VALUES (:name)");

    $stmt->bindParam(":name", $p->name);

    $stmt->execute();
    return $db->lastInsertId();
}

function updateMoveCategory($p) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_move_category
        SET name = :name WHERE id = :id");

    $stmt->bindParam(":id", $p->id, PDO::PARAM_INT);
    $stmt->bindParam(":name", $p->name);

    $stmt->execute();
}

function deleteMoveCategory($p){
    deleteMoveCategoryById($p->id);
}

function deleteMoveCategoryById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_move_category
        WHERE id = :id");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

?>
