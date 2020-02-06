<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './data_access/trainer.php';
require_once './data_access/news.php';
require_once './data_access/system_property.php';
require_once './ui_support/news.php';

$usr = getCurrentUser();
$curTrn = getCurrentTrainer();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Pokemon CosmosRPG - Members Area</title>
        <link href="css/style.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet'>
        <link href="images/layout/favicon.png" rel="shortcut icon">
        <script type="text/javascript" src="jquery/jquery-1.11.2.min.js"></script>
    </head>
    <body>
        <div class="wrap">


            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">

                    <div class="header">Create a Clan</div>

                    <?php
                    if (isset($req_data['n']) && isset($req_data['t'])) {
                        $t = (int) $req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>

                    <center>
                        <?php
                        $_POST['name'] = $_POST['name'];

                        if ($_POST['create'] != "") { //If they tried to create a clan
                            $curTrnEx = getTrainerByUserId($usr->id, PKMN_SIMPLE_LOAD);

                            $error .= ($curTrnEx->silver < 100) ? "You don't have enough silver to start a clan. You need at least 300 silver.<br>" : "";
                            $error .= ($curTrnEx->gold < 100) ? "You don't have enough gold to start a clan. You need at least 100 silver.<br>" : "";
                            $error .= (strlen($_POST['name']) < 4) ? "Your clan's name has to be at least 4 characters long.<br>" : "";
                            $error .= (strlen($_POST['name']) > 20) ? "Your clan's name can only be a max of 20 characters long.<br>" : "";

                            if (!preg_match('~^[a-z0-9 ]+$~i', $_POST['name'])) {
                                $error .= "Special characters in your clan name isn't allowed.<br>";
                            }


                            if ($error == "") { // if there are no errors, make the clan
                                $stmt = $db->prepare("INSERT INTO `pkmn_clan`(owner, owner_id, name) VALUES('$usr->username', '$usr->id', '" . $_POST['name'] . "')");
                                $stmt->execute(array($owner, $owner_id, $name));

                                echo '<div class="alert-box alert-box-success">You have successfully created your clan!</div>';
                            } else {
                                if ($error != "") {
                                    echo '<div class="alert-box alert-box-error">' . $error . '</div>';
                                }
                            }

                            if ($error == "") { //if no errors take money away
                                //new data
                                $silvernew = $curTrnEx->silver - 100;
                                $goldnew = $curTrnEx->gold - 100;

                                $stmt = $db->prepare("UPDATE pkmn_trainer SET silver ='$silvernew' WHERE id = '$usr->id'");
                                $stmt->execute();
                                
                                $stmt = $db->prepare("UPDATE pkmn_trainer SET gold ='$goldnew' WHERE id = '$usr->id'");
                                $stmt->execute();
                                }
                                }
                                ?>

                                <br><br>
                                <div style="color:white;">
                                    <div style="text-align:center;">Creating a clan will cost you 300 Silver and 100 Gold. If you don't have this yet, then save up.</div>
                                    <br>

                                    <div class="user-info">
                                        If you don't have enough, you can also join another clan. Just head over to the <a class="settings" href="clanList.php">Clan List</a> page to see a full list of available clans to join.
                                    </div>
                                    <form id='formData' method='post' action='createClan.php'>
                                        <table border='0' cellpadding='0' cellspacing='0'  style="margin: 10px auto;">
                                            <tr> 
                                                <td width='35%' height='27'>Clan Name: &nbsp; </td>
                                                <td width='65%'>
                                                    <input name='name' type='text' size='22'>
                                                </td>
                                            </tr>
                                            <tr> 
                                                <td>&nbsp;</td>
                                                <td>
                                                    <br>
                                                    <input type='submit' name='create' id='btnOk' value='Create' class='btnSubmit'>
                                                </td>
                                            </tr>
                                        </table>
                                </div>


                                </form></center>
                            <div style="padding: 5px;">
                                <div class="pkmn_ctrl">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- footer -->
                <?php
                         include './footer.php' ?>
    </body>
</html>

