<?php
defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH.'util/upload_functions.php';

function storeMapImg($obj, $file, $dir) {
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if(!preg_match("(gif|jpe?g|png)", $ext)){
        $_SESSION['mapObj'] = serialize($obj);
        header("Location: ../mapEdit.php?t=0&n=map_img_bad_type&id=$obj->id");
        return false;
    }

    $files = glob("$dir/".$file['name']);
    if ($files != false && !empty($files)) {
        $f = $files[0];
        unlink($f);
    }

    $r = storeImage($file, "$dir/".$file['name']);

    switch ($r) {
        case 1:
            return true;

        case 2:
            header("Location: ../mapEdit.php?t=0&n=map_img_bad_format&id=$obj->id");
            $_SESSION['mapObj'] = serialize($obj);
            return false;

        case 3:
            header("Location: ../mapEdit.php?t=0&n=pkmn_msg_error&id=$obj->id");
            $_SESSION['mapObj'] = serialize($obj);
            return false;
    }

    return true;
}

?>