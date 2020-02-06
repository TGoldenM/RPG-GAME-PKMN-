<?php
function getPokemonItem($id){
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_pokemon_item where id = " . $id);
        $res = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($res) {
            $obj = $res;
        }
    }

    return $obj;
}

function getPokemonItemsList($orderBy = null, $rows = null){
    global $db;
    $objs = array();

    $q = "select * from pkmn_pokemon_item";
    
    if(!is_null($orderBy)){
        $q .= " order by $orderBy";
    }
    
    if(!is_null($rows)){
         $q .= " limit $rows";
    }
    
    $stmt = $db->query($q);
    $res = $stmt->fetch(PDO::FETCH_OBJ);
    
    if ($res) {
        $objs = $res;
    }

    return $objs;
}

function insertPokemonItem($p){
    global $db;
    
    $stmt = $db->prepare("INSERT INTO pkmn_pokemon_item(name) VALUES (:name)");

    $stmt->bindParam(":name", $p->name);

    $stmt->execute();
    return $db->lastInsertId();
}

function updatePokemonItem($p){
    global $db;
    
    $stmt = $db->prepare("UPDATE pkmn_pokemon_item
        SET name = :name WHERE id = :id");

    $stmt->bindParam(":id", $p->id, PDO::PARAM_INT);
    $stmt->bindParam(":name", $p->name);
    
    $stmt->execute();
}
?>
