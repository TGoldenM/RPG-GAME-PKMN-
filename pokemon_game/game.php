<?php
require_once 'db_connection.php';
require_once './data_access/user.php';
require_once './data_access/pokemon.php';
require_once './data_access/pokemon_move.php';
require_once './data_access/trainer.php';
require_once './data_access/trainer_pokemon.php';
require_once './data_access/trainer_item.php';
require_once './data_access/item.php';
require_once './ui_support/alertbox.php';
require_once './ui_support/dropdown.php';
require_once 'checkLogin.php';

$req_data = filter($_REQUEST);

$curTrainer = getCurrentTrainer();
$curUser = getSystemUser($curTrainer->id_user);

if (!isset($req_data['rp'])) {
    $opTrainer = getTrainer($req_data['id_tr'], PKMN_SIMPLE_LOAD);
} else {
    $opTrainer = getTrainerByName('Forest', 'Wild Nature', PKMN_SIMPLE_LOAD);
    $req_data['id_tr'] = $opTrainer->id;
}

if (isset($opTrainer->user)) {
    $opUser = $opTrainer->user;
}

$curPkmns = getTrainerPokemonsList($curTrainer->id, 1, PKMN_DEEP_LOAD);
$curSelPkmn = getFirstAvailablePkmn($curPkmns);
if (is_null($curSelPkmn)) {
    $q = array();
    $q['n'] = 'pkmn_no_pokemon_available';
    $q['t'] = '0';
    $q['id_tr'] = $curTrainer->id;
    header("Location: trnPkmn.php?".http_build_query($q));
    exit();
}

if (empty($curSelPkmn->moves)) {
    $req_data['n'] = 'pkmn_no_moves';
    $req_data['t'] = '0';
    $req_data['content'] = $curSelPkmn->pokemon->name . " ($curUser->username)";
} else {
    $curMoves = getPkmnGroupedMoves($curSelPkmn->moves);
    $curMvIdx = array_rand($curSelPkmn->moves);
    $curMove = $curSelPkmn->moves[$curMvIdx];
    
    $assigItems = getTrainerItems($curTrainer->id, PKMN_SIMPLE_LOAD);
    $curItems = getGroupedTrainerItems($assigItems);
}

if (!isset($req_data['rp'])) {
    $opPkmns = getTrainerPokemonsList($opTrainer->id, 1, PKMN_DEEP_LOAD, "order_index");
    if(empty($opPkmns)){
        echo "No equipped pokemons for trainer " . $opTrainer->id;
        exit();
    }
    
    $opSelPkmn = getFirstAvailablePkmn($opPkmns);
    if (is_null($opSelPkmn)) {
        $opSelPkmn = $opPkmns[array_rand($opPkmns)];
    }
} else {
    $opSelPkmn = getTrainerPkmnLeveled($curSelPkmn->level
            , getPokemon($req_data['rp'], true, true, true)
            , $opTrainer);
    
    $opPkmns = array($opSelPkmn);
}
$opMoves = $opSelPkmn->moves;

if (empty($opMoves)) {
    $req_data['n'] = 'pkmn_no_moves';
    $req_data['t'] = '0';
    $req_data['content'] = $opSelPkmn->pokemon->name . " ($opTrainer->name)";
} else {
    $opMvIdx = array_rand($opMoves);
    $opMove = $opMoves[$opMvIdx];
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Pokemon CosmosRPG - Members Area</title>
        <link href="css/style.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet'>
        <link href="images/layout/favicon.png" rel="shortcut icon">

        <script type="text/javascript" src="jquery/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="javascript/include.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                curSelPkmn = jQuery.parseJSON('<?php echo json_encode($curSelPkmn) ?>');
                opSelPkmn = jQuery.parseJSON('<?php echo json_encode($opSelPkmn) ?>');
                curItems = jQuery.parseJSON('<?php echo json_encode($assigItems) ?>');
                $("#b_move_op").val('<?php echo isset($opMove->id) ? $opMove->id : 0 ?>');
            });
        </script>

        <script type="text/javascript" src="javascript/pokemon.js"></script>
        <script type="text/javascript" src="javascript/game.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">Battle</div>
                    <?php
                    if (isset($req_data['n']) && isset($req_data['t'])) {
                        $t = (int) $req_data['t'];
                        $n = $req_data['n'];
                        $c = $req_data['content'];
                        createAlertBox($t, $n, $c);
                    }
                    ?>

                    <div class="b_battle_stats" style="height: 252px;">
                        <input type="hidden" id="id_tr" name="id_tr"
                               value="<?php echo $req_data['id_tr'] ?>" />

                        <div class="b_trainer_panel_left">
                            <div class="b_title">
                                <span><?php echo $curUser->username ?></span>
                            </div>
                            <div id="usrPkmn">
                                <div class="b_panel_hp">
                                    <div class="b_pkmn_icon">
                                        <img src="<?php echo $curSelPkmn->pokemon->imgs[0]->image_url ?>"
                                             alt="<?php echo $curSelPkmn->pokemon->name ?>"/>
                                    </div>
                                    <div class="b_pkmn_hp">
                                        <span>
                                            <?php
                                            $pctg = $curSelPkmn->hp == 0 ? 0 :
                                                    round($curSelPkmn->cur_hp / $curSelPkmn->hp * 100);

                                            echo "$pctg%";
                                            ?>
                                        </span>
                                    </div>
                                    <div style="clear: both;"></div>
                                </div>
                                <div class="b_pkmn_lvl">
                                    <?php echo "Level: $curSelPkmn->level" ?>
                                </div>
                            </div>
                            <div class="b_panel_ctrl">
                                <select id="b_move_usr" name="b_move_usr">
                                    <?php genSelectOptionsArr($curMoves, $curMove->id, false) ?>
                                </select>
                                
                                <input id="b_attack" name="b_attack"
                                       value="Attack/Move" type="button"
                                       class="b_attack"/><br />
                                
                                <?php
                                if (!empty($curItems)) {
                                    echo "<div style='margin: 2px 0;'>Item:</div>";
                                    echo "<select id='b_item_usr' name='b_item_usr'>";
                                    echo "<option value='0'>--No selection--</option>";
                                    echo genSelectOptionsArr($curItems, null, false);
                                    echo "</select>";
                                    
                                    echo "&nbsp;<a id='b_item_use' class='b_item_use_h' href='#'>Use</a>";
                                }
                                ?>
                            </div>

                            <div id="usrLog" class="b_panel_log"></div>
                        </div>
                        <div class="b_trainer_panel_right">
                            <div class="b_title">
                                <span><?php echo isset($opUser) ? $opUser->username : (!is_null($opTrainer->name) ? $opTrainer->name : '') ?></span>
                            </div>
                            <div id="opPkmn">
                                <div class="b_panel_hp">
                                    <div class="b_pkmn_icon">
                                        <img src="<?php echo $opSelPkmn->pokemon->imgs[0]->image_url ?>"
                                             alt="<?php echo $opSelPkmn->pokemon->name ?>"/>
                                    </div>
                                    <div class="b_pkmn_hp">
                                        <span>
                                            <?php
                                            $pctg = $opSelPkmn->hp == 0 ? 0 :
                                                    round($opSelPkmn->cur_hp / $opSelPkmn->hp * 100);

                                            echo "$pctg%";
                                            ?>
                                        </span>
                                    </div>
                                    <div style="clear: both;"></div>
                                </div>
                                <div class="b_pkmn_lvl">
                                    <?php echo "Level: $opSelPkmn->level" ?>
                                </div>
                            </div>

                            <div class="b_panel_ctrl">
                                <input type="hidden" id="b_move_op" name="b_move_op" />
                            </div>

                            <div id="opLog" class="b_panel_log">

                            </div>
                        </div>
                        <div style="clear: both;"></div>
                    </div>

                    <div class="b_battle_stats">
                        <div class="b_trainer_panel_left">
                            <div class="b_title">
                                <span>Pokemons</span>
                            </div>
                            <div class="b_panel_content">
                                <table id="curEqpPkmn" class="b_pkmn_choices">
                                    <tr><td>Level</td><td>Name</td><td>HP%</td></tr>
                                    <?php
                                    foreach ($curPkmns as $tpkmn) {
                                        $pkmn = $tpkmn->pokemon;

                                        if ($curSelPkmn->id != $tpkmn->id) {
                                            $chosenStyle = "";
                                        } else {
                                            $chosenStyle = " class='b_pkmn_chosen'";
                                        }

                                        $pctg = $tpkmn->hp == 0 ? 0 :
                                                round($tpkmn->cur_hp / $tpkmn->hp * 100);

                                        echo "<tr$chosenStyle id='pkmn_$tpkmn->id'>
                                            <td class='b_pkmn_list_lvl'>$tpkmn->level</td><td>";

                                        if ($pctg > 0) {
                                            echo "<a class='b_pkmn_choice' href='#'>$pkmn->name</a>";
                                        } else {
                                            echo "<span>$pkmn->name</span>";
                                        }

                                        echo "</td><td><span class='b_pkmn_pctg'>$pctg%</span></td></tr>";
                                    }
                                    ?>
                                </table>
                            </div>
                            <div class="b_battle_restart">
                                <input id="restart" type="button" value="Restart"/>
                            </div>
                        </div>
                        <div class="b_trainer_panel_right">
                            <div class="b_title">
                                <span>Pokemons</span>
                            </div>
                            <div class="b_panel_content">
                                <table id="opEqpPkmn" class="b_pkmn_choices">
                                    <tr><td>Level</td><td>Name</td><td>HP%</td></tr>
                                    <?php
                                    foreach ($opPkmns as $tpkmn) {
                                        $pkmn = $tpkmn->pokemon;

                                        if ($opSelPkmn->id != $pkmn->id) {
                                            $chosenStyle = "";
                                        } else {
                                            $chosenStyle = " class='b_pkmn_chosen'";
                                        }

                                        $pctg = $tpkmn->hp == 0 ? 0 :
                                                round($tpkmn->cur_hp / $tpkmn->hp * 100);

                                        echo "<tr$chosenStyle id='pkmn_$tpkmn->id'>
                                            <td>$tpkmn->level</td>
                                            <td>$pkmn->name</td>
                                            <td><span class='b_pkmn_pctg'>$pctg%</span></td></tr>";
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>

                        <div style="clear: both;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer -->
        <?php include './footer.php' ?>
    </body>
</html>