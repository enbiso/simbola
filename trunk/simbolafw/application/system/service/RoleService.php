<?php
namespace application\system\service;

/**
 * Description of RoleService
 *
 * @author FARFLK
 */
class RoleService extends \simbola\core\application\AppService{
    
    public $schema_register = array(
        'req' => array('params' => array('rolename')),
        'res' => array(),
        'err' => array(),
    );
    
    function actionRegister() {
        $rolename = $this->_req_params('rolename');
        $rbap = \simbola\Simbola::app()->auth->getRBAP();
        $rbap->create($rolename, \simbola\core\component\auth\lib\ap\AuthType::ACCESS_ROLE);
    }
    
    public $schema_unregister = array(
        'req' => array('params' => array('rolename')),
        'res' => array(),
        'err' => array(),
    );
    
    function actionUnregister() {
        $rolename = $this->_req_params('rolename');
        $rbap = \simbola\Simbola::app()->auth->getRBAP();
        $rbap->delete($rolename);
    }
    
    public $schema_setType = array(
        'req' => array('params' => array('rolename', 'type')),
        'res' => array(),
        'err' => array(),
    );
    
    function actionSetType() {
        $rolename = $this->_req_params('rolename');
        $rbap = \simbola\Simbola::app()->auth->getRBAP();
        $rbap->itemSwitch($rolename, $this->_req_params('type'));
    }
    
}
