<?php

namespace simbola\core\component\system\lib;

/**
 * Description of ViewRenderEngine
 *
 * @author Faraj
 */
class ViewContent {

    private $moduleConfig;
    private $viewName;
    private $data = array();
    private $partial;
    private $controller;
    private $layout;

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

    public function pview($viewPath, $data = array(), $print = true) {
        return $this->controller->pview($viewPath, $data, $print);
    }

    public function setLayout($layout) {
        $this->layout = $layout;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function __get($name) {
        return $this->data[$name];
    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    public function isDataSet($name) {
        return isset($this->data[$name]);
    }

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

    //used in the layouts
    public function includeFile($layout) {        
        $layout = dirname($this->getLayoutPath())
                . DIRECTORY_SEPARATOR . $layout . ".php";
        include_once $layout;
    }

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

    private function getViewPath() {
        return \simbola\Simbola::app()->getParam('BASE')
                . DIRECTORY_SEPARATOR . $this->moduleConfig->name
                . DIRECTORY_SEPARATOR . $this->moduleConfig->view
                . DIRECTORY_SEPARATOR . $this->viewName . ".php";
    }

}

?>
