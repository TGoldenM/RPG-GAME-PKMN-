<?php
require_once '../db_connection.php';
require_once '../data_access/region.php';
require_once '../util/json_functions.php';

$req_data = filter($_REQUEST);

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            if(isset($req_data['btnRet'])){
                header('Location: ../regionAdmin.php');
                exit();
            }
            
            $obj = getRegion($id);
            $obj->name = $req_data['name'];
            updateRegion($obj);
            header("Location: ../regionAdmin.php?t=1&n=pkmn_msg_success");
            break;

        case 'delete':
            if (isset($req_data['btnYes'])) {
                deleteRegionById($id);
                header("Location: ../regionAdmin.php?t=1&n=pkmn_msg_success");
            }
            break;
            
        case 'read':
            header('Content-Type: application/json');
            $obj = getRegion($id);
            
            echo json_resp_test($obj, "Region not found: $id");
            exit();
    }
} else {
    if(isset($req_data['btnRet'])){
        header('Location: ../regionAdmin.php');
        exit();
    }

    $obj = (object)array(
        "name" => $req_data['name']
        );

    insertRegion($obj);
    header("Location: ../regionAdmin.php?t=1&n=pkmn_msg_success");
}
?>
