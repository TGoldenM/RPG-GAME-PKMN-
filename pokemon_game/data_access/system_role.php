<?php

function curUserIsAdmin(){
    return curUserIs('Administrator');
}

function curUserIs($role_name){
    return userHasRole($_SESSION['username'], $role_name);
}

function userHasRole($user_name, $role_name){
    global $db;
    
    $stmt = $db->prepare("select count(ur.id_role) from system_user_role ur
            left join system_role r on r.id = ur.id_role
            left join system_user u on u.id = ur.id_user
            where u.username = :user_name and r.name = :role_name");

    $stmt->bindParam(':user_name', $user_name);
    $stmt->bindParam(':role_name', $role_name);
    $stmt->execute();
    
    $res = $stmt->fetchColumn();
    return $res > 0;
}

function getUserHighestRole($user_name){
    global $db;
    
    $stmt = $db->prepare("select r.id, r.rank, r.name
            from system_user_role ur
            left join system_role r on r.id = ur.id_role
            left join system_user u on u.id = ur.id_user
            where u.username = :user_name
            order by r.rank desc limit 1");

    $stmt->bindParam(':user_name', $user_name);
    $stmt->execute();
    
    $res = $stmt->fetch(PDO::FETCH_OBJ);
    return $res;
}

function checkIfRoleExists($name) {
    global $db;
    
    if (!is_null($name)) {
        $stmt = $db->prepare("select count(*) from system_role"
                . " where name = :name");
        
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        
        $res = $stmt->fetchColumn();
        return $res > 0;
    }

    return false;
}

function getRole($id) {
    global $db;
    $obj = null;

    $stmt = $db->prepare("select * from system_role
            where id = :id");

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    $obj = $stmt->fetch(PDO::FETCH_OBJ);
    return $obj;
}

function getRoleByName($name) {
    global $db;
    $obj = null;

    $stmt = $db->prepare("select * from system_role
            where name = :name");

    $stmt->bindParam(':name', $name, PDO::PARAM_INT);
    $stmt->execute();
    
    $obj = $stmt->fetch(PDO::FETCH_OBJ);
    return $obj;
}

function getRoles($arr) {
    global $db;
    $obj = null;

    $stmt = $db->prepare("select * from system_role
            where id_role in :roles");

    $stmt->bindParam(':roles', implode(",", $arr));
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($res) {
        $obj = $res;
    }

    return $obj;
}

function getRolesList($orderBy = null, $rows = null) {
    global $db;
    $objs = array();

    $q = "select * from system_role";

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
    }

    return $objs;
}

function getAssignedRoles($id_user) {
    global $db;
    $objs = array();

    $q = "select r.id, r.name from system_user_role ur"
            . " left join system_role r on ur.id_role = r.id"
            . " where ur.id_user = :id_user";
    
    $stmt = $db->prepare($q);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($res) {
        $objs = $res;
    }

    return $objs;
}

function assignRoles($user, $roles, $removeAll = true){
    global $db;
    
    $stmt = $db->prepare("INSERT INTO system_user_role(id_user, id_role)"
                        . " VALUES (:id_user, :id_role)");

    if (!$removeAll) {
        //Insert only the non existent roles
        $res = getAssignedRoles($user->id);
        $c_roles = array();
        foreach ($res as $r) {
            $c_roles[] = $r->id;
        }

        foreach ($roles as $id_role) {
            if (!in_array($id_role, $c_roles)) {
                $stmt->bindParam(":id_user", $user->id, PDO::PARAM_INT);
                $stmt->bindParam(":id_role", $id_role, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    } else {
        //Clear assigned roles
        removeAllRoles($user);
        
        foreach ($roles as $id_role) {
            $stmt->bindParam(":id_user", $user->id, PDO::PARAM_INT);
            $stmt->bindParam(":id_role", $id_role, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}

function removeRole($user, $id_role){
    global $db;

    $stmt = $db->prepare("DELETE FROM system_user_role"
            . " where id_user = :id_user and id_role = :id_role");

    $stmt->bindParam(":id_user", $user->id, PDO::PARAM_INT);
    $stmt->bindParam(":id_role", $id_role, PDO::PARAM_INT);

    $stmt->execute();
}

function removeRoles($user, $roles){
    global $db;

    $stmt = $db->prepare("DELETE FROM system_user_role"
                . " where id_user = :id_user and id_role = :id_role");

    foreach ($roles as $id_role) {
        $stmt->bindParam(":id_user", $user->id, PDO::PARAM_INT);
        $stmt->bindParam(":id_role", $id_role, PDO::PARAM_INT);
        $stmt->execute();
    }
}

function removeAllRoles($user){
    global $db;

    $stmt = $db->prepare("DELETE FROM system_user_role"
            . " where id_user = :id_user");

    $stmt->bindParam(":id_user", $user->id, PDO::PARAM_INT);
    
    $stmt->execute();
}

function insertRole($p) {
    global $db;

    $stmt = $db->prepare("INSERT INTO system_role(rank, name)
             VALUES (:rank, :name)");

    $stmt->bindParam(":rank", $p->rank, PDO::PARAM_INT);
    $stmt->bindParam(":name", $p->name);

    $stmt->execute();
    return $db->lastInsertId();
}

function updateRole($p) {
    global $db;

    $stmt = $db->prepare("UPDATE system_role
        SET rank = :rank, name = :name WHERE id = :id");

    $stmt->bindParam(":id", $p->id, PDO::PARAM_INT);
    $stmt->bindParam(":rank", $p->rank, PDO::PARAM_INT);
    $stmt->bindParam(":name", $p->name);

    $stmt->execute();
}

function deleteRole($p){
    deleteRoleById($p->id);
}

function deleteRoleById($id) {
    global $db;

    $stmt = $db->prepare("DELETE FROM system_role
        WHERE id = :id");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
}

?>
