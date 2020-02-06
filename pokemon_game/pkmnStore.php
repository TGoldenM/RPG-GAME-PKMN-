<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './data_access/trade.php';
require_once './ui_support/alertbox.php';
require_once './ui_support/dropdown.php';

$req_data = filter($_REQUEST);
$curTrainer = getCurrentTrainer();
$curUser = getSystemUser($curTrainer->id_user);

$navPos = isset($req_data['navigation_position'])
        && in_array($req_data['navigation_position'], array('left', 'right'))
        ? $req_data['navigation_position'] : 'outside';

$pg = getPgTrainerPokemonsList($tpkmns, $navPos, null, null, 0, null, 1, PKMN_DEEP_LOAD
        , "tp.exp desc, tp.gold, tp.silver", 20);
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
                    <div class="header">Pokemons</div>
                    <?php
                    if (isset($req_data['n']) && isset($req_data['t'])) {
                        $t = (int) $req_data['t'];
                        $n = $req_data['n'];

                        if (isset($req_data['c'])) {
                            $c = $req_data['c'];
                            createAlertBox($t, $n, $c);
                        } else {
                            createAlertBox($t, $n);
                        }
                    }
                    ?>
                    
                    <div style="padding:5px;">
                        <form action="pkmnStore.php" method="post">
                            <table style="width: 100%;" class="data_table">
                                <tr class="header">
                                    <td>Owner</td>
                                    <td>Pokemon</td>
                                    <td>Gold</td>
                                    <td>Silver</td>
                                </tr>
                                <?php
                                foreach ($tpkmns as $t) {
                                    $usr = getSystemUser($t->trainer->id_user);
                                    echo "<tr>";
                                    
                                    $usrname = $curUser->username == $usr->username ?
                                            'Me' : $usr->username;

                                    $curUsrIsOwner = $usrname == 'Me';

                                    echo "<td>".$usrname."</td>";
                                    
                                    if($usrname != 'Me'){
                                        echo "<td><a href='pkmnView.php?id=$t->id'>".$t->pokemon->name." (Lvl:". $t->level. ")</a></td>";
                                    } else {
                                        echo "<td>".$t->pokemon->name."</a></td>";
                                    }
                                    
                                    echo "<td>".$t->gold."</td>";
                                    echo "<td>".$t->silver."</td>";

                                    echo "</tr>";
                                }
                                ?>
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
