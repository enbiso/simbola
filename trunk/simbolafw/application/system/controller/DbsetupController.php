<?php
namespace application\system\controller;

/**
 * Description of Dbsetup
 *
 * @author Faraj
 */
class DbsetupController extends \simbola\core\application\AppController {

    public function actionInstall() {        
        $LUs = \application\system\library\dbsetup\LogicalUnit::LoadAll(
                        \simbola\Simbola::app()->db,
                        $this->get('module'));
        $this->setViewData('LUs', $LUs);
        $this->setViewData('moduleName', $this->get('module'));
        $this->pview('dbsetup/index');
    }

}

?>
