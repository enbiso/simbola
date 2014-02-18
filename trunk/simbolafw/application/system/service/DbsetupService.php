<?php

namespace application\system\service;

/**
 * Description of DBSetup
 *
 * @author Faraj
 */
class DbsetupService extends \simbola\core\application\AppService {

    public $schema_createLu = array(
        'req' => array('params' => array('module', 'name')),
        'res' => array(),
        'err' => array(),
    );

    function actionCreateLu() {
        $lu = new \application\system\library\dbsetup\LogicalUnit(
                \simbola\Simbola::app()->db, $this->_req_params('module'), $this->_req_params('name'));
        $lu->create();
    }

    public $schema_setupObj = array(
        'req' => array('params' => array('module', 'lu', 'type', 'name')),
        'res' => array(),
        'err' => array("OBJ_NOT_FOUND"),
    );

    function actionSetupObj() {
        $lu = new \application\system\library\dbsetup\LogicalUnit(
                \simbola\Simbola::app()->db, $this->_req_params('module'), $this->_req_params('lu'));        
        $obj = $lu->getObj($this->_req_params('type'), lcfirst($this->_req_params('name')));
        if($obj != null){
            $obj->setup();
        }else{
            $this->_err("OBJ_NOT_FOUND");
        }
    }

    public $schema_setupLu = array(
        'req' => array('params' => array('module', 'lu')),
        'res' => array(),
        'err' => array(),
    );

    function actionSetupLu() {
        $lu = new \application\system\library\dbsetup\LogicalUnit(
                \simbola\Simbola::app()->db, $this->_req_params('module'), $this->_req_params('lu'));
        $lu->setup();
    }

    public $schema_setupModule = array(
        'req' => array('params' => array('module')),
        'res' => array(),
        'err' => array(),
    );

    function actionSetupModule() {
        $dbPath = \simbola\Simbola::app()->getModuleBase($this->_req_params('module'), 'database');
        foreach (glob($dbPath . DIRECTORY_SEPARATOR . "*", GLOB_ONLYDIR) as $luPath) {
            $luName = basename($luPath, ".php");
            $lu = new \application\system\library\dbsetup\LogicalUnit(
                    \simbola\Simbola::app()->db, $this->_req_params('module'), $luName);
            $lu->setup();
        }
    }

}

?>
