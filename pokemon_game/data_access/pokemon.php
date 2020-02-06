<?php

defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH . 'lib/Zebra_Pagination.php';
require_once ROOTPATH . 'data_access/pokemon_img.php';
require_once ROOTPATH . 'data_access/pokemon_type.php';
require_once ROOTPATH . 'data_access/pokemon_group.php';

defined('PKMN_NO_LOAD') || define('PKMN_NO_LOAD', 0);
defined('PKMN_SIMPLE_LOAD') || define('PKMN_SIMPLE_LOAD', 1);
defined('PKMN_DEEP_LOAD') || define('PKMN_DEEP_LOAD', 2);

function getPokemonAndInit($id, $typeLoad) {
    switch ($typeLoad) {
        case PKMN_SIMPLE_LOAD:
            $pkmn = getPokemon($id);
            break;

        case PKMN_DEEP_LOAD:
            $pkmn = getPokemon($id, true, true, true);
    }

    return $pkmn;
}

function initPkmnData(&$pkmn, $loadImgs = false, $loadTypes = false,
        $loadGroups = false) {
    
    if ($loadImgs) {
        $imgs = getPokemonImgList($pkmn->id);
        $pkmn->imgs = $imgs;
    }

    if ($loadTypes) {
        $types = getAssignedPokemonTypes($pkmn->id);
        $pkmn->types = $types;
    }

    if ($loadGroups) {
        $groups = getAssignedPokemonGroups($pkmn->id);
        $pkmn->groups = $groups;
    }
}

function getPokemon($id, $loadImgs = false, $loadTypes = false,
        $loadGroups = false) {
    global $db;
    $res = null;
    
    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_pokemon where id = " . $id);
        $res = $stmt->fetch(PDO::FETCH_OBJ);

        if ($res) {
            initPkmnData($res, $loadImgs, $loadTypes, $loadGroups);
        }
    }

    return $res;
}

function getRandPokemon($loadImgs = false, $loadTypes = false,
        $loadGroups = false) {
    global $db;

    $stmt = $db->query("select * from pkmn_pokemon
            where disabled != 1
            order by rand() limit 1");
    
    $res = $stmt->fetch(PDO::FETCH_OBJ);

    if ($res) {
        initPkmnData($res, $loadImgs, $loadTypes, $loadGroups);
    }

    return $res;
}

function getPokemonByName($name, $loadImgs = false, $loadTypes = false,
        $loadGroups = false) {
    global $db;

    $stmt = $db->prepare("select * from pkmn_pokemon where name = :name");
    $stmt->bindParam(":name", $name);
    $stmt->execute();

    $res = $stmt->fetch(PDO::FETCH_OBJ);

    if ($res) {
        initPkmnData($res, $loadImgs, $loadTypes, $loadGroups);
    }

    return $res;
}

function getPokemonsList($name = null, $loadImgs = false, $loadTypes = false,
        $loadGroups = false, $orderBy = null, $rows = 100) {
    global $db;
    $objs = array();

    $q = "select * from pkmn_pokemon";

    if (!is_null($name)) {
        $q .= " where name like '$name'";
    }

    if (!is_null($orderBy)) {
        $q .= " order by $orderBy";
    }

    if (!is_null($rows)) {
        $q .= " limit $rows";
    }

    $stmt = $db->query($q);
    $objs = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($objs) {
        foreach ($objs as $obj) {
            initPkmnData($obj, $loadImgs, $loadTypes, $loadGroups);
        }
    }

    return $objs;
}

function getPgPokemonsList(&$res, $navPos, $name = null, $records_per_page = 10,
        $loadImgs = false, $loadTypes = false, $loadGroups = false,
        $disabled = null) {
    global $db;
    $pg = new Zebra_Pagination();
    $pg->navigation_position($navPos);

    $q = "select SQL_CALC_FOUND_ROWS * from pkmn_pokemon";

    $conds = array();
    if (!is_null($name)){
        $conds[] = "name like '%$name%'";
    }

    if (!is_null($disabled)) {
        $conds[] = "disabled = $disabled";
    }
    
    if(!empty($conds)){
        $q .= " where ";
        $q .= implode(" and ", $conds);
    }
    
    $q .= " limit " . (($pg->get_page() - 1) * $records_per_page)
            . ", " . $records_per_page;

    $stmt = $db->query($q);
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);

    $numRows = $db->query("select FOUND_ROWS() as rows")->fetchColumn();

    $pg->records($numRows);
    $pg->records_per_page($records_per_page);

    foreach ($res as $obj) {
        initPkmnData($obj, $loadImgs, $loadTypes, $loadGroups);
    }

    return $pg;
}

function insertPokemon(&$obj) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_pokemon
            (initial,disabled,genderless,name,hp,attack,defense,speed
            ,spec_attack,spec_defense,base_exp,evhp,evattack,evdefense,evspeed
            ,evspec_attack,evspec_defense)
            VALUES (:initial,:disabled,:genderless,:name,:hp,:attack,:defense,:speed
            ,:spec_attack,:spec_defense,:base_exp,:evhp,:evattack,:evdefense,:evspeed
            ,:evspec_attack,:evspec_defense)");

    $stmt->bindParam(":initial", $obj->initial, PDO::PARAM_BOOL);
    $stmt->bindParam(":disabled", $obj->disabled, PDO::PARAM_BOOL);
    $stmt->bindParam(":genderless", $obj->genderless, PDO::PARAM_BOOL);
    $stmt->bindParam(":name", $obj->name);
    $stmt->bindParam(":hp", $obj->hp, PDO::PARAM_INT);
    $stmt->bindParam(":attack", $obj->attack, PDO::PARAM_INT);
    $stmt->bindParam(":defense", $obj->defense, PDO::PARAM_INT);
    $stmt->bindParam(":speed", $obj->speed, PDO::PARAM_INT);
    $stmt->bindParam(":spec_attack", $obj->spec_attack, PDO::PARAM_INT);
    $stmt->bindParam(":spec_defense", $obj->spec_defense, PDO::PARAM_INT);
    $stmt->bindParam(":base_exp", $obj->base_exp, PDO::PARAM_INT);
    $stmt->bindParam(":evhp", $obj->evhp, PDO::PARAM_INT);
    $stmt->bindParam(":evattack", $obj->evattack, PDO::PARAM_INT);
    $stmt->bindParam(":evdefense", $obj->evdefense, PDO::PARAM_INT);
    $stmt->bindParam(":evspeed", $obj->evspeed, PDO::PARAM_INT);
    $stmt->bindParam(":evspec_attack", $obj->evspec_attack, PDO::PARAM_INT);
    $stmt->bindParam(":evspec_defense", $obj->evspec_defense, PDO::PARAM_INT);

    $stmt->execute();
    $obj->id = $db->lastInsertId();
    return $obj->id;
}

function updatePokemon($obj) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_pokemon SET
        initial = :initial,
        disabled = :disabled,
        genderless = :genderless,
        name = :name,
        hp = :hp,
        attack = :attack,
        defense = :defense,
        speed = :speed,
        spec_attack = :spec_attack,
        spec_defense = :spec_defense,
        base_exp = :base_exp,
        evhp = :evhp,
        evattack = :evattack,
        evdefense = :evdefense,
        evspeed = :evspeed,
        evspec_attack = :evspec_attack,
        evspec_defense = :evspec_defense
        WHERE id = :id
        ");

    $stmt->bindParam(":id", $obj->id, PDO::PARAM_INT);
    $stmt->bindParam(":initial", $obj->initial, PDO::PARAM_BOOL);
    $stmt->bindParam(":disabled", $obj->disabled, PDO::PARAM_BOOL);
    $stmt->bindParam(":genderless", $obj->genderless, PDO::PARAM_BOOL);
    $stmt->bindParam(":name", $obj->name);
    $stmt->bindParam(":hp", $obj->hp, PDO::PARAM_INT);
    $stmt->bindParam(":attack", $obj->attack, PDO::PARAM_INT);
    $stmt->bindParam(":defense", $obj->defense, PDO::PARAM_INT);
    $stmt->bindParam(":speed", $obj->speed, PDO::PARAM_INT);
    $stmt->bindParam(":spec_attack", $obj->spec_attack, PDO::PARAM_INT);
    $stmt->bindParam(":spec_defense", $obj->spec_defense, PDO::PARAM_INT);
    $stmt->bindParam(":base_exp", $obj->base_exp, PDO::PARAM_INT);
    $stmt->bindParam(":evhp", $obj->evhp, PDO::PARAM_INT);
    $stmt->bindParam(":evattack", $obj->evattack, PDO::PARAM_INT);
    $stmt->bindParam(":evdefense", $obj->evdefense, PDO::PARAM_INT);
    $stmt->bindParam(":evspeed", $obj->evspeed, PDO::PARAM_INT);
    $stmt->bindParam(":evspec_attack", $obj->evspec_attack, PDO::PARAM_INT);
    $stmt->bindParam(":evspec_defense", $obj->evspec_defense, PDO::PARAM_INT);

    $stmt->execute();
}

?>
