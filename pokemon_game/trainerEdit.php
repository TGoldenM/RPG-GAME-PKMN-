<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/trainer.php';
require_once './ui_support/dropdown.php';
require_once './ui_support/alertbox.php';
require_once './ui_support/listbox.php';

$req_data = filter($_REQUEST);
$is_new = !isset($req_data['id']);

//If some validation failed, read from session the input data
if (isset($_SESSION['trainerObj'])) {
    $trainer = unserialize($_SESSION['trainerObj']);
    unset($_SESSION['trainerObj']);
} else {
    if ($is_new) {
        $trainer = (object) array(
                    "id" => null,
                    "id_user" => null,
                    "id_faction" => null,
                    "id_gym" => null,
                    "id_type" => null,
                    "visible" => null,
                    "silver" => null,
                    "gold" => null,
                    "faction_pts" => null,
                    "name" => null,
                    "pokemons" => array()
        );
    } else {
        $trainer = getTrainer($req_data['id'], PKMN_DEEP_LOAD);
    }
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
        <script type="text/javascript" src="javascript/listbox/listbox.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">Pokemon</div>
                    <?php
                    if (isset($req_data['n']) && isset($req_data['t'])) {
                        $t = (int) $req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>

                    <div style="padding: 5px;">
                        <form id="formData" action="proc_data/proc_trainer.php"
                              method="post">
                            <?php
                            if (!$is_new) {
                                echo "<input type='hidden' id='id' name='id' value='$trainer->id' />";
                                echo "<input type='hidden' id='action' name='action' value='edit' />";
                            }
                            ?>

                            <table class="pkmn_form">
                                <tr>
                                    <td class="label">Visible?</td>
                                    <td class="value">
                                        <?php
                                        echo "<select id='visible' name='visible' value='$trainer->visible'>";
                                        genSelectOptionsArr($simple_opts, $trainer->visible);
                                        echo "</select>";
                                        ?>
                                    </td>
                                </tr>
                                <tr><td class="label">Faction</td>
                                    <td class="value">
                                        <?php
                                        if(isset($trainer->faction)){
                                            $fid = $trainer->faction->id;
                                        } else {
                                            $fid = 0;
                                        }
                                        
                                        echo "<select id='id_faction' name='id_faction'
                                                value='".$fid."'>";
                                        
                                        echo "<option value='0'>--No faction--</option>";
                                        genSelectOptions('pkmn_faction', 'id', 'name'
                                                , $fid, null, null, 'name');
                                        
                                        echo "</select>";
                                        ?>
                                    </td>
                                </tr>
                                <tr><td class="label">Gym</td>
                                    <td class="value">
                                        <?php
                                        echo "<select id='id_gym' name='id_gym'
                                                value='$trainer->id_gym'>";
                                        
                                        echo "<option value='0'>--No gym--</option>";
                                        genSelectOptions('pkmn_gym', 'id', 'name'
                                                , $trainer->id_gym, null, null
                                                , 'name');
                                        
                                        echo "</select>";
                                        ?>
                                    </td>
                                </tr>
                                <tr><td class="label">Type</td>
                                    <td class="value">
                                        <?php
                                        echo "<select id='id_type' name='id_type'
                                                value='$trainer->id_type'>";
                                        
                                        genSelectOptions('pkmn_trainer_type', 'id', 'name'
                                                , $trainer->id_type, null, null
                                                , 'name');
                                        
                                        echo "</select>";
                                        ?>
                                    </td>
                                </tr>
                                <tr><td class="label">Name</td>
                                    <td class="value">
                                        <input type='text' id='name' name='name'
                                               value='<?php echo $trainer->name ?>' />
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