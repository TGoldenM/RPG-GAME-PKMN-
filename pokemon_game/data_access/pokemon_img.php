<?php
defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__).'/'));
require_once ROOTPATH.'util/url_functions.php';

function getPokemonImg($id, $createImgUrl = true){
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_pokemon_img where id = " . $id);
        $res = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($res) {
            $obj = $res;
        }
    }

    return $obj;
}

function getPokemonImgByType($id_pokemon, $type = 0, $createImgUrl = true){
    global $db;
    $obj = null;

    $stmt = $db->prepare("select * from pkmn_pokemon_img
            where id_pokemon = :id_pokemon
            and type = :type limit 1");
    
    $stmt->bindParam(':id_pokemon', $id_pokemon, PDO::PARAM_INT);
    $stmt->bindParam(':type', $type, PDO::PARAM_INT);
    $stmt->execute();
    
    $res = $stmt->fetch(PDO::FETCH_OBJ);

    if ($res) {
        $obj = $res;
        if($createImgUrl){
            $obj->image_url = buildPkmnImgURL($id_pokemon);
        }
    }

    return $obj;
}

function getPokemonImgList($id_pokemon = null, $createImgUrl = true
        , $orderBy = null, $rows = null){
    global $db;
    $objs = array();

    $q = "select * from pkmn_pokemon_img";
    
    if(!is_null($id_pokemon)){
        $q .= " where id_pokemon = $id_pokemon";
    }
    
    if(!is_null($orderBy)){
        $q .= " order by $orderBy";
    }
    
    if(!is_null($rows)){
         $q .= " limit $rows";
    }
    
    $stmt = $db->query($q);
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    if ($res) {
        $objs = $res;
        if($createImgUrl){
            foreach($objs as $obj){
                $obj->image_url = buildPkmnImgURL($obj->id_pokemon, $obj->type);
            }
        }
    }

    return $objs;
}

function insertPokemonImg($p){
    global $db;
    
    $stmt = $db->prepare("INSERT INTO pkmn_pokemon_img (id_pokemon,type,image)
                VALUES (:id_pokemon,:type,:image)");

    $stmt->bindParam(":id_pokemon", $p->id_pokemon, PDO::PARAM_INT);
    $stmt->bindParam(":type", $p->type, PDO::PARAM_INT);
    $stmt->bindParam(":image", $p->image);
    
    $stmt->execute();
    return $db->lastInsertId();
}

function updatePokemonImg($p){
    global $db;
    
    $stmt = $db->prepare("UPDATE pkmn_pokemon_img
            SET id_pokemon = :id_pokemon,
            type = :type, image = :image
            WHERE id = :id
        ");

    $stmt->bindParam(":id", $p->id, PDO::PARAM_INT);
    $stmt->bindParam(":id_pokemon", $p->id_pokemon, PDO::PARAM_INT);
    $stmt->bindParam(":type", $p->type, PDO::PARAM_INT);
    $stmt->bindParam(":image", $p->image);
    
    $stmt->execute();
}
?>
