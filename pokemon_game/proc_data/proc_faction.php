<?php
require_once '../db_connection.php';
require_once '../data_access/faction.php';
require_once '../util/json_functions.php';
require_once '../util/req_functions.php';

$req_data = filter($_REQUEST);

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            if(isset($req_data['btnRet'])){
                header('Location: ../factionAdmin.php');
                exit();
            }
            
            $obj = getFaction($id);
            updateObjFromReq($obj, $req_data);
            updateFaction($obj);
            header("Location: ../factionAdmin.php?t=1&n=pkmn_msg_success");
            break;
        
        case 'delete':
            if (isset($req_data['btnYes'])) {
                deleteFactionById($id);
                header("Location: ../factionAdmin.php?t=1&n=pkmn_msg_success");
            }
            break;
            
        case 'read':
            header('Content-Type: application/json');
            $obj = getFaction($id);
            
            echo json_resp_test($obj, "Faction not found: $id");
            exit();
    }
} else {
    if(isset($req_data['btnRet'])){
        header('Location: ../factionAdmin.php');
        exit();
    }

    $obj = createObjFromReq($req_data, array(
        "visible",
        "name",
        "points"
    ));

    insertFaction($obj);
    header("Location: ../factionAdmin.php?t=1&n=pkmn_msg_success");
}
?>
