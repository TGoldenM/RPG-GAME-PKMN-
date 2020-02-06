<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './data_access/trainer_item.php';
require_once './data_access/system_role.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$navPos = isset($req_data['navigation_position'])
        && in_array($req_data['navigation_position'], array('left', 'right'))
        ? $req_data['navigation_position'] : 'outside';

$curTr = getCurrentTrainer();
$curUserIsAdmin = curUserIsAdmin();
$pg = getPgTrainerItems($list, $navPos, $curTr->id, 10, PKMN_SIMPLE_LOAD);
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
        <script type="text/javascript" src="javascript/trnItems.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">Items</div>
                    <?php
                    if(isset($req_data['n']) && isset($req_data['t'])){
                        $t = (int)$req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>
                    <div style="padding: 5px;">
                        <div class="pkmn_ctrl">
                            <a href="trnItemEdit.php">Add</a>
                        </div>
                        
                        <form action="trnItems.php" method="post">
                            <?php if (count($list) > 0) :?>
                            
                            <table style="width: 100%;" class="data_table">
                                <?php
                                echo "<tr class='header'>";
                                echo "<td class='colWidth20'>Category</td>
                                    <td>Name</td>
                                    <td class='colWidth10'>Stat</td>
                                    <td class='colWidth10'>Value</td>";
                                
                                if($curUserIsAdmin){
                                    echo "<td class='colWidth20'>Actions</td>";
                                }
                                
                                echo "</tr>";
                                ?>
                                <?php
                                foreach ($list as $obj) {
                                    $item = $obj->item;
                                    echo "<tr><td>".$item->category->name."</td>";
                                    echo "<td>$item->name</td>";
                                    echo "<td>".(isset($item->stat) ? $item->stat->name : '')."</td>";
                                    echo "<td>$item->value</td>";
                                    
                                    echo "<td>";
                                    if ($curUserIsAdmin) {
                                        $q = http_build_query(array(
                                            "action"=>"delete",
                                            "id"=>$obj->id,
                                            "btnYes"=>1
                                        ));
                                        
                                        echo "<a id='obj_$obj->id' title='Delete (Admin)' href='proc_data/proc_trainer_item.php?$q'>"
                                            . "<img src='images/menu_icons/erase.png' alt='Delete'/></a>"
                                            . "&nbsp;";
                                    }
                                    
                                    echo "<a href='trnItemEdit.php?id=$obj->id' title='Edit'>"
                                           . "<img src='images/menu_icons/pencil.png' alt='edit' /></a>";
                                    
//                                    if($curTr->id_faction != null){
//                                        echo "&nbsp;<a href='fsEditElement.php?t=1&e=$obj->id' title='Add to faction shop'>"
//                                            . "<img src='images/menu_icons/shop.png' alt='Send to faction shop' /></a>";
//                                    }
                                    
                                    echo "</td>";
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