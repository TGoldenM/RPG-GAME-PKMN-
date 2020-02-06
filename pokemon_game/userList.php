<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$navPos = isset($req_data['navigation_position'])
        && in_array($req_data['navigation_position'], array('left', 'right'))
        ? $req_data['navigation_position'] : 'outside';

$usrname = isset($req_data['usrname']) ? $req_data['usrname'] : '';
$pg = getPgSystemUsersList($users, $navPos, 5, $usrname.'%');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Pokemon CosmosRPG - Members Area</title>
        <link href="css/style.css" rel="stylesheet">
        <link href="css/zebra_pagination.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet'>
        <link href="images/layout/favicon.png" rel="shortcut icon">
        <script type="text/javascript" src="javascript/zebra_pg/zebra_pagination.js"></script>
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
                    <div class="header">Users</div>
                    <?php
                    if(isset($req_data['n']) && isset($req_data['t'])){
                        $t = (int)$req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>
                    <div style="padding: 5px;">
                        <form id="formData" action="userList.php" method="post">
                            <div class="pkmn_ctrl">
                                <span>User name:</span>
                                <?php
                                echo "<input type='text' name='usrname' value='$usrname'>";
                                ?>
                                <input type="submit" value="Search"/>
                            </div>
                        </form>
                        
                        <form action="userList.php" method="post">
                            <?php
                            echo "<input type='hidden' name='usrname' value='$usrname'>";
                            ?>
                            
                            <ul class="user_list">
                                <?php
                                foreach ($users as $u) {
                                    echo "<li><div style='display:table;'>
                                            <img class='avatar' src='$u->img_avatar_url'/>
                                            <a class='username' href='userView.php?id=$u->id'>$u->username</a>
                                        </div></li>";
                                }
                                ?>
                            </ul>
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