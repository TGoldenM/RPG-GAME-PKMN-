<?php
defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));

use PHPImageWorkshop\ImageWorkshop;
require_once ROOTPATH . 'lib/PHPImageWorkshop/ImageWorkshop.php';

function outputResizedImg($path, $w, $h){
    $avatar = ImageWorkshop::initFromPath($path);
    $avatar->resizeInPixel($w, $h, false);
    $res = $avatar->getResult();

    $ext = pathinfo($path, PATHINFO_EXTENSION);
    switch($ext){
        case 'jpg':
        case 'jpeg':
            imagejpeg($res, null, 95);
            break;

        case 'gif':
            imagegif($res);
            break;

        case 'png':
            imagepng($res, null, 8);
            break;
    }
}
?>