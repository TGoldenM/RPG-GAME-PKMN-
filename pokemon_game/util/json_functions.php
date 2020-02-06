<?php
function json_resp($isCorrect, $data, $msg = null){
    $resp = array("correct"=>($isCorrect ? 1 : 0),
        "msg"=>$msg, "data"=>$data);
    
    return json_encode($resp);
}

function json_resp_test($obj, $msg){
    if($obj != null){
        return json_resp(true, $obj);
    } else {
        return json_resp(false, $obj, $msg);
    }
}
?>