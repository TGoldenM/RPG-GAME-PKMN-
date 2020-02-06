<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './data_access/trainer.php';
require_once './data_access/news.php';
require_once './data_access/system_property.php';

$req_data = filter($_REQUEST);
$navPos = isset($req_data['navigation_position']) && in_array($req_data['navigation_position'], array('left', 'right')) ? $req_data['navigation_position'] : 'outside';

$name = isset($req_data['name']) ? $req_data['name'] : null;
$pg = getPgTPkmnRarityList($pokemons, $navPos, $name);

$curTrainer = getCurrentTrainer();
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
        <script type="text/javascript" src="javascript/pokebox.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
<?php include './header.php' ?>

            <div id="content">
<?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header"><center><?php $curTrnEx = getTrainerByUserId($usr->id, PKMN_SIMPLE_LOAD);
                echo $curTrnEx->clan; ?></center></div>
                    <?php
                    if (isset($req_data['n']) && isset($req_data['t'])) {
                        $t = (int) $req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>

                    <div style="padding: 5px;">
                        <div style="color:white;">




                            <center>
                                <br>
                                <?php
                                $curTrnEx = getTrainerByUserId($usr->id, PKMN_SIMPLE_LOAD);

                                $stmt = $db->prepare("SELECT * FROM pkmn_clan WHERE name = :name");
                                $stmt->execute(array(':name' => "$curTrnEx->clan"));

                                $result = $stmt->fetch();

                                list($width, $height) = getimagesize($result[image]);

                                if ($width > 700) {

                                    echo 'Sorry this image is too large, maximum dimensions is 700 x 275';
                                } elseif ($height > 275) {

                                    echo'Sorry this image is too large, maximum dimensions is 700 x 275';
                                } else {
                                    echo "<img src='$result[image]'>";
                                }

                                echo "<br>
                                                <br>
                                                <br>
                                                <hr noshade><br><br>";

                                if (empty($result[description])) {

                                    echo 'Make a description dummy!';
                                } else {

                                    echo "$result[description]";
                                }
                                
                                echo '<br><br><br><hr noshade><br><br>';
                                ?>

                            </center>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer -->
<?php include './footer.php' ?>
    </body>
</html>