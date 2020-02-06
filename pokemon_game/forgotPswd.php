<?php
require_once 'db_connection.php';
require_once './ui_support/alertbox.php';
$req_data = filter($_REQUEST);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Pokemon Cosmos RPG - Forgot Password</title>
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
                        <a href="login.php">Home</a>
                        <a href="">Terms of Services</a>
                        <a href="">Privacy Policy</a>
                        <a href="">Contact Us</a>
                    </div>
                </div>
                <div id="header_container" class="wrap">
                    <div id="header">
                        <div class="pad">
                            <div id="logo">
                                <span class="slogan">Gotta catch 'em all!</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="main_container" class="row">
                    <?php
                    if (isset($req_data['n']) && isset($req_data['t'])) {
                        $t = (int) $req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n, isset($req_data['c']) ? $req_data['c'] : null);
                    }
                    ?>
                    <div id="content_box" class="row">
                        <div class="top_box row"></div>
                        <div class="middle_box row">
                            <div class="pad">
                                <form id="formData" action="proc_data/proc_login.php" method="post">
                                    <table class="pkmn_form">
                                        <input type="hidden" id="action" name="action" value="mail"/>
                                        <tr><td class='label'>Username</td>
                                            <td class='value'><input type='text' id='username' name='username' value='' />
                                            </td>
                                        </tr>
                                        <tr><td class='label'></td>
                                            <td><input class='btnSubmit' id='btnOk' name='btnOk'
                                                       type='submit' value='Request password reset' />
                                                &nbsp;&nbsp;<a href="login.php">Return</a>
                                            </td></tr>
                                    </table>
                                </form>
                            </div>
                        </div>
                        <div class="bottom_box row"></div>
                    </div>
                    <div id="footer">
                        <span class="title">Pokemon Cosmos!</span>
                        All rights Reserved. 2016<br />
                        Pokemon and respective character names/images are trademarks and copyrights of their respective owners.
We are not affiliated with Nintendo, The Pokemon Company Creatures Inc. or Game Freak.
No copyright or trademark infringement is intended.
                    </div>
                    <div class="spacer"></div>
                </div>
            </div>
        </div>
    </body>
</html>