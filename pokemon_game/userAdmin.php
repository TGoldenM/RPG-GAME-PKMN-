<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$navPos = isset($req_data['navigation_position'])
        && in_array($req_data['navigation_position'], array('left', 'right'))
        ? $req_data['navigation_position'] : 'outside';

$pg = getPgSystemUsersList($users, $navPos, 5);
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
        <script type="text/javascript" src="javascript/zebra_pg/zebra_pagination.js"></script>
        <script type="text/javascript" src="javascript/include.js"></script>
        <script type="text/javascript" src="javascript/userAdmin.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">Users</div>
                    <?php
                    if(isset($req_data['n']) && isset($req_data['t'])){
                        $t = (int)$req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>
                    <div style="padding: 5px;">
                        <div class="pkmn_ctrl">
                            <a href="userEdit.php">Add</a>
                        </div>
                        
                        <form action="userAdmin.php" method="post">
                            <table style="width: 100%;" class="data_table">
                                <tr class="header">
                                    <td class="colWidth5">Avatar</td>
                                    <td class="colWidth2">Banned</td>
                                    <td class="colWidth2">Disabled</td>
                                    <td class="colWidth20">User name</td>
                                    <td class="colWidth20">Email</td>
                                    <td class="colWidth5">Action</td></tr>
                                <?php
                                foreach ($users as $u) {
                                    $banned = filter_var($u->banned, FILTER_VALIDATE_BOOLEAN);
                                    $disabled = filter_var($u->disabled, FILTER_VALIDATE_BOOLEAN);
                                    $banTxt = $banned ? 'Yes' : 'No';
                                    $disTxt = $disabled ? 'Yes' : 'No';
                                    
                                    echo "<tr><td><img src='$u->img_avatar_url' style='width: 120px; height: 120px;'/></td>"
                                        . "<td>$banTxt</td><td>$disTxt</td><td>$u->username</td>"
                                        . "<td>$u->mail</td>"
                                        . "<td><a id='obj_$u->id' href='proc_data/proc_user.php?action=disable&id=$u->id'><img src='images/menu_icons/disable.png' alt='Disable'/></a>"
                                        . "&nbsp;<a href='userEdit.php?id=$u->id'><img src='images/menu_icons/pencil.png' alt='edit' /></a>"
                                        . "</td></tr>";
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