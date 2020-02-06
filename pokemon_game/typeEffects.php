<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './data_access/pokemon_type.php';
require_once './ui_support/alertbox.php';
require_once './ui_support/dropdown.php';

$req_data = filter($_REQUEST);
$type = getPokemonType($req_data['id']);
$list = getTypeEffectsList($req_data['id'], true);
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
        <script type="text/javascript" src="javascript/typeEffects.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">
                        Editing attack effects of '<?php echo $type->name ?>' against other types
                    </div>
                    <?php
                        if(isset($req_data['n']) && isset($req_data['t'])){
                            $t = (int)$req_data['t'];
                            $n = $req_data['n'];
                            createAlertBox($t, $n);
                        }
                    ?>
                    
                    <div style="padding: 5px;">
                        <form id="formData" action="proc_data/proc_type_effect.php"
                              method="post">
                            <input type='hidden' id='action'
                                   name='action' value='edit' />
                            
                            <?php if (count($list) > 0) :?>
                            <input type='hidden' id='type_id'
                                   name='type_id'
                                   value='<?php echo $list[0]->id_type_one ?>' />
                            
                            <table style="width: 100%;" class="data_table">
                                <thead>
                                    <tr class="header">
                                        <td class="colWidth50">Name</td>
                                        <td class="colWidth50">Multiplier</td>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($list as $obj) {
                                    $type2 = $obj->type_two;
                                    $idm = "multiplier[$type2->id]";
                                    
                                    echo "<tr><td>".$type2->name;
                                    echo "<td><input class='number' type='text'
                                        id='$type2->name' name='$idm'
                                        value='$obj->multiplier' /></td></tr>";
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                    <tr><td colspan="2" class="label">
                                        <input class='btnSubmit' id='btnOk' name='btnOk'
                                            type='submit' value='OK' />
                                        <input class='btnSubmit cancel' id='btnRet' name='btnRet'
                                            type='submit' value='Return'/>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer -->
        <?php include './footer.php' ?>
    </body>
</html>