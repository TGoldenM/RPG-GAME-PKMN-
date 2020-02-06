<?php

defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH . 'lib/Zebra_Pagination.php';
require_once ROOTPATH . 'data_access/pokemon.php';
require_once ROOTPATH . 'data_access/gym.php';

function initBadge(&$obj){
    $obj->gym = getGym($obj->id_gym);
    unset($obj->id_gym);
}

function getBadge($id, $typeLoad = PKMN_NO_LOAD) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_badge where id = " . $id);
        $obj = $stmt->fetch(PDO::FETCH_OBJ);

        if ($obj) {
            if($typeLoad != PKMN_NO_LOAD){
                initBadge($obj);
            }
        }
    }

    return $obj;
}

function getFirstBadgeByGym($id, $typeLoad = PKMN_NO_LOAD) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->prepare("select * from pkmn_badge where id_gym = :id
                limit 1");
        
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $obj = $stmt->fetch(PDO::FETCH_OBJ);

        if ($obj) {
            if($typeLoad != PKMN_NO_LOAD){
                initBadge($obj);
            }
        }
    }

    return $obj;
}

function getBadges($id_trainer, $typeLoad = PKMN_NO_LOAD,
        $orderBy = null, $rows = null) {
    global $db;
    $q = "select b.* from pkmn_trainer_badge tb
          left join pkmn_badge b on tb.id_badge = b.id
          where tb.id_trainer = :id";

    if (!is_null($orderBy)) {
        $q .= " order by $orderBy";
    }

    if (!is_null($rows)) {
        $q .= " limit $rows";
    }

    $stmt = $db->prepare($q);
    $stmt->bindParam(":id", $id_trainer, PDO::PARAM_INT);
    $stmt->execute();
    
    $objs = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($objs) {
        if($typeLoad != PKMN_NO_LOAD){
            foreach($objs as $obj){
                initBadge($obj);
            }
        }
    }

    return $objs;
}

function getPgBadgeList(&$res, $navPos, $records_per_page = 10,
        $typeLoad = PKMN_NO_LOAD) {
    global $db;
    $pg = new Zebra_Pagination();
    $pg->navigation_position($navPos);

    $q = "select SQL_CALC_FOUND_ROWS * from pkmn_badge";
    $q .= " limit " . (($pg->get_page() - 1) * $records_per_page)
            . ", " . $records_per_page;

    $stmt = $db->query($q);
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);

    if($typeLoad != PKMN_NO_LOAD){
        foreach ($res as $obj) {
            initBadge($obj);
        }
    }

    $stmt = $db->query("select FOUND_ROWS() as rows");
    $numRows = $stmt->fetchColumn();

    $pg->records($numRows);
    $pg->records_per_page($records_per_page);
    return $pg;
}

function assignBadges($trainer, $badges, $removeAll = true){
    global $db;
    
    $stmt = $db->prepare("INSERT INTO pkmn_trainer_badge(id_trainer, id_badge)"
                        . " VALUES (:id_trainer, :id_badge)");

    if (!$removeAll) {
        //Insert only the non existent badges
        $res = getBadges($trainer->id);
        $c_badges = array();
        foreach ($res as $r) {
            $c_badges[] = $r->id;
        }

        foreach ($badges as $id_badge) {
            if (!in_array($id_badge, $c_badges)) {
                $stmt->bindParam(":id_trainer", $trainer->id, PDO::PARAM_INT);
                $stmt->bindParam(":id_badge", $id_badge, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    } else {
        //Clear assigned badges
        removeAllBadges($trainer);
        
        foreach ($badges as $id_badge) {
            $stmt->bindParam(":id_trainer", $trainer->id, PDO::PARAM_INT);
            $stmt->bindParam(":id_badge", $id_badge, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}

function removeBadge($trainer, $id_badge){
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_trainer_badge"
            . " where id_trainer = :id_trainer and id_badge = :id_badge");

    $stmt->bindParam(":id_trainer", $trainer->id, PDO::PARAM_INT);
    $stmt->bindParam(":id_badge", $id_badge, PDO::PARAM_INT);

    $stmt->execute();
}

function removeBadges($trainer, $badges){
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_trainer_badge"
                . " where id_trainer = :id_trainer and id_badge = :id_badge");

    foreach ($badges as $id_badge) {
        $stmt->bindParam(":id_trainer", $trainer->id, PDO::PARAM_INT);
        $stmt->bindParam(":id_badge", $id_badge, PDO::PARAM_INT);
        $stmt->execute();
    }
}

function removeAllBadges($trainer){
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_trainer_badge"
            . " where id_trainer = :id_trainer");

    $stmt->bindParam(":id_trainer", $trainer->id, PDO::PARAM_INT);
    
    $stmt->execute();
}

function insertBadge(&$obj) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_badge(id_gym, name, image)
             VALUES (:id_gym, :name, :image)");

    $stmt->bindParam(":id_gym", $obj->id_gym, PDO::PARAM_INT);
    $stmt->bindParam(":name", $obj->name);
    $stmt->bindParam(":image", $obj->image);

    $stmt->execute();
    $obj->id = $db->lastInsertId();
    return $obj->id;
}

function updateBadge($obj) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_badge
        SET id_gym = :id_gym, name = :name, image = :image
        WHERE id = :id");

    $stmt->bindParam(":id", $obj->id, PDO::PARAM_INT);
    $stmt->bindParam(":id_gym", $obj->id_gym, PDO::PARAM_INT);
    $stmt->bindParam(":name", $obj->name);
    $stmt->bindParam(":image", $obj->image);

    $stmt->execute();
}

function deleteBadge($p){
    deleteBadgeById($p->id);
}

function deleteBadgeById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_badge
        WHERE id = :id");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

?>
