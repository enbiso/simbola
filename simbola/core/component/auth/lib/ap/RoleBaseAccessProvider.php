<?php

namespace simbola\core\component\auth\lib\ap;

/**
 * RoleBaseAccessProvider deinfitions
 *
 * @author Faraj Farook
 */
abstract class RoleBaseAccessProvider {

    abstract function init();

    abstract function isNewInstallation();

    //item
    abstract function itemCreate($name, $type);

    abstract function itemExist($name);

    abstract function itemDelete($name);

    abstract function itemRename($name, $newName);

    abstract function itemSwitch($name, $type);

    abstract function itemGet($type);

    //child
    abstract function childAssign($parent, $child);

    abstract function childRevoke($parent, $child);

    abstract function childExist($parent, $child);

    abstract function childExistRecurse($parent, $child);

    abstract function children($parent);

    //user
    abstract function userGet();

    abstract function userId($username);

    abstract function userExist($username);

    abstract function userUsername($userId);

    abstract function userCreate($username, $withDefaultRole = false);

    abstract function userActivate($username);

    abstract function userDeactivate($username);

    abstract function userRemove($username);

    abstract function userRename($username, $newUserame);

    abstract function userResetPassword($username, $newPassword);

    abstract function userAuthenticate($username, $password, $sessionInfo);

    abstract function userAssigned($username, $itemName);

    abstract function userAssign($username, $itemName);

    abstract function userRevoke($username, $itemName);

    abstract function userRoles($username);

    abstract function userSessionCheck($username, $sessionKey);

    abstract function userSession($username);

    abstract function userSessionRevokeById($sessionId, $userId);

    abstract function userSessionRevoke($username, $sessionKey);

    //import,Export
    abstract function import($data);

    abstract function export($types);
}

?>
