<?php

namespace application\system\controller;

/**
 * Description of WwwController
 *
 * @author Faraj
 */
class WwwController extends \simbola\core\application\AppController {

    function actionPagenotfound() {
        $this->setViewData("page", \simbola\Simbola::app()->router->getCurrentPage());
        $this->view('www/pagenotfound');
    }

    function actionNoaccess() {
        if($this->issetGet("page")){
            $this->setViewData("pageName", $this->get('page'));
        }else{
            $this->setViewData("pageName", "/");
        }
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
