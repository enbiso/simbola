<?php

namespace application\developer\service;

/**
 * Description of ModuleService
 *
 * @author Faraj
 */
class ModuleService extends \simbola\core\application\AppService {

    public $schema_create = array(
        'req' => array('params' => array('module', 'purpose')),
        'res' => array(),
        'err' => array('MODULE_EXIST')
    );

    function actionCreate() {
        $moduleGen = new \application\developer\library\module\generator\ModuleGenerator(
                $this->_req_params('module'), $this->_req_params('purpose'));
        $moduleGen->generate();
    }

    public $schema_createModel = array(
        'req' => array('params' => array('module', 'lu', 'model', 'purpose')),
        'res' => array(),
        'err' => array('MODEL_EXIST')
    );

    function actionCreateModel() {
        $modelGen = new \application\developer\library\module\generator\ModelGenerator(
                $this->_req_params('module'), $this->_req_params('lu'), $this->_req_params('model'), $this->_req_params('purpose'));
        $modelGen->generate();
    }

    public $schema_createService = array(
        'req' => array('params' => array('module', 'lu', 'model', 'service', 'purpose')),
        'res' => array(),
        'err' => array('SERVICE_EXIST')
    );

    function actionCreateService() {
        $servGen = new \application\developer\library\module\generator\ServiceGenerator(
                $this->_req_params('module'), $this->_req_params('lu'), $this->_req_params('model'), $this->_req_params('service'), $this->_req_params('purpose'));
        $servGen->generate();
    }

    public $schema_createController = array(
        'req' => array('params' => array('module', 'lu', 'model', 'controller', 'service', 'purpose')),
        'res' => array(),
        'err' => array('CONTROLLER_EXIST')
    );

    function actionCreateController() {
        $crudGen = new \application\developer\library\module\generator\ControllerGenerator(
               $this->_req_params('module'), $this->_req_params('lu'), $this->_req_params('model'), $this->_req_params('service'), $this->_req_params('controller'), $this->_req_params('purpose'));
        $crudGen->generate();
    }
    
    public $schema_createDbTable = array(
        'req' => array('params' => array('module', 'lu', 'model', 'purpose')),
        'res' => array(),
        'err' => array('TABLE_EXIST')
    );

    function actionCreateDbTable() {
        $dbTableGen = new \application\developer\library\module\generator\DbTableGenerator(
               $this->_req_params('module'), $this->_req_params('lu'), $this->_req_params('model'),$this->_req_params('purpose'));
        $dbTableGen->generate();
    }

    public $schema_upgradeFromSvn = array(
        'req' => array('params' => array('module', 'svn_username', 'svn_password', 'svn_url')),
        'res' => array(),
        'err' => array('SVN_LOGIN_FAILED', 'SVN_URL_INVALID')
    );
    
    function actionUpgradeFromSvn() {
        try {
            $svnClient = \simbola\Simbola::app()->svn->client();
            $svnClient->setRepository($this->_req_params('svn_url'));
            $svnClient->setAuth($this->_req_params('svn_username'), $this->_req_params('svn_password'));
            $targetpath = \simbola\Simbola::app()->getModuleConfig($this->_req_params('module'))->getPath();
            $svnClient->checkOut("/", $targetpath, true);        
        }  catch (\Exception $ex){
            $this->_err($ex->getMessage());
        }
    }
}
