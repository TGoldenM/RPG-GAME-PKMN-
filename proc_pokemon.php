<?php
require_once '../db_connection.php';
require_once '../data_access/pokemon.php';
require_once '../data_access/pokemon_img.php';
require_once '../data_access/trainer_pokemon.php';
require_once '../util/json_functions.php';
require_once '../util/upload_functions.php';
require_once '../util/req_functions.php';
require_once '../ui_support/img.php';

$req_data = filter($_REQUEST);
$imgDir = ROOTPATH."images";
$pkmnDir = $imgDir."/pokemon";

if (isset($req_data['id'])) {
    $id = $req_data['id'];

    switch ($req_data['action']) {
        case 'edit':
            if(isset($req_data['btnRet'])){
                header('Location: ../pokebox.php');
                exit();
            }
            
            $obj = getPokemon($id);
            updateObjFromReq($obj, $req_data);
            updatePokemon($obj);

            if (isset($req_data['types'])){
                assignPokemonTypes($obj, $req_data['types'], true);
            }
            
            if (isset($req_data['groups'])){
                assignPokemonGroups($obj, $req_data['groups'], true);
            }
            
            if($_FILES['img']['error'] != UPLOAD_ERR_NO_FILE){
                $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
                $fname = pathinfo($_FILES['img']['name'], PATHINFO_FILENAME);
                        
                $files = glob("$pkmnDir/$id/*_0.*");
                if($files != false && !empty($files)){
                    $avFile = $files[0];
                    unlink($avFile);
                }
                
                $imgFileName = "$pkmnDir/$id/$fname"."_0.$ext";
                $r = storeImage($_FILES['img'], $imgFileName, array(96,96));
                
                switch($r){
                case 1://Success
                    $pkmnImg = getPokemonImgByType($obj->id);
                    $image = str_replace(' ', '_', $fname."_0.".$ext);
                    
                    if($pkmnImg){
                        $pkmnImg->image = $image;
                        updatePokemonImg($pkmnImg);
                    } else {
                        insertPokemonImg((object)array("id_pokemon"=>$obj->id, "type"=>0
                            , "image"=>$image));
                    }
                    
                    break;
                
                case 2:
                    header("Location: ../pokebox.php?t=0&n=pkmn_img_bad_format&id=$id");
                    $_SESSION['pkmnObj'] = serialize($obj);
                    exit();
                
                case 3:
                    header("Location: ../pokebox.php?t=0&n=pkmn_msg_error&id=$id");
                    $_SESSION['pkmnObj'] = serialize($obj);
                    exit();
                }
            }
            
            header("Location: ../pokebox.php?t=1&n=pkmn_msg_success");
            break;

        case 'gen':
            header('Content-Type: application/json');
            
            $trn = getTrainer($req_data['id_trainer']);
            $tpkmn = getTrainerPkmnLeveled($req_data['lvl'],
                    getPokemon($id, false, true), $trn);
            
            echo json_resp_test($tpkmn, "Unable to generate pokemon with level");
            break;
        
        case 'disable':
            if (isset($req_data['btnYes'])) {
                $obj = getPokemon($id);
                $obj->disabled = $req_data['disabled'];
                updatePokemon($obj);
            }
            break;
            
        case 'read':
            header('Content-Type: application/json');
            $obj = getPokemonAndInit($id, PKMN_DEEP_LOAD);
            
            echo json_resp_test($obj, "Pokemon not found: $id");
            exit();

        case 'img':
            $type = isset($req_data['t']) ? $req_data['t'] : 0;
            $img = getPokemonImgByType($id, $type);
            
            if(!is_null($img)) {
                $image = $img->image;
                $files = glob("$pkmnDir/$id/$image");
            } else {
                $files = false;
            }
            
            if($files != false && !empty($files)){
                $imgFile = $files[0];
            } else {
                $imgFile = "$imgDir/image_missing.png";
            }
            
            $finfo = new finfo;
            $mime = $finfo->file($imgFile, FILEINFO_MIME);
            header('Content-Type: '.$mime);

            if(isset($req_data['w']) && isset($req_data['h'])){
                outputResizedImg($imgFile, $req_data['w'], $req_data['h']);
            } else {
                $file = @fopen($imgFile, "rb");
                if($file){
                    fpassthru($file);
                }
            }
            exit();
    }
} else {
    if(isset($req_data['action'])){
        switch($req_data['action']){
            case 'read_r':
                header('Content-Type: application/json');
                $obj = getRandPokemon();

                echo json_resp_test($obj, "Pokemon not found");
                exit();
                
            case 'acpl':
                header('Content-Type: application/json');
                $pkmns = getPokemonsList('%'.$req_data['query'].'%');
                
                $sugg = array();
                foreach ($pkmns as $pkmn) {
                    $sugg[] = array("value"=>$pkmn->name, "data"=>$pkmn);
                }
                
                $resp = array(
                    "suggestions"=>$sugg
                );
                
                echo json_encode($resp);
                exit();
            
            case 'load':
                if(isset($req_data['btnRet'])){
                    header('Location: ../pokebox.php');
                    exit();
                }

                if($_FILES['dataFile']['error'] == UPLOAD_ERR_NO_FILE){
                    header("Location: ../pokebox.php?t=0&n=pkmn_nodatafile");
                } else if($_FILES['zipFile']['error'] == UPLOAD_ERR_NO_FILE){
                    header("Location: ../pokebox.php?t=0&n=pkmn_nozipfile");
                } else {
                    $res = storePkmnData($_FILES['dataFile'], $_FILES['zipFile']);
                    $msg = 'pkmn_msg_success';
                    $typeMsg = 1;
                    $detail = '';
                    
                    switch($res['code']){
                    case 2://Invalid headers
                        $msg = 'pkmn_invhead_pokemon';
                        $typeMsg = 0;
                        
                        $detail = $res['data'];
                        $detail = base64_encode($detail);
                        break;
                    
                    case 3://Invalid content at specific row
                        $msg = 'pkmn_invcont_pokemon';
                        $typeMsg = 0;
                        
                        $detail = sprintf("Invalid row %d, field: %s, value: %s"
                                , $res['data']['row'], $res['data']['field']
                                , $res['data']['value']);
                        
                        $detail = base64_encode($detail);
                        break;
                    }
                    
                    header("Location: ../pokebox.php?t=$typeMsg&n=$msg&c=$detail");
                }
                
                exit();
        }
    }
    
    if(isset($req_data['btnRet'])){
        header('Location: ../pokebox.php');
        exit();
    }
    
    $obj = createObjFromReq($req_data, array(
        "initial", "disabled", "genderless", "name", "hp", "attack", "defense",
        "speed", "spec_attack", "spec_defense", "base_exp", "evhp", "evattack",
        "evdefense", "evspeed", "evspec_attack", "evspec_defense",
    ));

    $id = insertPokemon($obj);
    
    if(isset($req_data['types'])){
        assignPokemonTypes($obj, $req_data['types'], true);
    }
    
    if(isset($req_data['groups'])){
        assignPokemonGroups($obj, $req_data['groups'], true);
    }
    
    if($_FILES['img']['error'] != UPLOAD_ERR_NO_FILE){
        $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        $fname = pathinfo($_FILES['img']['name'], PATHINFO_FILENAME);

        $files = glob("$pkmnDir/$id/*_0.*");
        if($files != false && !empty($files)){
            $imgFile = $files[0];
            unlink($imgFile);
        }

        $image = str_replace(' ', '_', $fname."_0.".$ext);
        $imgPath = "$pkmnDir/$id/$image";
        $r = storeImage($_FILES['img'], $imgPath, array(96,96));

        switch($r){
        case 1://Success
            insertPokemonImg((object)array("id_pokemon"=>$obj->id, "type"=>0
                , "image"=>$image));
            
            break;

        case 2:
            header("Location: ../pokebox.php?t=0&n=pkmn_img_bad_format&id=$id");
            exit();

        case 3:
            header("Location: ../pokebox.php?t=0&n=pkmn_msg_error&id=$id");
            exit();
        }
    }
    
    header("Location: ../pokebox.php?t=1&n=pkmn_msg_success");
}
?>
