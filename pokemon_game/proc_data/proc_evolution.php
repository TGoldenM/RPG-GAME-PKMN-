<?php
require_once '../db_connection.php';
require_once '../data_access/evolution.php';
require_once '../data_access/trainer_pokemon.php';
require_once '../util/json_functions.php';
require_once '../util/req_functions.php';

$req_data = filter($_REQUEST);

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            if(isset($req_data['btnRet'])){
                header('Location: ../evolutionAdmin.php?idp='.$req_data['id_pkmn']);
                exit();
            }
            
            $obj = getEvolution($id);
            updateObjFromReq($obj, $req_data);
            if(empty($req_data['evolved_name'])){
                $obj->id_evolved_pkmn = null;
            }
            
            $obj->required_exp = getPkmnExp($req_data['required_lvl']);
            updateEvolution($obj);
            
            header("Location: ../evolutionAdmin.php?t=1&n=pkmn_msg_success&idp=".$req_data['id_pkmn']);
            break;

        case 'delete':
            if (isset($req_data['btnYes'])) {
                $obj = getEvolution($id);
                deleteEvolutionById($id);
                
                header("Location: ../evolutionAdmin.php?t=1&n=pkmn_msg_success&idp=".$obj->id_pkmn);
            }
            break;
            
        case 'read':
            header('Content-Type: application/json');
            $obj = getEvolution($id);
            
            echo json_resp_test($obj, "Evolution not found: $id");
            exit();
    }
} else {
    if(isset($req_data['btnRet'])){
        header('Location: ../evolutionAdmin.php?idp='.$req_data['id_pkmn']);
        exit();
    }

    $obj = createObjFromReq($req_data, array("id_pkmn", "id_evolved_pkmn",
        "required_exp", "id_required_item"));

    if(empty($req_data['evolved_name'])){
        $obj->id_evolved_pkmn = null;
    }

    $obj->required_exp = getPkmnExp($req_data['required_lvl']);
    insertEvolution($obj);
    header("Location: ../evolutionAdmin.php?t=1&n=pkmn_msg_success&idp=".$req_data['id_pkmn']);
}
?>
