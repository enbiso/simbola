<?php

namespace application\web\controller;

/**
 * Description of UserController
 *
 * @author Faraj
 */
class UserController extends \simbola\core\application\AppController {

    public function actionRegister() {
        if ($this->issetPost(array('username', 'password'))) {            
            try {
                //create User with default role
                $response = $this->invoke('system', 'user', 'register', array(
                    'username' => $this->post('username'),
                    'password' => $this->post('password'),
                    'with_default_role' => true,
                ));
                $object = $response["body"]['response']['user'];                
                //Create Person
                \application\enterprise\model\core\Person::create(array(
                    'user_id' => $object->id
                ));
                $this->setViewData("username", $this->post("username"));
                $this->view('user/registerSuccess');
            } catch (\Exception $ex) {
                $this->setViewData("errorMessage", $ex->getMessage());                                                
                $this->view('user/register');
            }
        }else{
            $this->view('user/register');
        }
    }

    public function actionLogin() {
        if ($this->issetPost(array('username', 'password'))) {
            try {
                $this->invoke("system", "auth", "login", array(
                    'username' => $this->post('username'),
                    'password' => $this->post('password'),
                ));                
                $this->redirect('web/site/index');
            } catch (\Exception $ex) {
                $this->setViewData("errorMessage", $ex->getMessage());
                $this->view('user/loginFailed');
            }
        } else {
            $this->view('user/login');
        }
    }

    public function actionLogout() {
        $this->invoke("system", "auth", "logout", array());
        $this->redirect('web/site/index');
    }
    
    public function actionChangePassword() {
        $username = \simbola\Simbola::app()->auth->getUsername();
        $this->setViewData('username', $username);
        if($this->issetPost(array('password','new_password','password_repeat'))){
            try {
                $this->invoke("system", "user", "changePasswordSafe", array(
                    'username'  => $username,
                    'password'  => $this->post('password'),
                    'new_password'  => $this->post('new_password'),
                    'password_repeat' => $this->post('password_repeat'),
                ));
                $this->setViewData('success', 'Password Changed!');
            } catch (\Exception $ex) {
                $this->setViewData('error', $ex->getMessage());
            }
        }
        $this->view("user/changePassword");
    }

    function actionMyProfile(){
        $userId = \simbola\Simbola::app()->auth->getId();                
        $object = \application\enterprise\model\core\Person::find_by_user_id($userId);
        if($object == null){
            $object = \application\enterprise\model\core\Person::create(array(
                'user_id' => $userId,
            ));            
        }
        $this->setViewData('object', $object);
        $this->view('user/myProfile');
    }
    
    function actionEditProfile() {
        $userId = \simbola\Simbola::app()->auth->getId();                
        $object = \application\enterprise\model\core\Person::find_by_user_id($userId);
        if($object == null){
            $object = \application\enterprise\model\core\Person::create(array(
                'user_id' => $userId,
            ));            
        }
        if($this->issetPost('data')){
            $object->set_attributes($this->post('data'));
            $object->save();
            $this->redirect('web/user/myProfile');
        }
        $this->setViewData('object', $object);
        $this->view('user/editProfile');
    }
    
}

?>
