<?php

namespace simbola\core\component\system\lib;

/**
 * ViewContent definitions
 *
 * @author Faraj Farook
 */
class ViewContent {

    /**
     * module configuration     
     * @var \simbola\core\application\AppModuleConfig 
     */
    private $moduleConfig;
    
    /**
     * View name     
     * @var string      
     */
    private $viewName;
    
    /**
     * View data array    
     * @var array 
     */
    private $data = array();
    
    /**
     * Render partial enable
     * @var boolean 
     */
    private $partial;
    
    /**
     * Controller name
     * @var string 
     */
    private $controller;
    
    /**
     * Layout name to render
     * @var string 
     */
    private $layout;

    /**
     * The contructor
     * 
     * @param string $controller Controller name
     * @param string $viewName View name
     * @param boolean $partial Partial render
     */
    public function __construct($controller, $viewName, $partial = false) {
        $this->controller = $controller;
        $this->moduleConfig = \simbola\Simbola::app()->getModuleConfig($controller->getCurrentPage()->module);
        $this->viewName = $viewName;
        $this->partial = $partial;
        $this->layout = $this->controller->getCustomLayout();
        if (!isset($this->layout)) {
            $this->layout = $this->moduleConfig->layout;
        }
    }

    /**
     * Renders the view without the layout
     * 
     * @param string $viewPath The path of the view from the module view folder     
     * @param array $data data to be processed along with the viewData when rendering
     * @param boolean $print TRUE - output the render, FALSE - return the render content as string
     * @return type render content
     */
    public function pview($viewPath, $data = array(), $print = true) {
        return $this->controller->pview($viewPath, $data, $print);
    }

    /**
     * Set the layout name
     * 
     * @param string $layout Layout name
     */
    public function setLayout($layout) {
        $this->layout = $layout;
    }

    /**
     * Set the view data
     * 
     * @param array $data
     */
    public function setData($data) {
        $this->data = $data;
    }

    /**
     * PHP interpreter function to enable Property like behavior
     * 
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        return $this->data[$name];
    }

    /**
     * PHP interpreter function to enable Property like behavior
     * 
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    /**
     * Returns the vuew data
     * 
     * @param type $name Name of the variable
     * @param type $unsetValue Value to send if not set
     * @return type
     */
    public function getData($name, $unsetValue = NULL) {
        if($this->isDataSet($name)){
            return $this->$name;
        }else{
            return $unsetValue;
        }
    }
    
    /**
     * Checks if the data is set
     * 
     * @param string $name Data name
     * @return boolean
     */
    public function isDataSet($name) {
        return isset($this->data[$name]);
    }

    /**
     * Process the content of the view
     * 
     * @return string Content
     * @throws \Exception View Path not found
     */
    private function processContent() {
        $path = $this->getViewPath();
        if (file_exists($path)) {
            $rawContent = file_get_contents($path);
            ob_start();
            eval('?>' . $rawContent);
            $content = ob_get_clean();
            return $content;
        } else {
            throw new \Exception("View Path {$path} not found");
        }
    }

    /**
     * Renders the specified view with the layout according to the object
     * 
     * @param boolean $print Return content if print is FALSE (default)
     * @return string content
     */
    public function render($print = false) {
        $data = $this->data;
        \simbola\Simbola::app()->term->loadTerm($this->moduleConfig->name, $this->viewName);
        $content = $this->processContent();        
        if (!$this->partial) {            
            if (sstring_starts_with($this->layout, DIRECTORY_SEPARATOR)) {
                $lpath = explode(DIRECTORY_SEPARATOR, $this->layout);
                \simbola\Simbola::app()->term->loadTerm($lpath[1]);
            }
            $layoutContent = file_get_contents($this->getLayoutPath());
            ob_start();
            eval('?>' . $layoutContent);
            $content = ob_get_clean();
        }
        if ($print) {
            echo $content;
        } else {
            return $content;
        }
    }

    /**
     * Used in the layouts to include partial layout files
     * 
     * @param string $layout
     */
    public function includeFile($layout) {        
        $layout = dirname($this->getLayoutPath())
                . DIRECTORY_SEPARATOR . $layout . ".php";
        include_once $layout;
    }

    /**
     * Returns the layout path
     * 
     * @param string $layout If not specified default from the pbject layout
     * @return string
     */
    private function getLayoutPath($layout = null) {
        if (!isset($layout)) {
            $layout = $this->layout;
        }

        //remove OS dependency
        $layout = str_replace(array("/", "\\"), DIRECTORY_SEPARATOR, $layout);
        if (sstring_starts_with($layout, DIRECTORY_SEPARATOR)) {
            return \simbola\Simbola::app()->getParam('BASE') . "{$layout}.php";
        } else {
            return \simbola\Simbola::app()->getParam('BASE')
                    . DIRECTORY_SEPARATOR . $this->moduleConfig->name
                    . DIRECTORY_SEPARATOR . "{$layout}.php";
        }
    }

    /**
     * Returns the view file path
     * 
     * @return string
     */
    private function getViewPath() {
        return \simbola\Simbola::app()->getParam('BASE')
                . DIRECTORY_SEPARATOR . $this->moduleConfig->name
                . DIRECTORY_SEPARATOR . $this->moduleConfig->view
                . DIRECTORY_SEPARATOR . $this->viewName . ".php";
    }

}

?>
