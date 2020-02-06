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

if(isset($_SESSION['tradeOpt'])){
    $trOpt = $_SESSION['tradeOpt'];
    unset($_SESSION['tradeOpt']);
} else{
    if(isset($req_data['tradeOpt'])){
        $trOpt = $req_data['tradeOpt'];
    } else {
        $trOpt = 1;
    }
}

$tradeOpts = array('I give pokemon', 'I receive pokemon');

if($trOpt == 0){
    $trId1 = $curTrainer->id;
    $trId2 = null;
} else{
    $trId1 = null;
    $trId2 = $curTrainer->id;
}

$pg = getPgTradeList($trades, $navPos, 10, $trId1, $trId2, PKMN_DEEP_LOAD, "creation_date desc");
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
        <script type="text/javascript" src="javascript/trades.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">Trades</div>
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
                        <form id="formData" action="trades.php" method="post">
                            <div class="pkmn_ctrl">
                                <a href="tradeSelUsrPkmn.php">Make a trade</a>
                                <span> | </span>
                                <span>Show trades where </span>
                                <?php
                                echo "<select id='tradeOpt' name='tradeOpt' value='$trOpt'>";
                                genSelectOptionsArr($tradeOpts, $trOpt);
                                echo "</select>";
                                ?>
                            </div>
                        </form>
                        
                        <form action="trades.php" method="post">
                            <?php
                            echo "<input type='hidden' id='tradeOpt' name='tradeOpt' value='$trOpt' />";
                            ?>
                            
                            <table style="width: 100%;" class="data_table">
                                <tr class="header">
                                    <td>Date</td>
                                    <td>Owner</td>
                                    <td>Beneficiary</td>
                                    <td>Offered Pokemon</td>
                                    <td>Wants Pokemon</td>
                                    <td>Trade state</td>
                                    <td>Actions</td>
                                </tr>
                                <?php
                                foreach ($trades as $t) {
                                    echo "<tr>";
                                    
                                    $dt = strtotime($t->creation_date);
                                    echo "<td>".date("d/m/Y H:i")."</td>";
                                    
                                    $usrname = $curUser->username == $t->trainer1->user->username ?
                                            'Me' : $t->trainer1->user->username;

                                    $curUsrIsOwner = $usrname == 'Me';

                                    echo "<td>".$usrname."</td>";

                                    $usrname = $curUser->username == $t->trainer2->user->username ?
                                            'Me' : $t->trainer2->user->username;
                                    echo "<td>".$usrname."</td>";

                                    echo "<td>".$t->tpkmn1->pokemon->name." (Lvl:". $t->tpkmn1->level. ")</td>";
                                    echo "<td>".$t->tpkmn2->pokemon->name." (Lvl:". $t->tpkmn2->level. ")</td>";

                                    echo "<td>".$t->state->name."</td>";

                                    if($curUsrIsOwner){
                                        if($t->state->name == 'Created'){
                                            echo "<td><a title='Cancel trade' id='cnl_$t->id' href='proc_data/proc_trade.php?tradeOpt=$trOpt&action=delete&btnYes=1&id=$t->id'>
                                                    <img src='images/menu_icons/cancel.png'/></a></td>";
                                        }
                                    } else {
                                        if($t->state->name == 'Created'){
                                            echo "<td><a title='Reject trade' id='rej_$t->id' href='proc_data/proc_trade.php?tradeOpt=$trOpt&action=reject&id=$t->id'>
                                                    <img src='images/menu_icons/disable.png'/></a>&nbsp;
                                                    <a title='Accept trade' id='acc_$t->id' href='proc_data/proc_trade.php?tradeOpt=$trOpt&action=accept&id=$t->id'>
                                                        <img src='images/menu_icons/accept_1.png' alt='edit' />
                                                    </a>
                                                    </td>";
                                        }
                                    }

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
