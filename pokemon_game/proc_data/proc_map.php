<?php
require_once '../db_connection.php';
require_once '../data_access/map.php';
require_once '../util/json_functions.php';
require_once '../util/req_functions.php';
require_once '../util/map_functions.php';
require_once '../ui_support/img.php';

$req_data = filter($_REQUEST);
$imgDir = ROOTPATH . "images";

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            $obj = getMap($id);
            if(isset($req_data['btnRet'])){
                header("Location: ../mapAdmin.php?id_region=$obj->id_region");
                exit();
            }
            
            updateObjFromReq($obj, $req_data);
            
            if($_FILES['imgFile']['error'] != UPLOAD_ERR_NO_FILE){
                if(storeMapImg($obj, $_FILES['imgFile'], $imgDir."/maps/$id")){
                    $obj->image = $_FILES['imgFile']['name'];
                    updateMap($obj);
                    header("Location: ../mapAdmin.php?t=1&n=pkmn_msg_success&id_region=$obj->region");
                } else {
                    header("Location: ../mapAdmin.php?t=1&n=pkmn_msg_error&id_region=$obj->region");
                }
            } else {
                updateMap($obj);
                header("Location: ../mapAdmin.php?t=1&n=pkmn_msg_success&id_region=$obj->region");
            }
            
            break;

        case 'delete':
            if (isset($req_data['btnYes'])) {
                $obj = getMap($id);
                deleteMapById($id);
                unlink($imgDir."/maps/$id/$obj->image");
                header("Location: ../mapAdmin.php?t=1&n=pkmn_msg_success&id_region=$obj->id_region");
            }
            break;
            
        case 'read':
            header('Content-Type: application/json');
            $obj = getMap($id);
            
            echo json_resp_test($obj, "Map not found: $id");
            exit();
            
        case 'img':
            $obj = getMap($id);
            $imgMap = $imgDir."/maps/$id/$obj->image";
            
            $finfo = new finfo;
            $mime = $finfo->file($imgMap, FILEINFO_MIME);
            header('Content-Type: ' . $mime);

            if (isset($req_data['w']) && isset($req_data['h'])) {
                outputResizedImg($imgMap, $req_data['w'], $req_data['h']);
            } else {
                $file = @fopen($imgMap, "rb");
                if ($file) {
                    fpassthru($file);
                }
            }
            
            exit();
    }
} else {
    if(isset($req_data['btnRet'])){
        header('Location: ../mapAdmin.php');
        exit();
    }

    $id_r = $req_data['id_region'];
    $obj = createObjFromReq($req_data, array("id_region", "name"));
    if($_FILES['imgFile']['error'] == UPLOAD_ERR_NO_FILE){
        $_SESSION['mapObj'] = serialize($obj);
        header("Location: ../mapEdit.php?t=0&n=map_img_not_loaded&id_region=$id_r");
        exit();
    }
    
    insertMap($obj);

    if(storeMapImg($obj, $_FILES['imgFile'], $imgDir."/maps/$obj->id")){
        header("Location: ../mapAdmin.php?t=1&n=pkmn_msg_success&id_region=$id_r");
    }
}
?>
