<?php

namespace simbola\core\component\term;

use simbola\Simbola;

/**
 * Description of Term
 *
 * @author Faraj
 */
class Term extends \simbola\core\component\system\lib\Component {

    private $term_cache = array();

    public function setupDefault() {
        parent::setupDefault();
        $this->setParamDefault('DEFAULT_LANG', 'en_US');
    }

    public function init() {
        parent::init();
        $lng = $this->getLanguage();
        if ($lng == null) {
            $this->setLanguage($this->params['DEFAULT_LANG']);
        }
    }

    public function setLanguage($lang_code) {
        Simbola::app()->session->set('TERM_LANGUAGE', $lang_code);
    }

    public function getLanguage() {
        return Simbola::app()->session->get('TERM_LANGUAGE');
    }

    public function loadTerm($moduleName, $viewName = null) {
        $moduleTermFile = $this->getModuleTermPath($moduleName);
        $this->importTermFile($moduleTermFile);
        if ($viewName != null) {
            $viewTermFile = $this->getViewTermPath($moduleName, $viewName);
            $this->importTermFile($viewTermFile);
        }
    }

    public function loadLayoutTerm($layoutPath) {
        
    }

    private function importTermFile($termFile) {
        $__term = array();
        if (file_exists($termFile)) {
            eval("?>" . file_get_contents($termFile));
        } else {
            throw new \Exception("No term file found : {$termFile}");
        }
        $this->term_cache = array_merge($this->term_cache, $__term);
    }

    private function getModuleTermPath($moduleName) {
        return $this->getViewTermPath($moduleName, "common");
    }

    private function getViewTermPath($moduleName, $viewName) {
        $moduleConfig = \simbola\Simbola::app()->getModuleConfig($moduleName);
        $path = $this->getTermBase($moduleName)
                . $moduleConfig->controller . DIRECTORY_SEPARATOR
                . "{$viewName}.php";
        return $path;
    }

    private $loadedModels = array();

    public function loadModelTerm($className) {
        if (!array_search($className, $this->loadedModels)) {
            $classArray = explode("\\", $className);
            $path = $this->getTermBase($classArray[1])
                    . implode(DIRECTORY_SEPARATOR, array_slice($classArray, 2))
                    . ".php";
            $this->importTermFile($path);
            array_push($this->loadedModels, $className);
        }
    }

    public function getTermBase($moduleName) {
        $app = Simbola::app();
        $moduleConfig = Simbola::app()->getModuleConfig($moduleName);
        return $app->getParam('BASEPATH') . DIRECTORY_SEPARATOR
                . $app->getParam("BASE") . DIRECTORY_SEPARATOR
                . $moduleConfig->name . DIRECTORY_SEPARATOR
                . $moduleConfig->term . DIRECTORY_SEPARATOR
                . $this->getLanguage() . DIRECTORY_SEPARATOR;
    }

    public function getModelTerm($modelClass, $name) {
        $this->loadModelTerm($modelClass);
        return $this->getTerm($this->getModelTermName($modelClass, $name));
    }

    public function getModelTermName($modelClass, $name) {
        $sliceIndex = sstring_starts_with($modelClass, "\\") ? 2 : 1;
        $modelName = implode('.', array_slice(explode("\\", $modelClass), $sliceIndex));
        $modelName = sstring_camelcase_to_underscore($modelName);
        $name = sstring_camelcase_to_underscore($name);
        return "{$modelName}.{$name}";
    }

    public function getTerm($name) {
        return isset($this->term_cache[$name]) ? $this->term_cache[$name] : "#" . $name . "#";
    }

    public static function Get($name, $data = array()) {
        $term = Simbola::app()->term->getTerm($name);
        for ($index = 0; $index < count($data); $index++) {
            $term = str_replace("{" . $index . "}", $data[$index], $term);
        }
        return isset($term) ? $term : "T:" . $name;
    }

    public static function eGet($name, $data = array()) {
        echo Term::Get($name, $data);
    }

}

?>
