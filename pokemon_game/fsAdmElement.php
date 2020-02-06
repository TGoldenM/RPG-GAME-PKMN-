<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/system_role.php';
require_once './data_access/trainer.php';
require_once './data_access/trainer_pokemon.php';
require_once './data_access/trainer_item.php';
require_once './data_access/faction_shop.php';
require_once './ui_support/dropdown.php';
require_once './ui_support/alertbox.php';
require_once './ui_support/listbox.php';

$req_data = filter($_REQUEST);

//If some validation failed, read from session the input data
if (isset($_SESSION['elemObj'])) {
    $obj = unserialize($_SESSION['elemObj']);
    unset($_SESSION['elemObj']);
} else {
    $obj = (object) array(
        "faction" => getFaction($req_data['idf']),
        "element" => (object)array("id"=>null),
        "type" => 1,
        "pts_cost" => 0
    );
}

$typesElem = array(1=>'Item', 2=>'Pokemon');
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
        <script type="text/javascript" src="javascript/fsAdmElement.js"></script>
    </head>
    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">Faction shop element</div>
                    <?php
                    if (isset($req_data['n']) && isset($req_data['t'])) {
                        $t = (int) $req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>

                    <div style="padding: 5px;">
                        <form id="formData" action="proc_data/proc_faction_shop.php"
                              method="post">
                            <?php
                            echo "<input type='hidden' id='action' name='action' value='create' />";
                            echo "<input type='hidden' id='id_faction' name='id_faction' value='".$obj->faction->id."' />";
                            ?>
                            
                            <table class="pkmn_form">
                                <tr>
                                    <td class='label'>Type</td>
                                    <td class="value">
                                        <?php
                                        echo "<select id='type' name='type' value='$obj->type'>";
                                        genSelectOptionsArr($typesElem, $obj->type);
                                        echo "</select>";
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>Level</td>
                                    <td class="value">
                                        <input class='number' type='text' id='lvl' name='lvl' />
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>Name</td>
                                    <td class="value">
                                        <input type='text' id='name' name='name'
                                               placeholder='Type a name to search' />
                                        <input type='hidden' id='id_obj' name='id_obj' />
                                    </td>
                                </tr>
                                <tr>
                                    <td class='label'>Point cost</td>
                                    <td class="value">
                                        <?php echo "<input type='text' class='number_large' id='pts_cost' name='pts_cost' value='$obj->pts_cost' />" ?>
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