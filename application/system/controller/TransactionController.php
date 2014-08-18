<?php

namespace application\system\controller;

/**
 * Description of TransactionController
 *
 * @author farflk
 */
class TransactionController extends \simbola\core\application\AppController {

    function actionIndex() {
        $this->view('transaction/index');
    }

    function actionCron() {     
        $this->view('transaction/cron/index');
    }

    function actionCronDelete() {
        if ($this->issetGet('id')) {
            if ($this->issetPost('keys')) {
                $keys = $this->post('keys');
                try {
                    $response = $this->invoke('system', 'transaction', 'cronDelete', array(
                        'keys' => $keys,
                    ));
                    $this->redirect('/system/transaction/cron/index');
                } catch (\Exception $ex) {
                    $this->setViewData("error", $ex->getMessage());
                    $this->setViewData('object', $this->getObject($keys));
                    $this->view('transaction/cron/delete');
                }
            } else {
                $keys = array(
                    "id" => $this->get("id")
                );
                $object = $this->getCronObject($keys);
                if (is_null($object)) {
                    $this->view('transaction/cron/notFound');
                } else {
                    $this->setViewData('object', $object);
                    $this->view('transaction/cron/delete');
                }
            }
        } else {
            $this->redirect('/system/transaction/cron/index');
        }
    }

    function actionCronView() {
        if ($this->issetGet('id')) {
            $keys = array(
                "id" => $this->get("id")
            );
            $object = $this->getCronObject($keys);
            if (is_null($object)) {
                $this->view('transaction/cron/notFound');
            } else {
                $this->setViewData('object', $object);
                $this->view('transaction/cron/view');
            }
        } else {
            $this->redirect('/system/transaction/cron/index');
        }
    }

    //queue
    
    function actionQueueCreate() {
        $object = new \application\system\model\transaction\Queue();
        if ($this->issetPost('data')) {
            try {
                $response = $this->invoke('system', 'transaction', 'queueCreate', array(
                    'data' => $this->post('data'),
                ));
                $object = $response["body"]['response']['object'];
                $keys = array(
                    "id" => $object->id
                );
                $this->redirect('/system/transaction/queueView', $keys);
            } catch (\simbola\core\component\system\lib\exception\ServiceUserException $ex) {
                $object = $ex->getResponse('object');
                $this->setViewData("error", $object->errors->full_messages());
            } catch (\Exception $ex) {
                $this->setViewData("error", $ex->getMessage());
            }
        }
        $this->setViewData('object', $object);
        $this->view('transaction/queue/create');
    }

    function actionQueueUpdate() {
        if ($this->issetGet('id')) {
            $keysFromGet = array(
                "id" => $this->get("id")
            );
            $object = $this->getQueueObject($keysFromGet);
            if (is_null($object)) {
                $this->view('transaction/queue/notFound');
            } else {
                $this->setViewData('object', $object);
                if ($this->issetPost('data')) {
                    try {
                        $response = $this->invoke('system', 'transaction', 'queueUpdate', array(
                            'keys' => $keysFromGet,
                            'data' => $this->post('data'),
                        ));
                        $object = $response["body"]['response']['object'];
                        $this->setViewData('object', $object);
                        $keys = array(
                            "id" => $object->id
                        );
                        $this->redirect('/system/transaction/queueView', $keys);
                    } catch (\simbola\core\component\system\lib\exception\ServiceUserException $ex) {
                        $object = $ex->getResponse('object');
                        $this->setViewData("error", $object->errors->full_messages());
                        $this->setViewData('object', $object);
                    } catch (\Exception $ex) {
                        $this->setViewData("error", $ex->getMessage());
                    }
                }
                $this->view('transaction/queue/update');
            }
        } else {
            $this->redirect('/system/transaction/queue');
        }
    }

    function actionQueueDelete() {
        if ($this->issetGet('id')) {
            if ($this->issetPost('keys')) {
                $keys = $this->post('keys');
                try {
                    $response = $this->invoke('system', 'transaction', 'queueDelete', array(
                        'keys' => $keys,
                    ));
                    $this->redirect('/system/transaction/queue');
                } catch (\Exception $ex) {
                    $this->setViewData("error", $ex->getMessage());
                    $this->setViewData('object', $this->getQueueObject($keys));
                    $this->view('transaction/queue/delete');
                }
            } else {
                $keys = array(
                    "id" => $this->get("id")
                );
                $object = $this->getQueueObject($keys);
                if (is_null($object)) {
                    $this->view('transaction/queue/notFound');
                } else {
                    $this->setViewData('object', $object);
                    $this->view('transaction/queue/delete');
                }
            }
        } else {
            $this->redirect('/system/transaction/queue');
        }
    }

    function actionQueueView() {
        if ($this->issetGet('id')) {
            $keys = array(
                "id" => $this->get("id")
            );
            $object = $this->getQueueObject($keys);
            if (is_null($object)) {
                $this->view('transaction/queue/notFound');
            } else {
                $this->setViewData('object', $object);
                $this->view('transaction/queue/view');
            }
        } else {
            $this->redirect('/system/transaction/queue');
        }
    }

    function actionQueue() {
        try {
            $response = $this->invoke('system', 'transaction', 'queueList', array(
                'search' => $this->post('data'),
            ));
            $data = $response["body"]['response']['data'];
            $this->setViewData('data', $data);
        } catch (\Exception $ex) {
            $this->setViewData("error", $ex->getMessage());
        }
        $this->view('transaction/queue/index');
    }

    //jobs

    function actionJobCreate() {
        $object = new \application\system\model\transaction\Job();
        if ($this->issetPost('data')) {
            try {
                $data = $this->post('data');
                if($data['type'] == 'service' && $this->issetPost("service")){
                    $data['content'] = $this->getServiceContent($this->post('service'));                    
                }
                $response = $this->invoke('system', 'transaction', 'jobCreate', array(
                    'data' => $data,
                ));
                $object = $response["body"]['response']['object'];
                $keys = array(
                    "id" => $object->id
                );
                $this->redirect('/system/transaction/jobView', $keys);
            } catch (\simbola\core\component\system\lib\exception\ServiceUserException $ex) {
                $object = $ex->getResponse('object');
                $this->setViewData("error", $object->errors->full_messages());
            } catch (\Exception $ex) {
                $this->setViewData("error", $ex->getMessage());
            }
        }
        $this->setViewData('object', $object);
        $this->view('transaction/job/create');
    }

    function actionJobUpdate() {
        if ($this->issetGet('id')) {
            $keysFromGet = array(
                "id" => $this->get("id")
            );
            $object = $this->getJobObject($keysFromGet);
            if (is_null($object)) {
                $this->view('transaction/job/notFound');
            } else {
                $this->setViewData('object', $object);
                if ($this->issetPost('data')) {
                    try {
                        $data = $this->post('data');
                        if($object->type == 'service' && $this->issetPost("service")){
                            $data['content'] = $this->getServiceContent($this->post('service'));                    
                        }
                        $response = $this->invoke('system', 'transaction', 'jobUpdate', array(
                            'keys' => $keysFromGet,
                            'data' => $data,
                        ));
                        $object = $response["body"]['response']['object'];
                        $this->setViewData('object', $object);
                        $keys = array(
                            "id" => $object->id
                        );
                        $this->redirect('/system/transaction/jobView', $keys);
                    } catch (\simbola\core\component\system\lib\exception\ServiceUserException $ex) {
                        $object = $ex->getResponse('object');
                        $this->setViewData("error", $object->errors->full_messages());
                        $this->setViewData('object', $object);
                    } catch (\Exception $ex) {
                        $this->setViewData("error", $ex->getMessage());
                    }
                }                
                $this->view('transaction/job/update');
            }
        } else {
            $this->redirect('/system/transaction/job');
        }
    }

    function actionJobDelete() {
        if ($this->issetGet('id')) {
            if ($this->issetPost('keys')) {
                $keys = $this->post('keys');
                try {
                    $response = $this->invoke('system', 'transaction', 'jobDelete', array(
                        'keys' => $keys,
                    ));
                    $this->redirect('/system/transaction/job');
                } catch (\Exception $ex) {
                    $this->setViewData("error", $ex->getMessage());
                    $this->setViewData('object', $this->getJobObject($keys));
                    $this->view('transaction/job/delete');
                }
            } else {
                $keys = array(
                    "id" => $this->get("id")
                );
                $object = $this->getJobObject($keys);
                if (is_null($object)) {
                    $this->view('transaction/job/notFound');
                } else {
                    $this->setViewData('object', $object);
                    $this->view('transaction/job/delete');
                }
            }
        } else {
            $this->redirect('/system/transaction/job');
        }
    }

    function actionJobView() {
        if ($this->issetGet('id')) {
            $keys = array(
                "id" => $this->get("id")
            );
            $object = $this->getJobObject($keys);
            if (is_null($object)) {
                $this->view('transaction/job/notFound');
            } else {
                $this->setViewData('object', $object);
                $this->view('transaction/job/view');
            }
        } else {
            $this->redirect('/system/transaction/job');
        }
    }

    function actionJob() {
        try {
            $response = $this->invoke('system', 'transaction', 'jobList', array(
                'search' => $this->post('data'),
            ));
            $data = $response["body"]['response']['data'];
            $this->setViewData('data', $data);
        } catch (\Exception $ex) {
            $this->setViewData("error", $ex->getMessage());
        }
        $this->view('transaction/job/index');
    }

    //private functions
    private function getJobObject($keys) {
        try {
            $response = $this->invoke('system', 'transaction', 'jobView', array(
                'keys' => $keys,
            ));
            return $response["body"]['response']['object'];
        } catch (\Exception $ex) {
            return null;
        }
    }
    
    private function getQueueObject($keys) {
        try {
            $response = $this->invoke('system', 'transaction', 'queueView', array(
                'keys' => $keys,
            ));
            return $response["body"]['response']['object'];
        } catch (\Exception $ex) {
            return null;
        }
    }

    private function getCronObject($keys) {
        try {
            $response = $this->invoke('system', 'transaction', 'cronView', array(
                'keys' => $keys,
            ));
            return $response["body"]['response']['object'];
        } catch (\Exception $ex) {
            return null;
        }
    }

    private function getServiceContent($serviceData) {
        //$serviceData['params'] = json_decode($serviceData['params']);
        return json_encode($serviceData);
    }
}
