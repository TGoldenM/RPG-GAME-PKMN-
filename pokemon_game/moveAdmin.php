<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './data_access/pokemon_move.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$navPos = isset($req_data['navigation_position'])
        && in_array($req_data['navigation_position'], array('left', 'right'))
        ? $req_data['navigation_position'] : 'outside';

$pg = getPgPokemonMoveList($list, $navPos, 10, true, true, 'name');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Pokemon CosmosRPG - Members Area</title>
        <link href="css/style.css" rel="stylesheet">
        <link href="css/zebra_pagination.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet'>
        <script type="text/javascript" src="jquery/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="javascript/zebra_pg/zebra_pagination.js"></script>
        <script type="text/javascript" src="javascript/include.js"></script>
        <script type="text/javascript" src="javascript/moveAdmin.js"></script>        
        <link href="images/layout/favicon.png" rel="shortcut icon">
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">Moves</div>
                    <?php
                    if(isset($req_data['n']) && isset($req_data['t'])){
                        $t = (int)$req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>
                    <div style="padding: 5px;">
                        <div class="pkmn_ctrl">
                            <a href="moveEdit.php">Add</a>
                        </div>
                        
                        <form action="moveAdmin.php" method="post">
                            <?php if (count($list) > 0) :?>
                            
                            <table style="width: 100%;" class="data_table">
                                <tr class="header">
                                    <td class="colWidth10">Type</td>
                                    <td class="colWidth10">Category</td>
                                    <td>Name</td>
                                    <td>Power</td>
                                    <td class="colWidth10">Actions</td>
                                </tr>
                                <?php
                                foreach ($list as $obj) {
                                    $tp = $obj->type;
                                    $cat = $obj->category;
                                    echo "<tr><td>".(empty($tp->name) ? "" : $tp->name)."</td>";
                                    echo "<td>".(empty($cat->name) ? "" : $cat->name)."</td>";
                                    echo "<td>$obj->name</td>";
                                    echo "<td>$obj->value</td>";
                                    echo "<td><a id='obj_$obj->id'"
                                        . " href='proc_data/proc_pokemon_move.php?action=delete&id=$obj->id&btnYes=1'>"
                                        . "<img src='images/menu_icons/erase.png' alt='Delete'/></a>"
                                        . "&nbsp;<a href='moveEdit.php?id=$obj->id'>"
                                        . "<img src='images/menu_icons/pencil.png' alt='edit' /></a>"
                                        . "</td></tr>";
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