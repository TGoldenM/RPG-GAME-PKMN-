<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './data_access/pokemon_move.php';
require_once './ui_support/dropdown.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$is_new = !isset($req_data['id']);

//If some validation failed, read from session the input data
if (isset($_SESSION['moveObj'])) {
    $move = unserialize($_SESSION['moveObj']);
    unset($_SESSION['moveObj']);
} else {
    if ($is_new) {
        $move = (object) array(
                    "id" => null,
                    "type_nat" => null,
                    "category" => null,
                    "name" => "",
                    "value" => null,
                    "accuracy" => null,
                    "power_points" => null
        );
    } else {
        $move = getPokemonMove($req_data['id']);
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
        <script type="text/javascript" src="javascript/moveEdit.js"></script>
    </head>

    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header"><?php echo $is_new ? 'New' : 'Edit' ?> move</div>
                    <?php
                    if (isset($req_data['n']) && isset($req_data['t'])) {
                        $t = (int) $req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n);
                    }
                    ?>

                    <div style="padding: 5px;">
                        <form id="formData" action="proc_data/proc_pokemon_move.php"
                              method="post">
                                  <?php
                                  if (!$is_new) {
                                      echo "<input type='hidden' id='id' name='id' value='$move->id' />";
                                      echo "<input type='hidden' id='action' name='action' value='edit' />";
                                  }
                                  ?>

                            <table class="pkmn_form">
                                <tr>
                                    <td class="label">Type nature</td>
                                    <td class="value">
                                        <?php
                                        echo "<select id='type_nat' name='type_nat' value='$move->type_nat'>";
                                        genSelectOptions("pkmn_pokemon_type", "id", "name", $move->type_nat);
                                        echo "</select>";
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">Category</td>
                                    <td class="value">
                                        <?php
                                        echo "<select id='category' name='category' value='$move->category'>";
                                        genSelectOptions("pkmn_move_category", "id", "name", $move->category);
                                        echo "</select>";
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">Name</td>
                                    <td class="value">
                                        <input type='text' id='name' name='name'
                                               value='<?php echo $move->name ?>' />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">Power</td>
                                    <td class="value">
                                        <input class='number' type='text' id='value' name='value'
                                               value='<?php echo $move->value ?>' />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">Accuracy</td>
                                    <td class="value">
                                        <input class='number' type='text' id='accuracy' name='accuracy'
                                               value='<?php echo $move->accuracy ?>' />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">Power Points (empty or zero for unlimited)</td>
                                    <td class="value">
                                        <input class='number' type='text' id='power_points' name='power_points'
                                               value='<?php echo $move->power_points ?>' />
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