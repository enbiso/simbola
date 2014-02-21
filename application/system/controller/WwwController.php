<?php

namespace application\system\controller;

/**
 * Description of WwwController
 *
 * @author Faraj
 */
class WwwController extends \simbola\core\application\AppController {

    function actionPagenotfound() {
        $this->view('www/pagenotfound');
    }

    function actionNoaccess() {
        $this->view('www/noaccess');
    }

    function actionIndex() {
        $this->view('www/index');
    }

    function actionService_api() {
        $this->view("www/service_api");
    }
    
}

?>
