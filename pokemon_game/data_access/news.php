<?php
defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH . 'lib/Zebra_Pagination.php';
require_once ROOTPATH . 'data_access/trainer.php';

function getNews($id) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_news where id = " . $id);
        $res = $stmt->fetch(PDO::FETCH_OBJ);

        if ($res) {
            $obj = $res;
        }
    }

    return $obj;
}

function getNewsList($orderBy = null, $rows = null) {
    global $db;
    $objs = array();

    $q = "select * from pkmn_news";

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

function getPgNewsList(&$res, $navPos, $records_per_page = 10
        , $orderBy = null) {
    global $db;
    $pg = new Zebra_Pagination();
    $pg->navigation_position($navPos);

    $q = "select SQL_CALC_FOUND_ROWS * from pkmn_news";
    
    if (!is_null($orderBy)) {
        $q .= " order by $orderBy";
    }
    
    $q .= " limit " . (($pg->get_page() - 1) * $records_per_page)
                . ", " . $records_per_page;
    
    $stmt = $db->query($q);
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);
    
//    foreach($res as $obj){
//    }
    
    $stmt = $db->query("select FOUND_ROWS() as rows");
    $numRows = $stmt->fetchColumn();
    
    $pg->records($numRows);
    $pg->records_per_page($records_per_page);
    return $pg;
}

function insertNews($p) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_news(id_trainer,title,created,content)"
            . " VALUES (:id_trainer,:title,:created,:content)");

    $stmt->bindParam(":id_trainer", $p->id_trainer, PDO::PARAM_INT);
    $stmt->bindParam(":title", $p->title);
    $stmt->bindParam(":created", $p->created);
    $stmt->bindParam(":content", $p->content);
            
    $stmt->execute();
    return $db->lastInsertId();
}

function updateNews($p) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_news SET 
        id_trainer = :id_trainer, title = :title, created = :created,
        content = :content WHERE id = :id");

    $stmt->bindParam(":id", $p->id, PDO::PARAM_INT);
    $stmt->bindParam(":id_trainer", $p->id_trainer, PDO::PARAM_INT);
    $stmt->bindParam(":title", $p->title);
    $stmt->bindParam(":created", $p->created);
    $stmt->bindParam(":content", $p->content);
    
    $stmt->execute();
}

function deleteNews($p) {
    deleteNewsById($p->id);
}

function deleteNewsById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_news WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    
    $stmt->execute();
}

?>
