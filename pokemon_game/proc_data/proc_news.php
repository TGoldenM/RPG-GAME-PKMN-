<?php
require_once '../db_connection.php';
require_once '../data_access/user.php';
require_once '../data_access/trainer.php';
require_once '../data_access/news.php';
require_once '../util/json_functions.php';

$msg = 0;
$req_data = filter($_REQUEST);

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            if(isset($req_data['btnRet'])){
                header('Location: ../newsAdmin.php');
                exit();
            }
            
            $obj = getNews($id);
            $obj->id_trainer = $req_data['id_tr'];
            $obj->title = $req_data['title'];
            $obj->content = addslashes($req_data['mce_editor']);
            
            try{
                updateNews($obj);
                unset($_SESSION['newsObj']);
                header("Location: ../newsAdmin.php?t=1&n=pkmn_msg_success");
            } catch(Exception $e){
                error_log($e->getMessage());
                $_SESSION['newsObj'] = serialize($obj);
                header("Location: ../newsAdmin.php?t=0&n=pkmn_msg_error");
            }
            break;

        case 'delete':
            if (isset($req_data['btnYes'])) {
                deleteNewsById($id);
                header("Location: ../newsAdmin.php?t=1&n=pkmn_msg_success");
            }
            break;
            
        case 'read':
            header('Content-Type: application/json');
            $obj = getNews($id);
            $obj->content = stripslashes($obj->content);
            echo json_resp_test($obj, "News not found: $id");
            exit();
    }
} else {
    if(isset($req_data['btnRet'])){
        header('Location: ../newsAdmin.php');
        exit();
    }
    
    $obj = (object)array(
        "id_trainer" => $req_data['id_tr'],
        "title" => $req_data['title'],
        "created" => date("Y-m-d H:i:s"),
        "content" => addslashes($req_data['mce_editor'])
        );

    try{
        insertNews($obj);
        unset($_SESSION['newsObj']);
        header("Location: ../newsAdmin.php?t=1&n=pkmn_msg_success");
    } catch(Exception $e){
        error_log($e->getMessage());
        $_SESSION['newsObj'] = serialize($obj);
        header("Location: ../newsAdmin.php?t=0&n=pkmn_msg_error");
    }
}
?>
