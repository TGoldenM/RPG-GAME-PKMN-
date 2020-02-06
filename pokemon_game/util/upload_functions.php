<?php

defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));

use PHPImageWorkshop\ImageWorkshop;

require_once ROOTPATH . 'lib/PHPExcel.php';
require_once ROOTPATH . 'lib/PHPImageWorkshop/ImageWorkshop.php';
require_once ROOTPATH . 'data_access/pokemon.php';
require_once ROOTPATH . 'data_access/pokemon_type.php';
require_once ROOTPATH . 'data_access/pokemon_group.php';
require_once ROOTPATH . 'util/excel_functions.php';

function storeAvatar($upFile, $dest) {
    return storeImage($upFile, $dest, array(120, 120));
}

function storeImgSignature($upFile, $dest) {
    return storeImage($upFile, $dest, array(402, 151));
}

/**
 * 
 * @param type $upFile a $_FILES object with key indicated
 * @param string $dest Where image is stored
 * @param type $maxDim Maximum dimensions of file in pixels. Specified in array
 *              like array($width, $height).
 * 
 * @param int $maxFileSize Maximum file size
 * @param type $types Allowed MIME image types
 * @return int 1 is correct, 2 is invalid image size, 3 is error storing file.
 */
function storeImage($upFile, $dest, $maxDim = null, $maxFileSize = null
, $types = array(
    'jpg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
)) {
    $img = ImageWorkshop::initFromPath($upFile['tmp_name']);

    $w = $img->getWidth();
    $h = $img->getHeight();

    if (!is_null($maxDim) && ($w > $maxDim[0] || $h > $maxDim[1])) {
        return 2;
    } else {
        $dir = dirname($dest);
        if (!file_exists($dir)) {
            mkdir($dir, 755, true);
        }

        $d = str_replace(' ', '_', $dest);
        $msg = storeFile($upFile, $d, $maxFileSize, $types);

        if (isset($msg)) {
            error_log($msg);
            return 3;
        }

        return 1;
    }
}

/**
 * 
 * @param type $upFile a $_FILES object with key indicated
 * @param type $dest Where image is stored
 * @param type $maxFileSize Maximum file size
 * @param type $types Allowed MIME types
 * @return int 1 is correct, 2 is invalid image size, 3 is error storing file.
 */
function storeFile($upFile, $dest, $maxFileSize = null, $types = array(
    'jpg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
)) {
    try {
        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (!isset($upFile['error']) ||
                is_array($upFile['error'])
        ) {
            throw new RuntimeException('Invalid parameters.');
        }

        // Check $_FILES[$uploadedFile]['error'] value.
        switch ($upFile['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }

        // You should also check filesize here. 
        if (!is_null($maxFileSize) && $upFile['size'] > $maxFileSize) {
            throw new RuntimeException('Exceeded filesize limit.');
        }

        // DO NOT TRUST $_FILES[$uploadedFile]['mime'] VALUE !!
        // Check MIME Type by yourself.
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search(
                $finfo->file($upFile['tmp_name']), $types, true)) {
            throw new RuntimeException('Invalid file format.');
        }

        // You should name it uniquely.
        // DO NOT USE $_FILES[$uploadedFile]['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.
        if (!move_uploaded_file($upFile['tmp_name'], $dest)) {
            throw new RuntimeException('Failed to move uploaded file.');
        }
    } catch (RuntimeException $e) {
        echo $e->getMessage();
    }
}

function storePkmnData($dataFile, $zipFile) {
    $za = new ZipArchive();

    if (preg_match("/^.*\.zip$/i", $zipFile['name'])) {
        $za->open($zipFile['tmp_name']);
    }

    if (preg_match("/^.*\.xlsx?$/i", $dataFile['name'])) {
        $reader = PHPExcel_IOFactory::createReaderForFile($dataFile['tmp_name']);
        $excelObj = $reader->load($dataFile['tmp_name']);
        $acSheet = $excelObj->getActiveSheet();

        $hdRow = $acSheet->getRowIterator(1)->current();
        $cellIt = $hdRow->getCellIterator();
        $cellIt->setIterateOnlyExistingCells(false);

        $hdCells = xlsRowToArray($cellIt);
        $res = xlsValidateHeader($hdCells, array("disabled"
            , "genderless", "name", "hp", "attack", "defense", "speed"
            , "spec_attack", "spec_defense", "base_exp", "evhp", "evattack"
            , "evdefense", "evspeed", "evspec_attack", "evspec_defense"
            , "types", "groups"
            , "image"));

        if ($res != null) {
            return array("code" => 2, "data" => $res);
        }

        $hdCols = xlsGetCellColumns($cellIt);
        $rowIt = $acSheet->getRowIterator(2);

        foreach ($rowIt as $row) {
            $cellIt = $row->getCellIterator();
            $rowIndex = $row->getRowIndex();
            $cells = xlsReadCells($acSheet, $rowIndex, $hdCols);

            //Skip invalid row
            if ($cells['disabled'] === null) {
                continue;
            }

            $numericFields = array("hp", "attack", "defense"
                , "speed", "spec_attack", "spec_defense", "base_exp", "evhp"
                , "evattack", "evdefense", "evspeed", "evspec_attack"
                , "evspec_defense"
                );

            foreach ($numericFields as $field) {
                $val = $cells[$field];
                if (!is_numeric($val)) {
                    return array("code" => 3, "data" => array("row" => $rowIndex, "field" => $field, "value" => $val));
                }
            }

            $obj = (object) $cells;
            $pkmn = getPokemonByName($cells['name']);

            if ($pkmn == null) {
                $id = insertPokemon($obj);
                $obj->id = $id;
            } else {
                $id = $obj->id = $pkmn->id;
                updatePokemon($obj);
            }

            if ($id != null) {
                $types = array();
                foreach (getPokemonTypesByName($cells['types']) as $t) {
                    $types[] = $t->id;
                }

                $groups = array();
                foreach (getPokemonGroupsByName($cells['groups']) as $g) {
                    $groups[] = $g->id;
                }

                assignPokemonTypes($obj, $types, true);
                assignPokemonGroups($obj, $groups, true);

                if ($cells['image'] != null) {
                    $pinfo = pathinfo($cells['image']);
                    $name = $pinfo['basename'];
                    $imgFile = $pinfo['filename'] . "_0." . $pinfo['extension'];
                    $imgFile = str_replace(" ", "_", $imgFile);

                    $obj = (object) array(
                                "id_pokemon" => $id,
                                "type" => 0,
                                "image" => $imgFile
                    );

                    $pkmnImg = getPokemonImgByType($id);
                    if ($pkmnImg == null) {
                        $imgId = insertPokemonImg($obj);
                    } else {
                        $imgId = $obj->id = $pkmnImg->id;
                        unlink(ROOTPATH . "images/pokemon/$id/$pkmnImg->image");
                        updatePokemonImg($obj);
                    }

                    if ($imgId != null) {
                        $za->extractTo(ROOTPATH . "images/pokemon/$id/"
                                , array($name));

                        rename(ROOTPATH . "images/pokemon/$id/$name"
                                , ROOTPATH . "images/pokemon/$id/$imgFile");
                    }
                }
            }
        }
    }

    $za->close();
    return array("code" => 1, "data" => "OK");
}

?>