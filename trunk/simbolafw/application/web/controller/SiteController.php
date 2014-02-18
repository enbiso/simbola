<?php
namespace application\web\controller;
/**
 * Description of SiteController
 *
 * @author Faraj
 */
class SiteController extends \simbola\core\application\AppController{
    
    function actionIndex() {
        $this->view('site/index');
    }
}

?>
