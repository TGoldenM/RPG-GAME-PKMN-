<?php
defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH.'data_access/pokemon_type.php';
require_once ROOTPATH.'data_access/move_category.php';
require_once ROOTPATH.'lib/Zebra_Pagination.php';

/**
 * Creates an associative array using the move types as key names.
 * This function is intended to be used with genSelectOptionsArr found
 * in ui_support/dropdown.php.
 * 
 * @param array $moves A moves array created with one of the get* functions
 * found in data_access/pokemon.php using PKMN_SIMPLE_LOAD
 * or PKMN_DEEP_LOAD.
 *  
 * @return array Associative array
 */
function getPkmnGroupedMoves($moves){
    foreach ($moves as $move) {
        $mv_name = sprintf("%s (%s)", $move->name, $move->category->name);
                
        if(!isset($values[$move->type->name])){
            $values[$move->type->name] = array(array($move->id, $mv_name));
        } else {
            $values[$move->type->name][] = array($move->id, $mv_name);
        }
    }
    
    return $values;
}

/**
 * Reads a set of pokemon moves 
 * @global PDO $db
 * @param array $types Pokemon types to take in count
 * @param boolean $loadType True to load type data from pkmn_pokemon_type
 * @param boolean $loadCategory True to load category data from pkmn_move_category
 * @return type
 */
function getRandPkmnMoves($types, $loadType = false, $loadCategory = false, $rows = null){
    global $db;
    
    $id_types = array();
    foreach($types as $type){
        $id_types[] = $type->id;
    }
    
    $q = "select * from pkmn_pokemon_move
        where type_nat in (".implode(",", $id_types).")
        order by rand()";
    
    if(!is_null($rows)){
        $q .= " limit $rows";
    }
    
    $stmt = $db->query($q);
    
    $data = $stmt->fetchAll(PDO::FETCH_OBJ);
    foreach($data as $obj) {
        if ($loadType) {
            $obj->type = getPokemonType($obj->type_nat, true);
            unset($obj->type_nat);
        }
        if ($loadCategory) {
            $id_cat = $obj->category;
            unset($obj->category);
            $obj->category = getMoveCategory($id_cat);
        }
    }

    return $data;
}

function getPokemonMove($id, $loadType = false, $loadCategory = false) {
    global $db;
    $obj = null;

    if (!is_null($id)) {
        $stmt = $db->query("select * from pkmn_pokemon_move where id = " . $id);
        $obj = $stmt->fetch(PDO::FETCH_OBJ);

        if ($obj) {
            if ($loadType) {
                $obj->type = getPokemonType($obj->type_nat);
                unset($obj->type_nat);
            }
            if ($loadCategory) {
                $id_cat = $obj->category;
                unset($obj->category);

                $obj->category = getMoveCategory($id_cat);
            }
        }
    }

    return $obj;
}

function getPokemonMovesList($loadType = false, $loadCategory = false, $orderBy = null, $rows = null) {
    global $db;
    $objs = array();

    $q = "select * from pkmn_pokemon_move";

    if (!is_null($orderBy)) {
        $q .= " order by $orderBy";
    }

    if (!is_null($rows)) {
        $q .= " limit $rows";
    }

    $stmt = $db->query($q);
    $objs = $stmt->fetchAll(PDO::FETCH_OBJ);

    if($loadType || $loadCategory){
        foreach ($objs as $obj) {
            if ($loadType) {
                $obj->type = getPokemonType($obj->type_nat);
                unset($obj->type_nat);
            }
            if ($loadCategory) {
                $id_cat = $obj->category;
                unset($obj->category);

                $obj->category = getMoveCategory($id_cat);
            }
        }
    }

    return $objs;
}

function getPgPokemonMoveList(&$res, $navPos, $records_per_page = 10
, $loadType = false, $loadCategory = false, $orderBy = null) {
    global $db;
    $pg = new Zebra_Pagination();
    $pg->navigation_position($navPos);

    $q = "select SQL_CALC_FOUND_ROWS * from pkmn_pokemon_move";
    $q .= " limit " . (($pg->get_page() - 1) * $records_per_page)
            . ", " . $records_per_page;

    $stmt = $db->query($q);
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);
    $numRows = $db->query("select FOUND_ROWS() as rows")->fetchColumn();
    $pg->records($numRows);
    $pg->records_per_page($records_per_page);
    
    if($loadType || $loadCategory){
        foreach ($res as $obj) {
            if ($loadType) {
                $obj->type = getPokemonType($obj->type_nat);
                unset($obj->type_nat);
            }
            if ($loadCategory) {
                $id_cat = $obj->category;
                unset($obj->category);

                $obj->category = getMoveCategory($id_cat);
            }
        }
    }
    
    return $pg;
}

function getAssignedMoves($id_trainer_pokemon, $loadType = false, $loadCategory = false) {
    global $db;
    $q = "select r.* from pkmn_pokemon_assig_move a
        left join pkmn_pokemon_move r on a.id_move = r.id
        where a.id_trainer_pokemon = :id";

    $stmt = $db->prepare($q);
    $stmt->bindParam(':id', $id_trainer_pokemon, PDO::PARAM_INT);
    $stmt->execute();

    $objs = $stmt->fetchAll(PDO::FETCH_OBJ);
    if($loadType || $loadCategory){
        foreach ($objs as $obj) {
            if ($loadType) {
                $obj->type = getPokemonType($obj->type_nat, true);
                unset($obj->type_nat);
            }
            if ($loadCategory) {
                $id_cat = $obj->category;
                unset($obj->category);

                $obj->category = getMoveCategory($id_cat);
            }
        }
    }

    return $objs;
}

function assignMovesCSV($tpkmn, $mvs, $removeAll = true) {
    if (empty($mvs)) {
        $moves = array();
    } else {
        $moves = explode(',', $mvs);
    }

    assignMoves($tpkmn, $moves, $removeAll);
}

function assignMoves($tpkmn, $moves, $removeAll = true) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_pokemon_assig_move(id_trainer_pokemon, id_move)"
            . " VALUES (:id_tr, :id_move)");

    if (!$removeAll) {
        //Insert only the non existent Moves
        $obj = getAssignedMoves($tpkmn->id);
        $c_moves = array();
        foreach ($c_moves as $r) {
            $c_moves[] = $r->id;
        }

        foreach ($moves as $id_move) {
            if (!in_array($id_move, $c_moves)) {
                $stmt->bindParam(":id_tr", $tpkmn->id, PDO::PARAM_INT);
                $stmt->bindParam(":id_move", $id_move, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    } else {
        //Clear assigned Moves
        removeAllMoves($tpkmn);

        foreach ($moves as $id_move) {
            $stmt->bindParam(":id_tr", $tpkmn->id, PDO::PARAM_INT);
            $stmt->bindParam(":id_move", $id_move, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}

function removeAssignedMove($tpkmn, $id_move) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_pokemon_assig_move"
            . " where id_trainer_pokemon = :id_tr and id_move = :id_move");

    $stmt->bindParam(":id_tr", $tpkmn->id, PDO::PARAM_INT);
    $stmt->bindParam(":id_move", $id_move, PDO::PARAM_INT);

    $stmt->execute();
}

function removeAssignedMoves($tpkmn, $moves) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_pokemon_assig_move"
            . " where id_trainer_pokemon = :id_tr and id_move = :id_move");

    foreach ($moves as $id_move) {
        $stmt->bindParam(":id_tr", $tpkmn->id, PDO::PARAM_INT);
        $stmt->bindParam(":id_move", $id_move, PDO::PARAM_INT);
        $stmt->execute();
    }
}

function removeAllMoves($tpkmn) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_pokemon_assig_move"
            . " where id_trainer_pokemon = :id_tr");

    $stmt->bindParam(":id_tr", $tpkmn->id, PDO::PARAM_INT);

    $stmt->execute();
}

function insertPokemonMove($p) {
    global $db;

    $stmt = $db->prepare("INSERT INTO pkmn_pokemon_move
            (type_nat, category, name, value, accuracy, power_points) VALUES
            (:type_nat, :category, :name, :value, :accuracy, :power_points)");

    $stmt->bindParam(":type_nat", $p->type_nat, PDO::PARAM_INT);
    $stmt->bindParam(":category", $p->category, PDO::PARAM_INT);
    $stmt->bindParam(":name", $p->name);
    $stmt->bindParam(":value", $p->value, PDO::PARAM_INT);
    $stmt->bindParam(":accuracy", $p->accuracy);
    $stmt->bindParam(":power_points", $p->power_points);

    $stmt->execute();
    return $db->lastInsertId();
}

function updatePokemonMove($p) {
    global $db;

    $stmt = $db->prepare("UPDATE pkmn_pokemon_move
        SET type_nat = :type_nat, category = :category
        , name = :name, value = :value, accuracy = :accuracy
        , power_points = :power_points
        WHERE id = :id");

    $stmt->bindParam(":id", $p->id, PDO::PARAM_INT);
    $stmt->bindParam(":type_nat", $p->type_nat, PDO::PARAM_INT);
    $stmt->bindParam(":category", $p->category, PDO::PARAM_INT);
    $stmt->bindParam(":name", $p->name);
    $stmt->bindParam(":value", $p->value, PDO::PARAM_INT);
    $stmt->bindParam(":accuracy", $p->accuracy);
    $stmt->bindParam(":power_points", $p->power_points);

    $stmt->execute();
}

function deletePokemonMove($p) {
    deletePokemonMoveById($p->id);
}

function deletePokemonMoveById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM pkmn_pokemon_move WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    $stmt->execute();
}

?>
