<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './data_access/trainer_type.php';
require_once './data_access/trainer.php';
require_once './data_access/gym.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$navPos = isset($req_data['navigation_position'])
        && in_array($req_data['navigation_position'], array('left', 'right'))
        ? $req_data['navigation_position'] : 'outside';

$curUserIsAdmin = curUserIsAdmin();

$type = null;
if(isset($req_data['tp'])){
    $type = $req_data['tp'];
}

$id_gym = null;
if(isset($req_data['g'])){
    $id_gym = $req_data['g'];
    $gym = getGym($id_gym);
}

$pg = getPgTrainerList($list, $navPos, 10, $type, $id_gym,
	null, null, "order_index", PKMN_DEEP_LOAD);
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
                    <?php
                    if(isset($gym)){
                       echo "<div class='header'>$gym->name</div>";
                    } else if($type == 2){//Tower
                       echo "<div class='header'>Battle Towers</div>";
                    } else {
                       echo "<div class='header'>Trainers</div>";
                    }

                    if(isset($req_data['n']) && isset($req_data['t'])){
                        $t = (int)$req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>
                    <div style="padding: 5px;">
                        <?php if($curUserIsAdmin && is_null($type) && is_null($id_gym)): ?>
                        <div class="pkmn_ctrl">
                            <a href="trainerEdit.php">Add (Admin)</a>
                        </div>
                        <?php endif; ?>
                        
                        <form action="trainers.php" method="post">
                            <?php if (count($list) > 0) :?>
                            
                            <table style="width: 100%;" class="data_table">
                                <tr class="header">
                                    <td>Name</td>
                                    <td>Type</td>
                                    <?php
                                    if($curUserIsAdmin && is_null($type) && is_null($id_gym)){
                                        echo "<td class='colWidth20'>Has pokemons?</td>";
                                        echo "<td class='colWidth20'>Actions (Admin)</td>";
                                    }
                                    ?>
                                </tr>
                                <?php
                                foreach ($list as $obj) {
                                    if(!$curUserIsAdmin && (!$obj->visible || is_null($obj->visible))){
                                        continue;
                                    }
                                    
                                    $pkmnCount = count($obj->pokemons);
                                    $hasPkmns = $pkmnCount > 0;
                                    
                                    if($hasPkmns){
                                        $lnk = "<a href='game.php?id_tr=$obj->id' alt='Click start battle against this trainer'>$obj->name</a>";
                                    } else {
                                        $lnk = $obj->name;
                                    }

                                    echo "<tr><td>$lnk</td>";
                                    
                                    $tp = getTrainerType($obj->id_type);
                                    if($tp != null){
                                        echo "<td>$tp->name</td>";
                                    } else {
                                        echo "<td></td>";
                                    }
                                        
                                    if($curUserIsAdmin && is_null($type) && is_null($id_gym)){
                                        echo "<td>".(!$hasPkmns ? 'No' : "Yes ($pkmnCount)")."</td>";
                                    
                                        echo "<td><a id='obj_$obj->id' href='proc_data/proc_trainer.php?action=delete&id=$obj->id&btnYes=1'><img src='images/menu_icons/erase.png' alt='Delete'/></a>"
                                            . "&nbsp;<a href='trainerEdit.php?id=$obj->id'>"
                                            . "<img src='images/menu_icons/pencil.png' alt='edit' /></a>"
                                            . "&nbsp;<a href='trnPkmn.php?id_tr=$obj->id'>"
                                            . "<img src='images/menu_icons/pokeball_1.png' alt='edit' /></a>"
                                            . "</td></tr>";
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