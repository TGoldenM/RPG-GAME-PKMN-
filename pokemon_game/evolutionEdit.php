<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/trainer.php';
require_once './data_access/trainer_pokemon.php';
require_once './data_access/evolution.php';
require_once './ui_support/dropdown.php';
require_once './ui_support/alertbox.php';
require_once './ui_support/checkbox.php';

$req_data = filter($_REQUEST);
$is_new = !isset($req_data['id']);

//If some validation failed, read from session the input data
if (isset($_SESSION['evObj'])) {
    $evl = unserialize($_SESSION['evObj']);
    unset($_SESSION['evObj']);
} else {
    if ($is_new) {
        $evl = (object) array(
            'pkmn' => (object)array("id"=>$req_data['idp'], "name"=>null),
            'evolved_pkmn' => (object)array("id"=>null, "name"=>null),
            'required_exp' => null,
            'required_item' => (object)array('id'=>null)
        );
    } else {
        $evl = getEvolution($req_data['id'], PKMN_DEEP_LOAD);
        
        if(is_null($evl->evolved_pkmn)){
            $evl->evolved_pkmn = (object)array("id"=>null, "name"=>null);
        }
        
        if(is_null($evl->required_item)){
            $evl->required_item = (object)array('id'=>null);
        }
    }
}

$item = $evl->required_item;
if(is_null($item)){
    $item = (object)array('id'=>null);
}

$simple_opts = array('No', 'Yes');
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
        
        <link href="css/autocpl.css" rel="stylesheet">
        <script type="text/javascript" src="jquery/jquery.autocomplete.min.js"></script>
        
        <script type="text/javascript" src="javascript/include.js"></script>
        <script type="text/javascript" src="javascript/evolutionEdit.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">Evolution</div>
                    <?php
                    if (isset($req_data['n']) && isset($req_data['t'])) {
                        $t = (int) $req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>

                    <div style="padding: 5px;">
                        <form id="formData" action="proc_data/proc_evolution.php"
                              method="post">
                            <?php
                            if (!$is_new) {
                                echo "<input type='hidden' id='id' name='id' value='$evl->id' />";
                                echo "<input type='hidden' id='action' name='action' value='edit' />";
                            }
                            
                            echo "<input type='hidden' id='id_pkmn' name='id_pkmn' value='".$evl->pkmn->id."' />";
                            ?>

                            <table class="pkmn_form">
                                <tr><td class='label'>Evolves to</td>
                                    <td class="value">
                                        <input type='text' id='evolved_name' name='evolved_name'
                                               placeholder='Type a name to search'
                                               value='<?php echo $evl->evolved_pkmn->name ?>' />
                                        <input type='hidden' id='id_evolved_pkmn' name='id_evolved_pkmn'
                                               value='<?php echo $evl->evolved_pkmn->id ?>' />
                                    </td>
                                </tr>
                                <tr><td class='label'>Required level for evolution</td>
                                    <td class="value">
                                        <input class='number' type='text' id='required_lvl' name='required_lvl'
                                               value='<?php echo getPkmnLevel($evl->required_exp) ?>' />
                                    </td>
                                </tr>
                                <tr><td class='label'>Required item for evolution</td>
                                    <td class="value">
                                        <?php
                                        echo "<select id='id_required_item' name='id_required_item' value='$item->id'>";
                                        echo "<option value='0'>--No selection--</option>";
                                        genSelectOptions("pkmn_pokemon_item", "id", "name", $item->id, null, 'id_category = 8');
                                        echo "</select>";
                                        ?>
                                    </td>
                                </tr>
                                <tr><td class="label"></td><td>
                                        <input class='btnSubmit' id='btnOk' name='btnOk'
                                               type='submit' value='OK' />
                                        <input class='btnSubmit cancel' id='btnRet' name='btnRet'
                                               type='submit' value='Return'/>
                                    </td>
                                </tr>
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