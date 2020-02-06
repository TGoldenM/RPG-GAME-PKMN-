<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/user.php';
require_once './data_access/trainer.php';
require_once './data_access/news.php';
require_once './ui_support/alertbox.php';
require_once './ui_support/checkbox.php';
require_once './ui_support/dropdown.php';

$req_data = filter($_REQUEST);
$is_new = !isset($req_data['id']);

//If some validation failed, read from session the input data
if(isset($_SESSION['newsObj'])){
    $news = unserialize($_SESSION['newsObj']);
    unset($_SESSION['newsObj']);
} else {
    if($is_new){
        $news = (object)array(
            "id" => null,
            "id_trainer" => getCurrentTrainer()->id,
            "title" => null,
            "created" => null,
            "content" => null
        );
    } else {
        $news = getNews($req_data['id']);
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
        <script type="text/javascript" src="javascript/tinymce/tinymce.min.js"></script>
        <script type="text/javascript" src="javascript/tinymce/jquery.tinymce.min.js"></script>
        <script type="text/javascript" src="javascript/newsEdit.js"></script>
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
                        <form id="formData" action="proc_data/proc_news.php"
                              method="post">
                            <?php
                            if(!$is_new){
                                echo "<input type='hidden' id='id' name='id' value='$news->id' />";
                                echo "<input type='hidden' id='action' name='action' value='edit' />";
                            }

                            echo "<input type='hidden' id='id_tr' name='id_tr' value='$news->id_trainer' />";
                            ?>

                            <div class="pkmn_form">
                                <div style="margin-bottom: 25px;">
                                    <input class='btnSubmit' id='btnOk' name='btnOk'
                                        type='submit' value='OK' />
                                    <input class='btnSubmit cancel' id='btnRet' name='btnRet'
                                        type='submit' value='Return'/><br/>
                                </div>
                                
                                <?php
                                
                                echo "<div style='margin-bottom: 25px;'>"
                                    . "Title:&nbsp;<input type='text' id='title' name='title' value='$news->title' />"
                                    . "</div>";
                                
                                echo "Content<br/>";
                                echo "<textarea class='value' id='mce_editor' name='mce_editor'>"
                                    . (!empty($news->content) ? stripslashes($news->content) : "") ."</textarea>";
                                ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer -->
        <?php include './footer.php' ?>
    </body>
</html>