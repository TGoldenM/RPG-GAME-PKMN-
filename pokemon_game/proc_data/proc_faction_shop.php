<?php
require_once '../db_connection.php';
require_once '../data_access/trainer.php';
require_once '../data_access/faction_shop.php';
require_once '../util/json_functions.php';
require_once '../util/req_functions.php';

$req_data = filter($_REQUEST);

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            if(isset($req_data['btnRet'])){
                header('Location: ../factionShop.php');
                exit();
            }
            
            $obj = getFactionShopElement($id);
            updateObjFromReq($obj, $req_data);
            updateFactionShopElement($obj);
            header("Location: ../factionShop.php?t=1&n=pkmn_msg_success&idf=$obj->id_faction");
            break;

        case 'delete':
            if (isset($req_data['btnYes'])) {
                $obj = getFactionShopElement($id);
                deleteFactionShopElem($obj);
                header("Location: ../factionShop.php?t=1&n=pkmn_msg_success&idf=$obj->id_faction");
            }
            break;
            
        case 'read':
            header('Content-Type: application/json');
            $obj = getFactionShopElement($id, PKMN_SIMPLE_LOAD);
            
            echo json_resp_test($obj, "Element not found: $id");
            exit();
        
        case 'buy':
            $obj = getFactionShopElement($id, PKMN_SIMPLE_LOAD);
            $fc = $obj->faction;
            $curTrn = getCurrentTrainer();
            
            if($curTrn->faction_pts < $obj->pts_cost){
                header("Location: ../factionShop.php?t=0&n=pkmn_not_enough_points&idf=$fc->id");
                exit();
            }
            
            $fc->points += (int)$obj->pts_cost;
            updateFaction($fc);
            
            $elem = $obj->element;
            $elem->id_trainer = $curTrn->id;
            
            //If type is trainer item
            if($obj->type == 1){
                $elem->id_item = $elem->item->id;
                updateTrainerItem($elem);
            } else if($obj->type == 2){
                //If type is trainer pokemon
                $elem->id_pokemon = $elem->pokemon->id;
                updateTrainerPokemon($elem);
            }
            
            $curTrn->faction_pts -= (int)$obj->pts_cost;
            if($curTrn->faction_pts < 0){
                $curTrn->faction_pts = 0;
            }
            
            updateTrainer($curTrn);
            header("Location: ../factionShop.php?t=1&n=pkmn_msg_success&idf=$fc->id");
            break;
    }
} else {
    if(isset($req_data['btnRet'])){
        header('Location: ../factionShop.php?idf='.$req_data['id_faction']);
        exit();
    }

    $obj = createObjFromReq($req_data, array("id_faction", "id_element",
        "id_obj", "type", "pts_cost", "lvl"));

    if(isset($req_data['action'])){
        switch($req_data['action']){
        case 'create':
            createFactionShopElem($obj);
            header("Location: ../factionShop.php?t=1&n=pkmn_msg_success&idf=$obj->id_faction");
            exit();
        }
    }
        
    insertFactionShopElement($obj);
    
    //If type is trainer item
    if($obj->type == 1){
        $trItem = getTrainerItem($obj->id_element);
        if (!is_null($trItem)) {
            $trItem->id_trainer = null;
            updateTrainerItem($trItem);
        }
    } else if($obj->type == 2){
        //If type is trainer pokemon
        $tpkmn = getTrainerPokemon($obj->id_element);
        if (!is_null($tpkmn)) {
            $tpkmn->id_trainer = null;
            updateTrainerPokemon($tpkmn);
        }
    }
    
    header("Location: ../factionShop.php?t=1&n=pkmn_msg_success&idf=$obj->id_faction");
}
?>
