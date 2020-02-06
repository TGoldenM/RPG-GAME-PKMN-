<?php

require_once '../db_connection.php';
require_once '../data_access/pokemon_move.php';
require_once '../util/json_functions.php';
require_once '../util/req_functions.php';

$req_data = filter($_REQUEST);

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            if (isset($req_data['btnRet'])) {
                header('Location: ../moveAdmin.php');
                exit();
            }

            $obj = getPokemonMove($id);
            updateObjFromReq($obj, $req_data);
            updatePokemonMove($obj);
            
            unset($_SESSION['moveObj']);
            header("Location: ../moveAdmin.php?t=1&n=pkmn_msg_success");
            break;

        case 'delete':
            if (isset($req_data['btnYes'])) {
                deletePokemonMoveById($id);
                header("Location: ../moveAdmin.php?t=1&n=pkmn_msg_success");
            }
            break;

        case 'read':
            header('Content-Type: application/json');
            $obj = getPokemonMove($id, true, true);
            echo json_resp_test($obj, "Pokemon move not found: $id");
            exit();
    }
} else {
    if (isset($req_data['btnRet'])) {
        header('Location: ../moveAdmin.php');
        exit();
    }

    $obj = createObjFromReq($req_data, array(
        "type_nat",
        "category",
        "name",
        "value",
        "accuracy",
        "power_points"
    ));
    
    insertPokemonMove($obj);
    unset($_SESSION['moveObj']);
    header("Location: ../moveAdmin.php?t=1&n=pkmn_msg_success");
}
?>
