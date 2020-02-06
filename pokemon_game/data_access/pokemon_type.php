<?php
defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH . 'data_access/type_effect.php';

function getPokemonType($id, $loadEffects = false) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_pokemon_type where id = " . $id);
        $obj = $stmt->fetch(PDO::FETCH_OBJ);

        if ($obj) {
            if($loadEffects){
                $obj->effects = getTypeEffectsList($obj->id);
                foreach($obj->effects as $e){
                    unset($e->id_type_one);
                }
            }
        }
    }

    return $obj;
}

function getPokemonTypeByName($name, $loadEffects = false) {
    global $db;
    $obj = null;

    if (!is_null($name)) {
        $stmt = $db->query("select * from pkmn_pokemon_type where name = " . $name);
        $obj = $stmt->fetch(PDO::FETCH_OBJ);

        if ($obj) {
            if($loadEffects){
                $obj->effects = getTypeEffectsList($obj->id);
                foreach($obj->effects as $e){
                    unset($e->id_type_one);
                }
            }
        }
    }

    return $obj;
}

function getPokemonTypesByName($names, $loadEffects = false) {
    global $db;
    $objs = array();

    if (!empty($names)) {
        $stmt = $db->prepare("select * from pkmn_pokemon_type where name in (:names)");
        $stmt->bindParam(":names", $names);
        $stmt->execute();
        
        $objs = $stmt->fetchAll(PDO::FETCH_OBJ);

        if($loadEffects){
            foreach ($objs as $obj) {
                $obj->effects = getTypeEffectsList($obj->id);
                foreach ($obj->effects as $e) {
                    unset($e->id_type_one);
                }
            }
        }
    }

    return $objs;
}

function getPokemonTypesList($orderBy = null, $rows = null, $loadEffects = false) {
    global $db;
    $objs = array();

    $q = "select * from pkmn_pokemon_type";

    if (!is_null($orderBy)) {
        $q .= " order by $orderBy";
    }

    if (!is_null($rows)) {
        $q .= " limit $rows";
    }

    $stmt = $db->query($q);
    $objs = $stmt->fetchAll(PDO::FETCH_OBJ);

    if($loadEffects){
        foreach ($objs as $obj) {
            $obj->effects = getTypeEffectsList($obj->id);
            foreach ($obj->effects as $e) {
                unset($e->id_type_one);
            }
        }
    }

    return $objs;
}

function insertPokemonType($p) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_pokemon_type(name) VALUES (:name)");

    $stmt->bindParam(":name", $p->name);

    $stmt->execute();
    return $db->lastInsertId();
}

function assignPokemonTypes($pkmn, $types, $removeAll = false){
    global $db;
    
    $stmt = $db->prepare("INSERT INTO pkmn_pokemon_assig_type(id_type, id_pokemon)"
                        . " VALUES (:id_type, :id_pokemon)");
    
    if (!$removeAll) {
        //Insert only the non existent types
        $res = getAssignedPokemonTypes($pkmn->id);
        $c_types = array();
        foreach ($res as $r) {
            $c_types[] = $r->id;
        }

        foreach ($types as $type) {
            if (!in_array($type, $c_types)) {
                $stmt->bindParam(":id_pokemon", $pkmn->id, PDO::PARAM_INT);
                $stmt->bindParam(":id_type", $type, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    } else {
        //Clear assigned types
        removeAllAssigPokemonTypes($pkmn);
        
        foreach ($types as $type) {
            $stmt->bindParam(":id_pokemon", $pkmn->id, PDO::PARAM_INT);
            $stmt->bindParam(":id_type", $type, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}

function removeAssigPokemonTypes($pkmn, $types) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_pokemon_assig_type
            WHERE id_pokemon = :id_pokemon and id_type = :id_type");

    foreach ($types as $type) {
        $stmt->bindParam(":id_pokemon", $pkmn->id, PDO::PARAM_INT);
        $stmt->bindParam(":id_type", $type, PDO::PARAM_INT);
        $stmt->execute();
    }
}

function removeAllAssigPokemonTypes($pkmn) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_pokemon_assig_type
            WHERE id_pokemon = :id_pokemon");

    $stmt->bindParam(":id_pokemon", $pkmn->id, PDO::PARAM_INT);
    $stmt->execute();
}

function assignPkmnTypeEffects($type, $effects, $removeAll = false){
    global $db;
    
    $stmt = $db->prepare("INSERT INTO pkmn_type_effect
            (id_type_one, id_type_two, multiplier) VALUES
            (:id_type_one, :id_type_two, :multiplier)");

    if (!$removeAll) {
        //Insert only the non existent types
        $res = getTypeEffectsList($type->id);
        $c_types = array();
        foreach ($res as $r) {
            $c_types[] = $r->id_type_two;
        }

        foreach ($effects as $effect) {
            if (!in_array($effect->id, $c_types)) {
                $stmt->bindParam(":id_type_one", $type->id, PDO::PARAM_INT);
                $stmt->bindParam(":id_type_two", $effect->id, PDO::PARAM_INT);
                $stmt->bindParam(":multiplier", $effect->multiplier);
                $stmt->execute();
            }
        }
    } else {
        //Clear assigned types
        deleteAllTypeEffects($type);
        
        foreach ($effects as $effect) {
            $stmt->bindParam(":id_type_one", $type->id, PDO::PARAM_INT);
            $stmt->bindParam(":id_type_two", $effect->id, PDO::PARAM_INT);
            $stmt->bindParam(":multiplier", $effect->multiplier);
            $stmt->execute();
        }
    }
}

function getAssignedPokemonTypes($id_pokemon, $loadTypes = true, $orderBy = null, $rows = null) {
    global $db;
    $objs = array();

    $q = "select * from pkmn_pokemon_assig_type where id_pokemon = $id_pokemon";

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
        if($loadTypes){
            foreach($objs as $k => $obj){
                $type = getPokemonType($obj->id_type);
                $objs[$k] = $type;
            }
        }
    }

    return $objs;
}

function updatePokemonType($p) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_pokemon_type
        SET name = :name WHERE id = :id");

    $stmt->bindParam(":id", $p->id, PDO::PARAM_INT);
    $stmt->bindParam(":name", $p->name);

    $stmt->execute();
}

?>
