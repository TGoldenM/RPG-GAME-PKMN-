<?php
require_once '../db_connection.php';
require_once '../data_access/trainer_item.php';
require_once '../util/json_functions.php';
require_once '../util/req_functions.php';

$req_data = filter($_REQUEST);

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            if(isset($req_data['btnRet'])){
                header('Location: ../itemAdmin.php');
                exit();
            }
            
            $obj = getPkmnItem($id);
            updateObjFromReq($obj, $req_data);
            updatePkmnItem($obj);
            header("Location: ../itemAdmin.php?t=1&n=pkmn_msg_success");
            break;

        case 'delete':
            if (isset($req_data['btnYes'])) {
                deletePkmnItemById($id);
                header("Location: ../itemAdmin.php?t=1&n=pkmn_msg_success");
            }
            break;
        
        case 'read':
            header('Content-Type: application/json');
            $obj = getPkmnItem($id, PKMN_SIMPLE_LOAD);
            
            echo json_resp_test($obj, "Pokemon item not found: $id");
            exit();
    }
} else {
    if (isset($req_data['action'])) {
        switch ($req_data['action']) {
            case 'acpl':
                header('Content-Type: application/json');
                $items = getPkmnItems('%'.$req_data['query'].'%');
                
                $sugg = array();
                foreach ($items as $item) {
                    $sugg[] = array("value"=>$item->name, "data"=>$item);
                }
                
                $resp = array(
                    "suggestions"=>$sugg
                );
                
                echo json_encode($resp);
                exit();
            
            case 'read_r':
                header('Content-Type: application/json');
                $obj = getRandPkmnItem(PKMN_SIMPLE_LOAD);
                echo json_resp_test($obj, "Could not read random item");
                exit();
        }
    }
    
    if(isset($req_data['btnRet'])){
        header('Location: ../itemAdmin.php');
        exit();
    }
    
    $obj = createObjFromReq($req_data, array(
        "id_category",
        "id_stat",
        "name",
        "value",
    ));

    insertPkmnItem($obj);
    header("Location: ../itemAdmin.php?t=1&n=pkmn_msg_success");
}
?>
