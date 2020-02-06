<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/trainer_pokemon.php';
require_once './ui_support/alertbox.php';

$ranking = getRankingLevelPkmn();
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
    </head>
    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">Players with highest pokemon levels</div>
                    <?php
                    if(isset($req_data['n']) && isset($req_data['t'])){
                        $t = (int)$req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>
                    
                    <div style="padding: 5px;">
                        <form action="userAdmin.php" method="post">
                            <table style="width: 100%;" class="data_table">
                                <tr class="header">
                                    <td class="colWidth5">Username</td>
                                    <td class="colWidth2">Pokemon</td>
                                    <td class="colWidth2">Level</td>
                                <?php
                                foreach ($ranking as $r) {
                                    echo "<tr><td>$r->username</td>
                                          <td>$r->pkmn</td><td>$r->level</td>
                                          </tr>";
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