<?php
namespace application\system\controller;
/**
 * Description of AuthController
 *
 * @author Faraj
 */
class AuthController extends \simbola\core\application\AppController{

    public function actionSocial() {
        \Hybrid_Endpoint::process($_REQUEST);
    }

    public function actionSession() {
        if (!$this->issetPost(array('username', 'skey'))) {
            $_POST['username'] = 'guest';
            $_POST['skey'] = '';
        }
        $auth = \simbola\Simbola::app()->auth;
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        $this->json($auth->updateSession($this->post('username'), $this->post('skey')));
    }
    
    public function actionLogin() {
        if ($this->issetPost(array('username', 'password'))) {
            try {
                $this->invoke("system", "auth", "login", array(
                    'username' => $this->post('username'),
                    'password' => $this->post('password'),
                ));
                $this->view('auth/loginSuccess');
            } catch (\Exception $ex) {
                $this->setViewData("errorMessage", $ex->getMessage());
                $this->view('auth/loginFailed');
            }
        } else {
            $this->view('auth/login');
        }
    }
    
}

?>
