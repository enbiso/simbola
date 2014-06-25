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

}