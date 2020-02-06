<?php
require_once 'db_connection.php';
require_once './data_access/user.php';
require_once './ui_support/loginContent.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$user = getSystemUser($req_data['u']);
$tmpHash = hash('sha512', $req_data['p']);
//$tmpHash = '3b82067224efb9247b332acb3a819262a0791d5a04343096b7690d0fcdb8dc120fb26e8b1600f9cf8b05daa3f43555c24c2d5ae92c55e340b9b9f554e1c1585d';

if ($user->tmp_password != $tmpHash) {
    header("Location: login.php?t=0&n=pswd_reset_error");
    exit();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Pokemon Core - The Game</title>
        <link type="text/css" rel="stylesheet" href="css/login.css"  />
        <script type="text/javascript" src="jquery/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="jquery/jquery.validate.min.js"></script>
        <script type="text/javascript" src="javascript/password_reset.js"></script>
    </head>
    <body>
        <div id="outer" class="wrap">
            <div id="inner" class="wrap">
                <div id="top_bar" class="wrap">
                    <div class="pad">
                        <a href="login.php">Home</a>
                        <a href="accountCreate.php">Create Account</a>
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
                                <form id="frmReset" action="proc_data/proc_user.php" method="post">
                                    <input type="hidden" id="id" name="id" value="<?php echo $user->id ?>"/>
                                    <input type="hidden" id="action" name="action" value="rstpwd"/>
                                    <input type="hidden" id="h" name="h" value="<?php echo $tmpHash?>"/>
                                    
                                    <table>
                                        <tr><td>
                                                <input type="password" class="login_input" id="password" name="password"
                                                       value="" placeholder="Password" />
                                            </td>
                                        </tr>
                                        <tr><td>
                                                <input type="password" class="login_input" id="cpassword" name="cpassword"
                                                       value="" placeholder="Confirm Password" />
                                            </td>
                                        </tr>
                                    </table>

                                    <input type="submit" class="go_btn" value="Save and continue" />
                                </form>
                                <div class="spacer"></div>
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