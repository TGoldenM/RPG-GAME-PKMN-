<?php
function login_check() {
    global $db;
    
    // Check if all session variables are set 
    if (isset($_SESSION['user_id'], $_SESSION['username']
            , $_SESSION['login_string'])) {
 
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        
        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
        
        $stmt = $db->prepare("SELECT password FROM system_user WHERE id = :id LIMIT 1");
        if ($stmt) {
            $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);  // Bind "$user_id" to parameter.
            $stmt->execute();    // Execute the prepared query.
            
            // get variables from result.
            $user = $stmt->fetch(PDO::FETCH_OBJ);
             
            if (count($user) === 1) {
                $password = $user->password;
                $login_check = hash('sha512', $password . $user_browser);
 
                if ($login_check === $login_string) {
                    // Logged In!!!! 
                    return true;
                } else {
                    // Not logged in 
                    return false;
                }
            } else {
                // Not logged in 
                return false;
            }
        } else {
            // Not logged in 
            return false;
        }
    } else {
        // Not logged in 
        return false;
    }
}

function sec_session_start() {
    $session_name = 'sec_session_id';   // Set a custom session name
    $secure = false;
    
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        error_log("Could not initiate a safe session (ini_set)");
        exit();
    }
    
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"], 
        $cookieParams["domain"], 
        $secure,
        $httponly);
    
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session 
}

/**
 * 
 * @global type $db
 * @param type $username
 * @param type $password
 * @return int 0 user do not exists, 1 user exists and password is correct,
 *      2 user exists and password is incorrect, 3 user is disabled
 *      and 4 user is banned
 */
function login($username, $password) {
    global $db;
    session_regenerate_id(true);// regenerated the session, delete the old one. 
    
    // Using prepared statements means that SQL injection is not possible.
    $stmt = $db->prepare("SELECT * FROM system_user WHERE username = :username LIMIT 1");
    if ($stmt) {
        $stmt->bindParam(":username", $username);  // Bind "$username" to parameter.
        $stmt->execute();    // Execute the prepared query.
        
        // get variables from result.
        $user = $stmt->fetch(PDO::FETCH_OBJ);
 
        // hash the password
        $password = hash('sha512', $password);
        
        if (count($user) == 1) {
            if($user->disabled)
                return 3;
            
            if($user->banned)
                return 4;
            
            // If the user exists we check if the account is locked
            // from too many login attempts 
 
//            if (checkbrute($user_id) == true) {
//                // Account is locked 
//                // Send an email to user saying their account is locked
//                return false;
//            } else {
                // Check if the password in the database matches
                // the password the user submitted.
                if ($user->password === $password) {
                    // Password is correct!
                    
                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    
                    // XSS protection as we might print this value
                    $user_id = preg_replace("/[^0-9]+/", "", $user->id);
                    $_SESSION['user_id'] = $user_id;
                    
                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", 
                        "", $user->username);
                    
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', 
                              $password . $user_browser);
                    
                    // Login successful.
                    return 1;
                } else {
                    // Password is not correct
                    // We record this attempt in the database
//                    $now = time();
//                    $mysqli->query("INSERT INTO login_attempts(user_id, time)
//                                    VALUES ('$user_id', '$now')");
                    return 2;
                }
            //}
        } else {
            // No user exists.
            return 0;
        }
    }
}

function logout(){
    unset($_SESSION['user_id']);
    unset($_SESSION['username']);
    unset($_SESSION['login_string']);
    session_regenerate_id(true); // regenerated the session, delete the old one. 
}

?>