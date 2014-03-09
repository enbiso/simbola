<?php

namespace application\developer\library\dbsetup;

/**
 * Description of LogicalUnit
 *
 * @author Faraj
 */
class LogicalUnit {

    private $name;
    private $module;
    private $dbDriver;
    private $objs = array(
        'table' => array(),
        'view' => array(),
        'procedure' => array(),
    );

    public function __construct($db, $module, $name) {
        $this->name = $name;
        $this->module = $module;
        $this->dbDriver = $db->getDriver();
        $this->load();
    }

    public function getName() {
        return $this->name;
    }

    function create() {
        $this->mkdir($this->getBasePath());
        $this->mkdir($this->getBasePath() . DIRECTORY_SEPARATOR . "table");
        $this->mkdir($this->getBasePath() . DIRECTORY_SEPARATOR . "view");
        $this->mkdir($this->getBasePath() . DIRECTORY_SEPARATOR . "procedure");
    }

    function setup() {
        foreach ($this->objs as $typeObjs) {
           foreach ($typeObjs as $obj) {
               $obj->setup();
           }   
        }
    }

    private function mkdir($path) {
        if (!file_exists($path)) {
            mkdir($path);
        }
    }

    private function getBasePath() {
        return \simbola\Simbola::app()->getModuleBase($this->module, 'database')
                . DIRECTORY_SEPARATOR . $this->name;
    }

    function load() {
        $this->loadObjs('table');
        $this->loadObjs('view');
        $this->loadObjs('procedure');
    }

    function getObj($type, $name) { 
        $name = sstring_camelcase_to_underscore($name);
        foreach ($this->objs[$type] as $obj) {
            slog_info("name: " . $obj->getName());
            if ($obj->getName() == $name) {
                return $obj;
            }
        }
        return null;
    }

    private function loadObjs($type) {
        $path = $this->getBasePath() . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . "*.php";
        foreach (glob($path) as $filePath) {
            $class = \simbola\Simbola::app()->getModuleNamespace($this->module, "database")
                    . "\\" . $this->name . '\\' . $type . '\\' . basename($filePath, ".php");
            $obj = new $class($this->dbDriver);
            $this->objs[$type][] = $obj;
        }
    }

    public static function LoadAll($db, $moduleName) {
        $path = \simbola\Simbola::app()->getModuleBase($moduleName, 'database')
                . DIRECTORY_SEPARATOR . "*.php";
        $lus = array();
        foreach (glob($path) as $file) {
            $lus[] = new LogicalUnit($db, $moduleName, basename($file, ".php"));
        }
        return $lus;
    }

}

?>
