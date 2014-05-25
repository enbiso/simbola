<?php

namespace application\system\service;

/**
 * Description of StateService
 *
 * @author Faraj
 */
class StateService extends \simbola\core\application\AppService {

    public function checkSecurity($page) {
        $model = (object) $this->_req_params("model");
        $state = $this->_req_params("state");
        $permObj = new \simbola\core\component\auth\lib\PermObject(
                $model->module, $model->lu, $model->name, "entity.state.{$state}");        
        return parent::checkSecurity($page) && \simbola\Simbola::app()->auth->checkPermission($permObj);
    }

    public $schema_change = array(
        'req' => array('params' => array('model', 'keys', 'state')),
        'res' => array(),
        'err' => array('CHANGE_FAILED', 'INVALID_MODEL', 'DATA_NOT_FOUND', 'STATE_CHANGE_ERROR'),
    );

    public function actionChange() {
        $model = (object) $this->_req_params("model");
        $modelClass = \simbola\core\application\AppModel::getClass($model->module, $model->lu, $model->name);
        if (class_exists($modelClass)) {
            $object = $modelClass::find($this->_req_params('keys'));
            if (is_null($object)) {
                $this->_err("DATA_NOT_FOUND");
            } else {
                if(!$object->state($this->_req_params('state'))){
                    $this->_err('STATE_CHANGE_ERROR');
                }
            }
        } else {
            $this->_err("INVALID_MODEL");
        }
    }

}
