<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './data_access/trainer.php';
require_once './data_access/badge.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$user = getSystemUser($req_data['id'], true);
$tr = getTrainerByUserId($req_data['id'], PKMN_SIMPLE_LOAD);
$fc = $tr->faction;
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
                    <div class="header">User - <?php echo $user->username ?></div>
                    <?php
                    if (isset($req_data['n']) && isset($req_data['t'])) {
                        $t = (int) $req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>

                    <div style="padding: 5px;">
                        <div style="color:white;">
                            <div style="float:left; width:50%; margin:0 10px 10px 0;">
                                <div class='p_container'>
                                    <div><div class='p_title'>Basic data</div>
                                        <div style='clear:left;'></div>
                                    </div>
                                    <div class="p_content">
                                        <div>
                                            <div style="float:left;">
                                                <div style="text-align:center;">Avatar</div>
                                                <div>
                                                    <img src='proc_data/proc_user.php?action=img&id=<?php echo $user->id?>' />
                                                </div>
                                            </div>
                                            <table style="float:left;">
                                                <?php
                                                    if($tr->id != getCurrentTrainer()->id){
                                                        echo "<tr><td colspan='2'><a href='game.php?id_tr=$tr->id'>Fight against this trainer</a></td></tr>";
                                                    }
                                                ?>
                                                <tr><td>Faction:</td><td><?php echo!is_null($fc) ? $fc->name : 'None' ?></td></tr>
                                                <tr><td>Victories:</td><td><?php echo $tr->victories ?></td></tr>
                                                <tr><td>Defeats:</td><td><?php echo $tr->defeats ?></td></tr>
                                            </table>
                                            <div style="clear:left;"></div>
                                        </div>
                                        <div>
                                            <img src='proc_data/proc_user.php?action=sgnt&id=<?php echo $user->id?>' />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="float:left; width:48%;">
                                <div class='p_container'>
                                    <div><div class='p_title'>Badges</div>
                                        <div style='clear:both;'></div>
                                    </div>
                                    <div class="p_content">
                                        <?php
                                            $badges = getBadges($tr->id);
                                            if(empty($badges)):
                                        ?>
                                        No badges
                                        <?php else: ?>
                                        <ul class="pkmn_badge_list">
                                            <?php
                                            foreach ($badges as $b):
                                            ?>

                                            <li><?php echo "<img alt='$b->name' title='$b->name' src='proc_data/proc_badge.php?action=img&id=$b->id&w=22&h=22' />"?></li>

                                            <?php endforeach; ?>
                                        </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div style="clear:left;"></div>
                        </div>
                        
                        <div class='p_container' style="color:white;">
                            <div><div class='p_title'>Pokemons</div>
                                <div style='clear:left;'></div>
                            </div>
                            <div class="p_content" style="min-height:170px;">
                                <ul class="pkmn_det_list">
                                    <?php
                                    $curPkmns = getTrainerPokemonsList($tr->id, 1, PKMN_DEEP_LOAD);
                                    foreach ($curPkmns as $tpkmn):
                                    ?>

                                    <li>
                                        <table>
                                            <?php echo "<tr><td>".getPkmnGenderGraphic($tpkmn)."&nbsp;</td><td>".$tpkmn->pokemon->name."</td></tr>" ?>
                                            <tr><td colspan="2"><img src='<?php echo $tpkmn->pokemon->imgs[0]->image_url ?>' /></td></tr>
                                            <?php
                                            echo "<tr><td>Level:</td><td>$tpkmn->level</td></tr>";
                                            echo "<tr><td>Exp:</td><td>$tpkmn->exp</td></tr>";
                                            ?>
                                        </table>
                                    </li>

                                    <?php endforeach; ?>
                                </ul>
                                <div style="clear:left;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer -->
        <?php include './footer.php' ?>
    </body>
</html>