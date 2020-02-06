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
$pg = getPgPokemonsList($pokemons, $navPos, $name, 5, true, true, true);

$curUserIsAdmin = curUserIsAdmin();
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
                    <div class="header">Pokebox</div>
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
                        <form action="pokebox.php" method="post">
                            <div class="pkmn_ctrl">
                                <?php if($curUserIsAdmin) : ?>
                                    <a href='pkmnEdit.php'>Add</a>&nbsp;(Admin)
                                    &nbsp;<a href='pkmnLoad.php'>Massive Load</a>&nbsp;(Admin)&nbsp;
                                <?php endif; ?>
                                <input type="text" name="name" value="<?php echo $name?>"
                                       placeholder="Type a name to search"/>
                                <input type="submit" id="btnSearch" name="btnSearch"
                                       value="Search"/>
                            </div>
                        </form>
                        
                        <!-- Pagination form -->
                        <form action="pokebox.php" method="post">
                            <input type="hidden" name="name" value="<?php echo $name?>" />
                            
                            <table style="width: 100%;" class="data_table">
                                <tr class="header">
                                    <td>Image</td>
                                    <td>Caught?</td>
                                    <td>Pokemon</td>
                                    <td>Type(s)</td>
                                    <td>Egg group(s)</td>
                                    
                                    <?php
                                        if($curUserIsAdmin){
                                            echo '<td>Actions (Admin)</td>';
                                        }
                                    ?>
                                </tr>
                                <?php
                                    foreach($pokemons as $pkmn){
                                        echo "<tr>";
                                        
                                        $groups = $pkmn->groups;
                                        $types = $pkmn->types;
                                        
                                        echo "<td><img src='proc_data/proc_pokemon.php?action=img&id=$pkmn->id' /></td>";
                                        echo "<td>".(isPkmnCaught($pkmn->id, $curTrainer->id) ? "Yes" : "No")."</td>";
                                        echo "<td><a href='pkmnStats.php?id=$pkmn->id'>$pkmn->name</a></td>";
                                        
                                        echo "<td>";
                                        foreach($types as $type){
                                            echo "$type->name&nbsp;";
                                        }
                                        echo "</td>";
                                        
                                        echo "<td>";
                                        foreach($groups as $group){
                                            echo "$group->name&nbsp;";
                                        }
                                        echo "</td>";
                                        
                                        if($curUserIsAdmin){
                                            echo "<td><a id='obj_$pkmn->id' title='Disable' href='proc_data/proc_pokemon.php?action=disable&id=$pkmn->id&btnYes=1'>"
                                                . "<img src='images/menu_icons/disable.png' alt='Disable'/></a>"
                                                . "&nbsp;<a href='pkmnEdit.php?action=edit&id=$pkmn->id' title='Edit'>"
                                                . "<img src='images/menu_icons/pencil.png' alt='edit' /></a>"
                                                . "&nbsp;<a href='evolutionAdmin.php?idp=$pkmn->id' title='Evolutions'>"
                                                . "<img src='images/menu_icons/stock_properties.png' alt='edit' /></a>"
                                                . "</td>";
                                        }
                                        
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