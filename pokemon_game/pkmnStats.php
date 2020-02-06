<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/trainer.php';
require_once './data_access/trainer_pokemon.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);

//If some validation failed, read from session the input data
$pkmn = getPokemon($req_data['id'], true, true, true);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Pokemon CosmosRPG - Members Area</title>
        <link href="css/style.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet'>
        <link href="images/layout/favicon.png" rel="shortcut icon">
        <script type="text/javascript" src="jquery/jquery-1.11.2.min.js"></script>
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
                    
                    $groups = $pkmn->groups;
                    $types = $pkmn->types;                    
                    ?>

                    <div style="padding: 5px;">
                        <form id="formData" action="proc_data/proc_pokemon.php"
                              method="post" enctype="multipart/form-data">
                            <table class="pkmn_form">
                                <tr><td class='label'>Image</td><td><img src='proc_data/proc_pokemon.php?action=img&id=<?php echo $pkmn->id?>' /></td></tr>
                                <tr><td class='label'>Name</td><td class="value"><?php echo $pkmn->name ?></td></tr>
                                <tr><td class='label'>Base experience</td><td class="value"><?php echo $pkmn->base_exp ?></td></tr>
                                <tr><td></td><td>
                                    <table style="float:left;">
                                        <tr><td colspan='2'>Base Values</td></tr>
                                        <tr><td>HP</td><td class="value"><?php echo $pkmn->hp ?></td></tr>
                                        <tr><td>Attack</td><td class="value"><?php echo $pkmn->attack ?></td></tr>
                                        <tr><td>Defense</td><td class="value"><?php echo $pkmn->defense ?></td></tr>
                                        <tr><td>Speed</td><td class="value"><?php echo $pkmn->speed ?></td></tr>
                                        <tr><td>Special Attack</td><td class="value"><?php echo $pkmn->spec_attack ?></td></tr>
                                        <tr><td>Special Defense</td><td class="value"><?php echo $pkmn->spec_defense ?></td></tr>
                                    </table>
                                    <table style="float:left;">
                                        <tr><td colspan='2'>Effort Values</td></tr>
                                        <tr><td>HP</td><td class="value"><?php echo $pkmn->evhp ?></td></tr>
                                        <tr><td>Attack</td><td class="value"><?php echo $pkmn->evattack ?></td></tr>
                                        <tr><td>Defense</td><td class="value"><?php echo $pkmn->evdefense ?></td></tr>
                                        <tr><td>Speed</td><td class="value"><?php echo $pkmn->evspeed ?></td></tr>
                                        <tr><td>Special Attack</td><td class="value"><?php echo $pkmn->evspec_attack ?></td></tr>
                                        <tr><td>Special Defense</td><td class="value"><?php echo $pkmn->evspec_defense ?></td></tr>
                                    </table>
                                    <div style="clear: both;"></div>
                                    </td>
                                </tr>
                                <tr><td class='label'>Types</td>
                                    <td class="value">
                                        <?php
                                        foreach($types as $type){
                                            echo "$type->name&nbsp;";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr><td class='label'>Egg Groups</td>
                                    <td class="value">
                                        <?php
                                        foreach($groups as $group){
                                            echo "$group->name&nbsp;";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                
                                <tr><td class="label"></td><td>
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