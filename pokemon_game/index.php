<?php
require_once 'db_connection.php';
require_once './data_access/user.php';
require_once './ui_support/loginContent.php';
require_once './ui_support/alertbox.php';

$req_data = filter($_REQUEST);
$usrsOnline = getSystemUsersCount(true);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Pokemon Core - The Game</title>
        <link type="text/css" rel="stylesheet" href="css/login.css"  />
    </head>
    <body>
        <div id="outer" class="wrap">
            <div id="inner" class="wrap">
                <div id="top_bar" class="wrap">
                    <div class="pad">
                        <a href="accountCreate.php">Create Account</a>
                        <a href="forgotPswd.php">Forgot Password?</a>
                        <!-- a href="">Terms of Services</a -->
                        <!-- a href="">Privacy Policy</a -->
                        <!-- a href="">Contact Us</a -->
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
                    <div id="players_online_box">
                        <?php echo genNumberOfPlyrsText($usrsOnline) ?> online
                    </div>
                    <?php
                    if (isset($req_data['n']) && isset($req_data['t'])) {
                        $t = (int) $req_data['t'];
                        $n = $req_data['n'];
                        createAlertBox($t, $n, isset($req_data['u']) ? $req_data['u'] : null);
                    }
                    ?>
                    <div id="content_box" class="row">
                        <div class="login_panel row">
                            <div class="pad">
                                <form action="proc_data/proc_login.php" method="post">
                                    <input type="text" class="login_input" name="username"
                                           value="" placeholder="Username" />
                                    <input type="password" class="login_input" name="password"
                                           value="" placeholder="Password" />
                                    <input type="submit" class="go_btn" value="" />
                                </form>
                                <div class="spacer"></div>
                            </div>
                        </div>
                        <div class="top_box row"></div>
                        <div class="middle_box row">
                            <div class="pad">
                                <h2>Welcome to Pokemon Core!</h2>
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 
                                1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but 
                                also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets 
                                containing Lorem Ipsum passages.
                            </div>
                        </div>
                        <div class="bottom_box row">
                            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                            <!-- PokemonRPG -->
                            <ins class="adsbygoogle"
                                 style="display:inline-block;width:728px;height:90px"
                                 data-ad-client="ca-pub-0917954362221668"
                                 data-ad-slot="9325226064"></ins>
                            <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                            </script>

                        </div>
                    </div>
                    <div id="footer">
                        <span class="title">Pokemon Core!</span>
                        All rights Reserved. 2014+<br />
                        All images are copyrighted and are protected under law.
                        <div id="social">
                            <a href="" class="facebook"></a>
                            <a href="" class="twitter"></a>
                            <a href="" class="rss"></a>
                            <div class="spacer"></div>
                        </div>
                    </div>
                    <div id="character"></div>
                    <div class="spacer"></div>
                </div>
            </div>
        </div>
    </body>
</html>