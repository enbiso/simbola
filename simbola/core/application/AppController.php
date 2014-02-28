<?php

namespace simbola\core\application;

use simbola\Simbola;

/**
 * AppController
 * The abstract base class that should be used to define the Application Controllers
 *
 * @author Faraj Farook
 */
abstract class AppController {

    /**
     * Data to be passed to the view when rendering
     * 
     * @access protected
     * @var array() 
     */
    protected $viewData = array();
    
    /**
     * The Page object representing the current request
     * 
     * @var \simbola\core\component\url\lib\Page
     */
    protected $currentPage;
    
    /**
     * Setup security bypassing actions in this controller, If given in the array, these 
     * actions wont be checked using the Role base access provider to verify the security
     * 
     * @access protected
     * @var array/boolean 
     */
    protected $securityBypass = false;
    
    /**
     * Used to define the custom layout path for this specific controller
     * /[module]/layout/[layout name]
     * 
     * @access protected
     * @var string
     */
    protected $customLayout = null;

    /**
     * Used to render the output as JSON
     * 
     * @access protected
     * @param array $data the data to process along with the $viewData
     * @param string $header The PHP HTML header default to application/json
     */
    protected function json($data = array(), $header = 'Content-Type: application/json') {        
        header($header);        
        $data = array_merge($data, $this->viewData);
        echo json_encode($data);
    }

    /**
     * Fetch the custom layout defined for this controller
     * 
     * @access public     
     * @return string
     */
    public function getCustomLayout() {
        return $this->customLayout;
    }

    /**
     * Fetch the current page on execution
     * 
     * @return \simbola\core\component\url\lib\Page
     */
    public function getCurrentPage() {
        return $this->currentPage;
    }

    /**
     * Set the view data that should be passed when rendering the view
     * 
     * @access protected
     * @param string $name name of the data
     * @param type $value value of the data
     */
    protected function setViewData($name, $value) {
        $this->viewData[$name] = $value;
    }

    /**
     * Used to render the view with layout by default
     * 
     * @access protected
     * @param string $viewPath The path of the view from the module view folder     
     * @param array $data data to be processed along with the viewData when rendering
     * @param boolean $print TRUE - output the render, FALSE - return the render content as string
     * @param boolean $partial TRUE - render without the layout, FALSE - render with the layout
     * @return type render content
     */
    protected function view($viewPath, $data = array(), $print = true, $partial = false) {
        $data = array_merge($data, $this->viewData);
        $viewContent = new \simbola\core\component\system\lib\ViewContent($this, $viewPath, $partial);
        $viewContent->setData($data);
        return $viewContent->render($print);
    }

    /**
     * Renders the view without the layout
     * 
     * @access protected
     * @param string $viewPath The path of the view from the module view folder     
     * @param array $data data to be processed along with the viewData when rendering
     * @param boolean $print TRUE - output the render, FALSE - return the render content as string
     * @return type render content
     */
    protected function pview($viewPath, $data = array(), $print = true) {
        return $this->view($viewPath, $data, $print, true);
    }

    /**
     * Check if the page is the login page
     * 
     * @access public
     * @param \simbola\core\component\url\lib\Page $page
     * @return boolean
     */
    private function isLoginScreen($page) {
        $loginpage = new \simbola\core\component\url\lib\Page();
        $loginpage->loadFromUrl(Simbola::app()->router->LOGIN);
        return $loginpage->module == $page->module && $loginpage->logicalUnit == $page->logicalUnit && $loginpage->action == $page->action;
    }

    /**
     * Used to run the controller with the given page action
     * 
     * @access public
     * @param \simbola\core\component\url\lib\Page $page the Page of execution
     */
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

    /**
     * Checks if the given action is defined in the security bypass action list
     * 
     * @access public
     * @param string $action action name
     * @return boolean
     */
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

    /**
     * Redirects to the given page. If the $page param is a string, the $params
     * paramater can be used to define the array of url params which will be used to 
     * generate the Page object
     * 
     * @access protected     
     * @param \simbola\core\component\url\lib\Page $page
     * @param array $params
     */
    protected function redirect($page, $params = array()) {
        if (is_string($page)) {
            $url_string = $page;
            $page = new \simbola\core\component\url\lib\Page();
            $page->loadFromUrl($url_string);
            $page->params = $params;
        }
        Simbola::app()->url->redirect($page);
    }

    /**
     * Check the security by passing the page object
     * 
     * @param \simbola\core\component\url\lib\Page $page
     * @return boolean
     */
    protected function checkSecurity($page) {
        return Simbola::app()->auth->checkPermissionByPage($page);
    }

    /**
     * Check if the $_POST isset for the given keys
     * 
     * @access protected
     * @param mixed $keys Can be a string name of the key or an array of key names
     * @return bool
     */
    protected function issetPost($keys) {
        return $this->issetArray($_POST, $keys);
    }

    /**
     * Check if the $_GET isset for the given keys
     * 
     * @param mixed $keys Can be a string name of the key or an array of key names
     * @return bool
     */
    protected function issetGet($names) {
        return $this->issetArray($_GET, $names);
    }

    /**
     * get the $_POST[$key] value, if not set returns null
     * 
     * @param mixed $key Name of the key
     * @return mixed
     */
    protected function post($key) {
        return isset($_POST[$key]) ? $_POST[$key] : null;
    }

    /**
     * 
     * Fetch the $_FILE object with extended data such as base64 encoded file content
     * if no parametr given for the content, by default fetches everything
     * 
     * @param string $name
     * @param string $content 'all', 'tmp_name', 'name', 'data64', etc...
     * @return type
     */
    protected function file($name, $content = 'all') {
        $nameArray = is_array($name) ? $name : array($name);                              
        $file = array();
        if(count($nameArray) == 2) {
            foreach ($_FILES[$nameArray[0]] as $key => $value) {
                $file[$key] = $value[$nameArray[1]];
            }
        } elseif(count($nameArray) == 1) {
            $file = $_FILES[$nameArray[0]];
        }   
        $file['data64'] = null;
        if (in_array($content, array('all', 'data64')) && !empty($file['tmp_name'])) {
            $file['data64'] = base64_encode(file_get_contents($file['tmp_name']));
        }
        return ($content == 'all') ? $file : $file[$content];
    }

    /**
     * Fetch the $_GET[$key] value or if not set returns null
     * 
     * @param string $key key of the $_GET
     * @return mixed Data or if not set returns null
     */
    protected function get($key) {
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }

    protected function issetFile($names) {
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

    private function issetArray($array, $names) {
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

    protected function preAction($page) {
        
    }

    protected function postAction($page) {
        
    }

    protected function getTerm($term_path, $data = array()) {
        return \simbola\core\component\term\Term::Get($term_path, $data);
    }

    protected function echoTerm($term_path, $data = array()) {
        return \simbola\core\component\term\Term::eGet($term_path, $data);
    }

    protected function invoke($module, $service, $action, $params) {
        $serviceClient = new \simbola\core\component\system\lib\ServiceClient();
        $serviceClient->module = $module;
        $serviceClient->service = $service;
        $serviceClient->action = $action;
        $serviceClient->params = $params;
        return $serviceClient->execute();
    }

}

?>
