<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/pokemon.php';
require_once './data_access/trainer_pokemon.php';
require_once './data_access/system_role.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$navPos = isset($req_data['navigation_position'])
        && in_array($req_data['navigation_position'], array('left', 'right'))
        ? $req_data['navigation_position'] : 'outside';

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
                    <div class="header">Rarity List</div>
                    <?php
                    if(isset($req_data['n']) && isset($req_data['t'])){
                        $t = (int)$req_data['t'];
                        $n = $req_data['n'];
                        
                        if(isset($req_data['c'])){
                            $c = base64_decode($req_data['c']);
                            createAlertBox($t, $n, $c);
                        } else {
                            createAlertBox($t, $n);
                        }
                    }
                    ?>
                    
                    <div style="padding: 5px;">
                        <!-- Search form -->
                        <form action="rarityList.php" method="post">
                            <div class="pkmn_ctrl">
                                <input type="text" name="name" value="<?php echo $name?>"
                                       placeholder="Type a name to search"/>
                                <input type="submit" id="btnSearch" name="btnSearch"
                                       value="Search"/>
                            </div>
                        </form>
                        
                        <!-- Pagination form -->
                        <form action="rarityList.php" method="post">
                            <input type="hidden" name="name" value="<?php echo $name?>" />
                            
                            <table style="width: 100%;" class="data_table2">
                                <tr class="header">
                                    <td>Pokemon</td>
                                    <td>Males</td>
                                    <td>Females</td>
                                    <td>Genderless</td>
                                </tr>
                                <?php
                                    foreach($pokemons as $pkmn){
                                        echo "<tr>";
                                        echo "<td>$pkmn->name</td>";
                                        echo "<td>$pkmn->males</td>";
                                        echo "<td>$pkmn->females</td>";
                                        echo "<td>$pkmn->genderless</td>";
                                        echo "</tr>";
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