<?php
require_once '../db_connection.php';
require_once '../data_access/pokemon_img.php';

$msg = 0;
$req_data = filter($_POST);

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            $obj = getPokemonImg($id);
            $obj->id_pokemon = $req_data['id_pokemon'];
            $obj->type = $req_data['type'];
            $obj->image = $req_data['image'];
            updatePokemonImg($obj);
            break;

        case 'delete':
            if (isset($req_data['btnYes'])) {
                //deletePokemonImg($id);
            }
            break;
            
        case 'read':
            header('Content-Type: application/json');
            $obj = getPokemonImg($id);
            echo json_resp_test($obj, "Pokemon image not found: $id");
            exit();
    }
} else {
    $obj = array(
        "id_pokemon"=>$req_data['id_pokemon'],
        "type"=>$req_data['type'],
        "image"=>$req_data['image']
        );

    insertPokemonImg($obj);
}

if ($msg == 0) {
    header('Location: ../main.php');
} else {
    header('Location: ../main.php?msg=' . $msg);
}
?>
