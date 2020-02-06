<?php

require_once '../db_connection.php';
require_once '../data_access/user.php';
require_once '../data_access/system_role.php';
require_once '../data_access/trainer.php';
require_once '../util/upload_functions.php';
require_once '../util/req_functions.php';
require_once '../ui_support/checkbox.php';
require_once '../ui_support/img.php';

$imgDir = ROOTPATH . "images";
$req_data = filter($_REQUEST);

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'ping':
            $obj = getSystemUser($id);
            $obj->last_updated = date("Y-m-d H:i:s");
            updateSystemUser($obj);
            exit();
        
        case 'rstpwd':
            $obj = getSystemUser($id);
            $tmpHash = $req_data['h'];

            if ($obj->tmp_password == $tmpHash) {
                $obj->password = hash('sha512', $req_data['password']);
                $obj->tmp_password = null;
                updateSystemUser($obj);

                header("Location: ../login.php?t=1&n=pswd_reset_ok");
            } else {
                header("Location: ../login.php?t=0&n=pswd_reset_error");
            }
            exit();

        case 'edit':
            if (isset($req_data['btnRet'])) {
                if (curUserIsAdmin()) {
                    header('Location: ../userAdmin.php');
                } else {
                    header('Location: ../main.php');
                }
                exit();
            }

            $imgUsrDir = $imgDir . "/user/$id";
            $obj = getSystemUser($id);
            $obj->banned = isset($req_data['banned']) ? $req_data['banned'] : 0;
            $obj->disabled = isset($req_data['disabled']) ? $req_data['disabled'] : 0;

            if ($obj->username != $req_data['username'] && curUserIsAdmin()) {
                if (checkIfUserExists($req_data['username']) == true) {
                    $_SESSION['userObj'] = serialize($obj);
                    header("Location: ../userEdit.php?t=0&n=user_exists&id=$id");
                    exit();
                }
            }

            $obj->username = $req_data['username'];

            if (!empty($req_data['password'])) {
                $obj->password = hash('sha512', $req_data['password']);
            }

            $obj->mail = $req_data['mail'];
            $obj->gender = $req_data['gender'];

            if ($_FILES['avatarFile']['error'] != UPLOAD_ERR_NO_FILE) {
                $ext = pathinfo($_FILES['avatarFile']['name'], PATHINFO_EXTENSION);

                $files = glob("$imgUsrDir/avatar.*");
                if ($files != false && !empty($files)) {
                    $avFile = $files[0];
                    unlink($avFile);
                }

                $r = storeAvatar($_FILES['avatarFile'], $imgUsrDir . "/avatar.$ext");

                switch ($r) {
                    case 1:
                        break;

                    case 2:
                        header("Location: ../userEdit.php?t=0&n=avatar_img_bad_format&id=$id");
                        $_SESSION['userObj'] = serialize($obj);
                        exit();

                    case 3:
                        header("Location: ../userEdit.php?t=0&n=pkmn_msg_error&id=$id");
                        $_SESSION['userObj'] = serialize($obj);
                        exit();
                }
            }

            if ($_FILES['signFile']['error'] != UPLOAD_ERR_NO_FILE) {
                $ext = pathinfo($_FILES['signFile']['name'], PATHINFO_EXTENSION);

                $files = glob("$imgUsrDir/signature.*");
                if ($files != false && !empty($files)) {
                    $avFile = $files[0];
                    unlink($avFile);
                }

                $r = storeImgSignature($_FILES['signFile'], $imgUsrDir . "/signature.$ext");

                switch ($r) {
                    case 1:
                        break;

                    case 2:
                        header("Location: ../userEdit.php?t=0&n=sign_bad_format&id=$id");
                        $_SESSION['userObj'] = serialize($obj);
                        exit();

                    case 3:
                        header("Location: ../userEdit.php?t=0&n=pkmn_msg_error&id=$id");
                        $_SESSION['userObj'] = serialize($obj);
                        exit();
                }
            }

            updateSystemUser($obj);
            if (isset($req_data['roles'])) {
                assignRoles($obj, $req_data['roles']);
            }

            unset($_SESSION['userObj']);
            $tr = getTrainerByUserId($id);
            $tr->id_faction = $req_data['id_faction'];
            updateTrainer($tr);

            if (curUserIsAdmin()) {
                header("Location: ../userAdmin.php?t=1&n=pkmn_msg_success");
            } else {
                header("Location: ../main.php?t=1&n=pkmn_msg_success");
            }
            break;

        case 'disable':
            $obj = getSystemUser($id);
            $obj->disabled = 1;
            updateSystemUser($obj);
            header("Location: ../userAdmin.php?t=1&n=pkmn_msg_success");
            exit();

        case 'delete':
            if (isset($req_data['btnYes'])) {
                //deleteUser($id);
            }
            break;

        case 'read':
            header('Content-Type: application/json');
            $obj = getSystemUser($id);
            unset($obj->password);

            echo json_resp_test($obj, "User not found: $id");
            exit();

        case 'img':
            $imgUsrDir = $imgDir . "/user/$id";

            $files = glob("$imgUsrDir/avatar.*");
            if ($files != false && !empty($files)) {
                $avFile = $files[0];
            } else {
                $avFile = "$imgDir/layout/avatar-big.png";
            }

            $finfo = new finfo;
            $mime = $finfo->file($avFile, FILEINFO_MIME);
            header('Content-Type: ' . $mime);

            if (isset($req_data['w']) && isset($req_data['h'])) {
                outputResizedImg($avFile, $req_data['w'], $req_data['h']);
            } else {
                $file = @fopen($avFile, "rb");
                if ($file) {
                    fpassthru($file);
                }
            }

            exit();

        case 'sgnt':
            $imgUsrDir = $imgDir . "/user/$id";

            $files = glob("$imgUsrDir/signature.*");
            if ($files != false && !empty($files)) {
                $avFile = $files[0];
                $finfo = new finfo;
                $mime = $finfo->file($avFile, FILEINFO_MIME);
                header('Content-Type: ' . $mime);

                $file = @fopen($avFile, "rb");
                if ($file) {
                    fpassthru($file);
                }
            } else {
                //create image with specified sizes
                $image = imagecreatetruecolor(1, 1);
                //saving all full alpha channel information
                imagesavealpha($image, true);
                //setting completely transparent color
                $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
                //filling created image with transparent color
                imagefill($image, 0, 0, $transparent);

                header('Content-Type: image/png');
                imagepng($image, null, 8);
            }

            break;
    }
} else {
    if (isset($req_data['btnRet'])) {
        if (curUserIsAdmin()) {
            header('Location: ../userAdmin.php');
        } else {
            header('Location: ../main.php');
        }
        exit();
    }

    $obj = createObjFromReq($req_data, array(
        "last_updated",
        "banned",
        "disabled",
        "username",
        "password",
        "tmp_password",
        "mail",
        "gender"
    ));

    $obj->password = hash('sha512', $req_data['password']);

    if ($req_data['username']) {
        if (checkIfUserExists($req_data['username']) == true) {
            $_SESSION['userObj'] = serialize($obj);
            header("Location: ../userEdit.php?t=0&n=user_exists");
            exit();
        }
    }

    $id = insertSystemUser($obj);
    $imgUsrDir = $imgDir . "/user/$id";

    if (isset($req_data['roles'])) {
        assignRoles($obj, $req_data['roles'], false);
    }
    unset($_SESSION['userObj']);

    if ($_FILES['avatarFile']['error'] != UPLOAD_ERR_NO_FILE) {
        $ext = pathinfo($_FILES['avatarFile']['name'], PATHINFO_EXTENSION);

        $files = glob("$imgUsrDir/avatar.*");
        if ($files != false && !empty($files)) {
            $avFile = $files[0];
            unlink($avFile);
        }

        $r = storeAvatar($_FILES['avatarFile'], $imgUsrDir . "/avatar.$ext");

        switch ($r) {
            case 1:
                break;

            case 2:
                header("Location: ../userEdit.php?t=0&n=avatar_img_bad_format&id=$id");
                $_SESSION['userObj'] = serialize($obj);
                exit();

            case 3:
                header("Location: ../userEdit.php?t=0&n=pkmn_msg_error&id=$id");
                $_SESSION['userObj'] = serialize($obj);
                exit();
        }
    }

    if ($_FILES['signFile']['error'] != UPLOAD_ERR_NO_FILE) {
        $ext = pathinfo($_FILES['signFile']['name'], PATHINFO_EXTENSION);

        $files = glob("$imgUsrDir/signature.*");
        if ($files != false && !empty($files)) {
            $avFile = $files[0];
            unlink($avFile);
        }

        $r = storeImgSignature($_FILES['signFile'], $imgUsrDir . "/signature.$ext");

        switch ($r) {
            case 1:
                break;

            case 2:
                header("Location: ../userEdit.php?t=0&n=sign_bad_format&id=$id");
                $_SESSION['userObj'] = serialize($obj);
                exit();

            case 3:
                header("Location: ../userEdit.php?t=0&n=pkmn_msg_error&id=$id");
                $_SESSION['userObj'] = serialize($obj);
                exit();
        }
    }

    $tr = createObjFromReq($req_data, array(
        "id_user",
        "id_faction",
        "id_gym",
        "id_type",
        "visible",
        "order_index",
        "silver",
        "gold",
        "faction_pts",
        "name"
    ));

    $tr->id_user = $id;
    insertTrainer($tr);
    if (curUserIsAdmin()) {
        header("Location: ../userAdmin.php?t=1&n=pkmn_msg_success");
    } else {
        header("Location: ../main.php?t=1&n=pkmn_msg_success");
    }
}
?>
