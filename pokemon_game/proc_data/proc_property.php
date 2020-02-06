<?php

require_once '../db_connection.php';
require_once '../data_access/system_property.php';
require_once '../util/json_functions.php';
require_once '../util/req_functions.php';

$req_data = filter($_REQUEST);

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            require_once '../checkLogin.php';
            if (isset($req_data['btnRet'])) {
                header('Location: ../sysPropAdmin.php');
                exit();
            }

            $obj = getProperty($id);
            updateObjFromReq($obj, $req_data);
            updateProperty($obj);

            header("Location: ../sysPropAdmin.php?t=1&n=pkmn_msg_success");
            break;

        case 'delete':
            require_once '../checkLogin.php';
            if (isset($req_data['btnYes'])) {
                deletePropertyById($id);
                header("Location: ../sysPropAdmin.php?t=1&n=pkmn_msg_success");
            }
            break;

        case 'read':
            header('Content-Type: application/json');
            $obj = getProperty($id);
            echo json_resp_test($obj, "Property not found: $id");
            exit();
    }
} else {
    require_once '../checkLogin.php';
    if (isset($req_data['btnRet'])) {
        header('Location: ../sysPropAdmin.php');
        exit();
    }

    if (isset($req_data['action'])) {
        switch ($req_data['action']) {
            case 'read':
                header('Content-Type: application/json');
                $obj = getPropertyByName($req_data['name']);
                echo json_resp_test($obj, "Property not found: ".$req_data['name']);
                exit();
        }
    }

    $obj = createObjFromReq($req_data, array("name", "value"));

    insertProperty($obj);
    header("Location: ../sysPropAdmin.php?t=1&n=pkmn_msg_success");
}
?>
