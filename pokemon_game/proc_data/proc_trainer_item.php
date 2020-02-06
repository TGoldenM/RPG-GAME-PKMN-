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
                header('Location: ../trnItems.php');
                exit();
            }
            
            $obj = getTrainerItem($id);
            updateObjFromReq($obj, $req_data);
            updateTrainerItem($obj);
            header("Location: ../trnItems.php?t=1&n=pkmn_msg_success");
            break;

        case 'delete':
            if (isset($req_data['btnYes'])) {
                deleteTrainerItemById($id);
                
                if(isset($req_data['j'])){
                    header('Content-Type: application/json');
                    echo json_resp_test(true, "Trainer item not found: $id");
                    
                } else {
                    header("Location: ../trnItems.php?t=1&n=pkmn_msg_success");
                }
            }
            break;
            
        case 'read':
            header('Content-Type: application/json');
            $obj = getTrainerItem($id);
            
            echo json_resp_test($obj, "Trainer item not found: $id");
            exit();
    }
} else {
    if (isset($req_data['action'])) {
        switch ($req_data['action']) {
            case 'assign':
                header('Content-Type: application/json');
                $item = getRandPkmnItem();
                $obj = createObjFromReq($req_data, array("id_trainer"));
                $obj->id_item = $item->id;
                insertTrainerItem($obj);
                echo json_resp_test($item, "Unable to assign random item to trainer $obj->id_trainer");
                exit();
        }
    }
    
    if(isset($req_data['btnRet'])){
        header('Location: ../trnItems.php');
        exit();
    }

    $obj = createObjFromReq($req_data, array(
        "id_trainer",
        "id_item"
    ));

    insertTrainerItem($obj);
    header("Location: ../trnItems.php?t=1&n=pkmn_msg_success");
}
?>
