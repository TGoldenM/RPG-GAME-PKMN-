<?php
defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH.'lib/PHPExcel.php';

function xlsValidateHeader($cells, $headers){
    foreach ($headers as $hd){
        if(!in_array($hd, $cells)){
            return $hd;
        }
    }
    
    return null;
}

function xlsGetCellColumns($cellIt){
    $res = array();
    
    foreach ($cellIt as $c) {
        $res[$c->getValue()] = $c->getColumn();
    }
    
    return $res;
}

function xlsRowToArray($cellIt){
    $res = array();
    
    foreach ($cellIt as $cell) {
        $res[] = $cell->getValue();
    }

    return $res;
}

function xlsReadCells($acSheet, $rowIdx, $hdCols){
    $res = array();
    
    foreach($hdCols as $hd => $col){
        $colIndex = PHPExcel_Cell::columnIndexFromString($col);
        $cell = $acSheet->getCellByColumnAndRow($colIndex-1, $rowIdx);
        $res[$hd] = $cell->getValue();
    }

    return $res;
}

?>