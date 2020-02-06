<?php
function getPokemonGroup($id){
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_pokemon_group where id = " . $id);
        $res = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($res) {
            $obj = $res;
        }
    }

    return $obj;
}

function getPokemonGroupsByName($names){
    global $db;
    $obj = null;

    if (!empty($names)) {
        $stmt = $db->prepare("select * from pkmn_pokemon_group
                 where name in (:names)");
        
        $stmt->bindParam(":names", $names);
        $stmt->execute();
        
        $res = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        if ($res) {
            $obj = $res;
        }
    }

    return $obj;
}

function getPokemonGroupList($orderBy = null, $rows = null){
    global $db;
    $objs = array();

    $q = "select * from pkmn_pokemon_group";
    
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
    }

    return $objs;
}

function insertPokemonGroup($p){
    global $db;
    
    $stmt = $db->prepare("INSERT INTO pkmn_pokemon_group(name) VALUES (:name)");

    $stmt->bindParam(":name", $p->name);

    $stmt->execute();
    return $db->lastInsertId();
}

function assignPokemonGroup($pkmn, $id_group){
    global $db;
    
    $stmt = $db->prepare("INSERT INTO pkmn_pokemon_assig_group(id_pokemon,id_group)
             VALUES (:id_pokemon,:id_group)");

    $stmt->bindParam(":id_pokemon", $pkmn->id, PDO::PARAM_INT);
    $stmt->bindParam(":id_group", $id_group, PDO::PARAM_INT);

    $stmt->execute();
}

function assignPokemonGroups($pkmn, $groups, $removeAll = false){
    global $db;
    
    $stmt = $db->prepare("INSERT INTO pkmn_pokemon_assig_group(id_group, id_pokemon)"
                        . " VALUES (:id_group, :id_pokemon)");

    if (!$removeAll) {
        //Insert only the non existent roles
        $res = getAssignedPokemonGroups($pkmn->id);
        $c_groups = array();
        foreach ($res as $r) {
            $c_groups[] = $r->id;
        }

        foreach ($groups as $group) {
            if (!in_array($group, $c_groups)) {
                $stmt->bindParam(":id_pokemon", $pkmn->id, PDO::PARAM_INT);
                $stmt->bindParam(":id_group", $group, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    } else {
        //Clear assigned types
        removeAllAssigPokemonGroups($pkmn);
        
        foreach ($groups as $group) {
            $stmt->bindParam(":id_pokemon", $pkmn->id, PDO::PARAM_INT);
            $stmt->bindParam(":id_group", $group, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}

function removeAssigPokemonGroup($pkmn, $id_group){
    global $db;
    
    $stmt = $db->prepare("DELETE FROM pkmn_pokemon_assig_group
            WHERE id_pokemon = :id_pokemon and id_group = :id_group");

    $stmt->bindParam(":id_pokemon", $pkmn->id, PDO::PARAM_INT);
    $stmt->bindParam(":id_group", $id_group, PDO::PARAM_INT);

    $stmt->execute();
}

function removeAssigPokemonGroups($pkmn, $groups) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_pokemon_assig_group
            WHERE id_pokemon = :id_pokemon and id_group = :id_group");

    foreach ($groups as $group) {
        $stmt->bindParam(":id_pokemon", $pkmn->id, PDO::PARAM_INT);
        $stmt->bindParam(":id_group", $group, PDO::PARAM_INT);
        $stmt->execute();
    }
}

function removeAllAssigPokemonGroups($pkmn) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_pokemon_assig_group
            WHERE id_pokemon = :id_pokemon");

    $stmt->bindParam(":id_pokemon", $pkmn->id, PDO::PARAM_INT);
    $stmt->execute();
}

function getAssignedPokemonGroups($id_pokemon, $loadGroups = true, $orderBy = null, $rows = null) {
    global $db;
    $objs = array();

    $q = "select * from pkmn_pokemon_assig_group where id_pokemon = $id_pokemon";

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
        if($loadGroups){
            foreach($objs as $k => $obj){
                $group = getPokemonGroup($obj->id_group);
                $objs[$k] = $group;
            }
        }
    }

    return $objs;
}

function updatePokemonGroup($p){
    global $db;
    
    $stmt = $db->prepare("UPDATE pkmn_pokemon_group
        SET name = :name WHERE id = :id");

    $stmt->bindParam(":id", $p->id, PDO::PARAM_INT);
    $stmt->bindParam(":name", $p->name);
    
    $stmt->execute();
}
?>
