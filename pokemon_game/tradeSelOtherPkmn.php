<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './data_access/trainer.php';
require_once './data_access/trainer_pokemon.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$navPos = isset($req_data['navigation_position']) && in_array($req_data['navigation_position'], array('left', 'right')) ? $req_data['navigation_position'] : 'outside';

$tid = $req_data['tid'];
$chosen_tpkmn = getTrainerPokemon($tid);

$cid_tr = $req_data['cid_tr'];
$curTrn = getTrainer($cid_tr);

$oid_tr = $req_data['oid_tr'];
$otherTrn = getTrainer($oid_tr);
$otherUsr = getSystemUser($otherTrn->id_user);

$pg = getPgTrainerPokemonsList($tpkmns, $navPos, $otherTrn->id, null, null, 1, null, PKMN_DEEP_LOAD, 'tp.exp desc');
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
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">Trading with <?php echo $otherUsr->username ?></div>
                    <?php
                    if (isset($req_data['n']) && isset($req_data['t'])) {
                        $t = (int) $req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>

                    <div style="padding: 5px;">
                        <form action="trainerPkmn.php" method="post">
                            <?php
                            echo "<input type='hidden' id='id' name='id' value='$tid'/>";
                            echo "<input type='hidden' id='cid_tr' name='cid_tr' value='$cid_tr'/>";
                            echo "<input type='hidden' id='oid_tr' name='oid_tr' value='$oid_tr'/>";
                            ?>
                            <table style="width: 100%;" class="data_table">
                                <tr class="header">
                                    <td>Lvl</td>
                                    <td>Pokemon</td>
                                    <td>Type(s)</td>
                                    <td>Egg group(s)</td>
                                </tr>
                                <?php
                                foreach ($tpkmns as $tpkmn) {
                                    echo "<tr>";

                                    $pkmn = $tpkmn->pokemon;
                                    $groups = $pkmn->groups;
                                    $types = $pkmn->types;

                                    echo "<td>$tpkmn->level</td>";

                                    $q = http_build_query(array(
                                        'action' => 'create',
                                        'id_trainer1'=>$cid_tr,
                                        'id_trainer2'=>$oid_tr,
                                        'id_tpkmn1' => $tid,
                                        'id_tpkmn2' => $tpkmn->id
                                    ));

                                    echo "<td><a href='proc_data/proc_trade.php?$q'>$pkmn->name</a></td>";

                                    echo "<td>";
                                    foreach ($types as $type) {
                                        echo "$type->name&nbsp;";
                                    }
                                    echo "</td>";

                                    echo "<td>";
                                    foreach ($groups as $group) {
                                        echo "$group->name&nbsp;";
                                    }
                                    echo "</td>";

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