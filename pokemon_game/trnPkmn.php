<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/trainer.php';
require_once './data_access/trainer_pokemon.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$navPos = isset($req_data['navigation_position'])
        && in_array($req_data['navigation_position'], array('left', 'right'))
        ? $req_data['navigation_position'] : 'outside';

if (isset($req_data['id_tr'])) {
    $id_tr = $req_data['id_tr'];
    $curTrn = getTrainer($id_tr);
} else {
    $curTrn = getCurrentTrainer();
    $id_tr = $curTrn->id;
}

$name = isset($req_data['name']) ? $req_data['name'] : null;
$pg = getPgTrainerPokemonsList($tpkmns, $navPos, $curTrn->id, $name, null, null
        , null, PKMN_DEEP_LOAD, 'tp.equipped desc');

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
        <script type="text/javascript" src="javascript/trnPkmn.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">Pokemons of <?php echo isset($curTrn->name) ? $curTrn->name : getCurrentUser()->username;?></div>
                    <?php
                    if(isset($req_data['n']) && isset($req_data['t'])){
                        $t = (int)$req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n, isset($req_data['c']) ? $req_data['c'] : null);
                    }
                    ?>
                    
                    <div style="padding: 5px;">
                        <form action="trnPkmn.php" method="post">
                            <div class="pkmn_ctrl">
                                <?php
                                if(isset($req_data['id_tr']) && curUserIsAdmin()){
                                    echo "<input type='hidden' name='id_tr' value='".$req_data['id_tr']."' />";
                                    echo "<a href='trnPkmnEdit.php?id_tr=$id_tr'>Add</a>&nbsp;";
                                }
                                ?>
                                
                                <input type="text" name="name" value="<?php echo $name?>"
                                       placeholder="Type a pokemon name"/>
                                <input type="submit" id="btnSearch" name="btnSearch"
                                       value="Search"/>
                            </div>
                        </form>
                        
                        <form action="trnPkmn.php" method="post">
                            <?php
                            if(isset($req_data['id_tr'])){
                                echo "<input type='hidden' name='id_tr' value='".$req_data['id_tr']."' />";
                            }
                            
                            if(isset($req_data['c'])){
                                echo "<input type='hidden' name='c' value='".$req_data['c']."' />";
                            }
                            
                            echo "<input type='hidden' name='name' value='$name' />";
                            ?>
                            
                            <table style="width: 100%;" class="data_table">
                                <tr class="header">
                                   <td class="colWidth10">In the team?</td>
                                   <td class="colWidth5">Tradeable?</td>
                                   <td class="colWidth5">Sellable?</td>
                                   <td class="colWidth5">Lvl</td>
                                   <td class="colWidth10">Pokemon</td>
                                   <td class="colWidth5">Gender</td>
                                   <td class="colWidth10">Type(s)</td>
                                   <td class="colWidth10">Egg group(s)</td>
                                   <td class="colWidth10">Actions</td>
                                </tr>
                                
                                <?php
                                foreach($tpkmns as $tpkmn){
                                    echo "<tr>";

                                    $pkmn = $tpkmn->pokemon;
                                    $groups = isset($pkmn->groups) ? $pkmn->groups : null;
                                    $types = $pkmn->types;
                                    
                                    $eqpTxt = $tpkmn->equipped ? 'Yes': 'No';
                                    echo "<td>$eqpTxt</td>";
                                    
                                    $tradTxt = $tpkmn->tradeable ? 'Yes': 'No';
                                    echo "<td>$tradTxt</td>";
                                    
                                    $sllTxt = $tpkmn->sellable ? 'Yes': 'No';
                                    echo "<td>$sllTxt</td>";
                                    echo "<td>$tpkmn->level</td>";
                                    
                                    echo "<td>$pkmn->name</td>";
                                    echo "<td>".getPkmnGenderGraphic($tpkmn)."</td>";

                                    echo "<td>";
                                    foreach($types as $type){
                                        echo "$type->name&nbsp;";
                                    }
                                    echo "</td>";

                                    echo "<td>";
                                    if(!is_null($groups)){
                                        foreach($groups as $group){
                                            echo "$group->name&nbsp;";
                                        }
                                    }
                                    echo "</td>";

                                    echo "<td>";
                                    if($curUserIsAdmin){
                                        echo "<a id='tpkmn_$tpkmn->id' title='Delete'
                                           href='proc_data/proc_trainer_pokemon.php?action=delete&btnYes=1&id=$tpkmn->id'>
                                           <img src='images/menu_icons/erase.png' /></a>&nbsp;";
                                    }
                                    
                                    echo "<a href='trnPkmnEdit.php?id=$tpkmn->id' title='Edit'>
                                            <img src='images/menu_icons/pencil.png' /></a>";
                                    
//                                    echo "<a href='proc_data/proc_trainer_pokemon.php?action=evlp&id=$tpkmn->id' title='Edit'>
//                                            <img src='images/menu_icons/upgrade.png' /></a>";
                                    
//                                    if($curTrn->id_faction != null){
//                                        echo "&nbsp;<a href='fsEditElement.php?t=2&e=$tpkmn->id' title='Add to faction shop'>"
//                                            . "<img src='images/menu_icons/shop.png' alt='Add to faction shop' /></a>";
//                                    }
                                    
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