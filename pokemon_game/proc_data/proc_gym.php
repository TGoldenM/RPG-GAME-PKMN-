<?php
require_once '../db_connection.php';
require_once '../data_access/gym.php';
require_once '../util/json_functions.php';

$req_data = filter($_REQUEST);

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            if(isset($req_data['btnRet'])){
                header('Location: ../gymAdmin.php');
                exit();
            }
            
            $obj = getGym($id);
            $obj->id_region = $req_data['id_region'];
            $obj->name = $req_data['name'];
            updateGym($obj);
            header("Location: ../gymAdmin.php?t=1&n=pkmn_msg_success");
            break;

        case 'delete':
            if (isset($req_data['btnYes'])) {
                deleteGymById($id);
                header("Location: ../gymAdmin.php?t=1&n=pkmn_msg_success");
            }
            break;
            
        case 'read':
            header('Content-Type: application/json');
            $obj = getGym($id);
            
            echo json_resp_test($obj, "Gym not found: $id");
            exit();
    }
} else {
    if(isset($req_data['btnRet'])){
        header('Location: ../gymAdmin.php');
        exit();
    }

    $obj = (object)array(
        "id_region" => $req_data['id_region'],
        "name" => $req_data['name']
        );

    insertGym($obj);
    header("Location: ../gymAdmin.php?t=1&n=pkmn_msg_success");
}
?>
