<?php

namespace simbola\core\component\term;

use simbola\Simbola;

/**
 * Term component definitions
 *
 * @author Faraj
 */
class Term extends \simbola\core\component\system\lib\Component {

    const SESSION_TERM_LANGUAGE = 'system.term.language';
    
    /**
     * Term cache variable
     * 
     * @var array 
     */
    private $termCache = array();
    
    /**
     * Loaded model names of the terms
     * 
     * @var array
     */
    private $loadedModels = array();

    /**
     * Setup the default component values
     */
    public function setupDefault() {
        parent::setupDefault();
        $this->setParamDefault('DEFAULT_LANG', 'en_US');
    }

    /**
     * Initialize the component
     */
    public function init() {
        parent::init();
        $lng = $this->getLanguage();
        if ($lng == null) {
            $this->setLanguage($this->params['DEFAULT_LANG']);
        }
    }

    /**
     * Set the session language 
     * ie, en_US, en_GB
     * 
     * @param type $langCode
     */
    public function setLanguage($langCode) {
        Simbola::app()->session->set(self::SESSION_TERM_LANGUAGE, $langCode);
    }

    /**
     * Gets the session language
     * 
     * @return string
     */
    public function getLanguage() {
        return Simbola::app()->session->get(self::SESSION_TERM_LANGUAGE);
    }

    /**
     * Load the view terms specified
     * 
     * @param string $moduleName Module name
     * @param string $viewName View name
     */
    public function loadTerm($moduleName, $viewName = null) {
        $moduleTermFile = $this->getModuleTermPath($moduleName);
        $this->importTermFile($moduleTermFile);
        if ($viewName != null) {
            $viewTermFile = $this->getViewTermPath($moduleName, $viewName);
            $this->importTermFile($viewTermFile);
        }
    }

    /**
     * Loads the layout terms specified
     * 
     * @todo Implement the function
     * @param string $layoutPath Layout path
     */
    public function loadLayoutTerm($layoutPath) {
        throw new \Exception(__METHOD__."not implemented");
    }

    /**
     * Implementation function of loading the term files
     * 
     * @param string $termFile term file name
     * @throws \Exception No term file found
     */
    private function importTermFile($termFile) {
        $__term = array();
        if (file_exists($termFile)) {
            eval("?>" . file_get_contents($termFile));
        } else {
            throw new \Exception("No term file found : {$termFile}");
        }
        $this->termCache = array_merge($this->termCache, $__term);
    }

    /**
     * Returns the module term file path
     * 
     * @param type $moduleName Module name
     * @return type
     */
    private function getModuleTermPath($moduleName) {
        return $this->getViewTermPath($moduleName, "common");
    }

    /**
     * Returns the view term file path
     * 
     * @param string $moduleName Module name
     * @param string $viewName View name
     * @return string
     */
    private function getViewTermPath($moduleName, $viewName) {
        $moduleConfig = \simbola\Simbola::app()->getModuleConfig($moduleName);
        $path = $this->getTermBase($moduleName)
                . $moduleConfig->controller . DIRECTORY_SEPARATOR
                . "{$viewName}.php";
        return $path;
    }

    /**
     * Load the model term file according to the given model class name
     * 
     * @param string $className
     */
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

    /**
     * Get the module term base path
     * 
     * @param string $moduleName Module name
     * @return string Path
     */
    private function getTermBase($moduleName) {
        $app = Simbola::app();
        $moduleConfig = Simbola::app()->getModuleConfig($moduleName);
        return $app->getParam('BASEPATH') . DIRECTORY_SEPARATOR
                . $app->getParam("BASE") . DIRECTORY_SEPARATOR
                . $moduleConfig->name . DIRECTORY_SEPARATOR
                . $moduleConfig->term . DIRECTORY_SEPARATOR
                . $this->getLanguage() . DIRECTORY_SEPARATOR;
    }

    /**
     * Get the term for the given field in the model
     * 
     * @param string $modelClass Model class name
     * @param string $name Model field name
     * @return string Term
     */
    public function getModelTerm($modelClass, $name) {
        $this->loadModelTerm($modelClass);
        return $this->getTerm($this->getModelTermName($modelClass, $name));
    }

    /**
     * Returns the model term name of the model class and field name given
     * 
     * @param string $modelClass Model class name
     * @param string $name Field name
     * @return string
     */
    public function getModelTermName($modelClass, $name) {
        $sliceIndex = sstring_starts_with($modelClass, "\\") ? 2 : 1;
        $modelName = implode('.', array_slice(explode("\\", $modelClass), $sliceIndex));
        $modelName = sstring_camelcase_to_underscore($modelName);
        $name = sstring_camelcase_to_underscore($name);
        return "{$modelName}.{$name}";
    }

    /**
     * Returns the term for the given term name
     * 
     * @param string $name Term name
     * @param array $params Term parameters
     * @return string
     */
    public function getTerm($name, $params = array()) {
        $term = "#" . $name . "#";
        if(isset($this->termCache[$name])){
            $term = $this->termCache[$name];
            for ($index = 0; $index < count($params); $index++) {
                $term = str_replace("{" . $index . "}", $params[$index], $term);
            }
        }
        return $term;
    }

    /**
     * Returns the term for the given term name
     * 
     * @param string $name Term name
     * @param array $params Term parameters     
     * @return string
     */
    public static function Get($name, $params = array()) {
        return Simbola::app()->term->getTerm($name, $params);
        
    }

    /**
     * Echo the term for the given term name
     * 
     * @param string $name Term name
     * @param array $params Term parameters          
     */
    public static function eGet($name, $params = array()) {
        echo Term::Get($name, $params);
    }

}

?>
