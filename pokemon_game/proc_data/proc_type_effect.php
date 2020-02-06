<?php

require_once '../db_connection.php';
require_once '../data_access/pokemon_type.php';
require_once '../util/json_functions.php';

$req_data = filter($_REQUEST);

switch ($req_data['action']) {
    case 'edit':
        if (isset($req_data['btnRet'])) {
            header('Location: ../typesAdmin.php');
            exit();
        }

        foreach($req_data['multiplier'] as $id=>$value){
            $obj = getTypeEffect($req_data['type_id'], $id);
            $obj->multiplier = $value;
            updateTypeEffect($obj);
        }
        
        header("Location: ../typesAdmin.php?t=1&n=pkmn_msg_success");
        break;
}
?>
