<?php

require_once '../db_connection.php';
require_once '../data_access/user.php';
require_once '../data_access/system_role.php';
require_once '../data_access/system_property.php';
require_once '../data_access/trainer.php';
require_once '../data_access/trainer_pokemon.php';
require_once '../util/sec_functions.php';
require_once '../util/upload_functions.php';
require_once '../util/req_functions.php';
require_once '../ui_support/checkbox.php';
require_once '../ui_support/img.php';

$imgDir = ROOTPATH . "images";
$req_data = filter($_REQUEST);

if (isset($req_data['btnRet'])) {
    header('Location: ../login.php');
    exit();
}

$obj = createObjFromReq($req_data, array(
    "logged",
    "banned",
    "username",
    "disabled",
    "password",
    "mail",
    "gender"
));

$obj->logged = 0;
$obj->banned = 0;
$obj->disabled = 0;
$obj->password = hash('sha512', $obj->password);

if ($req_data['username']) {
    if (checkIfUserExists($req_data['username']) == true) {
        $_SESSION['userObj'] = serialize($obj);
        header("Location: ../accountCreate?t=0&n=user_exists");
        exit();
    }
}

$rcptResp = $req_data['g-recaptcha-response'];
$gQuery = http_build_query(array(
    "secret"=>"6LdZIBsTAAAAADfvC3DgnI79pvlFWR70TZZJ0AdC",
    "response"=>$rcptResp,
    "remoteip"=>get_ip_address()
));

$rcptResp2 = file_get_contents("https://www.google.com/recaptcha/api/siteverify?$gQuery");
$data = json_decode($rcptResp2);
if(!$data->success){
    $_SESSION['userObj'] = serialize($obj);
    header("Location: ../accountCreate?t=0&n=captcha_failed");
    exit();
}

$id = insertSystemUser($obj);
$role = getRoleByName('Player');
assignRoles($obj, array($role->id), false);
unset($_SESSION['userObj']);

$imgUsrDir = $imgDir . "/user/$id";

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
        "name",
        "victories",
        "defeats"
    ));

$tr->id_user = $id;
$tr->silver = 0;
$tr->gold = 0;
$tr->faction_pts = 0;
$tr->id_type = 1;//Normal
$trId = insertTrainer($tr);

if (!is_null($req_data['initial'])) {
    $startLvl = (int)getPropertyByName("pkmn_start_level")->value;
    $pkmn = getPokemon($req_data['initial'], false, true);
    $tpkmn = getTrainerPkmnLeveled($startLvl, $pkmn, $tr, false);
    $tpkmn->id_trainer = $trId;
    $tpkmn->id_pokemon = $pkmn->id;
    insertTrainerPokemon($tpkmn);
}

$res = login($obj->username, $req_data['password']);
switch($res){
    case 1:
        // Login success
        $user = getSystemUserByName($obj->username);
        $user->logged = true;
        updateSystemUser($user);

        header('Location: ../main.php');
        break;
    
    default:
        header('Location: ../login.php');
        break;
}
?>
