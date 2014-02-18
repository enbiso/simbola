<?php

namespace simbola\core\application;

use simbola\Simbola;

/**
 * Description of AppController
 *
 * @author Faraj
 */
abstract class AppController {

    protected $currentData = array();
    protected $currentPage;
    protected $securityBypass = false;
    protected $customLayout = null;

    public function security() {
        
    }

    public function json($data = null) {
        header('Content-Type: application/json');
        if (!isset($data)) {
            $data = $this->currentData;
        }
        echo json_encode($data);
    }

    public function getCustomLayout() {
        return $this->customLayout;
    }

    public function getCurrentPage() {
        return $this->currentPage;
    }

    public function setViewData($name, $value) {
        $this->currentData[$name] = $value;
    }

    public function view($viewPath, $data = array(), $print = true, $partial = false) {
        $data = array_merge($data, $this->currentData);
        $viewContent = new \simbola\core\component\system\lib\ViewContent($this, $viewPath, $partial);
        $viewContent->setData($data);
        return $viewContent->render($print);
    }

    public function pview($viewPath, $data = array(), $print = true) {
        return $this->view($viewPath, $data, $print, true);
    }

    private function isLoginScreen($page) {
        $loginpage = new \simbola\core\component\url\lib\Page();
        $loginpage->loadFromUrl(Simbola::app()->router->LOGIN);
        return $loginpage->module == $page->module && $loginpage->logicalUnit == $page->logicalUnit && $loginpage->action == $page->action;
    }

    public function run($page) {
        $this->currentPage = $page;
        if ($this->isLoginScreen($page) || $this->checkSecurityBypass($page->action) || $this->checkSecurity($page)) {
            $this->preAction($page);
            $funcName = 'action' . ucfirst($page->action);
            if (method_exists($this, $funcName)) {
                $this->$funcName();
            } else {
                $this->redirect(Simbola::app()->router->NOPAGE);
            }
            $this->postAction($page);
        } else if (!Simbola::app()->auth->isLogged()) {
            \simbola\Simbola::app()->session->set('PRE_LOGIN_PAGE', Simbola::app()->router->page);
            $this->redirect(Simbola::app()->router->LOGIN);
        } else {
            $this->redirect(Simbola::app()->router->NOACCESS);
        }
    }

    protected function preLoginPage() {
        $prePage = \simbola\Simbola::app()->session->get('PRE_LOGIN_PAGE');
        \simbola\Simbola::app()->session->set('PRE_LOGIN_PAGE', NULL);
        return $prePage;
    }

    protected function checkSecurityBypass($action) {
        if (!is_array($this->securityBypass)) {
            $this->securityBypass = array($this->securityBypass);
        }
        foreach ($this->securityBypass as $bypassAction) {
            if ($bypassAction == "*") {
                return true;
            } elseif ($bypassAction == $action) {
                return true;
            }
        }
        return false;
    }

    protected function redirect($page, $params = array()) {
        if (is_string($page)) {
            $url_string = $page;
            $page = new \simbola\core\component\url\lib\Page();
            $page->loadFromUrl($url_string);
            $page->params = $params;
        }
        Simbola::app()->url->redirect($page);
    }

    public function checkSecurity($page) {
        return Simbola::app()->auth->checkPermissionByPage($page);
    }

    public function issetPost($names) {
        return $this->issetArray($_POST, $names);
    }

    public function issetGet($names) {
        return $this->issetArray($_GET, $names);
    }

    public function post($name) {
        return isset($_POST[$name]) ? $_POST[$name] : null;
    }

    public function file($name, $content = 'all') {
        $nameArray = is_array($name) ? $name : array($name);                              
        $file = array();
        if(count($nameArray) == 2) {
            foreach ($_FILES[$nameArray[0]] as $key => $value) {
                $file[$key] = $value[$nameArray[1]];
            }
        } elseif(count($nameArray) == 1) {
            $file = $_FILES[$nameArray[0]];
        }        
        if (in_array($content, array('all', 'data64'))) {
            $file['data64'] = base64_encode(file_get_contents($file['tmp_name']));
        }
        return ($content == 'all') ? $file : $file[$content];
    }

    public function get($name) {
        return isset($_GET[$name]) ? $_GET[$name] : null;
    }

    public function issetFile($names) {
        if(is_string($names) && sstring_contains($names, "[") && sstring_ends_with($names, "]")){
            $namearr = explode("[", $names);
            $namearr[1] = trim($namearr[1], "]");            
            $isset = $this->issetArray($_FILES, $namearr[0]) 
                    && $this->issetArray($_FILES[$namearr[0]]['name'], $namearr[1]) 
                    && (!empty($_FILES[$namearr[0]]['name'][$namearr[1]]));                                    
        }else{
            $isset = $this->issetArray($_FILES, $names);
        }
        return $isset;
    }

    public function issetArray($array, $names) {
        if (!is_array($names)) {
            $names = array($names);
        }
        $isset = true;
        foreach ($names as $name) {
            $isset &= isset($array[$name]);
        }
        return $isset;
    }

    public function init() {
        
    }

    public function destroy() {
        
    }

    public function preAction($page) {
        
    }

    public function postAction($page) {
        
    }

    public function getTerm($term_path, $data = array()) {
        return \simbola\core\component\term\Term::Get($term_path, $data);
    }

    public function echoTerm($term_path, $data = array()) {
        return \simbola\core\component\term\Term::eGet($term_path, $data);
    }

    public function invoke($module, $service, $action, $params) {
        $serviceClient = new \simbola\core\component\system\lib\ServiceClient();
        $serviceClient->module = $module;
        $serviceClient->service = $service;
        $serviceClient->action = $action;
        $serviceClient->params = $params;
        return $serviceClient->execute();
    }

}

?>
