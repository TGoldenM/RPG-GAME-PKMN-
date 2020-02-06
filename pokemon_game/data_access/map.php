<?php
defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH . 'lib/Zebra_Pagination.php';

function getMap($id) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_map where id = " . $id);
        $res = $stmt->fetch(PDO::FETCH_OBJ);

        if ($res) {
            $obj = $res;
        }
    }

    return $obj;
}

function getMapsList($id_region, $orderBy = null, $rows = null) {
    global $db;
    $q = "select * from pkmn_map";
    $q .= " where id_region = $id_region";

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

function getPgMapsList(&$res, $navPos, $id_region
        , $orderBy = null, $records_per_page = 10) {
    global $db;
    $pg = new Zebra_Pagination();
    $pg->navigation_position($navPos);

    $q = "select * from pkmn_map";
    $q .= " where id_region = $id_region";

    if (!is_null($orderBy)) {
        $q .= " order by $orderBy";
    }

    $q .= " limit " . (($pg->get_page() - 1) * $records_per_page)
            . ", " . $records_per_page;

    $stmt = $db->query($q);
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);
    $stmt = $db->query("select FOUND_ROWS() as rows");
    $numRows = $stmt->fetchColumn();

    $pg->records($numRows);
    $pg->records_per_page($records_per_page);
    
    return $pg;
}

function insertMap(&$p) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_map(id_region, name, image)
             VALUES (:id_region, :name, :image)");

    $stmt->bindParam(":id_region", $p->id_region, PDO::PARAM_INT);
    $stmt->bindParam(":name", $p->name);
    $stmt->bindParam(":image", $p->image);
    $stmt->execute();
    $p->id = $db->lastInsertId();
    return $p->id;
}

function updateMap($p) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_map
        SET id_region = :id_region, name = :name
        , image = :image WHERE id = :id");

    $stmt->bindParam(":id", $p->id, PDO::PARAM_INT);
    $stmt->bindParam(":id_region", $p->id_region, PDO::PARAM_INT);
    $stmt->bindParam(":name", $p->name);
    $stmt->bindParam(":image", $p->image);

    $stmt->execute();
}

function deleteMap($p){
    deleteMapById($p->id);
}

function deleteMapById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_map
        WHERE id = :id");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

?>
