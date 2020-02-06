<?php
require_once '../db_connection.php';
require_once '../data_access/user.php';
require_once '../util/url_functions.php';
require_once '../lib/SwiftMailer/swift_required.php';

$req_data = filter($_REQUEST);

if (!isset($req_data['action'])) {
    if (isset($req_data['username'], $req_data['password'])) {
        $username = $req_data['username'];
        $password = $req_data['password'];

        $res = login($username, $password);

        switch ($res) {
            case 0: //User does not exists
            case 2: //User exists and password is incorrect
            case 3: //User was disabled
                // Login failed 
                header('Location: ../login.php?t=0&n=login_failed');
                break;

            case 1:
                // Login success
                $user = getSystemUserByName($username);
                $user->last_updated = date("Y-m-d H:i:s");
                updateSystemUser($user);

                header('Location: ../main.php');
                break;

            case 4: //User was banned
                header('Location: ../login.php?t=0&n=user_banned&u=' . $username);
                break;
        }
    } else {
        // The correct POST variables were not sent to this page. 
        echo 'Invalid Request';
    }
} else {
    switch ($req_data['action']) {
        case 'logout':
            $user = getSystemUserByName($_SESSION['username']);
            $user->last_updated = null;
            updateSystemUser($user);

            logout();
            header('Location: ../login.php');
            break;

        case 'mail':
            $username = $req_data['username'];
            if (!checkIfUserExists($username)) {
                header("Location: ../forgotPswd.php?t=0&n=user_not_exists&c=" . $username);
                exit();
            }

            $user = getSystemUserByName($username);
            $emailCut = substr($user->mail, 0, 4);
            $randNum = rand(10000, 99999);
            $tmpPswd = $emailCut . $randNum;
            $tmpHash = hash('sha512', $tmpPswd);
            $user->tmp_password = $tmpHash;
            updateSystemUser($user);

            $from = array("cosmosrpg@hotmail.com" => "Cosmos RPG");
            $to = array($user->mail => $user->username);

            $headers = "From: $from\n";
            $headers .= "MIME-Version: 1.0\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1 \n";
            $subject = "CosmosRPG - Password reset";

            $msg = '<h2>Hello ' . $username . '</h2><p>This is an automated message from CosmosRPG.'
                    . ' If you did not recently initiate the Forgot Password process, please'
                    . ' disregard this email.</p><p>You indicated that you forgot your login'
                    . ' password.</p>'
                    . '<p>Please click the link below to initiate password reset<br />'
                    . '</p><p><a href="' . getBaseURL() . '/pokemon_game/password_reset.php?u=' . $user->id
                    . '&p=' . $tmpPswd . '">Reset password</a></p>'
                    . '<p>If you do not click the link in this email,'
                    . ' no changes will be made to your account. In order to reset your password'
                    . ' you must click the link above.</p>';

            $transport = Swift_SmtpTransport::newInstance('smtp.live.com', 587, 'tls');
            $transport->setUsername('cosmosrpg@hotmail.com');
            $transport->setPassword('freelancer12!');
            $swift = Swift_Mailer::newInstance($transport);

            $message = new Swift_Message($subject);
            $message->setFrom($from);
            $message->setBody($msg, 'text/html');
            $message->setTo($to);

            if ($recipients = $swift->send($message, $failures)) {
                header("Location: ../login.php?t=1&n=pswd_mail_reset_ok");
            } else {
                header("Location: ../forgotPswd.php?t=0&n=pswd_mail_reset_error");
            }
//            if (mail($to, $subject, $msg, $headers)) {
//                header("Location: ../login.php?t=1&n=pswd_mail_reset_ok");
//            } else {
//                header("Location: ../forgotPswd.php?t=0&n=pswd_mail_reset_error");
//            }
            break;
    }
}
?>