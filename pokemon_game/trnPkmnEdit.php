<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/system_role.php';
require_once './data_access/trainer.php';
require_once './data_access/trainer_pokemon.php';
require_once './ui_support/dropdown.php';
require_once './ui_support/alertbox.php';
require_once './ui_support/listbox.php';

$req_data = filter($_REQUEST);
$is_new = !isset($req_data['id']);

$selMoves = array();

//If some validation failed, read from session the input data
if (isset($_SESSION['tpkmnObj'])) {
    $tpkmn = unserialize($_SESSION['tpkmnObj']);
    if (isset($tpkmn->moves)) {
        foreach ($tpkmn->moves as $m) {
            $selMoves[] = $m->id;
        }
    }
    
    unset($_SESSION['tpkmnObj']);
} else {
    if ($is_new) {
        $tpkmn = (object) array(
            "id_trainer" => null,
            "id_pokemon" => null,
            "order_index"=>0,
            "pokemon" => (object)array("id"=>null, "name"=>"", "genderless"=>1),
            "id_item" => null,
            "level" => 1,
            "exp" => null,
            "equipped" => null,
            "tradeable" => null,
            "sellable" => null,
            "gender" => null,
            "hp" => null,
            "cur_hp" => null,
            "attack" => null,
            "defense" => null,
            "speed" => null,
            "spec_attack" => null,
            "spec_defense" => null,
            "gold"=>null,
            "silver"=>null
        );
    } else {
        $tpkmn = getTrainerPokemon($req_data['id'], PKMN_DEEP_LOAD);
        foreach($tpkmn->moves as $m){
            $selMoves[] = $m->id;
        }
    }
}

$simple_opts = array('No', 'Yes');
$gender_opts = array('M'=>'Male', 'F'=>'Female');
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
        
        <?php if(curUserIsAdmin()): ?>
        <link href="css/autocpl.css" rel="stylesheet">
        <script type="text/javascript" src="jquery/jquery.autocomplete.min.js"></script>
        <?php endif;?>
        
        <script type="text/javascript" src="javascript/include.js"></script>
        <script type="text/javascript" src="javascript/pokemon.js"></script>
        <script type="text/javascript" src="javascript/listbox/listbox.js"></script>
        <script type="text/javascript" src="javascript/trnPkmnEdit.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">Trainer pokemon</div>
                    <?php
                    if (isset($req_data['n']) && isset($req_data['t'])) {
                        $t = (int) $req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>

                    <div style="padding: 5px;">
                        <form id="formData" action="proc_data/proc_trainer_pokemon.php"
                              method="post">
                            <?php
                            if (!$is_new) {
                                echo "<input type='hidden' id='id' name='id' value='$tpkmn->id' />";
                                echo "<input type='hidden' id='action' name='action' value='edit' />";
                            }
                            
                            if ($tpkmn->pokemon->genderless == 1) {
                                echo "<input type='hidden' id='gender' name='gender' value='?' />";
                            }
                                                        
                            if(isset($req_data['id_tr'])){
                                $id_tr = $req_data['id_tr'];
                            } else {
                                $id_tr = $tpkmn->trainer->id;
                            }
                            
                            echo "<input type='hidden' id='id_trainer' name='id_trainer'
                                   value='$id_tr' />";
                            ?>
                            
                            <table class="pkmn_form">
                                <?php if(!curUserIsAdmin()): ?>
                                <tr><td class='label'></td><td><img src='<?php echo $tpkmn->pokemon->imgs[0]->image_url ?>' /></td></tr>
                                <tr><td class='label'>Name</td><td class="value"><?php echo $tpkmn->pokemon->name ?></td></tr>
                                <tr><td class='label'>Level</td><td class="value"><?php echo $tpkmn->level ?></td></tr>
                                <tr><td class='label'>Experience</td><td class="value" id='exp'><?php echo $tpkmn->exp ?></td></tr>
                                <tr><td class='label'>Gender</td><td class="value"><?php echo $tpkmn->gender == 1 ? 'Male' : 'Female' ?></td></tr>
                                <tr><td class='label'>HP</td><td class="value"><?php echo $tpkmn->hp == 0 ? 0 : ('' . ($tpkmn->cur_hp . '/' . $tpkmn->hp) . ' (' . round($tpkmn->cur_hp / $tpkmn->hp * 100) . '%)') ?></td></tr>
                                <tr><td class='label'>Attack</td><td class="value"><?php echo $tpkmn->attack ?></td></tr>
                                <tr><td class='label'>Defense</td><td class="value"><?php echo $tpkmn->defense ?></td></tr>
                                <tr><td class='label'>Speed</td><td class="value"><?php echo $tpkmn->speed ?></td></tr>
                                <tr><td class='label'>Special Attack</td><td class="value"><?php echo $tpkmn->spec_attack ?></td></tr>
                                <tr><td class='label'>Special Defense</td><td class="value"><?php echo $tpkmn->spec_defense ?></td></tr>
                                
                                <?php else: ?>
                                
                                <tr><td class='label'>Pokemon</td>
                                    <td class="value">
                                        <input type='text' id='name' name='name'
                                               placeholder='Type a name to search'
                                               value='<?php echo $tpkmn->pokemon->name ?>' />
                                        <input type='hidden' id='id_pokemon' name='id_pokemon'
                                               value='<?php echo $tpkmn->pokemon->id ?>' />
                                    </td>
                                </tr>
                                
                                <?php if(!isset($tpkmn->id_user)): ?>
                                <tr><td class='label'>Order index</td><td class="value"><input class='number' type='text' id='order_index' name='order_index' value='<?php echo $tpkmn->order_index ?>' /></td></tr>
                                <?php endif; ?>
                                
                                <tr><td class='label'>Level</td><td class="value">
                                        <input class='number' type='text' id='lvl' name='lvl' value='<?php echo $tpkmn->level ?>' />
                                        &nbsp;<input type="button" id="norm" value="Normalize" />
                                        &nbsp;(Uses IVs between 0 and 15)
                                    </td>
                                </tr>
                                <tr><td class='label'>Experience</td>
                                    <td class="value" id='exp'>
                                        <?php echo $tpkmn->exp ?>
                                    </td>
                                </tr>
                                
                                <?php if ($tpkmn->pokemon->genderless != 1) :?>
                                <tr><td class='label'>Gender</td><td class="value">
                                    <?php
                                    echo "<select id='gender' name='gender' value='$tpkmn->gender'>";
                                    genSelectOptionsArr($gender_opts, $tpkmn->gender);
                                    echo "</select>"
                                    ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                
                                <tr><td class='label'>Total HP</td><td class="value"><input class='number_large' type='text' id='hp' name='hp' value='<?php echo $tpkmn->hp ?>' /></td></tr>
                                <tr><td class='label'>Current HP</td><td class="value"><input class='number_large' type='text' id='cur_hp' name='cur_hp' value='<?php echo $tpkmn->cur_hp ?>' /></td></tr>
                                <tr><td class='label'>Attack</td><td class="value"><input class='number_large' type='text' id='attack' name='attack' value='<?php echo $tpkmn->attack ?>' /></td></tr>
                                <tr><td class='label'>Defense</td><td class="value"><input class='number_large' type='text' id='defense' name='defense' value='<?php echo $tpkmn->defense ?>' /></td></tr>
                                <tr><td class='label'>Speed</td><td class="value"><input class='number_large' type='text' id='speed' name='speed' value='<?php echo $tpkmn->speed ?>' /></td></tr>
                                <tr><td class='label'>Special Attack</td><td class="value"><input class='number_large' type='text' id='spec_attack' name='spec_attack' value='<?php echo $tpkmn->spec_attack ?>' /></td></tr>
                                <tr><td class='label'>Special Defense</td><td class="value"><input class='number_large' type='text' id='spec_defense' name='spec_defense' value='<?php echo $tpkmn->spec_defense ?>' /></td></tr>
                                <?php endif; ?>
                                
                                <tr><td class='label'>Is in the team?</td>
                                    <td class="value">
                                        <?php
                                        echo "<select id='equipped' name='equipped' value='$tpkmn->equipped'>";
                                        genSelectOptionsArr($simple_opts, $tpkmn->equipped);
                                        echo "</select>";
                                        ?>
                                    </td>
                                </tr>
                                <tr><td class='label'>This pokemon is tradeable?</td>
                                    <td class="value">
                                        <?php
                                        echo "<select id='tradeable' name='tradeable' value='$tpkmn->tradeable'>";
                                        genSelectOptionsArr($simple_opts, $tpkmn->tradeable);
                                        echo "</select>";
                                        ?>
                                    </td>
                                </tr>
                                <tr><td class='label'>This pokemon is sellable?</td>
                                    <td class="value">
                                        <?php
                                        echo "<select id='sellable' name='sellable' value='$tpkmn->sellable'>";
                                        genSelectOptionsArr($simple_opts, $tpkmn->sellable);
                                        echo "</select>";
                                        echo " | Gold: ";
                                        echo "<input class='number' type='text'
                                            id='gold' name='gold' value='$tpkmn->gold'/>";
                                        echo " Silver: ";
                                        echo "<input class='number' type='text'
                                            id='silver' name='silver' value='$tpkmn->silver'/>";
                                        ?>
                                    </td>
                                </tr>
                                <tr><td class='label'>Moves</td>
                                    <td class='value'>
                                        <?php createDbListBox('moves', 'all_moves'
                                            , 'pkmn_pokemon_move', 'id', 'name'
                                            , 'btnOk', $selMoves)
                                        ?>
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