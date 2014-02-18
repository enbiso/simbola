<?php

namespace application\developer\controller;

/**
 * Description of ModuleController
 *
 * @author Faraj
 */
class ModuleController extends \simbola\core\application\AppController {

    function actionCreate() {
        $this->view("module/create");
    }

    function actionIndex() {
        $app = \simbola\Simbola::app();
        $sOutput = \simbola\Simbola::app()->session->get('sproxy_system/module/index', true);
        $this->setViewData('modules', \simbola\Simbola::app()->getModuleNames());
        $this->setViewData('sOutput', $sOutput);
        //$this->view("module/index");
        var_dump(\application\product\model\core\Item::Columns());        
    }

}

?>
