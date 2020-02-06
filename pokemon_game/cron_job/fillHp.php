<?php
require_once 'db_connection.php';

$db->prepare("update pkmn_trainer_pokemon set cur_hp = cur_hp + hp/10
             where cur_hp < hp")->execute();

$db->prepare("update pkmn_trainer_pokemon set cur_hp = hp
             where cur_hp > hp")->execute();
?>