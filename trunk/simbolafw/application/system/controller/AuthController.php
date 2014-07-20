<?php

namespace application\system\controller;

/**
 * Description of AuthController
 *
 * @author Faraj
 */
class AuthController extends \simbola\core\application\AppController {

    public function __construct() {
        $this->securityBypass = array('session');
    }
    
    public function actionSocial() {
        \Hybrid_Endpoint::process($_REQUEST);
    }

    public function actionLogin() {
        if ($this->issetPost(array('username', 'password'))) {
            try {
                $this->invoke("system", "auth", "login", array(
                    'username' => $this->post('username'),
                    'password' => $this->post('password'),
                ));
                $this->redirect('/system/www/index');
            } catch (\Exception $ex) {
                $this->setViewData("errorMessage", $ex->getMessage());
            }
        }
        $this->view('auth/login');
    }

    public function actionLogout() {
        $this->invoke("system", "auth", "logout", array());
        $this->redirect('/system/www/index');
    }

}

?>
