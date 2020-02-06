<?php

require_once '../db_connection.php';
require_once '../data_access/user.php';
require_once '../data_access/trade.php';
require_once '../util/json_functions.php';
require_once '../util/req_functions.php';

$req_data = filter($_REQUEST);
if(isset($req_data['tradeOpt'])){
    $_SESION['tradeOpt'] = $req_data['tradeOpt'];
}

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            if (isset($req_data['btnRet'])) {
                header('Location: ../trades.php');
                exit();
            }

            $obj = getTrade($id);
            updateObjFromReq($obj, $req_data);
            updateTrade($obj);
            header("Location: ../trades.php?t=1&n=pkmn_msg_success");
            break;

        case 'delete':
            if (isset($req_data['btnYes'])) {
                deleteTradeById($id);
                header("Location: ../trades.php?t=1&n=pkmn_msg_success");
            }
            break;

        case 'read':
            header('Content-Type: application/json');
            $obj = getTrade($id, PKMN_SIMPLE_LOAD);

            echo json_resp_test($obj, "Trade not found: $id");
            exit();

        case 'reject':
            $obj = getTrade($id);
            $obj->id_state = getTradeStateByName('Rejected')->id;
            updateTrade($obj);
            header("Location: ../trades.php?t=1&n=pkmn_msg_success");
            break;

        case 'accept':
            $obj = getTrade($id);
            $obj->id_state = getTradeStateByName('Accepted')->id;
            updateTrade($obj);

            //Other users desiring to trade the same pokemon
            //will be notified of their trade being cancelled
            cancelOtherTrades($obj);

            //Get trainer pokemons
            $tpkmn1 = getTrainerPokemon($obj->id_tpkmn1);
            $tpkmn2 = getTrainerPokemon($obj->id_tpkmn2);

            $tr = $tpkmn1->id_trainer;
            $tpkmn1->id_trainer = $tpkmn2->id_trainer;
            $tpkmn2->id_trainer = $tr;

            //The new pokemon of the owner must not be tradeable. User must
            //set this. Cannot be in the team initially.
            $tpkmn1->tradeable = 0;
            $tpkmn1->equipped = 0;
            $tpkmn1->sellable = 0;
            $tpkmn1->silver = 0;
            $tpkmn1->gold = 0;
            $tpkmn2->tradeable = 0;
            $tpkmn2->equipped = 0;
            $tpkmn2->sellable = 0;
            $tpkmn2->silver = 0;
            $tpkmn2->gold = 0;
            
            updateTrainerPokemon($tpkmn1);
            updateTrainerPokemon($tpkmn2);
            header("Location: ../trnPkmn.php?t=1&c=1&n=pkmn_msg_success");
            break;
    }
} else {
    if (isset($req_data['action'])) {
        switch ($req_data['action']) {
            case 'verify':
                if (!checkIfUserExists($req_data['name'])) {
                    header("Location: ../tradeSelUsrPkmn.php?t=0&n=user_not_exists&c=" . $req_data['name']);
                    exit();
                }

                $q = http_build_query(array(
                    'tid' => $req_data['tid'],
                    'cid_tr' => $req_data['cid_tr'],
                    'oid_tr' => $req_data['oid_tr']
                ));

                header("Location: ../tradeSelOtherPkmn.php?$q");
                break;

            case 'create':
                $obj = createObjFromReq($req_data, array(
                    "creation_date", "type", "id_state", "id_trainer1",
                    "id_trainer2", "id_tpkmn1", "id_tpkmn2"));

                $obj->creation_date = date("Y-m-d H:i:s");
                $obj->id_state = getTradeStateByName('Created')->id;

                insertTrade($obj);
                header("Location: ../trades.php?t=1&n=pkmn_msg_success");
                break;
        }
    }
}
?>
