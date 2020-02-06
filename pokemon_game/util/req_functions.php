<?php

function createObjFromReq($req_data, $params, $testZero = false){
    $res = array();
    foreach($params as $p){
        if(isset($req_data[$p])){
            if($testZero && $req_data[$p] == 0){
                $res[$p] = null;
            } else {
                $res[$p] = $req_data[$p];
            }
        }
    }
    return (object)$res;
}

function updateObjFromReq(&$obj, $req_data, $testZero = false){
    $res = array();
    foreach($obj as $k => $v){
        if(isset($req_data[$k])){
            if($testZero && $req_data[$k] == 0){
                $obj->$k = null;
            } else {
                $obj->$k = $req_data[$k];
            }
        }
    }
    return $res;
}

?>