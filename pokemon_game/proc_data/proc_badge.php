<?php
require_once '../db_connection.php';
require_once '../data_access/badge.php';
require_once '../data_access/trainer.php';
require_once '../util/json_functions.php';
require_once '../util/req_functions.php';
require_once '../util/badge_functions.php';
require_once '../ui_support/img.php';

$req_data = filter($_REQUEST);
$imgDir = ROOTPATH . "images";

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            $obj = getBadge($id);
            if(isset($req_data['btnRet'])){
                header('Location: ../badgeAdmin.php');
                exit();
            }
            
            updateObjFromReq($obj, $req_data);
            
            if($_FILES['imgFile']['error'] != UPLOAD_ERR_NO_FILE){
                if(storeBadgeImg($obj, $_FILES['imgFile'], $imgDir."/badges/$id")){
                    $obj->image = $_FILES['imgFile']['name'];
                    updateBadge($obj);
                    header("Location: ../badgeAdmin.php?t=1&n=pkmn_msg_success");
                } else {
                    header("Location: ../badgeAdmin.php?t=1&n=pkmn_msg_error");
                }
            } else {
                updateBadge($obj);
                header("Location: ../badgeAdmin.php?t=1&n=pkmn_msg_success");
            }
            
            break;

        case 'delete':
            if (isset($req_data['btnYes'])) {
                $obj = getBadge($id);
                deleteBadgeById($id);
                unlink($imgDir."/badges/$id/$obj->image");
                header("Location: ../badgeAdmin.php?t=1&n=pkmn_msg_success");
            }
            break;
            
        case 'read':
            header('Content-Type: application/json');
            if($req_data['type'] != 'gym'){
                $obj = getBadge($id);
            } else {
                $obj = getFirstBadgeByGym($id);
            }
            
            echo json_resp_test($obj, "Badge not found: $id");
            exit();
        
        case 'assign':
            header('Content-Type: application/json');
            $trn = getTrainer($req_data['id_trainer']);
            $validTrn = !is_null($trn);
            if($validTrn){
                assignBadges($trn, array($id), false);
            }
            
            echo json_resp_test($validTrn, "Trainer not found: "
                    . $req_data['id_trainer']);
            
            exit();
            
        case 'img':
            $obj = getBadge($id);
            $imgMap = $imgDir."/badges/$id/$obj->image";
            
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
        header('Location: ../badgeAdmin.php');
        exit();
    }

    $obj = createObjFromReq($req_data, array("id_gym", "name"));
    if($_FILES['imgFile']['error'] == UPLOAD_ERR_NO_FILE){
        $_SESSION['badgeObj'] = serialize($obj);
        header("Location: ../badgeEdit.php?t=0&n=badge_img_not_loaded&id=$id");
        exit();
    }

    $obj->image = $_FILES['imgFile']['name'];
    insertBadge($obj);
    
    if(storeBadgeImg($obj, $_FILES['imgFile'], $imgDir."/badges/$obj->id")){
        header("Location: ../badgeAdmin.php?t=1&n=pkmn_msg_success");
    } else {
        header("Location: ../badgeAdmin.php?t=1&n=pkmn_msg_error");
    }
}
?>
