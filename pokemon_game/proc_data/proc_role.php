<?php
require_once '../db_connection.php';
require_once '../data_access/system_role.php';
require_once '../util/json_functions.php';

$req_data = filter($_REQUEST);

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            if(isset($req_data['btnRet'])){
                header('Location: ../roleAdmin.php');
                exit();
            }
            
            $obj = getRole($id);
            $obj->rank = $req_data['rank'];
            $obj->name = $req_data['name'];
            updateRole($obj);
            header("Location: ../roleAdmin.php?t=1&n=pkmn_msg_success");
            break;

        case 'delete':
            if (isset($req_data['btnYes'])) {
                deleteRoleById($id);
                header("Location: ../roleAdmin.php?t=1&n=pkmn_msg_success");
            }
            break;
            
        case 'read':
            header('Content-Type: application/json');
            $obj = getRole($id);
            
            echo json_resp_test($obj, "Role not found: $id");
            exit();
    }
} else {
    if(isset($req_data['btnRet'])){
        header('Location: ../roleAdmin.php');
        exit();
    }

    $obj = (object)array(
        "rank" => $req_data['rank'],
        "name" => $req_data['name']
        );

    insertRole($obj);
    header("Location: ../roleAdmin.php?t=1&n=pkmn_msg_success");
}
?>
