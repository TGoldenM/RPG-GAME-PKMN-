<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './data_access/trainer.php';
require_once './data_access/trainer_pokemon.php';
require_once './data_access/news.php';
require_once './data_access/system_property.php';
require_once './ui_support/news.php';

$usr = getCurrentUser();
$usrTrn = getCurrentTrainer();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Pokemon Cosmos RPG - Members Area</title>
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
                    <?php
                    if (isset($req_data['n']) && isset($req_data['t'])) {
                        $t = (int) $req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    } else {
                        //Every 3 mins, show a new wild pokemon
                        if((int)date("i") % 3 == 1){
                            $pkmn = getRandPokemon();
                            $p = getPropertyByName('pkmn_appeared');
                            $msg = sprintf($p->value, $pkmn->name);

                            echo "<div class='alert-box alert-box-notice'>$msg. <a href='game.php?rp=$pkmn->id'>Catch</a></div>";
                        }
                    }
                    ?>
                    <div class="header fixed">Trainer Statistic</div>
                    <table class="trainer-stats">
                        <tr>
                            <td class="td1">
                                <table>
                                    <tr><td><div class="avatar-big">
                                                <img src='proc_data/proc_user.php?action=img&id=<?php echo $usr->id ?>' />
                                            </div>
                                        </td>
                                    </tr>		

                                    <tr>
                                        <td>
                                            <div class="info">
                                                <?php
                                                echo "<i>$usr->username</i>";
                                                $curTrnEx = getTrainerByUserId($usr->id, PKMN_SIMPLE_LOAD);
                                                $fc = $curTrnEx->faction;
                                                
                                                if(!is_null($fc)){
                                                    echo "<i>".$fc->name."</i>";
                                                }
                                                
                                                echo "<p>Silver: $curTrnEx->silver</p>";
                                                echo "<p>Gold: $curTrnEx->gold</p>";
                                                ?>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>	

                            <td class="td2">
                                <table style="width: 100%;">
                                    <tr><td><div class="title">Team</div></td></tr>	

                                    <tr>
                                        <td>
                                            <ul class="pkmn_list">
                                                <?php
                                                $curPkmns = getTrainerPokemonsList($usrTrn->id, 1, PKMN_DEEP_LOAD);
                                                foreach ($curPkmns as $p) {
                                                    $pkmn = $p->pokemon;
                                                    echo "<li><img alt='$pkmn->name' title='$pkmn->name' src='" . $pkmn->imgs[0]->image_url . "' /></li>";
                                                }
                                                ?>
                                            </ul>
                                        </td>
                                    </tr>		

                                    <tr><td><div class="title">Signature</div></td></tr>

                                    <tr><td><div class="signature">
                                                <?php echo "<img src='proc_data/proc_user.php?action=sgnt&id=$usr->id' />" ?>
                                            </div>
                                        </td>
                                    </tr>	
                                </table>
                            </td>
                        </tr>
                    </table>

                    <div class="header">News/Updates</div>
                    <?php outputNews(true) ?>
                </div>
            </div>
        </div>

        <!-- footer -->
        <?php include './footer.php' ?>
    </body>
</html>