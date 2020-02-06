<?php

defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH . 'lib/Zebra_Pagination.php';
require_once ROOTPATH . 'data_access/user.php';
require_once ROOTPATH . 'data_access/trainer_pokemon.php';
require_once ROOTPATH . 'data_access/trainer_item.php';
require_once ROOTPATH . 'data_access/faction.php';

function getTrainer($id, $typeLoad = PKMN_NO_LOAD) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->prepare("select * from pkmn_trainer where id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_OBJ);

        if ($res) {
            $obj = $res;
            if ($typeLoad != PKMN_NO_LOAD) {
                $obj->faction = getFaction($obj->id_faction);
                unset($obj->id_faction);
                
                $obj->user = getSystemUser($obj->id_user);
                unset($obj->id_user);
                unset($obj->user->password);

                if ($typeLoad == PKMN_DEEP_LOAD) {
                    $obj->pokemons = getTrainerPokemonsList($obj->id, null, $typeLoad);
                }
            }
        }
    }

    return $obj;
}

function getTrainerByName($name, $factionName = null, $typePkmnLoad = PKMN_NO_LOAD) {
    global $db;
    $obj = null;

    $q = "select t.* from pkmn_trainer t
				inner join pkmn_faction f on f.id = t.id_faction
				where t.name = :tname";

    if (!is_null($factionName)) {
        $q .= " and f.name = :fname";
    }

    $stmt = $db->prepare($q);
    $stmt->bindParam(":tname", $name);

    if (!is_null($factionName)) {
        $stmt->bindParam(":fname", $factionName);
    }

    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_OBJ);

    if ($res) {
        $obj = $res;
        if ($typePkmnLoad != PKMN_NO_LOAD) {
            $obj->faction = getFaction($obj->id_faction);
            unset($obj->id_faction);

            if ($typePkmnLoad == PKMN_DEEP_LOAD) {
                $obj->pokemons = getTrainerPokemonsList($obj->id, null, $typePkmnLoad);
            }
        }
    }

    return $obj;
}

function getTrainerByUserId($uId, $typePkmnLoad = PKMN_NO_LOAD) {
    global $db;
    $obj = null;

    if (!is_null($uId)) {
        $stmt = $db->query("select * from pkmn_trainer
                 where id_user = $uId limit 1");

        $res = $stmt->fetch(PDO::FETCH_OBJ);

        if ($res) {
            $obj = $res;
            if ($typePkmnLoad != PKMN_NO_LOAD) {
                $obj->faction = getFaction($obj->id_faction);
                unset($obj->id_faction);

                if ($typePkmnLoad == PKMN_DEEP_LOAD) {
                    $obj->pokemons = getTrainerPokemonsList($obj->id, null, $typePkmnLoad);
                }
            }
        }
    }

    return $obj;
}

function getUsrTrainerList($name = null, $orderBy = null, $rows = null) {
    global $db;
    $objs = array();

    $q = "select t.*, u.username from pkmn_trainer t
		inner join system_user u on u.id = t.id_user";

    $conds = array();

    if (!is_null($name)) {
        $conds[] = "u.username like '$name'";
    }

    if (!empty($conds)) {
        $q .= " where ";
        $q .= implode(" and ", $conds);
    }

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

function getPgTrainerList(&$res, $navPos, $records_per_page = 10,
        $id_type = null, $id_gym = null, $id_user = null, $visible = null,
        $orderBy = null, $typePkmnLoad = PKMN_NO_LOAD) {
    global $db;
    $pg = new Zebra_Pagination();
    $pg->navigation_position($navPos);

    $q = "select SQL_CALC_FOUND_ROWS * from pkmn_trainer";

    $conds = array();
    if (!is_null($id_user) && $id_user != -1) {
        $conds[] = "id_user = $id_user";
    } else if (is_null($id_user)) {
        $conds[] = "id_user is null";
    }

    if (!is_null($id_type)) {
        $conds[] = "id_type = $id_type";
    }

    if (!is_null($id_gym)) {
        $conds[] = "id_gym = $id_gym";
    }

    if (!is_null($visible)) {
        $conds[] = "visible = $visible";
    }

    if (!empty($conds)) {
        $q .= " where ";
        $q .= implode(" and ", $conds);
    }

    if(!is_null($orderBy)){
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

    if ($typePkmnLoad != PKMN_NO_LOAD) {
        foreach ($res as $obj) {
            $obj->faction = getFaction($obj->id_faction);
            unset($obj->id_faction);

            if ($typePkmnLoad == PKMN_DEEP_LOAD) {
                $obj->pokemons = getTrainerPokemonsList($obj->id, null, $typePkmnLoad);
            }
        }
    }

    return $pg;
}

function getRankingTrainerCurrency(){
    global $db;

    $stmt = $db->query("select u.username username,
        tr.gold gold, tr.silver silver
        from pkmn_trainer tr
        inner join system_user u on u.id = tr.id_user
        order by tr.gold desc, tr.silver desc
        limit 10");

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getRankingTrainerVD(){
    global $db;

    $stmt = $db->query("select u.username username,
        tr.victories victories, tr.defeats defeats
        from pkmn_trainer tr
        inner join system_user u on u.id = tr.id_user
        order by tr.victories desc, tr.defeats asc
        limit 10");

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getCurrentTrainer() {
    //Expected one row result
    $uId = $_SESSION['user_id'];
    return getTrainerByUserId($uId);
}

function deleteTrainer($p) {
    deleteTrainerById($p->id);
}

function deleteTrainerById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_trainer
        WHERE id = :id");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

function insertTrainer(&$obj) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_trainer
                (id_user,id_faction,id_gym,id_type,
                visible,order_index,silver,gold,faction_pts,name,victories,defeats)
                VALUES (:id_user,:id_faction,:id_gym,:id_type,
                :visible,:order_index,:silver,:gold,:faction_pts,:name,
                :victories,:defeats)");

    $id_user = isset($obj->id_user) && $obj->id_user != 0 ?
            $obj->id_user : null;

    $id_faction = isset($obj->id_faction) && $obj->id_faction != 0 ?
            $obj->id_faction : null;

    $id_gym = isset($obj->id_gym) && $obj->id_gym != 0 ?
            $obj->id_gym : null;

    $stmt->bindParam(":id_user", $id_user, PDO::PARAM_INT);
    $stmt->bindParam(":id_faction", $id_faction, PDO::PARAM_INT);
    $stmt->bindParam(":id_gym", $id_gym, PDO::PARAM_INT);
    $stmt->bindParam(":id_type", $obj->id_type, PDO::PARAM_INT);
    $stmt->bindParam(":visible", $obj->visible, PDO::PARAM_BOOL);
    $stmt->bindParam(":order_index", $obj->order_index, PDO::PARAM_INT);
    $stmt->bindParam(":silver", $obj->silver, PDO::PARAM_INT);
    $stmt->bindParam(":gold", $obj->gold, PDO::PARAM_INT);
    $stmt->bindParam(":faction_pts", $obj->faction_pts, PDO::PARAM_INT);
    $stmt->bindParam(":name", $obj->name);
    $stmt->bindParam(":victories", $obj->name, PDO::PARAM_INT);
    $stmt->bindParam(":defeats", $obj->name, PDO::PARAM_INT);

    $stmt->execute();
    $obj->id = $db->lastInsertId();
    return $obj->id;
}

function updateTrainer($obj) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_trainer
            SET id_user = :id_user,
            id_faction = :id_faction,
            id_gym = :id_gym,
            id_type = :id_type,
            visible = :visible,
            order_index = :order_index,
            silver = :silver,
            gold = :gold,
            faction_pts = :faction_pts,
            name = :name,
            victories = :victories,
            defeats = :defeats
            WHERE id = :id");

    $id_user = isset($obj->id_user) && $obj->id_user != 0 ?
            $obj->id_user : null;

    $id_faction = isset($obj->id_faction) && $obj->id_faction != 0 ?
            $obj->id_faction : null;

    $id_gym = isset($obj->id_gym) && $obj->id_gym != 0 ?
            $obj->id_gym : null;
    
    $stmt->bindParam(":id", $obj->id, PDO::PARAM_INT);
    $stmt->bindParam(":id_user", $id_user, PDO::PARAM_INT);
    $stmt->bindParam(":id_faction", $id_faction, PDO::PARAM_INT);
    $stmt->bindParam(":id_gym", $id_gym, PDO::PARAM_INT);
    $stmt->bindParam(":id_type", $obj->id_type, PDO::PARAM_INT);
    $stmt->bindParam(":visible", $obj->visible, PDO::PARAM_BOOL);
    $stmt->bindParam(":order_index", $obj->order_index, PDO::PARAM_INT);
    $stmt->bindParam(":silver", $obj->silver, PDO::PARAM_INT);
    $stmt->bindParam(":gold", $obj->gold, PDO::PARAM_INT);
    $stmt->bindParam(":faction_pts", $obj->faction_pts, PDO::PARAM_INT);
    $stmt->bindParam(":name", $obj->name);
    $stmt->bindParam(":victories", $obj->victories, PDO::PARAM_INT);
    $stmt->bindParam(":defeats", $obj->defeats, PDO::PARAM_INT);

    $stmt->execute();
}

?>
