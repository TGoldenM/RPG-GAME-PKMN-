<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './data_access/trainer.php';
require_once './ui_support/alertbox.php';
require_once './ui_support/checkbox.php';
require_once './ui_support/dropdown.php';

$req_data = filter($_REQUEST);
$is_new = !isset($req_data['id']);
$curUserIsAdmin = curUserIsAdmin();

//If some validation failed, read from session the input data
if (isset($_SESSION['userObj'])) {
    $user = unserialize($_SESSION['userObj']);
    unset($_SESSION['userObj']);
} else {
    if ($is_new) {
        $user = (object) array(
                    "id" => null,
                    "logged" => null,
                    "banned" => null,
                    "disabled" => null,
                    "username" => null,
                    "password" => null,
                    "mail" => null,
                    "gender" => null,
                    "roles" => array()
        );

        $faction = 0;
    } else {
        $user = getSystemUser($req_data['id'], true);
        $tr = getTrainerByUserId($req_data['id']);
        $faction = $tr->id_faction;
    }
}

$simple_opts = array('No', 'Yes');
$gender_opts = array('M' => 'Male', 'F' => 'Female');
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
        <script type="text/javascript" src="javascript/userEdit.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header"><?php echo $is_new ? 'New' : 'Edit' ?> user</div>
                    <?php
                        if(isset($req_data['n']) && isset($req_data['t'])){
                            $t = (int)$req_data['t'];
                            $n = $req_data['n'];
                            createAlertBox($t, $n);
                        }
                    ?>
                    
                    <form id="formData" action="proc_data/proc_user.php" method="post"
                          enctype="multipart/form-data">
                        <?php
                        if(!$is_new){
                            echo "<input type='hidden' id='id' name='id' value='$user->id' />";
                            echo "<input type='hidden' id='action' name='action' value='edit' />";
                        }
                        ?>
                        
                        <table class="pkmn_form">
                            <?php
                            if($curUserIsAdmin){
                                echo "<tr><td class='label'>User name</td><td class='value'><input type='text' id='username' name='username' value='$user->username' /></td></tr>";
                            } else {
                                echo "<tr><td class='label'>User name</td><td class='value'>$user->username
                                    <input type='hidden' id='username' name='username' value='$user->username' />
                                    </td></tr>";
                            }
                            
                            echo "<tr><td class='label'>Password</td><td class='value'><input type='password' id='password' name='password' value='' /></td></tr>";
                            echo "<tr><td class='label'>Email</td><td class='value'><input type='text' id='mail' name='mail' value='$user->mail' /></td></tr>";

                            if (!$is_new && $curUserIsAdmin) {
                                echo "<tr><td class='label'>Is banned?</td>
                                     <td class='value'><select id='banned' name='banned'
                                     value='$user->banned'>";
                                
                                genSelectOptionsArr($simple_opts, $user->banned);
                                
                                echo "</select></td></tr>";
                            }

                            if($curUserIsAdmin){
                                echo "<tr><td class='label'>Is disabled?</td>
                                    <td class='value'><select id='disabled' name='disabled'
                                    value='$user->disabled'>";
                            
                                genSelectOptionsArr($simple_opts, $user->disabled);

                                echo "</select></td></tr>";
                            }
                            
                            echo "<tr><td class='label'>Avatar</td><td><input type='file' name='avatarFile' id='avatarFile' /></td></tr>";
                            if (!$is_new) {
                                echo "<tr><td class='label'>Current Avatar</td><td><img src='proc_data/proc_user.php?action=img&id=$user->id' /></td></tr>";
                            }
                            
                            echo "<tr><td class='label'>Signature</td><td><input type='file' name='signFile' id='signFile' /></td></tr>";
                            if (!$is_new) {
                                echo "<tr><td class='label'>Current signature</td><td><img src='proc_data/proc_user.php?action=sgnt&id=$user->id' /></td></tr>";
                            }
                            
                            echo "<tr><td class='label'>Gender</td>
                                    <td class='value'><select id='gender' name='gender'
                                    value='$user->gender'>";
                            
                            genSelectOptionsArr($gender_opts, $user->gender);
                            
                            echo "</select></td></tr>";
                            
                            if ($curUserIsAdmin) {
                                $rolesSel = array();
                                foreach($user->roles as $r){
                                    $rolesSel[] = $r->id;
                                }

                                echo "<tr><td class='label'>Roles</td>";
                                echo "<td class='value'>";
                                createDbCheckboxList('roles', 'system_role', 'id', 'name', $rolesSel);
                                echo "</td></tr>";
                            }
                            
                            echo "<tr><td class='label'>Faction</td>";
                            echo "<td class='value'><select id='id_faction' name='id_faction' value='$faction'>";
                            echo "<option value='0'>--No faction--</option>";
                            genSelectOptions('pkmn_faction', 'id', 'name', $faction, null, "visible = 1");
                            echo "</select></td></tr>";
                                
                            echo "<tr><td class='label'></td>"
                                . "<td><input class='btnSubmit' id='btnOk' name='btnOk'"
                                . " type='submit' value='OK' />"
                                . "<input class='btnSubmit cancel' id='btnRet' name='btnRet'"
                                . " type='submit' value='Return'/>"
                                . "</td></tr>";
                            ?>
                        </table>
                    </form>
                </div>
            </div>
        </div>

        <!-- footer -->
        <?php include './footer.php' ?>
    </body>
</html>