<?php

namespace application\system\service;

/**
 * Description of Service
 *
 * Service 	: transaction
 * Created	: 05Jun2014
 * Purpose 	: Transaction Cron Service
 *
 * Change Logs
 * -----------------------------------------------------------
 * 05Jun2014 faraj: Created the Service transaction
 *  
 * @author faraj
 */
class TransactionService extends \simbola\core\application\AppService {

    public $schema_cronView = array(
        'req' => array('params' => array('keys')),
        'res' => array('object'),
        'err' => array('VIEW_ERROR')
    );

    function actionCronView() {
        $object = \application\system\model\transaction\Cron::find($this->_req_params('keys'));
        if ($object == NULL) {
            $this->_err('VIEW_ERROR');
        }
        $this->_res('object', $object);
    }

    public $schema_cronList = array(
        'req' => array('params' => array('search')),
        'res' => array('data'),
        'err' => array('LIST_ERROR')
    );

    function actionCronList() {
        try {
            $data = \application\system\model\transaction\Cron::find($this->_req_params('search'));
            $this->_res('data', $data);
        } catch (Exception $ex) {
            $this->_err('LIST_ERROR');
        }
    }

    public $schema_cronDelete = array(
        'req' => array('params' => array('keys')),
        'res' => array(),
        'err' => array('DELETE_ERROR')
    );

    function actionCronDelete() {
        $object = \application\system\model\transaction\Cron::find($this->_req_params('keys'));
        if (!$object->delete()) {
            $this->_err('DELETE_ERROR');
        }
    }

    //cron queue
    public $schema_cronQueueCreate = array(
        'req' => array('params' => array('data')),
        'res' => array(),
        'err' => array('CREATE_ERROR')
    );

    function actionCronQueueCreate() {
        $object = new \application\system\model\transaction\CronQueue($this->_req_params('data'));
        if (!$object->save()) {
            $this->_err('CREATE_ERROR');
        }
        $this->_res('object', $object);
    }
    
    public $schema_cronQueueDelete = array(
        'req' => array('params' => array('keys')),
        'res' => array(),
        'err' => array('DELETE_ERROR')
    );

    function actionCronQueueDelete() {
        $object = \application\system\model\transaction\CronQueue::find($this->_req_params('keys'));
        if (!$object->delete()) {
            $this->_err('DELETE_ERROR');
        }
    }
    
    //queue
    public $schema_queueView = array(
        'req' => array('params' => array('keys')),
        'res' => array('object'),
        'err' => array('VIEW_ERROR')
    );

    function actionQueueView() {
        $object = \application\system\model\transaction\Queue::find($this->_req_params('keys'));
        if ($object == NULL) {
            $this->_err('VIEW_ERROR');
        }
        $this->_res('object', $object);
    }

    public $schema_queueList = array(
        'req' => array('params' => array('search')),
        'res' => array('data'),
        'err' => array('LIST_ERROR')
    );

    function actionQueueList() {
        try {
            $data = \application\system\model\transaction\Queue::find($this->_req_params('search'));
            $this->_res('data', $data);
        } catch (Exception $ex) {
            $this->_err('LIST_ERROR');
        }
    }

    public $schema_queueCreate = array(
        'req' => array('params' => array('data')),
        'res' => array('object'),
        'err' => array('CREATE_ERROR')
    );

    function actionQueueCreate() {
        $object = new \application\system\model\transaction\Queue($this->_req_params('data'));
        if (!$object->save()) {
            $this->_err('CREATE_ERROR');
        }
        $this->_res('object', $object);
    }

    public $schema_queueUpdate = array(
        'req' => array('params' => array('keys', 'data')),
        'res' => array('object'),
        'err' => array('UPDATE_ERROR')
    );

    function actionQueueUpdate() {
        $object = \application\system\model\transaction\Queue::find($this->_req_params('keys'));
        $object->set_attributes($this->_req_params('data'));
        if (!$object->save()) {
            $this->_err('UPDATE_ERROR');
        }
        $this->_res('object', $object);
    }

    public $schema_queueDelete = array(
        'req' => array('params' => array('keys')),
        'res' => array(),
        'err' => array('DELETE_ERROR')
    );

    function actionQueueDelete() {
        $object = \application\system\model\transaction\Queue::find($this->_req_params('keys'));
        if (!$object->delete()) {
            $this->_err('DELETE_ERROR');
        }
    }

    public $schema_jobView = array(
        'req' => array('params' => array('keys')),
        'res' => array('object'),
        'err' => array('VIEW_ERROR')
    );

    function actionJobView() {
        $object = \application\system\model\transaction\Job::find($this->_req_params('keys'));
        if ($object == NULL) {
            $this->_err('VIEW_ERROR');
        }
        $this->_res('object', $object);
    }

    public $schema_jobList = array(
        'req' => array('params' => array('search')),
        'res' => array('data'),
        'err' => array('LIST_ERROR')
    );

    function actionJobList() {
        try {
            $data = \application\system\model\transaction\Job::find($this->_req_params('search'));
            $this->_res('data', $data);
        } catch (Exception $ex) {
            $this->_err('LIST_ERROR');
        }
    }

    public $schema_jobCreate = array(
        'req' => array('params' => array('data')),
        'res' => array('object'),
        'err' => array('CREATE_ERROR')
    );

    function actionJobCreate() {
        $object = new \application\system\model\transaction\Job($this->_req_params('data'));
        $object->user_id = \simbola\Simbola::app()->auth->getId();
        if (!$object->save()) {
            $this->_err('CREATE_ERROR');
        }
        $this->_res('object', $object);
    }

    public $schema_jobUpdate = array(
        'req' => array('params' => array('keys', 'data')),
        'res' => array('object'),
        'err' => array('UPDATE_ERROR')
    );

    function actionJobUpdate() {
        $object = \application\system\model\transaction\Job::find($this->_req_params('keys'));
        $object->set_attributes($this->_req_params('data'));
        $object->user_id = \simbola\Simbola::app()->auth->getId();
        if (!$object->save()) {
            $this->_err('UPDATE_ERROR');
        }
        $this->_res('object', $object);
    }

    public $schema_jobDelete = array(
        'req' => array('params' => array('keys')),
        'res' => array(),
        'err' => array('DELETE_ERROR')
    );

    function actionJobDelete() {
        $object = \application\system\model\transaction\Job::find($this->_req_params('keys'));
        if (!$object->delete()) {
            $this->_err('DELETE_ERROR');
        }
    }

}
