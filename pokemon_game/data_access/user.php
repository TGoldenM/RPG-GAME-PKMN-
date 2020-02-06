<?php

defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH . 'data_access/system_role.php';
require_once ROOTPATH . 'lib/Zebra_Pagination.php';
require_once ROOTPATH . 'util/url_functions.php';

function getSystemUser($uId, $loadRoles = false) {
    global $db;
    $obj = null;

    if (!is_null($uId)) {
        $stmt = $db->query("select * from system_user where id = " . $uId);
        $res = $stmt->fetch(PDO::FETCH_OBJ);

        if ($res) {
            $obj = $res;
            if ($loadRoles) {
                $obj->roles = getAssignedRoles($obj->id);
            }
        }
    }

    return $obj;
}

function getSystemUserByName($username) {
    global $db;
    $obj = null;

    if (!is_null($username)) {
        $stmt = $db->query("select * from system_user where username = '$username'");
        $obj = $stmt->fetch(PDO::FETCH_OBJ);
    }

    return $obj;
}

function getSystemUserByEmail($email) {
    global $db;
    $obj = null;

    if (!is_null($email)) {
        $stmt = $db->query("select * from system_user where mail = '$email'");
        $obj = $stmt->fetch(PDO::FETCH_OBJ);
    }

    return $obj;
}

function checkIfUserExists($username) {
    global $db;

    if (!is_null($username)) {
        $stmt = $db->prepare("select count(*) from system_user"
                . " where username = :username");

        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $res = $stmt->fetchColumn();
        return $res > 0;
    }

    return false;
}

function getSystemUsersList($createImgUrls = true, $loadRoles = false, $orderBy = null, $rows = null) {
    global $db;
    $objs = array();

    $q = "select * from system_user";

    if (!is_null($orderBy)) {
        $q .= " order by $orderBy";
    }

    if (!is_null($rows)) {
        $q .= " limit $rows";
    }

    $stmt = $db->query($q);
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($res) {
        $objs = $res;
        foreach ($objs as $obj) {
            if ($createImgUrls) {
                $obj->img_avatar_url = buildUsrImgURL($obj->id);
            }

            if ($loadRoles) {
                $obj->roles = getAssignedRoles($obj->id);
            }
        }
    }

    return $objs;
}

function getPgSystemUsersList(&$res, $navPos, $records_per_page = 10,
        $name = null, $createImgUrls = true, $loadRoles = false) {
    global $db;
    $pg = new Zebra_Pagination();
    $pg->navigation_position($navPos);

    $q = "select SQL_CALC_FOUND_ROWS * from system_user";
    
    if(!is_null($name)){
        $q .= " where username like '$name'";
    }
    
    $q .= " limit " . (($pg->get_page() - 1) * $records_per_page)
            . ", " . $records_per_page;

    $stmt = $db->query($q);
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach ($res as $obj) {
        if ($createImgUrls) {
            $obj->img_avatar_url = buildUsrImgURL($obj->id);
        }

        if ($loadRoles) {
            $obj->roles = getAssignedRoles($obj->id);
        }
    }

    $stmt = $db->query("select FOUND_ROWS() as rows");
    $numRows = $stmt->fetchColumn();

    $pg->records($numRows);
    $pg->records_per_page($records_per_page);
    return $pg;
}

function getSystemUsersCount($cntOnline = null) {
    global $db;
    $q = "select count(id) from system_user";

    if (!is_null($cntOnline)) {
        if($cntOnline){
            $sym = ">"; //Online users
        } else {
            $sym = "<"; //Offline users
        }
            
        $q .= " where date_add(last_updated, interval 5 minute) $sym now()";
    }

    return $db->query($q)->fetchColumn();
}

function getCurrentUser($loadRoles = false) {
    $uId = $_SESSION['user_id'];
    return getSystemUser($uId, $loadRoles);
}

function insertSystemUser(&$user) {
    global $db;

    $stmt = $db->prepare("INSERT INTO system_user (username, password, tmp_password
            , mail, last_updated, banned, disabled, gender) VALUES (:username
            , :password, :tmp_password, :mail, :last_updated
            , :banned, :disabled, :gender)");

    $banned = is_null($user->banned) ? 0 : $user->banned;
    $disabled = is_null($user->disabled) ? 0 : $user->disabled;

    $stmt->bindParam(":username", $user->username);
    $stmt->bindParam(":password", $user->password);
    $stmt->bindParam(":tmp_password", $user->tmp_password);
    $stmt->bindParam(":mail", $user->mail);
    $stmt->bindParam(":last_updated", $user->last_updated);
    $stmt->bindParam(":banned", $banned, PDO::PARAM_BOOL);
    $stmt->bindParam(":disabled", $disabled, PDO::PARAM_BOOL);
    $stmt->bindParam(":gender", $user->gender, PDO::PARAM_INT);

    $stmt->execute();
    $user->id = $db->lastInsertId();
    return $user->id;
}

function updateSystemUser($user) {
    global $db;

    $stmt = $db->prepare("UPDATE system_user set
            username = :username
            , password = :password
            , tmp_password = :tmp_password
            , mail = :mail
            , last_updated = :last_updated
            , banned = :banned
            , disabled = :disabled
            , gender = :gender
            where id = :id");

    $banned = is_null($user->banned) ? 0 : $user->banned;
    $disabled = is_null($user->disabled) ? 0 : $user->disabled;

    $stmt->bindParam(":username", $user->username);
    $stmt->bindParam(":password", $user->password);
    $stmt->bindParam(":tmp_password", $user->tmp_password);
    $stmt->bindParam(":mail", $user->mail);
    $stmt->bindParam(":last_updated", $user->last_updated);
    $stmt->bindParam(":banned", $banned, PDO::PARAM_BOOL);
    $stmt->bindParam(":disabled", $disabled, PDO::PARAM_BOOL);
    $stmt->bindParam(":gender", $user->gender, PDO::PARAM_INT);
    $stmt->bindParam(":id", $user->id, PDO::PARAM_INT);

    $stmt->execute();
}

?>
