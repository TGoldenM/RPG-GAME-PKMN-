<?php
require_once '../db_connection.php';
require_once '../data_access/pokemon_group.php';
require_once '../util/json_functions.php';

$req_data = filter($_POST);

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            if(isset($req_data['btnRet'])){
                header('Location: ../groupAdmin.php');
                exit();
            }
            
            $obj = getPokemonGroup($id);
            $obj->name = $req_data['name'];
            updatePokemonGroup($obj);
			unset($_SESSION['groupObj']);
            header("Location: ../groupAdmin.php?t=1&n=pkmn_msg_success");
            break;

        case 'delete':
            if (isset($req_data['btnYes'])) {
                //deletePokemonGroup($id);
            }
            break;
            
        case 'read':
            header('Content-Type: application/json');
            $obj = getPokemonGroup($id);
            echo json_resp_test($obj, "Pokemon group not found: $id");
            exit();
    }
} else {
    if(isset($req_data['btnRet'])){
        header('Location: ../groupAdmin.php');
        exit();
    }

    $obj = array(
        "name"=>$req_data['name']
        );

    insertPokemonGroup($obj);
	unset($_SESSION['groupObj']);
    header("Location: ../groupAdmin.php?t=1&n=pkmn_msg_success");
}

?>
