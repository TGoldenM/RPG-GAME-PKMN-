<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/trainer.php';
require_once './data_access/trainer_pokemon.php';
require_once './ui_support/dropdown.php';
require_once './ui_support/alertbox.php';
require_once './ui_support/checkbox.php';

$req_data = filter($_REQUEST);
$is_new = !isset($req_data['id']);
$types_sel = array();
$groups_sel = array();

//If some validation failed, read from session the input data
if (isset($_SESSION['pkmnObj'])) {
    $pkmn = unserialize($_SESSION['pkmnObj']);
    unset($_SESSION['pkmnObj']);
} else {
    if ($is_new) {
        $pkmn = (object) array(
                    'initial' => null,
                    'disabled' => null,
                    'genderless' => null,
                    'name' => null,
                    'hp' => null,
                    'attack' => null,
                    'defense' => null,
                    'speed' => null,
                    'spec_attack' => null,
                    'spec_defense' => null,
                    'base_exp' => null,
                    'evattack' => 0,
                    'evdefense' => 0,
                    'evspeed' => 0,
                    'evspec_attack' => 0,
                    'evspec_defense' => 0
        );
    } else {
        $pkmn = getPokemon($req_data['id'], true, true, true);
        
        foreach($pkmn->types as $t){
            $types_sel[] = $t->id;
        }
        
        foreach($pkmn->groups as $g){
            $groups_sel[] = $g->id;
        }
    }
}

$simple_opts = array('No', 'Yes');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Pokemon CosmosRPG - Members Area</title>
        <link href="css/style.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet'>
        <link href="images/layout/favicon.png" rel="shortcut icon">
        <script type="text/javascript" src="jquery/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="jquery/jquery.validate.min.js"></script>
        
        <script type="text/javascript" src="javascript/include.js"></script>
        <script type="text/javascript" src="javascript/pkmnEdit.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">Pokemon</div>
                    <?php
                    if (isset($req_data['n']) && isset($req_data['t'])) {
                        $t = (int) $req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>

                    <div style="padding: 5px;">
                        <form id="formData" action="proc_data/proc_pokemon.php"
                              method="post" enctype="multipart/form-data">
                            <?php
                            if (!$is_new) {
                                echo "<input type='hidden' id='id' name='id' value='$pkmn->id' />";
                                echo "<input type='hidden' id='action' name='action' value='edit' />";
                            }
                            ?>

                            <table class="pkmn_form">
                                <tr><td class='label'>Is allowed to be initial?</td>
                                    <td>
                                        <select id='initial' name='initial'
                                            value='<?php echo $pkmn->initial ?>'>
                                            <?php genSelectOptionsArr($simple_opts, $pkmn->initial) ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr><td class='label'>Disabled?</td>
                                    <td>
                                        <select id='disabled' name='disabled'
                                            value='<?php echo $pkmn->disabled ?>'>
                                            <?php genSelectOptionsArr($simple_opts, $pkmn->disabled) ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr><td class='label'>Genderless?</td>
                                    <td>
                                        <select id='genderless' name='genderless'
                                            value='<?php echo $pkmn->genderless ?>'>
                                            <?php genSelectOptionsArr($simple_opts, $pkmn->genderless) ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr><td class='label'>Image</td><td><input type='file' id='img' name='img'/></td></tr>
                                
                                <?php if(!$is_new):?>
                                <tr><td class='label'>Current Image</td><td><img src='proc_data/proc_pokemon.php?action=img&id=<?php echo $pkmn->id?>' /></td></tr>
                                <?php endif;?>
                                
                                <tr><td class='label'>Name</td><td class="value"><input type='text' id='name' name='name' value='<?php echo $pkmn->name ?>' /></td></tr>
                                <tr><td class='label'>Base experience</td><td class="value"><input class='number' type='text' id='base_exp' name='base_exp' value='<?php echo $pkmn->base_exp ?>' /></td></tr>
                                
                                <tr><td></td><td>
                                    <table style="float:left;">
                                        <tr><td colspan='2'>Base Values</td></tr>
                                        <tr><td>HP</td><td class="value"><input class='number' type='text' id='hp' name='hp' value='<?php echo $pkmn->hp ?>' /></td></tr>
                                        <tr><td>Attack</td><td class="value"><input class='number' type='text' id='attack' name='attack' value='<?php echo $pkmn->attack ?>' /></td></tr>
                                        <tr><td>Defense</td><td class="value"><input class='number' type='text' id='defense' name='defense' value='<?php echo $pkmn->defense ?>' /></td></tr>
                                        <tr><td>Special Attack</td><td class="value"><input class='number' type='text' id='spec_attack' name='spec_attack' value='<?php echo $pkmn->spec_attack ?>' /></td></tr>
                                        <tr><td>Special Defense</td><td class="value"><input class='number' type='text' id='spec_defense' name='spec_defense' value='<?php echo $pkmn->spec_defense ?>' /></td></tr>
                                         <tr><td>Speed</td><td class="value"><input class='number' type='text' id='speed' name='speed' value='<?php echo $pkmn->speed ?>' /></td></tr>
                                    </table>
                                    <table style="float:left;">
                                        <tr><td colspan='2'>Effort Values</td></tr>
                                        <tr><td>HP</td><td class="value"><input class='number' type='text' id='evhp' name='evhp' value='<?php echo $pkmn->evhp ?>' /></td></tr>
                                        <tr><td>Attack</td><td class="value"><input class='number' type='text' id='evattack' name='evattack' value='<?php echo $pkmn->evattack ?>' /></td></tr>
                                        <tr><td>Defense</td><td class="value"><input class='number' type='text' id='evdefense' name='evdefense' value='<?php echo $pkmn->evdefense ?>' /></td></tr>
                                        <tr><td>Speed</td><td class="value"><input class='number' type='text' id='evspeed' name='evspeed' value='<?php echo $pkmn->evspeed ?>' /></td></tr>
                                        <tr><td>Special Attack</td><td class="value"><input class='number' type='text' id='evspec_attack' name='evspec_attack' value='<?php echo $pkmn->evspec_attack ?>' /></td></tr>
                                        <tr><td>Special Defense</td><td class="value"><input class='number' type='text' id='evspec_defense' name='evspec_defense' value='<?php echo $pkmn->evspec_defense ?>' /></td></tr>
                                    </table>
                                    </td>
                                </tr>
                                
                                <tr><td class='label'>Types</td>
                                    <td class="value"><?php createDbCheckboxList('types', 'pkmn_pokemon_type', 'id', 'name', $types_sel, 'pkmn_types_list') ?>
                                    </td>
                                </tr>
                                <tr><td class='label'>Egg Groups</td>
                                    <td class="value"><?php createDbCheckboxList('groups', 'pkmn_pokemon_group', 'id', 'name', $groups_sel, 'pkmn_types_list') ?>
                                    </td>
                                </tr>
                                
                                <tr><td class="label"></td><td>
                                        <input class='btnSubmit' id='btnOk' name='btnOk'
                                               type='submit' value='OK' />
                                        <input class='btnSubmit cancel' id='btnRet' name='btnRet'
                                               type='submit' value='Return'/>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer -->
        <?php include './footer.php' ?>
    </body>
</html>