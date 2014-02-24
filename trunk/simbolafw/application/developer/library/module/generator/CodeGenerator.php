<?php

namespace application\developer\library\module\generator;

/**
 * Description of CodeGenerator
 *
 * @author Faraj
 */
abstract class CodeGenerator {

    //Variables
    protected $module;
    protected $lu;
    protected $model;
    protected $purpose;
    protected $controller;
    protected $service;
    
    public function __construct($module, $lu, $model, $service, $controller, $purpose) {
        $this->module = $module;
        $this->lu = $lu;
        $this->model = $model;
        $this->service = $service;
        $this->controller = $controller;
        $this->purpose = $purpose;
    }
    
    public abstract function generate();

    function getTemplatePath($templateName) {
        $app = \simbola\Simbola::app();
        $mconf = $app->getModuleConfig('developer');
        return $mconf->getPath('library')
                . DIRECTORY_SEPARATOR . "module"
                . DIRECTORY_SEPARATOR . "templates"
                . DIRECTORY_SEPARATOR . $templateName;
    }

    function getKeysArray() {
        $class = \simbola\core\application\AppModel::getClass($this->module, $this->lu, $this->model);
        $keysArr = array();
        foreach ($class::Keys() as $key) {
            $keysArr[] = $key->name;
        }
        return $keysArr;
    }

    function getColsArray() {        
        $colsArr = array();
        $tblMeta = $this->getTableMeta();
        foreach ($tblMeta['columns'] as $col) {
            if (!sstring_starts_with($col['name'], "_")) {
                $colsArr[] = $col['name'];
            }
        }
        return $colsArr;
    }
    
    function getTemplateContent($templateName){
        $templatePath = $this->getTemplatePath($templateName);        
        return file_get_contents($templatePath);
    }
    
    function initializeWithBasicInfo($content, $limited = false){
        $content = str_replace("#AUTHER#", \simbola\Simbola::app()->auth->getUsername(), $content);
        $content = str_replace("#TODAY_DATE#", date("dMY"), $content);
        $content = str_replace("#MODULE_NAME#", $this->module, $content);
        $content = str_replace("#MODEL_NAME#", $this->model, $content);
        $content = str_replace("#TABLE_NAME#", sstring_camelcase_to_underscore($this->model), $content);
        $content = str_replace("#MODEL_PURPOSE#", $this->purpose, $content);
        $content = str_replace("#LU_NAME#", $this->lu, $content);
        $content = str_replace("#MODEL_CLASS_NAME#", ucfirst($this->model), $content);
        $content = str_replace("#PURPOSE#", $this->purpose, $content);
        $content = str_replace("#CONTROLLER_NAME#", $this->controller, $content);
        $content = str_replace("#UCF_CONTROLLER_NAME#", ucfirst($this->controller), $content);
        $content = str_replace("#UCF_MODULE_NAME#", ucfirst($this->module), $content);
        $content = str_replace("#CTS_CONTROLLER_NAME#", sstring_camelcase_to_space($this->controller), $content);
        $content = str_replace('#CTU_MODEL_NAME#', sstring_camelcase_to_underscore($this->model), $content);
        $content = str_replace("#SERVICE_NAME#", $this->service, $content);
        $content = str_replace("#SERVICE_CLASS_NAME#", ucfirst($this->service), $content);
        $content = str_replace("#CONTROLLER_CLASS_NAME#", ucfirst($this->controller), $content);        
        $modelClass = \simbola\core\application\AppModel::getClass($this->module, $this->lu, $this->model);
        $content = str_replace("#MODEL_CLASS#", $modelClass, $content);
        if(!$limited){
            $content = str_replace("#KEYS_COMMA_STR#", implode("','", $this->getKeysArray()), $content);
            //keys
            $this->loadKeysAndColsArrays();
            $content = str_replace("#KEYS_ARRAY_FROM_OBJ#", implode("," . PHP_EOL, $this->keysArrayFromObj), $content);
            $content = str_replace("#KEYS_ARRAY_FROM_GET#", implode("," . PHP_EOL, $this->keysArrayFromGet), $content);        
            $content = str_replace("#COLS_ARRAY_FOR_TABLE#", implode("," . PHP_EOL, $this->colsArrayForTable), $content);
            $content = str_replace("#KEYS_ARRAY_FOR_LINK#", implode("", $this->keysArrayForLink), $content);
        }
        return $content;
    }

    private $keysArrayFromObj = array();
    private $keysArrayFromGet = array();
    private $keysArrayForLink = array();
    private $colsArrayForTable = array();

    private function loadKeysAndColsArrays() {
        $keysArray = $this->getKeysArray();
        foreach ($keysArray as $key) {
            $this->keysArrayFromObj[] = '"' . $key . '" => $object->' . $key;
            $this->keysArrayFromGet[] = '"' . $key . '" => $this->get("' . $key . '")';
            $this->keysArrayForLink[] = '[' . $key . ':%' . $key . '%]';
        }
        $colsArray = $this->getColsArray();
        foreach ($colsArray as $col) {
            $this->colsArrayForTable[] = "\t" . '"' . $col . '" => "' . $col . '"';
        }
    }
    
    public function getTableMeta() {
        return \simbola\Simbola::app()->db->getMetaInfo(
                sstring_camelcase_to_underscore($this->module), 
                sstring_camelcase_to_underscore($this->lu), 
                sstring_camelcase_to_underscore($this->model));
    }
}
