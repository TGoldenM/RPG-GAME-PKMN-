<?php

function getTrainerType($id) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_trainer_type where id = " . $id);
        $res = $stmt->fetch(PDO::FETCH_OBJ);

        if ($res) {
            $obj = $res;
        }
    }

    return $obj;
}

function getTrainerTypeByName($name) {
    global $db;
    
	$stmt = $db->prepare("select * from pkmn_trainer_type where name = :name");
	$stmt->bindParam(':name', $name);
	$stmt->execute();
	$obj = $stmt->fetch(PDO::FETCH_OBJ);
	
    return $obj;
}

function getTrainerTypesList($orderBy = null, $rows = null) {
    global $db;
    $objs = array();

    $q = "select * from pkmn_trainer_type";

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

function insertTrainerType($p) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_trainer_type(name)
            VALUES (:name)");

    $stmt->bindParam(":name", $p->name);

    $stmt->execute();
    return $db->lastInsertId();
}

function updateTrainerType($p) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_trainer_type
        SET name = :name WHERE id = :id");

    $stmt->bindParam(":id", $p->id, PDO::PARAM_INT);
    $stmt->bindParam(":name", $p->name);

    $stmt->execute();
}

function deleteTrainerType($p){
    deleteTrainerTypeById($p->id);
}

function deleteTrainerTypeById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_trainer_type
        WHERE id = :id");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

?>
