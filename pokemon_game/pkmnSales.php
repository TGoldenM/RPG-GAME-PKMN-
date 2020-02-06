<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/pokemon.php';
require_once './data_access/trainer.php';
require_once './data_access/sale.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$navPos = isset($req_data['navigation_position'])
        && in_array($req_data['navigation_position'], array('left', 'right'))
        ? $req_data['navigation_position'] : 'outside';

$curTrainer = getCurrentTrainer();
$pg = getPgSalesList($sales, $curTrainer->id, $navPos, 10, true, true);
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
                    <div class="header">Pokebox</div>
                    <?php
                    if(isset($req_data['n']) && isset($req_data['t'])){
                        $t = (int)$req_data['t'];
                        $n = $req_data['n'];
                        
                        if(isset($req_data['c'])){
                            $c = base64_decode($req_data['c']);
                            createAlertBox($t, $n, $c);
                        } else {
                            createAlertBox($t, $n);
                        }
                    }
                    ?>
                    
                    <div style="padding: 5px;">
                        <form action="pokebox.php" method="post">
                            <table style="width: 100%;" class="data_table">
                                <tr class="header">
                                    <td>Date</td>
                                    <td>Pokemon</td>
                                    <td>Level</td>
                                    <td>Gold</td>
                                    <td>Silver</td>
                                </tr>
                                <?php
                                    foreach($sales as $s){
                                        echo "<tr>";
                                        
                                        $dt = strtotime($s->date_sold);
                                        echo "<td>".date("d/m/Y H:i")."</td>";
                                        echo "<td>".$s->pokemon->name."</td>";
                                        echo "<td>".getPkmnLevel($s->exp)."</td>";
                                        echo "<td>".$s->gold."</td>";
                                        echo "<td>".$s->silver."</td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </table>
                            
                            <?php $pg->render() ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer -->
        <?php include './footer.php' ?>
    </body>
</html>