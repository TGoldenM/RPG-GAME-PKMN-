<?php
defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH . 'lib/Zebra_Pagination.php';
require_once ROOTPATH . 'data_access/pokemon.php';
require_once ROOTPATH . 'data_access/evolution.php';
require_once ROOTPATH . 'data_access/pokemon_move.php';
require_once ROOTPATH . 'data_access/trainer.php';
require_once ROOTPATH . 'util/str_functions.php';

function getPkmnLevel($exp) {
    return round(pow($exp > 0 ? $exp : 1, 1 / 3));
}

function getPkmnExp($lvl){
    return $lvl > 0 ? pow($lvl, 3) : $lvl;
}

function getPkmnGenderGraphic($tpkmn){
    switch($tpkmn->gender){
        case 'M': //Male
            return '&#9794;';
        case 'F': //Female
            return '&#9792';
        case '?': //Ungendered
            return '?';
    }
    
    return null;
}

function getTrainerPkmnLeveled($baseLvl, $pkmn, $trainer, $randLvl = true) {
    if ($randLvl) {
        $midLvl = round($baseLvl / 2);
        $lvl = rand($baseLvl, $baseLvl + $midLvl);
    } else {
        $lvl = $baseLvl;
    }

    $gender = rand(0, 1);
    $iv = rand(0, 15);
    
    $hp = (int)floor(($iv + $pkmn->hp + (sqrt($pkmn->evhp) / 8) + 50) * $lvl / 50) + 10;

    $others = array("attack" => null,
        "defense" => null,
        "speed" => null,
        "spec_attack" => null,
        "spec_defense" => null);

    foreach ($others as $k => $v) {
        $ev = "ev$k";
        $others[$k] = (int)floor(($iv + $pkmn->$k + (sqrt($pkmn->$ev) / 8)) * $lvl / 50) + 5;
    }

    $tpkmn = array_merge(array(
        "id" => generateRandomString(4),
        "trainer" => $trainer,
        "pokemon" => $pkmn,
        "moves" => getRandPkmnMoves($pkmn->types, true, true, 10),
        "id_item" => null,
        "exp" => getPkmnExp($lvl),
        "level" => $lvl,
        "equipped" => 1,
        "tradeable" => 0,
        "sellable" => 0,
        "gender" => $pkmn->genderless == 1 ? '?' : ($gender == 0 ? 'M' : 'F'),
        "hp" => $hp,
        "cur_hp" => $hp,
        "gold" => 0,
        "silver" => 0
    ), $others);

    return (object) $tpkmn;
}

function getTrainerPokemon($id, $typeLoad = PKMN_NO_LOAD) {
    global $db;
    $obj = null;

    if (!is_null($id)) {

        $q = "select * from pkmn_trainer_pokemon";
        $q .= " where id = " . $id;

        $stmt = $db->query($q);
        $obj = $stmt->fetch(PDO::FETCH_OBJ);

        if ($obj) {
            $obj->level = getPkmnLevel($obj->exp);
            if ($typeLoad != PKMN_NO_LOAD) {
                $obj->trainer = getTrainer($obj->id_trainer, $typeLoad);
                unset($obj->id_trainer);
                
                if($typeLoad == PKMN_DEEP_LOAD){
                    $obj->pokemon = getPokemonAndInit($obj->id_pokemon, $typeLoad);
                    unset($obj->id_pokemon);

                    $obj->moves = getAssignedMoves($obj->id, true, true);
                }
            }
        }
    }

    return $obj;
}

function getFirstAvailablePkmn($tpkmns) {
    foreach ($tpkmns as $tpkmn) {
        if (round($tpkmn->cur_hp/$tpkmn->hp * 100) > 0) {
            return $tpkmn;
        }
    }

    return null;
}

function getHighestLvlTradPkmn($tpkmns){
    $exp = 0;
    $r = null;
    foreach ($tpkmns as $tpkmn) {
        if ($tpkmn->exp > $exp && $tpkmn->tradeable) {
            $exp = $tpkmn->exp;
            $r = $tpkmn;
        }
    }

    return $r;
}

function getRankingLevelPkmn(){
    global $db;

    $stmt = $db->query("select u.username username,
        p.name pkmn, round(pow(tp.exp, 1/3)) level
        from pkmn_trainer_pokemon tp
        inner join pkmn_pokemon p on p.id = tp.id_pokemon
        inner join pkmn_trainer tr on tr.id = tp.id_trainer
        inner join system_user u on u.id = tr.id_user
        order by tp.exp desc
        limit 10");

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getEqpTPkmnCount($id_trainer = null){
    global $db;

    $q = "select count(tp.id_pokemon) from pkmn_trainer_pokemon tp";
    
    $conds = array();
    $conds[] = "tp.equipped = 1";
    if(!is_null($id_trainer)){
        $conds[] = "tp.id_trainer = $id_trainer";
    }
    
    if(!empty($conds)){
        $q .= " where ";
        $q .= implode(" and ", $conds);
    }
    
    $cnt = $db->query($q)->fetchColumn();
    return (int)$cnt;
}

function isPkmnCaught($id_pokemon, $id_trainer){
    global $db;

    $stmt = $db->prepare("select count(tp.id_pokemon)
        from pkmn_trainer_pokemon tp
        where tp.id_pokemon = :id_pokemon
        and tp.id_trainer = :id_trainer");

    $id_pkmn = (int)$id_pokemon;
    $id_trn = (int)$id_trainer;
    
    $stmt->bindParam(":id_pokemon", $id_pkmn, PDO::PARAM_INT);
    $stmt->bindParam(":id_trainer", $id_trn, PDO::PARAM_INT);
    $stmt->execute();
    
    $cnt = $stmt->fetchColumn();
    return $cnt > 0;
}

function getTrainerPokemonsList($id_trainer, $equipped = null
, $typeLoad = PKMN_NO_LOAD, $orderBy = null, $rows = null) {
    global $db;
    $objs = array();

    $q = "select * from pkmn_trainer_pokemon";
    $q .= " where id_trainer = $id_trainer";

    if (!is_null($equipped)) {
        $q .= " and equipped = $equipped";
    }

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
        foreach ($objs as $obj) {
            $obj->level = getPkmnLevel($obj->exp);

            if ($typeLoad != PKMN_NO_LOAD) {
                $obj->trainer = getTrainer($obj->id_trainer);
                unset($obj->id_trainer);
                
                if ($typeLoad == PKMN_DEEP_LOAD) {
                    $obj->pokemon = getPokemonAndInit($obj->id_pokemon, $typeLoad);
                    unset($obj->id_pokemon);

                    $obj->moves = getAssignedMoves($obj->id, true, true);
                }
            }
        }
    }

    return $objs;
}

function getPgTrainerPokemonsList(&$res, $navPos, $id_trainer
, $name = null, $equipped = null, $tradeable = null, $sellable = null
, $typeLoad = PKMN_NO_LOAD, $orderBy = null, $records_per_page = 10) {
    global $db;
    $pg = new Zebra_Pagination();
    $pg->navigation_position($navPos);

    $q = "select SQL_CALC_FOUND_ROWS tp.* from pkmn_trainer_pokemon tp";
    
    $conds = array();
    
    if(!is_null($name)){
        $q .= " inner join pkmn_pokemon p on p.id = tp.id_pokemon";
        $conds[] = "p.name like '%$name%'";
    }
    
    if(!is_null($id_trainer)){
        $conds[] = "tp.id_trainer = $id_trainer";
    }
    
    if (!is_null($equipped)) {
        $conds[] = "tp.equipped = $equipped";
    }

    if (!is_null($tradeable)){
        $conds[] = "tp.tradeable = $tradeable";
    }
    
    if (!is_null($sellable)){
        $conds[] = "tp.sellable = $sellable";
        $conds[] = "tp.equipped = 0";
        $conds[] = "(tp.gold > 0 or tp.silver > 0)";
    }
    
    if(!empty($conds)){
        $q .= " where ";
        $q .= implode(" and ", $conds);
    }
    
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
    
    if ($typeLoad != PKMN_NO_LOAD) {
        foreach ($res as $obj) {
            $obj->level = getPkmnLevel($obj->exp);
            $obj->trainer = getTrainer($obj->id_trainer);
            unset($obj->id_trainer);

            if ($typeLoad == PKMN_DEEP_LOAD) {
                $obj->pokemon = getPokemonAndInit($obj->id_pokemon, $typeLoad);
                unset($obj->id_pokemon);

                $obj->moves = getAssignedMoves($obj->id, true, true);
            }
        }
    }

    return $pg;
}

function getPgTPkmnRarityList(&$res, $navPos, $name = null, $records_per_page = 10) {
    global $db;
    $pg = new Zebra_Pagination();
    $pg->navigation_position($navPos);

    $q = "select SQL_CALC_FOUND_ROWS p.name, sum(tp.gender = 'M') males,
            sum(tp.gender = 'F') females, sum(tp.gender = '?') genderless
            from pkmn_trainer_pokemon tp
            inner join pkmn_trainer t on t.id = tp.id_trainer
            inner join pkmn_pokemon p on p.id = tp.id_pokemon";

    $conds = array();
    if (!is_null($name)){
        $conds[] = "p.name like '%$name%'";
    }

    $conds[] = "t.id_user is not null";
    $conds[] = "p.disabled != 1";
    
    if(!empty($conds)){
        $q .= " where ";
        $q .= implode(" and ", $conds);
    }
    
    $q .= " group by p.name";
    $q .= " limit " . (($pg->get_page() - 1) * $records_per_page)
            . ", " . $records_per_page;

    $stmt = $db->query($q);
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);

    $numRows = $db->query("select FOUND_ROWS() as rows")->fetchColumn();

    $pg->records($numRows);
    $pg->records_per_page($records_per_page);
    return $pg;
}

function deleteTrainerPkmn($p) {
    deleteTrainerPkmnById($p->id);
}

function deleteTrainerPkmnById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_trainer_pokemon
        WHERE id = :id");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

function insertTrainerPokemon(&$obj) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_trainer_pokemon
        (id_trainer,id_pokemon,order_index,exp,gender,equipped,
        tradeable,sellable,hp,cur_hp,attack,defense,speed,spec_attack,
        spec_defense,silver,gold)
        VALUES (:id_trainer,:id_pokemon,:order_index,:exp,:gender,:equipped,
        :tradeable,:sellable,:hp,:cur_hp,:attack,:defense,:speed,:spec_attack,
        :spec_defense,:silver,:gold)");

    $stmt->bindParam(":id_trainer", $obj->id_trainer, PDO::PARAM_INT);
    $stmt->bindParam(":id_pokemon", $obj->id_pokemon, PDO::PARAM_INT);
    $stmt->bindParam(":order_index", $obj->order_index, PDO::PARAM_INT);
    $stmt->bindParam(":exp", $obj->exp, PDO::PARAM_INT);
    $stmt->bindParam(":gender", $obj->gender);
    $stmt->bindParam(":equipped", $obj->equipped, PDO::PARAM_BOOL);
    $stmt->bindParam(":tradeable", $obj->tradeable, PDO::PARAM_BOOL);
    $stmt->bindParam(":sellable", $obj->sellable, PDO::PARAM_BOOL);
    $stmt->bindParam(":hp", $obj->hp, PDO::PARAM_INT);
    $stmt->bindParam(":cur_hp", $obj->cur_hp, PDO::PARAM_INT);
    $stmt->bindParam(":attack", $obj->attack, PDO::PARAM_INT);
    $stmt->bindParam(":defense", $obj->defense, PDO::PARAM_INT);
    $stmt->bindParam(":speed", $obj->speed, PDO::PARAM_INT);
    $stmt->bindParam(":spec_attack", $obj->spec_attack, PDO::PARAM_INT);
    $stmt->bindParam(":spec_defense", $obj->spec_defense, PDO::PARAM_INT);
    $stmt->bindParam(":silver", $obj->silver, PDO::PARAM_INT);
    $stmt->bindParam(":gold", $obj->gold, PDO::PARAM_INT);

    $stmt->execute();
    $obj->id = $db->lastInsertId();
    return $obj->id;
}

function updateTrainerPokemon($obj) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_trainer_pokemon SET
        id_trainer = :id_trainer,
        id_pokemon = :id_pokemon,
        order_index = :order_index,
        exp = :exp,
        gender = :gender,
        equipped = :equipped,
        tradeable = :tradeable,
        sellable = :sellable,
        hp = :hp,
        cur_hp = :cur_hp,
        attack = :attack,
        defense = :defense,
        speed = :speed,
        spec_attack = :spec_attack,
        spec_defense = :spec_defense,
        silver = :silver,
        gold = :gold
        WHERE id = :id
        ");

    $stmt->bindParam(":id", $obj->id, PDO::PARAM_INT);
    $stmt->bindParam(":id_trainer", $obj->id_trainer, PDO::PARAM_INT);
    $stmt->bindParam(":id_pokemon", $obj->id_pokemon, PDO::PARAM_INT);
    $stmt->bindParam(":order_index", $obj->order_index, PDO::PARAM_INT);
    $stmt->bindParam(":exp", $obj->exp, PDO::PARAM_INT);
    $stmt->bindParam(":gender", $obj->gender);
    $stmt->bindParam(":equipped", $obj->equipped, PDO::PARAM_BOOL);
    $stmt->bindParam(":tradeable", $obj->tradeable, PDO::PARAM_BOOL);
    $stmt->bindParam(":sellable", $obj->sellable, PDO::PARAM_BOOL);
    $stmt->bindParam(":hp", $obj->hp, PDO::PARAM_INT);
    $stmt->bindParam(":cur_hp", $obj->cur_hp, PDO::PARAM_INT);
    $stmt->bindParam(":attack", $obj->attack, PDO::PARAM_INT);
    $stmt->bindParam(":defense", $obj->defense, PDO::PARAM_INT);
    $stmt->bindParam(":speed", $obj->speed, PDO::PARAM_INT);
    $stmt->bindParam(":spec_attack", $obj->spec_attack, PDO::PARAM_INT);
    $stmt->bindParam(":spec_defense", $obj->spec_defense, PDO::PARAM_INT);
    $stmt->bindParam(":silver", $obj->silver, PDO::PARAM_INT);
    $stmt->bindParam(":gold", $obj->gold, PDO::PARAM_INT);

    $stmt->execute();
}

function tpkmnEvolveIfReady($id_item, &$tpkmn){
    $pkmn = getPokemon($tpkmn->id_pokemon, true);
    $evList = getEvolutionList($tpkmn->id_pokemon, null, null, PKMN_DEEP_LOAD);
    
    foreach($evList as $ev){
        $epkmn = $ev->evolved_pkmn;
        if($epkmn != null && (($tpkmn->exp >= $ev->required_exp &&
                $ev->required_item == null) || ($ev->required_exp <= 1
                && $ev->required_item->id == $id_item))){
            $tpkmn->id_pokemon = $epkmn->id;
            return $epkmn;
        }
    }
    
    return $pkmn;
}

?>
