<?php

namespace application\system\controller;

/**
 * Description of Dbsetup
 *
 * @author Faraj
 */
class RbamController extends \simbola\core\application\AppController {

    public function __construct() {
        $this->customLayout = "layout/main";
    }

    function actionIndex() {
        $this->view('rbam/index');
    }

    function actionTabRoles() {
        $this->pview('rbam/roles');
    }

    function actionTabUsers() {
        $this->pview('rbam/users');
    }

    function actionTabImportExport() {
        $this->pview('rbam/import_export');
    }

    function actionTabRoleAccessObj() {
        $rbap = \simbola\Simbola::app()->auth->getRBAP();
        $data['allRoles'] = array_merge($rbap->itemGet(\simbola\core\component\auth\lib\ap\AuthType::ENDUSER_ROLE), $rbap->itemGet(\simbola\core\component\auth\lib\ap\AuthType::ACCESS_ROLE));
        $this->pview('rbam/role_accessobj', $data);
    }

    function actionTabRoleRole() {
        $rbap = \simbola\Simbola::app()->auth->getRBAP();
        $data['enduserRoles'] = $rbap->itemGet(\simbola\core\component\auth\lib\ap\AuthType::ENDUSER_ROLE);
        $data['allRoles'] = array_merge($rbap->itemGet(\simbola\core\component\auth\lib\ap\AuthType::ENDUSER_ROLE), $rbap->itemGet(\simbola\core\component\auth\lib\ap\AuthType::ACCESS_ROLE));
        $this->pview('rbam/role_role', $data);
    }

    function actionTabManAccessObj() {
        $this->setViewData('moduleNames', \simbola\Simbola::app()->getModuleNames());
        $this->pview('rbam/man_accessobj');
    }

    function actionTabUserRole() {
        $rbap = \simbola\Simbola::app()->auth->getRBAP();
        $data['endusers'] = $rbap->userGet();
        $this->pview('rbam/user_role', $data);
    }

    public function actionDlgUserRegister() {
        $this->pview('rbam/dlg_user_register');
    }

    public function actionDlgRoleRegister() {
        $this->pview('rbam/dlg_role_register');
    }

    public function actionDlgUserChangePassword() {
        $data['username'] = $this->issetPost(array('username')) ? $_POST['username'] : "";
        $this->pview('rbam/dlg_user_change_password', $data);
    }

    function actionRoleRegister() {
        try {
            if (!$this->issetPost('rolename')) {
                throw new \Exception('Role name not defined');
            }
            $this->invoke('system', 'role', 'register', array(
                'rolename' => $this->post('rolename'),
            ));
            $this->json(array(
                'title' => 'Success',
                'type' => 'success',
                'text' => 'Successfully added new role ' . $this->post('rolename')
            ));
        } catch (\Exception $exc) {
            $this->json(array(
                'title' => 'Warning',
                'type' => 'warning',
                'text' => $exc->getTraceAsString(),
            ));
        }
    }

    function actionRoleUnregister() {
        try {
            if (!$this->issetPost('rolename')) {
                throw new \Exception('Role name not defined');
            }
            $this->invoke('system', 'role', 'unregister', array(
                'rolename' => $this->post('rolename'),
            ));
            $this->json(array(
                'title' => 'Success',
                'type' => 'success',
                'text' => 'Successfully removed role ' . $this->post('rolename')
            ));
        } catch (\Exception $exc) {
            $this->json(array(
                'title' => 'Warning',
                'type' => 'warning',
                'text' => $exc->getTraceAsString(),
            ));
        }
    }

    function actionRoleSetEnduser() {
        try {
            if (!$this->issetPost('rolename')) {
                throw new \Exception('Role name not defined');
            }
            $this->invoke('system', 'role', 'setType', array(
                'rolename' => $this->post('rolename'),
                'type' => \simbola\core\component\auth\lib\ap\AuthType::ENDUSER_ROLE,
            ));
            $this->json(array(
                'title' => 'Success',
                'type' => 'success',
                'text' => 'Successfully switched role ' . $this->post('rolename')
            ));
        } catch (\Exception $exc) {
            $this->json(array(
                'title' => 'Warning',
                'type' => 'warning',
                'text' => $exc->getMessage(),
            ));
        }
    }

    function actionRoleSetAccess() {
        try {
            if (!$this->issetPost('rolename')) {
                throw new \Exception('Role name not defined');
            }
            $this->invoke('system', 'role', 'setType', array(
                'rolename' => $this->post('rolename'),
                'type' => \simbola\core\component\auth\lib\ap\AuthType::ACCESS_ROLE,
            ));
            $this->json(array(
                'title' => 'Success',
                'type' => 'success',
                'text' => 'Successfully switched role ' . $this->post('rolename')
            ));
        } catch (\Exception $exc) {
            $this->json(array(
                'title' => 'Warning',
                'type' => 'warning',
                'text' => $exc->getTraceAsString(),
            ));
        }
    }

    function actionUserRegister() {
        try {
            if (!$this->issetPost('username')) {
                throw new \Exception('Username not defined');
            }
            $this->invoke('system', 'user', 'register', array(
                'username' => $this->post('username'),
            ));
            $this->json(array(
                'title' => 'Success',
                'type' => 'success',
                'text' => 'Successfully added new user'
            ));
        } catch (\Exception $exc) {
            $this->json(array(
                'title' => 'Warning',
                'type' => 'warning',
                'text' => $exc->getTraceAsString(),
            ));
        }
    }

    function actionUserChangePassword() {
        try {
            if (!$this->issetPost('username', 'password', 'password_repeat')) {
                throw new \Exception('Params not defined');
            }
            $this->invoke('system', 'user', 'changePassword', array(
                'username' => $this->post('username'),
                'password' => $this->post('password'),
                'password_repeat' => $this->post('password_repeat'),
            ));
            $this->json(array(
                'title' => 'Success',
                'type' => 'success',
                'text' => 'Successfully changed the password'
            ));
        } catch (\Exception $exc) {
            $this->json(array(
                'title' => 'Warning',
                'type' => 'warning',
                'text' => $exc->getTraceAsString(),
            ));
        }
    }

    function actionUserDeactivate() {
        try {
            if (!$this->issetPost('username')) {
                throw new \Exception('Username not defined');
            }
            $this->invoke('system', 'user', 'deactivate', array(
                'username' => $this->post('username'),
            ));
            $this->json(array(
                'title' => 'Success',
                'type' => 'success',
                'text' => 'Successfully deactivated',
            ));
        } catch (\Exception $exc) {
            $this->json(array(
                'title' => 'Warning',
                'type' => 'warning',
                'text' => $exc->getTraceAsString(),
            ));
        }
    }

    function actionUserActivate() {
        try {
            if (!$this->issetPost('username')) {
                throw new \Exception('Username not defined');
            }
            $this->invoke('system', 'user', 'activate', array(
                'username' => $this->post('username'),
            ));
            $this->json(array(
                'title' => 'Success',
                'type' => 'success',
                'text' => 'Successfully activated',
            ));
        } catch (\Exception $exc) {
            $this->json(array(
                'title' => 'Warning',
                'type' => 'warning',
                'text' => $exc->getTraceAsString(),
            ));
        }
    }

    function actionUserUnregister() {
        try {
            if (!$this->issetPost('username')) {
                throw new \Exception('Username not defined');
            }
            $this->invoke('system', 'user', 'unregister', array(
                'username' => $this->post('username'),
            ));
            $this->json(array(
                'title' => 'Success',
                'type' => 'success',
                'text' => 'Successfully unregistered',
            ));
        } catch (\Exception $exc) {
            $this->json(array(
                'title' => 'Warning',
                'type' => 'warning',
                'text' => $exc->getTraceAsString(),
            ));
        }
    }

    //import export
    function actionExport() {
        $types = array();
        if ($this->issetPost("type")) {
            $types = array_keys($this->post("type"));
        }
        $data = \simbola\Simbola::app()->auth->getRBAP()->export($types);
        $header = "Content-disposition: attachment; filename=simbola_security.json";
        $this->json($data, $header, JSON_PRETTY_PRINT);
    }

    function actionImport() {
        if ($this->issetFile("secFile")) {
            $file = $this->file('secFile');
            if (\simbola\Simbola::app()->auth->getRBAP()->import(json_decode(base64_decode($file['data64']), true))) {
                $this->setViewData("message", "Successfully imported.");
            } else {
                $this->setViewData("error", "Import failed.");
            }
        }
        $this->pview("rbam/import_form");
    }

    private function registerPage($page) {
        $count = 0;
        $className = \simbola\Simbola::app()->getPageClass($page);
        if (class_exists($className)) {
            $rbap = \simbola\Simbola::app()->auth->getRBAP();
            foreach (get_class_methods($className) as $method) {
                if (sstring_starts_with($method, "action")) {
                    $page->action = lcfirst(substr($method, 6));
                    $perbObj = new \simbola\core\component\auth\lib\PermObject($page);
                    if ($rbap->itemCreate($perbObj->getAccessItem(), \simbola\core\component\auth\lib\ap\AuthType::ACCESS_OBJECT)) {
                        $count++;
                    }
                }
            }
        }
        return $count;
    }

    private function registerModule($module) {                        
        $count = 0;
        $mconf = \simbola\Simbola::app()->getModuleConfig($module);
        $basepath = \simbola\Simbola::app()->basepath('app') . DIRECTORY_SEPARATOR
                . \simbola\Simbola::app()->getParam('BASE') . DIRECTORY_SEPARATOR
                . $mconf->name;
        //get all controllers
        $controllerBasePath = $basepath . DIRECTORY_SEPARATOR
                . $mconf->controller . DIRECTORY_SEPARATOR . "*";
        foreach (array_filter(glob($controllerBasePath), 'is_file') as $fileName) {
            $page = new \simbola\core\component\url\lib\Page;
            $page->module = $mconf->name;
            $page->type = \simbola\core\component\url\lib\Page::TYPE_CONTROLLER;
            $page->logicalUnit = lcfirst(str_replace("Controller.php", "", basename($fileName)));
            $count += $this->registerPage($page);
        }        
        //get all services
        $serviceBasePath = $basepath . DIRECTORY_SEPARATOR
                . $mconf->service . DIRECTORY_SEPARATOR . "*";
        foreach (array_filter(glob($serviceBasePath), 'is_file') as $fileName) {
            $page = new \simbola\core\component\url\lib\Page;
            $page->module = $mconf->name;
            $page->type = \simbola\core\component\url\lib\Page::TYPE_SERVICE;
            $page->logicalUnit = lcfirst(str_replace("Service.php", "", basename($fileName)));
            $count += $this->registerPage($page);
        }        
        //get all db objects
        $dbBasePath = $basepath . DIRECTORY_SEPARATOR . $mconf->database;
        $dbBasePathWithWildcard = $dbBasePath . DIRECTORY_SEPARATOR . "*";
        foreach (array_filter(glob($dbBasePathWithWildcard), 'is_dir') as $dbLuPath) {
            $luName = basename($dbLuPath);
            $count += $this->registerDbObjects($module, $dbBasePath, $luName, 'procedure');            
            $count += $this->registerDbObjects($module, $dbBasePath, $luName, 'table');                        
            $count += $this->registerDbObjects($module, $dbBasePath, $luName, 'view');            
        }
        return $count;
    }

    private function registerDbObjects($module, $dbBasePath, $luName, $dbObjType) {
        $count = 0;
        $rbap = \simbola\Simbola::app()->auth->getRBAP();
        $dbLuBasePath = $dbBasePath . DIRECTORY_SEPARATOR
                . $luName . DIRECTORY_SEPARATOR . $dbObjType . DIRECTORY_SEPARATOR . "*";
        foreach (array_filter(glob($dbLuBasePath), 'is_file') as $fileName) {
            $dbObjName = basename($fileName, ".php");
            if ($dbObjType == 'table') { //consider as entity
                //query
                $perbObj = new \simbola\core\component\auth\lib\PermObject($module, $luName, $dbObjName, "entity.query");                
                if ($rbap->itemCreate($perbObj->getAccessItem(), \simbola\core\component\auth\lib\ap\AuthType::ACCESS_OBJECT)) {
                    $count++;
                }                
                //state machine actions
                $modelClass = \simbola\core\application\AppModel::getClass($module, $luName, $dbObjName);                
                if (class_exists($modelClass)) {
                    foreach ($modelClass::getStates() as $stateName) {                        
                        $perbObj = new \simbola\core\component\auth\lib\PermObject($module, $luName, $dbObjName, "entity.state." . $stateName);
                        if ($rbap->itemCreate($perbObj->getAccessItem(), \simbola\core\component\auth\lib\ap\AuthType::ACCESS_OBJECT)) {
                            $count++;
                        }
                    }
                }                
            } else {
                //views/procedures
                $perbObj = new \simbola\core\component\auth\lib\PermObject($module, $luName, $dbObjName, $dbObjType);
                if ($rbap->itemCreate($perbObj->getAccessItem(), \simbola\core\component\auth\lib\ap\AuthType::ACCESS_OBJECT)) {
                    $count++;
                }
            }
        }
        return $count;
    }

    function actionUnregister() {
        $objs = $this->post('objs');
        $rbap = \simbola\Simbola::app()->auth->getRBAP();
        $count = 0;
        foreach ($objs as $obj) {            
            $rbap->itemDelete($obj);
            $count++;
        }
        $this->json(array(
            'title' => 'Success',
            'type' => 'success',
            'text' => 'Successfully removed ' . $count . ' entry(s)'
        ));
    }

    function actionRegisterAllModules() {
        $count = 0;
        foreach (\simbola\Simbola::app()->getModuleNames() as $moduleName) {
            $count += $this->registerModule($moduleName);
        }
        $this->json(array(
            'title' => 'Success',
            'type' => 'success',
            'text' => 'Successfully added ' . $count . ' entry(s)'
        ));
    }

    function actionRegisterModule() {
        $module = $this->get('module');
        $count = $this->registerModule($module);
        $this->json(array(
            'title' => 'Success',
            'type' => 'success',
            'text' => 'Successfully added ' . $count . ' entry(s)'
        ));
    }

    function actionAccessObjects() {
        $assign_role = $this->get('role');
        $data = $this->fetchObjects(\simbola\core\component\auth\lib\ap\AuthType::ACCESS_OBJECT, $assign_role);
        $this->json($data);
    }

    function actionEnduserRoles() {
        $assign_user = $this->get('user');
        $this->json($this->fetchObjects(\simbola\core\component\auth\lib\ap\AuthType::ENDUSER_ROLE, $assign_user));
    }

    function actionAccessRoles() {
        $assign_role = $this->get('role');
        $data = $this->fetchObjects(\simbola\core\component\auth\lib\ap\AuthType::ACCESS_ROLE, $assign_role);
        $data = array_merge($data, $this->fetchObjects(\simbola\core\component\auth\lib\ap\AuthType::ENDUSER_ROLE, $assign_role));
        $this->json($data);
    }

    function actionGrant() {
        $auth_type = $this->post('auth_type');
        $parent = $this->post('parent');
        $grants = $this->post('grants');

        $rbap = \simbola\Simbola::app()->auth->getRBAP();
        $grants = is_array($grants) ? $grants : array($grants);
        //If Enduser        
        if ($auth_type == \simbola\core\component\auth\lib\ap\AuthType::ENDUSER_ROLE) {
            foreach ($grants as $grantobj) {
                if ($grantobj['state'] == 'GRANT') {
                    $rbap->userAssign($parent, $grantobj['key']);
                } else {
                    $rbap->userRevoke($parent, $grantobj['key']);
                }
            }
        } else { //Access role, Access Obj
            foreach ($grants as $grantobj) {
                if ($grantobj['state'] == 'GRANT') {
                    if ($rbap->childExistRecurse($grantobj['key'], $parent)) {
                        $this->json(array(
                            'title' => 'Failed',
                            'type' => 'failed',
                            'text' => $grantobj['key'] . ' contains ' . $parent
                        ));
                        return;
                    } else if ($rbap->itemExist($grantobj['key'])) {
                        $rbap->childAssign($parent, $grantobj['key']);
                    }
                } else {
                    $rbap->childRevoke($parent, $grantobj['key']);
                }
            }
        }
        $this->json(array(
            'title' => 'Success',
            'type' => 'success',
            'text' => 'Permission Changed Successfully'
        ));
    }

    private function fetchObjects($type, $assignRoleOrUser) {
        $rbap = \simbola\Simbola::app()->auth->getRBAP();
        $accessObjs = $rbap->itemGet($type);
        $modules = array();
        foreach ($accessObjs as $aObj) {
            $itemName = $aObj['item_name']; //get the current item_name
            if ($itemName != $assignRoleOrUser) {//self assign check disabled
                //set if selected
                $selected = ($rbap->userAssigned($assignRoleOrUser, $itemName) || $rbap->childExist($assignRoleOrUser, $itemName));
                //set if should expand
                $expanded = $selected;

                $itemNameArr = explode(".", $itemName);
                if (count($itemNameArr) > 0) {
                    //module
                    $moduleName = $itemNameArr[0];
                    $currModuleIndex = -1;
                    for ($moduleIndex = 0; $moduleIndex < count($modules); $moduleIndex++) {
                        if ($modules[$moduleIndex]['title'] == $moduleName) {
                            $currModuleIndex = $moduleIndex;
                            break;
                        }
                    }
                    $moduleKey = $moduleName;
                    if ($currModuleIndex < 0) {
                        $modules[] = array(
                            'title' => $moduleName,
                            'key' => $moduleKey,
                            'isFolder' => true,
                            'select' => $selected,
                            'children' => array(),
                            'expand' => $expanded,
                        );
                        $currModuleIndex = count($modules) - 1;
                    }

                    if (count($itemNameArr) > 1) {
                        //type
                        $types = $modules[$currModuleIndex]['children'];
                        $typeName = $itemNameArr[1];
                        $currTypeIndex = -1;
                        for ($typeIndex = 0; $typeIndex < count($types); $typeIndex++) {
                            if ($types[$typeIndex]['title'] == $typeName) {
                                $currTypeIndex = $typeIndex;
                                break;
                            }
                        }
                        $typeKey = $moduleName . '.' . $typeName;
                        if ($currTypeIndex < 0) {
                            $types[] = array(
                                'title' => $typeName,
                                'key' => $typeKey,
                                'isFolder' => true,
                                'select' => $selected,
                                'children' => array(),
                                'expand' => $expanded,
                            );
                            $currTypeIndex = count($types) - 1;
                        }
                        $modules[$currModuleIndex]['children'] = $types;

                        //logical unit
                        if (count($itemNameArr) > 2) {
                            $lus = $modules[$currModuleIndex]['children'][$currTypeIndex]['children'];
                            $logicalUnitName = $itemNameArr[2];
                            $currLuIndex = -1;
                            for ($luIndex = 0; $luIndex < count($lus); $luIndex++) {
                                if ($lus[$luIndex]['title'] == $logicalUnitName) {
                                    $currLuIndex = $luIndex;
                                    break;
                                }
                            }
                            $luKey = $moduleName . '.' . $typeName . "." . $logicalUnitName;
                            if ($currLuIndex < 0) {
                                $lus[] = array(
                                    'title' => $logicalUnitName,
                                    'key' => $luKey,
                                    'isFolder' => true,
                                    'select' => $selected,
                                    'children' => array(),
                                        //     'expand' => $expanded,
                                );
                                $currLuIndex = count($lus) - 1;
                            }
                            $modules[$currModuleIndex]['children'][$currTypeIndex]['children'] = $lus;
                            //action - system.service.user.deactivate
                            if (count($itemNameArr) == 4) {
                                $actions = $modules[$currModuleIndex]['children'][$currTypeIndex]['children'][$currLuIndex]['children'];
                                $actionName = $itemNameArr[3];
                                $currActionIndex = -1;
                                for ($actionIndex = 0; $actionIndex < count($actions); $actionIndex++) {
                                    if ($actions[$actionIndex]['title'] == $actionName) {
                                        $currActionIndex = $actionIndex;
                                        break;
                                    }
                                }
                                if ($currActionIndex < 0) {
                                    $actions[] = array(
                                        'title' => $actionName,
                                        'key' => $moduleName . '.' . $typeName . "." . $logicalUnitName . "." . $actionName,
                                        'isFolder' => false,
                                        'select' => $selected,
                                    );
                                    $currActionIndex = count($actions) - 1;
                                }
                                $modules[$currModuleIndex]['children'][$currTypeIndex]['children'][$currLuIndex]['children'] = $actions;
                                //system.entity.auth.Assign.*
                            } elseif (count($itemNameArr) > 4) {
                                $entities = $modules[$currModuleIndex]['children'][$currTypeIndex]['children'][$currLuIndex]['children'];
                                $entityName = $itemNameArr[3];
                                $currEntityIndex = -1;
                                for ($entityIndex = 0; $entityIndex < count($entities); $entityIndex++) {
                                    if ($entities[$entityIndex]['title'] == $entityName) {
                                        $currEntityIndex = $entityIndex;
                                        break;
                                    }
                                }
                                if ($currEntityIndex < 0) {
                                    $entities[] = array(
                                        'title' => $entityName,
                                        'key' => $moduleName . '.' . $typeName . "." . $logicalUnitName . "." . $entityName,
                                        'isFolder' => true,
                                        'children' => array(),
                                        'select' => $selected,
                                    );
                                    $currEntityIndex = count($entities) - 1;
                                }
                                $modules[$currModuleIndex]['children'][$currTypeIndex]['children'][$currLuIndex]['children'] = $entities;
                                //system.entity.auth.Assign.query
                                if (count($itemNameArr) == 5) {
                                    $entityData = $modules[$currModuleIndex]['children'][$currTypeIndex]['children'][$currLuIndex]['children'][$currEntityIndex]['children'];
                                    $entityDataName = $itemNameArr[4];
                                    $currEntityDataIndex = -1;
                                    for ($entityDataIndex = 0; $entityDataIndex < count($entityData); $entityDataIndex++) {
                                        if ($entityData[$entityDataIndex]['title'] == $entityDataName) {
                                            $currEntityDataIndex = $entityDataIndex;
                                            break;
                                        }
                                    }
                                    if ($currEntityDataIndex < 0) {
                                        $entityData[] = array(
                                            'title' => $entityDataName,
                                            'key' => $moduleName . '.' . $typeName . "." . $logicalUnitName . "." . $entityName . "." . $entityDataName,
                                            'isFolder' => false,                                                                                        
                                            'select' => $selected,
                                        );
                                        $currEntityDataIndex = count($entityData) - 1;
                                    }
                                    $modules[$currModuleIndex]['children'][$currTypeIndex]['children'][$currLuIndex]['children'][$currEntityIndex]['children'] = $entityData;
                                //system.entity.auth.Assign.state.*
                                }elseif (count($itemNameArr) > 5) {
                                    $entityStateData = $modules[$currModuleIndex]['children'][$currTypeIndex]['children'][$currLuIndex]['children'][$currEntityIndex]['children'];
                                    $entityStateDataName = $itemNameArr[4];
                                    $currEntityStateDataIndex = -1;
                                    for ($entityStateDataIndex = 0; $entityStateDataIndex < count($entityStateData); $entityStateDataIndex++) {
                                        if ($entityStateData[$entityStateDataIndex]['title'] == $entityStateDataName) {
                                            $currEntityStateDataIndex = $entityStateDataIndex;
                                            break;
                                        }
                                    }
                                    if ($currEntityStateDataIndex < 0) {
                                        $entityStateData[] = array(
                                            'title' => $entityStateDataName,
                                            'key' => $moduleName . '.' . $typeName . "." . $logicalUnitName . "." . $entityName . "." . $entityStateDataName,
                                            'isFolder' => true,
                                            'children' => array(),
                                            'select' => $selected,
                                        );
                                        $currEntityStateDataIndex = count($entityStateData) - 1;
                                    }
                                    $modules[$currModuleIndex]['children'][$currTypeIndex]['children'][$currLuIndex]['children'][$currEntityIndex]['children'] = $entityStateData;
                                    //system.entity.auth.Assign.state.idle
                                    if (count($itemNameArr) == 6) {
                                        $states = $modules[$currModuleIndex]['children'][$currTypeIndex]['children'][$currLuIndex]['children'][$currEntityIndex]['children'][$currEntityStateDataIndex]['children'];
                                        $stateName = $itemNameArr[5];
                                        $currStateIndex = -1;
                                        for($stateIndex = 0; $stateIndex < count($states); $stateIndex++){
                                            if($states[$stateIndex]['title'] == $stateName){
                                                $currStateIndex = $stateIndex;
                                                break;
                                            }
                                        }
                                        if($currStateIndex < 0){
                                            $states[] = array(
                                                'title' => $stateName,
                                                'key' => $moduleName . '.' . $typeName . "." . $logicalUnitName . "." . $entityName . "." . $entityStateDataName . "." . $stateName,
                                                'isFolder' => false,
                                                'select' => $selected,
                                            );
                                            $currStateIndex = count($states) - 1;
                                        }
                                        $modules[$currModuleIndex]['children'][$currTypeIndex]['children'][$currLuIndex]['children'][$currEntityIndex]['children'][$currEntityStateDataIndex]['children'] = $states;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $modules;
    }

}

?>
