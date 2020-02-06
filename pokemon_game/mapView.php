<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/region.php';
require_once './data_access/map.php';
require_once './ui_support/dropdown.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$map = getMap($req_data['id']);

if (isset($req_data['id_r'])) {
    $id_r = $req_data['id_r'];
} else {
    if(isset($map)){
        $id_r = $map->id_region;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Pokemon CosmosRPG - Members Area</title>
        <link href="css/style.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet'>
        <link href="images/layout/favicon.png" rel="shortcut icon">
        <script type="text/javascript" src="jquery/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="jquery/jquery.validate.min.js"></script>
        <script type="text/javascript" src="javascript/sprintf/sprintf.min.js"></script>
        <script type="text/javascript" src="javascript/include.js"></script>
        <script type="text/javascript" src="javascript/pokemon.js"></script>
        <script type="text/javascript" src="javascript/mapView.js"></script>
    </head>
    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">Viewing <?php echo $map->name ?></div>
                    <div id="alertBox">
                    <?php
                    if (isset($req_data['n']) && isset($req_data['t'])) {
                        $t = (int) $req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>
                    </div>
                    
                    <div style="padding: 5px;">
                        <div class="pkmn_ctrl">
                            <a href='mapAdmin.php?id_region=<?php echo $id_r?>'>Return</a>
                        </div>
                        <form id="formData" action="proc_data/proc_map.php"
                              method="post" enctype="multipart/form-data">
                            <?php
                            echo "<input type='hidden' id='id' name='id' value='$map->id' />";
                            echo "<input type='hidden' id='id_region' name='id_region'
                                    value='$id_r' />";
                            ?>

                            <table class="pkmn_form">
                                <?php
                                echo "<tr><td style='text-align:center;'>
                                    <img id='map' src='proc_data/proc_map.php?action=img&id=$map->id' />
                                    </td></tr>";
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