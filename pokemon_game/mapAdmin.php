<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/system_role.php';
require_once './data_access/region.php';
require_once './data_access/map.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$navPos = isset($req_data['navigation_position'])
        && in_array($req_data['navigation_position'], array('left', 'right'))
        ? $req_data['navigation_position'] : 'outside';

if (isset($req_data['id_region'])) {
    $id_r = $req_data['id_region'];
    $region = getRegion($id_r);
} else {
    $region = getRegionsList(null, 1);
    $id_r = $region[0]->id;
}

$pg = getPgMapsList($maps, $navPos, $id_r, 'name', 6);
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
        <script type="text/javascript" src="javascript/zebra_pg/zebra_pagination.js"></script>
        <script type="text/javascript" src="javascript/include.js"></script>
        <script type="text/javascript" src="javascript/mapAdmin.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">Viewing <?php echo $region->name ?></div>
                    <?php
                    if(isset($req_data['n']) && isset($req_data['t'])){
                        $t = (int)$req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>
                    
                    <div style="padding: 5px;">
                        <form id="frmSearch" action="mapAdmin.php" method="post">
                            <div class="pkmn_ctrl">
                                <?php
                                if($curUserIsAdmin){
                                    echo "<a href='mapEdit.php?id_r=$id_r'>Add</a>";
                                }
                                ?>
                            </div>
                        </form>
                        
                        <form id="frmData" action="mapAdmin.php" method="post">
                            <ul class="map_list">
                                <?php
                                foreach($maps as $map){
                                    echo "<li style='margin-bottom:5px;'>
                                        <div class='p_container'>
                                        <div><div class='p_title'>$map->name</div>";
                                    
                                    if($curUserIsAdmin){
                                        echo "<div class='p_opts'>
                                            <a id='obj_$map->id' href='proc_data/proc_map.php?action=delete&btnYes=1&id=$map->id'>
                                                <img src='images/menu_icons/erase.png' alt='edit' />
                                            </a>
                                            <a href='mapEdit.php?id=$map->id'>
                                                <img src='images/menu_icons/pencil.png' alt='edit' />
                                            </a>
                                            </div>";
                                    }
                                    
                                    echo "<div style='clear:both;'></div></div>";
                                    echo "<div><a href='mapView.php?id=$map->id'>
                                            <img src='proc_data/proc_map.php?action=img&id=$map->id&w=240&h=240' />
                                        </a></div>";
                                    
                                    echo "</div></li>";
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