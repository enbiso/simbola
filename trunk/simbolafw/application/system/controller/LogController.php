<?php
namespace application\system\controller;
/**
 * Description of LogController
 *
 * @author Faraj
 */
class LogController extends \simbola\core\application\AppController{
        
    function actionIndex() {
        $this->view('log/index');
    }
    
}

?>
