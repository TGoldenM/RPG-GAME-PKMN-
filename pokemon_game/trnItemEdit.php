<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/system_role.php';
require_once './data_access/trainer.php';
require_once './data_access/trainer_pokemon.php';
require_once './data_access/trainer_item.php';
require_once './ui_support/dropdown.php';
require_once './ui_support/alertbox.php';
require_once './ui_support/listbox.php';

$req_data = filter($_REQUEST);
$is_new = !isset($req_data['id']);

$selMoves = array();

//If some validation failed, read from session the input data
if (isset($_SESSION['trItemObj'])) {
    $trnItem = unserialize($_SESSION['trItemObj']);
    unset($_SESSION['trItemObj']);
} else {
    if ($is_new) {
        $trnItem = (object) array(
            "item" => null,
            "trainer" => getCurrentTrainer()
        );
    } else {
        $trnItem = getTrainerItem($req_data['id'], PKMN_DEEP_LOAD);
    }
}

$curUserIsAdmin = curUserIsAdmin();
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
        
        <?php if($curUserIsAdmin): ?>
        <link href="css/autocpl.css" rel="stylesheet">
        <script type="text/javascript" src="jquery/jquery.autocomplete.min.js"></script>
        <?php endif;?>
        
        <script type="text/javascript" src="javascript/include.js"></script>
        <script type="text/javascript" src="javascript/trnItemEdit.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">Trainer pokemon</div>
                    <?php
                    if (isset($req_data['n']) && isset($req_data['t'])) {
                        $t = (int) $req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>

                    <div style="padding: 5px;">
                        <form id="formData" action="proc_data/proc_trainer_item.php"
                              method="post">
                            <?php
                            if (!$is_new) {
                                echo "<input type='hidden' id='id' name='id' value='$trnItem->id' />";
                                echo "<input type='hidden' id='action' name='action' value='edit' />";
                            }
                            
                            echo "<input type='hidden' id='id_trainer' name='id_trainer'
                                    value='".$trnItem->trainer->id."' />";
                            echo "<input type='hidden' id='id_item' name='id_item'
                                    value='".(isset($trnItem->item) ? $trnItem->item->id : '')."'/>";
                            ?>
                            
                            <table class="pkmn_form">
                                <?php if ($curUserIsAdmin) : ?>
                                <tr><td class='label'>Item</td>
                                    <td class="value">
                                        <input type="text" id="itemName" name="itemName"
                                               value="<?php echo (isset($trnItem->item) ? $trnItem->item->name : '')?>"/>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                
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