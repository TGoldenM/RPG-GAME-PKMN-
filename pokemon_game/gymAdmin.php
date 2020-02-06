<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './data_access/gym.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$list = getGymsList('name');
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
        <script type="text/javascript" src="javascript/gymAdmin.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">Gyms</div>
                    <?php
                    if(isset($req_data['n']) && isset($req_data['t'])){
                        $t = (int)$req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>
                    <div style="padding: 5px;">
                        <div class="pkmn_ctrl">
                            <a href="gymEdit.php">Add</a>
                        </div>
                        
                        <form action="gymAdmin.php" method="post">
                            <?php if (count($list) > 0) :?>
                            
                            <table style="width: 100%;" class="data_table">
                                <tr class="header">
                                    <td>Name</td>
                                    <td class="colWidth10">Actions</td>
                                </tr>
                                <?php
                                foreach ($list as $obj) {
                                    echo "<tr><td>$obj->name</td>";
                                    
                                    echo "<td><a id='obj_$obj->id' href='proc_data/proc_gym.php?action=delete&id=$obj->id&btnYes=1'><img src='images/menu_icons/erase.png' alt='Delete'/></a>"
                                        . "&nbsp;<a href='gymEdit.php?id=$obj->id'>"
                                        . "<img src='images/menu_icons/pencil.png' alt='edit' /></a>"
                                        . "</td></tr>";
                                }
                                ?>
                            </table>
                            
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer -->
        <?php include './footer.php' ?>
    </body>
</html>