<?php
function getBaseURL() {
    $isHttps = ((array_key_exists('HTTPS', $_SERVER) 
            && $_SERVER['HTTPS']) ||
        (array_key_exists('HTTP_X_FORWARDED_PROTO', $_SERVER) 
                && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
    );
    return 'http' . ($isHttps ? 's' : '') .'://' . $_SERVER['SERVER_NAME'];
}

function buildPkmnImgURL($id, $type = null, $w = null, $h = null){
    $url = getBaseURL() . "/pokemon_game/proc_data/proc_pokemon.php?action=img&id=$id";
    
    if(!is_null($type)){
        $url .= "&t=$type";
    }
    
    if(!is_null($w)){
        $url .= "&w=$w";
    }
    
    if(!is_null($h)){
        $url .= "&h=$h";
    }
    
    return $url;
}

function buildUsrImgURL($id, $w = null, $h = null){
    $url = getBaseURL() . "/pokemon_game/proc_data/proc_user.php?action=img&id=$id";
    
    if(!is_null($w)){
        $url .= "&w=$w";
    }
    
    if(!is_null($h)){
        $url .= "&h=$h";
    }
    
    return $url;
}
?>