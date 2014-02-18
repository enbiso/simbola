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
}

?>
