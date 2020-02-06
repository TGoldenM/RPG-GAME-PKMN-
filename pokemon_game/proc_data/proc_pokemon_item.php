<?php
require_once '../db_connection.php';
require_once '../data_access/pokemon_item.php';

$msg = 0;
$req_data = filter($_POST);

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            $obj = getPokemonItem($id);
            $obj->name = $req_data['name'];
            updatePokemonItem($obj);
            break;

        case 'delete':
            if (isset($req_data['btnYes'])) {
                //deletePokemonItem($id);
            }
            break;
            
        case 'read':
            header('Content-Type: application/json');
            $obj = getPokemonItem($id);
            echo json_resp_test($obj, "Pokemon item not found: $id");
            exit();
    }
} else {
    $obj = array(
        "name"=>$req_data['name']
        );

    insertPokemonItem($obj);
}

if ($msg == 0) {
    header('Location: ../main.php');
} else {
    header('Location: ../main.php?msg=' . $msg);
}
?>
