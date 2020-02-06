<?php

function getProperty($id) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from system_property where id = " . $id);
        $res = $stmt->fetch(PDO::FETCH_OBJ);

        if ($res) {
            $obj = $res;
        }
    }

    return $obj;
}

function getPropertyByName($name) {
    global $db;
    $obj = null;

    $stmt = $db->prepare("select * from system_property where name = :name");
    $stmt->bindParam(":name", $name);
    $stmt->execute();

    $res = $stmt->fetch(PDO::FETCH_OBJ);

    if ($res) {
        $obj = $res;
    }

    return $obj;
}

function getPropertiesList($orderBy = null, $rows = null) {
    global $db;
    $objs = array();

    $q = "select * from system_property";

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

function insertProperty($p) {
    global $db;

    $stmt = $db->prepare("INSERT INTO system_property(name, value)"
            . " VALUES (:name, :value)");

    $stmt->bindParam(":name", $p->name);
    $stmt->bindParam(":value", $p->value);

    $stmt->execute();
    return $db->lastInsertId();
}

function updateProperty($p) {
    global $db;

    $stmt = $db->prepare("UPDATE system_property
        SET name = :name, value = :value WHERE id = :id");

    $stmt->bindParam(":id", $p->id, PDO::PARAM_INT);
    $stmt->bindParam(":name", $p->name);
    $stmt->bindParam(":value", $p->value);

    $stmt->execute();
}

function deleteProperty($p){
    deletePropertyById($p->id);
}

function deletePropertyById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM system_property
        WHERE id = :id");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

?>
