<?php

require_once '../db_connection.php';
require_once '../data_access/trainer.php';
require_once '../data_access/faction.php';
require_once '../data_access/system_property.php';
require_once '../util/json_functions.php';
require_once '../util/req_functions.php';

$req_data = filter($_REQUEST);

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            if (isset($req_data['btnRet'])) {
                header('Location: ../trainers.php');
                exit();
            }

            $obj = getTrainer($id);
            updateObjFromReq($obj, $req_data);
            updateTrainer($obj);
            header("Location: ../trainers.php?t=1&n=pkmn_msg_success");
            break;

        case 'vd'://Victory/Defeat action
            $obj = getTrainer($id);
            $obj->victories += isset($req_data['victory']) ? (int) $req_data['victory'] : 0;
            $obj->defeats += isset($req_data['defeat']) ? (int) $req_data['defeat'] : 0;

            updateTrainer($obj);
            break;

        case 'pts':
            $obj = getTrainer($id);
            if ($req_data['type'] == 1) {
                $pts = getPropertyByName('faction_higher_pts');
            } else if ($req_data['type'] == 2) {
                $pts = getPropertyByName('faction_lower_pts');
            }

            if (!is_null($pts)) {
                $obj->faction_pts += (int) $pts->value;
                updateTrainer($obj);
            }

            echo json_resp_test($obj->faction_pts, "");
            break;

        case 'delete':
            if (isset($req_data['btnYes'])) {
                deleteTrainerById($id);
                header("Location: ../trainers.php?t=1&n=pkmn_msg_success");
            }
            break;

        case 'read':
            header('Content-Type: application/json');
            $obj = getTrainer($id);

            echo json_resp_test($obj, "Trainer not found: $id");
            exit();
    }
} else {
    if (isset($req_data['action'])) {
        switch ($req_data['action']) {
            case 'ctrn':
                header('Content-Type: application/json');
                $obj = getCurrentTrainer();
                echo json_resp_test($obj, "User trainer data not found");
                exit();

            case 'acpl':
                header('Content-Type: application/json');
                $trainers = getUsrTrainerList($req_data['query'] . '%', 'u.username', 20);

                $sugg = array();
                foreach ($trainers as $t) {
                    $sugg[] = array("value" => $t->username, "data" => $t);
                }

                $resp = array(
                    "suggestions" => $sugg
                );

                echo json_encode($resp);
                exit();
        }
    }

    if (isset($req_data['btnRet'])) {
        header('Location: ../trainers.php');
        exit();
    }


    $obj = createObjFromReq($req_data, array(
        "id_user",
        "id_faction",
        "id_gym",
        "id_type",
        "visible",
        "order_index",
        "silver",
        "gold",
        "faction_pts",
        "name",
        "victories",
        "defeats"
    ));

    insertTrainer($obj);
    header("Location: ../trainers.php?t=1&n=pkmn_msg_success");
}
?>
