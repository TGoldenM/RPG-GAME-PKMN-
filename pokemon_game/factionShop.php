<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './data_access/faction_shop.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$navPos = isset($req_data['navigation_position'])
        && in_array($req_data['navigation_position'], array('left', 'right'))
        ? $req_data['navigation_position'] : 'outside';

$pg = getPgFactionShopElements($list, $navPos, $req_data['idf'], 10, PKMN_SIMPLE_LOAD);
$curUserIsAdmin = curUserIsAdmin();
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
        
        <?php if($curUserIsAdmin) : ?>
        <script type="text/javascript" src="javascript/include.js"></script>
        <script type="text/javascript" src="javascript/factionShop.js"></script>
        <?php endif; ?>
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
                        <?php if($curUserIsAdmin): ?>
                        <div class="pkmn_ctrl">
                            <a href="fsAdmElement.php?idf=<?php echo $req_data['idf']?>">Add</a>
                        </div>
                        <?php endif; ?>
                        
                        <form action="itemAdmin.php" method="post">
                            <?php if (count($list) > 0) :?>
                            
                            <table style="width: 100%;" class="data_table">
                                <?php
                                echo "<tr class='header'>
                                    <td class='colWidth10'>Type</td>
                                    <td >Name</td>
                                    <td class='colWidth10' style='text-align:right;padding-right:10px;'>Point cost</td>";
                                
                                if($curUserIsAdmin){
                                    echo "<td class='colWidth10'>Actions</td>";
                                }
                                
                                echo "</tr>";
                                
                                foreach ($list as $obj) {
                                    $elem = $obj->element;
                                    $name = $obj->type == 1 ? $elem->item->name : sprintf("%s (Lvl:%d)", $elem->pokemon->name, $elem->level);
                                    
                                    echo "<tr><td>".($obj->type == 1 ? 'Item' : 'Pokemon')."</td>";
                                    echo "<td><a href='proc_data/proc_faction_shop.php?id=$obj->id&action=buy'>$name</a></td>";
                                    echo "<td style='text-align:right;padding-right:10px;'>".number_format((float)$obj->pts_cost, 0, ",", ".")."</td>";
                                    
                                    if($curUserIsAdmin){
                                        echo "<td><a id='obj_$obj->id' href='proc_data/proc_faction_shop.php?action=delete&id=$obj->id&btnYes=1'>"
                                            . "<img src='images/menu_icons/erase.png' alt='Delete'/></a>"
                                            . "</td>";
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