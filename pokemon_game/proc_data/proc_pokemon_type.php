<?php
require_once '../db_connection.php';
require_once '../data_access/pokemon_type.php';
require_once '../util/json_functions.php';

$req_data = filter($_REQUEST);

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            if(isset($req_data['btnRet'])){
                header('Location: ../typesAdmin.php');
                exit();
            }
            
            $obj = getPokemonType($id);
            $obj->name = $req_data['name'];
            updatePokemonType($obj);
            header("Location: ../typesAdmin.php?t=1&n=pkmn_msg_success");
            break;

        case 'delete':
            if (isset($req_data['btnYes'])) {
                //deletePokemonType($id);
            }
            break;
            
        case 'read':
            header('Content-Type: application/json');
            $obj = getPokemonType($id);
            echo json_resp_test($obj, "Pokemon type not found: $id");
            exit();
    }
} else {
    if(isset($req_data['btnRet'])){
        header('Location: ../typesAdmin.php');
        exit();
    }

    $obj = (object)array(
        "name"=>$req_data['name']
        );

    insertPokemonType($obj);
    header("Location: ../typesAdmin.php?t=1&n=pkmn_msg_success");
}
?>
