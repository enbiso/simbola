<?php

namespace application\developer\controller;

/**
 * Description of Webcode
 *
 * @author Faraj
 */
class IdeController extends \simbola\core\application\AppController {

    public function actionIndex() {
        $this->view('ide/index');
    }

    public function actionGetFileList() {
        $path = "";        
        if($this->issetPost(array('path'))){
            $path = $this->post('path');    
        }
        $out = $this->invoke("developer", 'ide', 'getFileList', array('path' => $path));        
        $this->json($out['body']['response']['files']);
    }
    
    public function actionDbSetup() {        
        $LUs = \application\developer\library\dbsetup\LogicalUnit::LoadAll(
                        \simbola\Simbola::app()->db,
                        $this->get('module'));
        $this->setViewData('LUs', $LUs);
        $this->setViewData('moduleName', $this->get('module'));
        $this->pview('ide/dbsetup');
    }
}

?>
