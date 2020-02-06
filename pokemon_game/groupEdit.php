<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './data_access/pokemon_group.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$is_new = !isset($req_data['id']);

//If some validation failed, read from session the input data
if(isset($_SESSION['groupObj'])){
    $group = unserialize($_SESSION['groupObj']);
    unset($_SESSION['groupObj']);
} else {
    if($is_new){
        $group = (object)array(
            "id" => null,
            "name" => null
        );
    } else {
        $group = getPokemonGroup($req_data['id']);
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
        <script type="text/javascript" src="javascript/groupEdit.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">News</div>
                    <?php
                        if(isset($req_data['n']) && isset($req_data['t'])){
                            $t = (int)$req_data['t'];
                            $n = $req_data['n'];
                            createAlertBox($t, $n);
                        }
                    ?>
                    
                    <div style="padding: 5px;">
                        <form id="formData" action="proc_data/proc_pokemon_group.php"
                              method="post">
                            <?php
                            if(!$is_new){
                                echo "<input type='hidden' id='id' name='id' value='$group->id' />";
                                echo "<input type='hidden' id='action' name='action' value='edit' />";
                            }
                            ?>

                            <table class="pkmn_form">
                                <tr>
                                    <td class="label">Name</td>
                                    <td class="value">
                                        <input type='text' id='name' name='name'
                                               value='<?php echo $group->name ?>' />
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