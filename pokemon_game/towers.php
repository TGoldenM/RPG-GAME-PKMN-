<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './data_access/trainer.php';
require_once './data_access/gym.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$navPos = isset($req_data['navigation_position'])
        && in_array($req_data['navigation_position'], array('left', 'right'))
        ? $req_data['navigation_position'] : 'outside';

$pg = getPgTrainerList($list, $navPos, 10, 2, null,
	null, 1, "order_index", PKMN_DEEP_LOAD);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Pokemon CosmosRPG - Members Area</title>
        <link href="css/style.css" rel="stylesheet">
        <link href="css/zebra_pagination.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet'>
        <link href="images/layout/favicon.png" rel="shortcut icon">
        
        <script type="text/javascript" src="jquery/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="javascript/include.js"></script>
        <script type="text/javascript" src="javascript/trainers.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class='header'>Battle Towers</div>
                    <?php
                    if(isset($req_data['n']) && isset($req_data['t'])){
                        $t = (int)$req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>
                    <div style="padding: 5px;">
                        <form action="trainers.php" method="post">
                            <?php if (count($list) > 0) :?>
                            
                            <table style="width: 100%;" class="data_table">
                                <tr class="header">
                                    <td>Name</td>
                                    <td>Pokemon count</td>
                                    <td>Prize</td>
                                </tr>
                                <?php
                                foreach ($list as $obj) {
                                    $pkmnCount = count($obj->pokemons);
                                    $tpkmn = getHighestLvlTradPkmn($obj->pokemons);
                                    $hasPkmns = $pkmnCount > 0;
                                    
                                    echo "<tr>";
                                    if($hasPkmns){
                                        echo "<td><a href='game.php?id_tr=$obj->id'>$obj->name</a></td>";
                                    } else {
                                        echo "<td>$obj->name</td>";
                                    }
                                    
                                    echo "<td>$pkmnCount</td>";
                                    
                                    if(!is_null($tpkmn)){
                                        echo "<td>".$tpkmn->pokemon->name."</td>";
                                    } else {
                                        echo "<td></td>";
                                    }
                                    
                                    echo "</tr>";
                                }
                                ?>
                            </table>
                            
                            <?php $pg->render(); endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer -->
        <?php include './footer.php' ?>
    </body>
</html>