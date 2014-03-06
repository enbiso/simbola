<?php

namespace application\developer\library\module\generator;

/**
 * Description of ControllerGenerator
 *
 * @author Faraj
 */
class ControllerGenerator extends CodeGenerator {

    private $modelClass;

    public function __construct($module, $lu, $model, $service, $controller, $purpose) {
        parent::__construct($module, $lu, $model, $service, $controller, $purpose);
    }

    public function generate() {
        $this->loadKeysAndColsArrays();
        $this->loadFieldElements();
        $this->modelClass = \simbola\core\application\AppModel::getClass($this->module, $this->lu, $this->model);

        $content = $this->getTemplateContent('controller.txt');
        $content = $this->initializeWithBasicInfo($content);

        $app = \simbola\Simbola::app();
        $mconf = $app->getModuleConfig($this->module);
        $servicePath = $mconf->getPath('controller')
                . DIRECTORY_SEPARATOR . ucfirst($this->controller) . "Controller.php";
        file_put_contents($servicePath, $content);
        //views & terms
        $this->loadFieldElements();
        $this->createView('create');
        $this->createTerms('create');
        $this->createView('index');
        $this->createTerms('index');
        $this->createView('list');
        $this->createTerms('list');
        $this->createView('update');
        $this->createTerms('update');
        $this->createView('delete');
        $this->createTerms('delete');
        $this->createView('view');
        $this->createTerms('view');
        $this->createView('_form');
        $this->createTerms('_form');
    }

    private $formElements = array();
    private $formHiddenElements = array();
    private $viewElements = array();

    private function loadFieldElements() {
        $this->formElements = array();
        $this->formHiddenElements = array();
        $this->viewElements = array();
        $colsArray = $this->getColsArray();
        $elem['textbox'] = file_get_contents($this->getTemplatePath('view' . DIRECTORY_SEPARATOR . "element" . DIRECTORY_SEPARATOR . "textbox.txt"));
        $elem['fieldbox'] = file_get_contents($this->getTemplatePath('view' . DIRECTORY_SEPARATOR . "element" . DIRECTORY_SEPARATOR . "fieldbox.txt"));
        $elem['hiddenbox'] = file_get_contents($this->getTemplatePath('view' . DIRECTORY_SEPARATOR . "element" . DIRECTORY_SEPARATOR . "hiddenbox.txt"));
        foreach ($colsArray as $col) {
            $content = $elem['textbox'];
            $content = str_replace("#CONTROLLER_NAME#", $this->controller, $content);
            $content = str_replace("#MODULE_NAME#", $this->module, $content);
            $content = str_replace("#FIELD_NAME#", $col, $content);
            $content = str_replace("#PLACEHOLDER#", ucfirst($col), $content);
            $this->formElements[] = $content;

            $content = $elem['fieldbox'];
            $content = str_replace("#FIELD_NAME#", $col, $content);
            $this->viewElements[] = $content;
        }
        $keyArray = $this->getKeysArray();
        foreach ($keyArray as $key) {
            $content = $elem['hiddenbox'];
            $content = str_replace("#FIELD_NAME#", $key, $content);
            $this->formHiddenElements[] = $content;
        }
    }
    
    private function createTerms($template) {
        $app = \simbola\Simbola::app();
        $mconf = $app->getModuleConfig($this->module);
        $templatePath = $this->getTemplatePath('term'
                . DIRECTORY_SEPARATOR . "en_US"
                . DIRECTORY_SEPARATOR . 'controller'
                . DIRECTORY_SEPARATOR . $template . ".txt");
        
        $content = file_get_contents($templatePath);
        $content = $this->initializeWithBasicInfo($content);
        
        $termPath = $mconf->getPath('term')
                . DIRECTORY_SEPARATOR . 'en_US'
                . DIRECTORY_SEPARATOR . 'controller';
        if (!is_dir($termPath)) {
            mkdir($termPath);
        }        
        $termPath = $termPath . DIRECTORY_SEPARATOR . $this->controller;
        if (!is_dir($termPath)) {
            mkdir($termPath);
        }
        $dest = $termPath . DIRECTORY_SEPARATOR . $template . ".php";
        file_put_contents($dest, $content);
    }

    private function createView($template) {
        $content = $this->getTemplateContent('view'
                . DIRECTORY_SEPARATOR . $template . ".txt");        
        $content = $this->initializeWithBasicInfo($content);
        $content = str_replace("#FORM_ELEMENTS#", implode("\n", $this->formElements), $content);
        $content = str_replace("#FORM_HIDDEN_ELEMENTS#", implode("\n", $this->formHiddenElements), $content);
        $content = str_replace("#VIEW_ELEMENTS#", implode("<hr/>\n", $this->viewElements), $content);
        $app = \simbola\Simbola::app();
        $mconf = $app->getModuleConfig($this->module);
        $viewPath = $mconf->getPath('view')
                . DIRECTORY_SEPARATOR . $this->controller;
        if (!is_dir($viewPath)) {
            mkdir($viewPath);
        }
        $servicePath = $viewPath . DIRECTORY_SEPARATOR . $template . ".php";
        file_put_contents($servicePath, $content);
    }

}
