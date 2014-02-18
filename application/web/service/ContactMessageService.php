<?php
namespace application\web\service;
/**
 * Description of Service
 *
 * Service 	: ContactMessage
 * Created	: 17Feb2014
 * Purpose 	: Contact Message Service
 *
 * Change Logs
 * -----------------------------------------------------------
 * 17Feb2014 faraj: Created the Service ContactMessage
 *  
 * @author faraj
 */
class ContactMessageService extends \simbola\core\application\AppService {

    public $schema_view = array(
        'req' => array('params' => array('keys')),
        'res' => array('object'),
        'err' => array('VIEW_ERROR')
    );

    function actionView() {
        $object = \application\web\model\contact\Message::find($this->_req_params('keys'));        
        if($object == NULL){
            $this->_err('VIEW_ERROR');
        }
        $object->is_read = true;
        $object->save();
        $this->_res('object', $object);
    }
    
    public $schema_list = array(
        'req' => array('params' => array('search')),
        'res' => array('data'),
        'err' => array('LIST_ERROR')
    );

    function actionList() {
        try {
            $data = \application\web\model\contact\Message::find($this->_req_params('search'));  
            $this->_res('data', $data);
        } catch(Exception $ex) {
            $this->_err('LIST_ERROR');
        }
    }
    
    public $schema_create = array(
        'req' => array('params' => array('data')),
        'res' => array('object'),
        'err' => array('CREATE_ERROR','CAPCHA_ERROR')
    );

    function actionCreate() {
        $object = new \application\web\model\contact\Message($this->_req_params('data'));        
        $this->_res('object', $object);
        if(!$object->validateCapcha()){
            $this->_err('CAPCHA_ERROR');
        }elseif(!$object->save()){
            $this->_err('CREATE_ERROR');
        }        
    }
    
    public $schema_update = array(
        'req' => array('params' => array('keys', 'data')),
        'res' => array('object'),
        'err' => array('UPDATE_ERROR')
    );

    function actionUpdate() {
        $object = \application\web\model\contact\Message::find($this->_req_params('keys'));        
        $object->set_attributes($this->_req_params('data'));
        if(!$object->save()){
            $this->_err('UPDATE_ERROR');
        }
        $this->_res('object', $object);
    }
    
    public $schema_delete = array(
        'req' => array('params' => array('keys')),
        'res' => array(),
        'err' => array('DELETE_ERROR')
    );

    function actionDelete() {
        $object = \application\web\model\contact\Message::find($this->_req_params('keys'));
        if(!$object->delete()){
            $this->_err('DELETE_ERROR');
        }        
    }
}