<?php

require_once '../db_connection.php';
require_once '../data_access/trainer_pokemon.php';
require_once '../data_access/sale.php';
require_once '../data_access/system_property.php';
require_once '../util/json_functions.php';
require_once '../util/req_functions.php';

$msg = 0;
$req_data = filter($_REQUEST);

if (!empty($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            $obj = getTrainerPokemon($id);
            if (isset($req_data['btnRet'])) {
                header("Location: ../trnPkmn.php?id_tr=$obj->id_trainer");
                exit();
            }

            $maxCnt = (int)getPropertyByName("pkmn_max_team_count")->value;
            if(isset($req_data['equipped']) && $req_data['equipped'] == true
                && $obj->equipped != $req_data['equipped']
                && getEqpTPkmnCount($obj->id_trainer)+1 > $maxCnt){
                
                updateObjFromReq($obj, $req_data);
                $obj->pokemon = getPokemon($obj->id_pokemon);
                $obj->moves = getAssignedMoves($id, true, true);
                
                $_SESSION['tpkmnObj'] = serialize($obj);
                header("Location: ../trnPkmnEdit.php?id=$id&id_tr=$obj->id_trainer&t=0&n=pkmn_msg_max_team_count");
                exit();
            }
            
            updateObjFromReq($obj, $req_data);
            if(isset($req_data['lvl'])){
                $obj->exp = getPkmnExp($req_data['lvl']);
            }
            
            updateTrainerPokemon($obj);

            if (isset($req_data['moves'])) {
                assignMovesCSV($obj, $req_data['moves']);
            }

            header("Location: ../trnPkmn.php?id_tr=$obj->id_trainer&t=1&n=pkmn_msg_success");
            break;

        case 'delete':
            if (isset($req_data['btnYes'])) {
                $obj = getTrainerPokemon($id);
                deleteTrainerPkmn($obj);
                header("Location: ../trnPkmn.php?id_tr=$obj->id_trainer&t=1&n=pkmn_msg_success");
            }
            break;

        case 'read':
            header('Content-Type: application/json');
            $obj = getTrainerPokemon($id);

            echo json_resp_test($obj, "Trainer pokemon not found: $id");
            exit();

        case 'evl':
            header('Content-Type: application/json');
            $obj = getTrainerPokemon($id);
            $id_item = isset($req_data['id_item']) ? $req_data['id_item'] : null;
            
            $p = tpkmnEvolveIfReady($id_item, $obj);
            updateTrainerPokemon($obj);
            
            echo json_resp_test($p, "Error evolving " . $p->name);
            break;
        
        case 'evlp':
            $obj = getTrainerPokemon($id);
            $pkmn = getPokemon($obj->id_pokemon);
            $id_item = isset($req_data['id_item']) ? $req_data['id_item'] : null;
            
            $p = tpkmnEvolveIfReady($id_item, $obj);
            updateTrainerPokemon($obj);
            header("Location: ../trnPkmn.php?id_tr=$obj->id_trainer&t=1&n=pkmn_evolved_ok&c=$pkmn->name");
            break;
        
        case 'buy':
            if (isset($req_data['btnBuy'])) {
                $obj = getTrainerPokemon($id);

                //Plain and simple trainer object to update
                $ownerTr = getTrainer($obj->id_trainer);

                $ownerTr->silver += $obj->silver;
                $ownerTr->gold += $obj->gold;
                
                $s = (object)array("id_pokemon"=>$obj->id_pokemon,
                    "id_trainer"=>$obj->id_trainer,
                    "date_sold"=>date("Y-m-d H:i:s"),
                    "exp"=>$obj->exp,
                    "gold"=>$obj->gold,
                    "silver"=>$obj->silver);
                
                insertSale($s);
                updateTrainer($ownerTr);

                //The pokemon of the new owner must not be tradeable. User must
                //set this. Cannot be in the team initially.
                $obj->id_trainer = $req_data['trId'];
                $obj->tradeable = 0;
                $obj->equipped = 0;
                $obj->sellable = 0;

                updateTrainerPokemon($obj);
                header("Location: ../pkmnStore.php?t=1&n=pkmn_msg_success");
            } else if (isset($req_data['btnRet'])) {
                header('Location: ../trnPkmn.php');
                exit();
            }
            break;
    }
} else {
    if (isset($req_data['id_trainer']) && isset($req_data['action'])) {
        switch ($req_data['action']) {
            case 'list':
                $lst = getTrainerPokemonsList($req_data['id_trainer'], $req_data['equipped'],
                        $req_data['typeLoad'], isset($req_data['orderBy']) ? $req_data['orderBy'] : null,
                        isset($req_data['rows']) ? $req_data['rows'] : null);

                if ($lst != null) {
                    $resp = json_resp(true, $lst);
                } else {
                    $resp = json_resp(false, $lst, "Trainer pokemons not found. ID Trainer: " . $req_data['id_trainer']);
                }

                echo $resp;
                exit();
        }
    }

    if (isset($req_data['btnRet'])) {
        header('Location: ../trnPkmn.php?id_tr='.$req_data['id_trainer']);
        exit();
    }

    $obj = createObjFromReq($req_data, array(
        "id_trainer",
        "id_pokemon",
        "order_index",
        "exp",
        "equipped",
        "tradeable",
        "sellable",
        "gender",
        "hp",
        "cur_hp",
        "attack",
        "defense",
        "speed",
        "spec_attack",
        "spec_defense",
        "gold",
        "silver"
    ));

    if(isset($req_data['lvl'])){
        $obj->exp = getPkmnExp($req_data['lvl']);
    }

    //Ensure item is a valid id, because can be received as empty string
    if (empty($obj->id_item)) {
        $obj->id_item = null;
    }

    $maxCnt = (int)getPropertyByName("pkmn_max_team_count")->value;
    if(isset($req_data['equipped']) && $req_data['equipped'] == true
        && getEqpTPkmnCount($obj->id_trainer)+1 > $maxCnt){
        $obj->pokemon = getPokemon($obj->id_pokemon);
        $obj->level = getPkmnLevel($obj->exp);
        
        $_SESSION['tpkmnObj'] = serialize($obj);
        header("Location: ../trnPkmnEdit.php?id_tr=$obj->id_trainer&t=0&n=pkmn_msg_max_team_count");
        exit();
    }

    $obj->cur_hp = $obj->hp;
    insertTrainerPokemon($obj);

    if (isset($req_data['moves'])) {
        if (!is_array($req_data['moves'])) {
            assignMovesCSV($obj, $req_data['moves'], false);
        } else {
            $mvs = array();
            foreach ($req_data['moves'] as $mv) {
                $mvs[] = $mv['id'];
            }

            assignMoves($obj, $mvs, false);
        }
    }

    header("Location: ../trnPkmn.php?id_tr=$obj->id_trainer&t=1&n=pkmn_msg_success");
}
?>
