<?php
namespace application\system\controller;
/**
 * Description of LogController
 *
 * @author Faraj
 */
class LogController extends \simbola\core\application\AppController{

    public function __construct() {
        $this->customLayout = "layout/log/main";
    }
            
    function actionIndex() {
        $this->view('log/index');
    }
    
}

?>
