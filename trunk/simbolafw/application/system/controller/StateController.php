<?php

namespace application\system\controller;

/**
 * Description of StateController
 *
 * @author Faraj
 */
class StateController extends \simbola\core\application\AppController {

    public function actionIndex() {
        $this->view('state/index');
    }

    public function actionChange() {
        if ($this->issetPost(array('model', 'keys', 'state', 'redirect'))) {
            try {
                $this->invoke('system', 'state', 'change', array(
                    'model' => (object) $this->post('model'),
                    'state' => $this->post('state'),
                    'keys' => $this->post('keys'),
                ));
                $this->redirect($this->post('redirect'));
            } catch (\Exception $exc) {
                $this->setViewData('redirect', $this->post('redirect'));
                $this->setViewData('error', $exc->getMessage());
                $this->view('state/changeError');
            }
        } else {
            $this->redirect('system/state/index');
        }
    }

}
