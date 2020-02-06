<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/region.php';
require_once './data_access/map.php';
require_once './ui_support/dropdown.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$is_new = !isset($req_data['id']);

//If some validation failed, read from session the input data
if (isset($_SESSION['mapObj'])) {
    $map = unserialize($_SESSION['mapObj']);
    unset($_SESSION['mapObj']);
} else {
    if ($is_new) {
        $map = (object) array(
                    "id" => null,
                    "id_region" => null,
                    "name" => '',
                    "image" => null
        );
    } else {
        $map = getMap($req_data['id']);
    }
}

if (isset($req_data['id_r'])) {
    $id_r = $req_data['id_r'];
} else {
    if(isset($map)){
        $id_r = $map->id_region;
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
        <script type="text/javascript" src="javascript/mapEdit.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header"><?php echo $is_new ? 'New' : 'Edit' ?> map</div>
                    <?php
                    if (isset($req_data['n']) && isset($req_data['t'])) {
                        $t = (int) $req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>

                    <div style="padding: 5px;">
                        <form id="formData" action="proc_data/proc_map.php"
                              method="post" enctype="multipart/form-data">
                            <?php
                            if (!$is_new) {
                                echo "<input type='hidden' id='id' name='id' value='$map->id' />";
                                echo "<input type='hidden' id='action' name='action' value='edit' />";
                            }
                            
                            echo "<input type='hidden' id='id_region' name='id_region'
                                    value='$id_r' />";
                            ?>

                            <table class="pkmn_form">
                                <tr>
                                    <td class="label">Name</td>
                                    <td class="value">
                                        <input type='text' id='name' name='name' value='<?php echo $map->name ?>' />
                                    </td>
                                </tr>
                                <?php
                                echo "<tr><td class='label'>Image:</td><td><input type='file' name='imgFile' id='imgFile' /></td></tr>";
                                if (!$is_new) {
                                    echo "<tr><td class='label'>Current Image:</td>
                                        <td><img src='proc_data/proc_map.php?action=img&id=$map->id&w=240&h=240' />
                                        </td></tr>";
                                }
                                ?>
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