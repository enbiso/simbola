<?php
namespace application\system\service;
/**
 * Description of Service
 *
 * Service 	: ModelId
 * Created	: 24Jun2014
 * Purpose 	: Model Id Service
 *
 * Change Logs
 * -----------------------------------------------------------
 * 24Jun2014 faraj: Created the Service ModelId
 *  
 * @author faraj
 */
class ModelIdService extends \simbola\core\application\AppService {

    public $schema_list = array(
        'req' => array('params' => array()),
        'res' => array('data'),
        'err' => array('LIST_ERROR')
    );

    function actionList() {
        try {
            $search = $this->_req_params('search');
            if(empty($search)){
               $data = \application\system\model\setup\ModelId::find('all');   
            }else{
               $data = \application\system\model\setup\ModelId::find('all', $search);  
            }
            $this->_res('data', $data);            
        } catch(Exception $ex) {
            $this->_err('LIST_ERROR');
        }
    }
    
    public $schema_init = array(
        'req' => array('params' => array('module', 'lu', 'name', 'allocator')),
        'res' => array('data'),
        'err' => array('MODEL_ID_NOT_ENABLED')
    );

    function actionInit() {
        try {
            $modelClass = \simbola\core\application\AppModel::getClass($this->_req_params('module'), $this->_req_params('lu'), $this->_req_params('name'));
            $modelId = $modelClass::modelIdInit($this->_req_params('allocator'));
            $this->_res('data', $modelId);
        } catch (\Exception $ex) {
            $this->_err('MODEL_ID_NOT_ENABLED');
        }
    }

}
