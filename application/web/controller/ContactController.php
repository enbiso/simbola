<?php

namespace application\web\controller;

/**
 * Description of Controller
 *
 * Controller 	: contact
 * Created	: 17Feb2014
 * Purpose 	: Contact Controller
 *
 * Change Logs
 * -----------------------------------------------------------
 * 17Feb2014 faraj: Created the Controller contact
 *  
 * @author faraj
 */
class ContactController extends \simbola\core\application\AppController {

    function actionIndex() {
        $object = new \application\web\model\contact\Message();        
        if ($this->issetPost('data')) {
            try {
                $response = $this->invoke('web', 'contactMessage', 'create', array(
                    'data' => $this->post('data'),
                ));
                $object = $response["body"]['response']['object'];
                $keys = array(
                    "id" => $object->id
                );
                $this->setViewData('success', true);                
                $object = new \application\web\model\contact\Message;//clear the object
            } catch (\Exception $ex) {
                $this->setViewData("error", $ex->getMessage());     
                $object->set_attributes($this->post('data'));                
            }
        }
        $this->setViewData('object',$object);
        $this->view('contact/index');
    }

    function actionUpdate() {
        if ($this->issetGet('id')) {
            $keysFromGet = array(
                "id" => $this->get("id")
            );
            $response = $this->invoke('web', 'contactMessage', 'view', array(
                'keys' => $keysFromGet,
            ));
            $object = $response["body"]['response']['object'];
            $this->setViewData('object', $object);
            if ($this->issetPost('data')) {
                try {
                    $response = $this->invoke('web', 'contactMessage', 'update', array(
                        'keys' => $keysFromGet,
                        'data' => $this->post('data'),
                    ));
                    $object = $response["body"]['response']['object'];
                    $this->setViewData('object', $object);
                    $keys = array(
                        "id" => $object->id
                    );
                    $this->redirect('/web/contact/view', $keys);
                } catch (\Exception $ex) {
                    $this->setViewData("error", $ex->getMessage());
                }
            }
            $this->view('contact/update');
        } else {
            $this->redirect('/web/contact/index');
        }
    }

    function actionDelete() {
        if ($this->issetGet('id')) {
            if ($this->issetPost('keys')) {
                try {
                    $response = $this->invoke('web', 'contactMessage', 'delete', array(
                        'keys' => $this->post('keys'),
                    ));
                    $this->redirect('/web/contact/list');
                } catch (\Exception $ex) {
                    $this->setViewData("error", $ex->getMessage());
                }
                $this->redirect('/web/contact/list');
            } else {
                $keys = array(
                    "id" => $this->get("id")
                );
                $response = $this->invoke('web', 'contactMessage', 'view', array(
                    'keys' => $keys,
                ));
                $object = $response["body"]['response']['object'];
                $this->setViewData('object', $object);
                $this->view('contact/delete');
            }
        } else {
            $this->redirect('/web/contact/list');
        }
    }

    function actionView() {
        if ($this->issetGet('id')) {
            $keys = array(
                "id" => $this->get("id")
            );
            $response = $this->invoke('web', 'contactMessage', 'view', array(
                'keys' => $keys,
            ));
            $object = $response["body"]['response']['object'];
            $this->setViewData('object', $object);
            $this->view('contact/view');
        } else {
            $this->redirect('/web/contact/index');
        }
    }

    function actionList() {
        try {
            $response = $this->invoke('web', 'contactMessage', 'list', array(
                'search' => $this->post('data'),
            ));
            $data = $response["body"]['response']['data'];
            $this->setViewData('data', $data);
        } catch (\Exception $ex) {
            $this->setViewData("error", $ex->getMessage());
        }
        $this->view('contact/list');
    }

}

?>
