<?php
namespace simbola\core\component\auth\lib\ap;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RoleBaseAccessProvider
 *
 * @author Faraj
 */
abstract class RoleBaseAccessProvider {
    abstract function init($params);
    abstract function isNewInstallation();
    //item
    abstract function itemCreate($name,$type);
    abstract function itemExist($name);
    abstract function itemDelete($name);
    abstract function itemRename($name,$new_name);
    abstract function itemSwitch($name,$type);
    abstract function itemGet($type);
    //child
    abstract function childAssign($parent,$child);
    abstract function childRevoke($parent,$child);
    abstract function childExist($parent,$child);
    abstract function childExistRecurse($parent,$child);
    abstract function children($parent);
    //user
    abstract function userGet();
    abstract function userId($user_name);
    abstract function userExist($user_name);
    abstract function userUsername($user_id);
    abstract function userCreate($user_name, $with_default_role = false);
    abstract function userActivate($user_name);
    abstract function userDeactivate($user_name);
    abstract function userRemove($user_name);
    abstract function userRename($user_name,$new_name);
    abstract function userResetPassword($user_name, $new_password);
    abstract function userAuthenticate($user_name, $password, $session_info);
    abstract function userAssigned($user_name,$item_name);
    abstract function userAssign($user_name,$item_name);
    abstract function userRevoke($user_name,$item_name);
    abstract function userRoles($user_name);
    abstract function userSessionCheck($user_name,$session_key);
    abstract function userSession($user_name);
    abstract function userSessionRevokeById($session_id,$user_id);    
    abstract function userSessionRevoke($user_name, $session_key);    
    //import,Export
    abstract function import($data);
    abstract function export($types);
}
?>
