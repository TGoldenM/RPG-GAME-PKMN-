<?php
require_once 'db_connection.php';
require_once './ui_support/alertbox.php';
require_once './ui_support/checkbox.php';
require_once './ui_support/dropdown.php';

$req_data = filter($_REQUEST);

//If some validation failed, read from session the input data
if (isset($_SESSION['userObj'])) {
    $user = unserialize($_SESSION['userObj']);
    unset($_SESSION['userObj']);
} else {
    $user = (object)array(
        "id" => null,
        "logged" => 0,
        "banned" => 0,
        "disabled" => 0,
        "username" => "",
        "password" => "",
        "mail" => "",
        "gender" => 'M',
        "roles" => array()
    );
}

$gender_opts = array('M' => 'Male', 'F' => 'Female');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Pokemon Core - The Game</title>
        <link type="text/css" rel="stylesheet" href="css/login.css"  />
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script type="text/javascript" src="jquery/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="jquery/jquery.validate.min.js"></script>
        <script type="text/javascript" src="javascript/userEdit.js"></script>
    </head>
    <body>
        <div id="outer" class="wrap">
            <div id="inner" class="wrap">
                <div id="top_bar" class="wrap">
                    <div class="pad">
                        <a href="">Home</a>
                        <a href="">Terms of Services</a>
                        <a href="">Privacy Policy</a>
                        <a href="">Contact Us</a>
                    </div>
                </div>
                <div id="header_container" class="wrap">
                    <div id="header">
                        <div class="pad">
                            <div id="logo">
                                <span class="slogan">This will be your slogan</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="main_container" class="row">
                    <?php
                    if (isset($req_data['n']) && isset($req_data['t'])) {
                        $t = (int) $req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n, isset($req_data['u']) ? $req_data['u'] : null);
                    }
                    ?>
                    <div id="content_box" class="row">
                        <div class="top_box row"></div>
                        <div class="middle_box row">
                            <div class="pad">
                                <form id="formData" action="proc_data/proc_register.php" method="post"
                                      enctype="multipart/form-data">
                                    <table class="pkmn_form">
                                        <?php
                                        echo "<tr><td class='label'>User name</td><td class='value'><input type='text' id='username' name='username' value='$user->username' /></td></tr>";
                                        echo "<tr><td class='label'>Password</td><td class='value'><input type='password' id='password' name='password' value='' /></td></tr>";
                                        echo "<tr><td class='label'>Email</td><td class='value'><input type='text' id='mail' name='mail' value='$user->mail' /></td></tr>";

                                        echo "<tr><td class='label'>Gender</td>
                                            <td class='value'><select id='gender' name='gender'
                                            value='$user->gender'>";
                                        genSelectOptionsArr($gender_opts, $user->gender);
                                        echo "</select></td></tr>";
                                        
                                        echo "<tr><td class='label'>Choose your initial pokemon</td>
                                            <td class='value'><select id='initial' name='initial'>";
                                        genSelectOptions("pkmn_pokemon", "id", "name", null, null, "initial = 1");
                                        echo "</select></td></tr>";
                                        
                                        echo "<tr><td class='label'>Captcha</td>
                                            <td><div class='g-recaptcha' data-sitekey='6LdZIBsTAAAAAEcz272KgyX_BAf-jVSXQrOCIAPn'></div>
                                            </td></tr>";
                                        
                                        echo "<tr><td class='label'></td>
                                            <td><input class='btnSubmit' id='btnOk' name='btnOk'
                                             type='submit' value='OK' />
                                            <input class='btnSubmit cancel' id='btnRet' name='btnRet'
                                             type='submit' value='Return'/>
                                            </td></tr>";
                                        ?>
                                    </table>
                                </form>
                            </div>
                        </div>
                        <div class="bottom_box row"></div>
                    </div>
                    <div id="footer">
                        <span class="title">Pokemon Core!</span>
                        All rights Reserved. 2014+<br />
                        All images are copyrighted and are protected under law.
                    </div>
                    <div id="character"></div>
                    <div class="spacer"></div>
                </div>
            </div>
        </div>
    </body>
</html>