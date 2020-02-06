<?php

function getTradeState($id) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_trade_state where id = " . $id);
        $res = $stmt->fetch(PDO::FETCH_OBJ);

        if ($res) {
            $obj = $res;
        }
    }

    return $obj;
}

function getTradeStateByName($name) {
    global $db;
    $obj = null;

    if (!is_null($name)) {
        $stmt = $db->prepare("select * from pkmn_trade_state where name = :name");
        $stmt->bindParam(":name", $name);
        $stmt->execute();
        
        $res = $stmt->fetch(PDO::FETCH_OBJ);

        if ($res) {
            $obj = $res;
        }
    }

    return $obj;
}

function getTradeStatesList($orderBy = null, $rows = null) {
    global $db;
    $objs = array();

    $q = "select * from pkmn_trade_state";

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

function insertTradeState($p) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_trade_state(visible, name)
            VALUES (:visible, :name)");

    $stmt->bindParam(":name", $p->name);

    $stmt->execute();
    return $db->lastInsertId();
}

function updateTradeState($p) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_trade_state
        SET visible = :visible, name = :name WHERE id = :id");

    $stmt->bindParam(":id", $p->id, PDO::PARAM_INT);
    $stmt->bindParam(":name", $p->name);

    $stmt->execute();
}

function deleteTradeState($p){
    deleteTradeStateById($p->id);
}

function deleteTradeStateById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_trade_state
        WHERE id = :id");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

?>
