<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './data_access/faction.php';
require_once './ui_support/alertbox.php';
require_once './ui_support/dropdown.php';

$req_data = filter($_REQUEST);
$is_new = !isset($req_data['id']);

//If some validation failed, read from session the input data
if(isset($_SESSION['factionObj'])){
    $faction = unserialize($_SESSION['factionObj']);
    unset($_SESSION['factionObj']);
} else {
    if($is_new){
        $faction = (object)array(
            "id" => null,
            "visible" => 1,
            "name" => null
        );
    } else {
        $faction = getFaction($req_data['id']);
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
        <script type="text/javascript" src="javascript/factionEdit.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header"><?php echo $is_new ? 'New' : 'Edit' ?> faction</div>
                    <?php
                        if(isset($req_data['n']) && isset($req_data['t'])){
                            $t = (int)$req_data['t'];
                            $n = $req_data['n'];
                            createAlertBox($t, $n);
                        }
                    ?>
                    
                    <div style="padding: 5px;">
                        <form id="formData" action="proc_data/proc_faction.php"
                              method="post">
                            <?php
                            if(!$is_new){
                                echo "<input type='hidden' id='id' name='id' value='$faction->id' />";
                                echo "<input type='hidden' id='action' name='action' value='edit' />";
                            }
                            ?>

                            <table class="pkmn_form">
                                <tr>
                                    <td class="label">Visible?</td>
                                    <td class="value">
                                        <?php
                                        echo "<select id='visible' name='visible' value='$faction->visible'>";
                                        genSelectOptionsArr($simple_opts, $faction->visible);
                                        echo "</select>";
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">Name</td>
                                    <td class="value">
                                        <input type='text' id='name' name='name'
                                               value='<?php echo $faction->name ?>' />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">Points</td>
                                    <td class="value">
                                        <input type='text' class='number_large'
                                               id='name' name='name'
                                               value='<?php echo $faction->points ?>' />
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