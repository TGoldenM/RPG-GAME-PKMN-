<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './data_access/trade.php';
require_once './data_access/trainer_pokemon.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$curTrainer = getCurrentTrainer();
$curUser = getSystemUser($curTrainer->id_user);
$tpkmn = getTrainerPokemon($req_data['id'], PKMN_DEEP_LOAD);
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
        <script type="text/javascript" src="javascript/pkmnView.js"></script>
    </head>
    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">Pokemon details</div>
                    <form id="formData" action="proc_data/proc_trainer_pokemon.php"
                          method="post">
                        <?php
                        echo "<input type='hidden' id='id' name='id' value='$tpkmn->id' />";
                        echo "<input type='hidden' id='action' name='action' value='buy' />";
                        echo "<input type='hidden' id='trId' name='trId' value='$curTrainer->id' />";
                        ?>
                        
                        <table class="pkmn_form">
                            <tr><td class='label'></td><td><img src='<?php echo $tpkmn->pokemon->imgs[0]->image_url ?>' /></td></tr>
                            <tr><td class='label'>Name</td><td class="value"><?php echo $tpkmn->pokemon->name ?></td></tr>
                            <tr><td class='label'>Price</td><td class="value"><?php printf("Gold: %d, Silver: %d", $tpkmn->gold, $tpkmn->silver) ?></td></tr>
                            <tr><td class='label'>Level</td><td class="value"><?php echo $tpkmn->level ?></td></tr>
                            <tr><td class='label'>Experience</td><td class="value"><?php echo $tpkmn->exp ?></td></tr>
                            <tr><td class='label'>Gender</td><td class="value"><?php echo $tpkmn->gender == 1 ? 'Male' : 'Female' ?></td></tr>
                            <tr><td class='label'>HP</td><td class="value"><?php echo $tpkmn->hp == 0 ? 0 : ('' . ($tpkmn->cur_hp . '/' . $tpkmn->hp) . ' (' . round($tpkmn->cur_hp / $tpkmn->hp * 100) . '%)') ?></td></tr>
                            <tr><td class='label'>Attack</td><td class="value"><?php echo $tpkmn->attack ?></td></tr>
                            <tr><td class='label'>Defense</td><td class="value"><?php echo $tpkmn->defense ?></td></tr>
                            <tr><td class='label'>Speed</td><td class="value"><?php echo $tpkmn->speed ?></td></tr>
                            <tr><td class='label'>Special Attack</td><td class="value"><?php echo $tpkmn->spec_attack ?></td></tr>
                            <tr><td class='label'>Special Defense</td><td class="value"><?php echo $tpkmn->spec_defense ?></td></tr>
                            <tr><td class="label"></td><td>
                                    <?php
                                    if($curTrainer->gold > $tpkmn->gold
                                            && $curTrainer->silver > $tpkmn->silver){
                                        echo "<input class='btnSubmit' id='btnBuy' name='btnBuy'
                                           type='submit' value='Buy' />";
                                    } else {
                                        echo "<span>You can't buy this pokemon</span>";
                                    }
                                    ?>
                                    
                                    <input class='btnSubmit cancel' id='btnRet' name='btnRet'
                                           type='submit' value='Return'/>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>

        <!-- footer -->
        <?php include './footer.php' ?>
    </body>
</html>